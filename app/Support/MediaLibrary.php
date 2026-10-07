<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Setting;
use RuntimeException;

/**
 * Biblioteca de mídia: inventário das imagens de public/assets/img.
 *
 * A fonte da verdade é o sistema de arquivos, não uma tabela. Assim as imagens
 * que já estavam na pasta (as da demonstração) aparecem sem precisar de migração,
 * e o inventário nunca diverge do que existe em disco.
 *
 * "Órfã" é a imagem que nenhum cadastro usa E que nenhum arquivo de código cita.
 * A segunda metade importa: o logo e a imagem padrão de Open Graph estão escritos
 * direto nas views, não no banco — sem essa checagem apareceriam como órfãs e
 * poderiam ser apagadas, quebrando o site.
 *
 * Escala: cada listagem lê o cabeçalho de todos os arquivos (filesize +
 * getimagesize) e varre os .php em busca de caminhos citados. É barato na ordem
 * de centenas de imagens, que é o caso deste site. Se o acervo passar de alguns
 * milhares, vale trocar por uma tabela alimentada no envio.
 */
final class MediaLibrary
{
    /** Raiz pública das imagens, relativa a public/. */
    public const DIR = 'assets/img';

    /** Onde os envios feitos pelo painel são gravados. */
    public const UPLOAD_DIR = 'assets/img/biblioteca';

    /** Extensões que entram no inventário. */
    private const EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'ico'];

    /**
     * Cadastros que apontam para uma imagem. Cada entrada custa UMA consulta
     * para a listagem inteira — não uma por arquivo.
     *
     * Para ligar um cadastro novo à biblioteca, basta acrescentar aqui.
     */
    private const ATTACHED_TO = [
        ['table' => 'services', 'column' => 'image', 'label' => 'Serviço', 'title' => 'title', 'url' => '/painel/servicos'],
        ['table' => 'users', 'column' => 'avatar', 'label' => 'Usuário', 'title' => 'name', 'url' => '/painel/perfil'],
    ];

    /** Pastas varridas em busca de caminhos de imagem escritos no código. */
    private const CODE_DIRS = ['views', 'app'];

    /** @var array<string, list<array<string, string>>>|null */
    private static ?array $attachmentCache = null;

    /** @var list<string>|null */
    private static ?array $codeCache = null;

    /**
     * Inventário completo, mais recentes primeiro.
     *
     * @return list<array<string, mixed>>
     */
    public static function items(): array
    {
        $raiz = self::absoluteRoot();
        if (!is_dir($raiz)) {
            return [];
        }

        $anexos = self::attachments();
        $noCodigo = self::codeReferences();
        $itens = [];

        foreach (self::scan($raiz) as $absoluto) {
            $relativo = self::relativePath($absoluto);
            $usos = $anexos[$relativo] ?? [];
            $citadoNoCodigo = in_array($relativo, $noCodigo, true);

            $itens[] = self::describe($absoluto, $relativo, $usos, $citadoNoCodigo);
        }

        usort($itens, static fn (array $a, array $b): int => $b['modified'] <=> $a['modified']);

        return $itens;
    }

    /**
     * Números do topo da tela.
     *
     * @param list<array<string, mixed>> $itens
     * @return array<string, mixed>
     */
    public static function stats(array $itens): array
    {
        $bytes = 0;
        $orfas = 0;
        $emUso = 0;
        foreach ($itens as $item) {
            $bytes += (int) $item['bytes'];
            $item['orphan'] ? $orfas++ : $emUso++;
        }

        return [
            'total' => count($itens),
            'bytes' => $bytes,
            'size_label' => ImageProcessor::humanSize($bytes),
            'orphans' => $orfas,
            'in_use' => $emUso,
        ];
    }

    /** @return array<string, mixed>|null */
    public static function find(string $relativo): ?array
    {
        $relativo = self::normalize($relativo);
        $absoluto = self::toAbsolute($relativo);
        if ($absoluto === null || !is_file($absoluto)) {
            return null;
        }

        return self::describe(
            $absoluto,
            $relativo,
            self::attachments()[$relativo] ?? [],
            in_array($relativo, self::codeReferences(), true)
        );
    }

    /**
     * Grava um envio na biblioteca e devolve o item criado.
     *
     * @param array<string, mixed> $file Item de $_FILES
     * @return array<string, mixed>
     */
    public static function store(array $file): array
    {
        $processor = new ImageProcessor(Setting::imageConfig());

        $nomeOriginal = (string) ($file['name'] ?? 'imagem');
        $base = slugify(pathinfo($nomeOriginal, PATHINFO_FILENAME));
        if ($base === '') {
            $base = 'imagem';
        }
        // Sufixo aleatório: dois envios com o mesmo nome não se sobrescrevem.
        $base .= '-' . bin2hex(random_bytes(3));

        $resultado = $processor->handleUpload($file, BASE_PATH . '/public/' . self::UPLOAD_DIR, $base);
        $relativo = self::UPLOAD_DIR . '/' . basename((string) $resultado['path']);

        self::$attachmentCache = null;

        $item = self::find($relativo);
        if ($item === null) {
            throw new RuntimeException('A imagem foi enviada mas não pôde ser lida de volta.');
        }

        return $item;
    }

    /**
     * Apaga uma imagem da biblioteca.
     *
     * Recusa se a imagem estiver em uso por algum cadastro ou citada no código —
     * apagar nesses casos deixaria o site com imagem quebrada.
     */
    public static function delete(string $relativo): void
    {
        $item = self::find($relativo);
        if ($item === null) {
            throw new RuntimeException('Imagem não encontrada na biblioteca.');
        }

        if ($item['in_code']) {
            throw new RuntimeException(
                'Esta imagem é usada diretamente pelo código do site (logo, ícone ou imagem padrão) e não pode ser removida pelo painel.'
            );
        }

        if ($item['usage'] !== []) {
            $onde = implode(', ', array_map(
                static fn (array $u): string => $u['label'] . ' "' . $u['title'] . '"',
                $item['usage']
            ));
            throw new RuntimeException("Esta imagem está em uso em: $onde. Troque a imagem nesses cadastros antes de removê-la.");
        }

        $absoluto = self::toAbsolute($item['path']);
        if ($absoluto === null || !is_file($absoluto)) {
            throw new RuntimeException('Arquivo não encontrado em disco.');
        }

        if (!@unlink($absoluto)) {
            throw new RuntimeException('Não foi possível apagar o arquivo. Verifique as permissões da pasta.');
        }

        self::$attachmentCache = null;
    }

    /**
     * Resolve qual imagem um formulário escolheu.
     *
     * Precedência: arquivo enviado do computador > imagem escolhida na
     * biblioteca > valor que já estava gravado. Assim o usuário pode salvar o
     * cadastro sem tocar na imagem e nada se perde.
     *
     * @param string $campoArquivo Nome do <input type="file">
     * @param string $campoCaminho Nome do campo oculto com o caminho da biblioteca
     * @param string $atual        Caminho já gravado no cadastro
     * @return string Caminho relativo para gravar no banco
     * @throws RuntimeException Se o envio falhar ou o caminho informado não existir
     */
    public static function resolveChoice(string $campoArquivo, string $campoCaminho, string $atual = ''): string
    {
        $arquivo = $_FILES[$campoArquivo] ?? null;
        $temEnvio = is_array($arquivo)
            && !is_array($arquivo['name'] ?? null)
            && (int) ($arquivo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;

        if ($temEnvio) {
            return self::store($arquivo)['path'];
        }

        $escolhido = self::normalize(input_str($campoCaminho, 500));
        if ($escolhido === '') {
            return $atual;
        }

        // Mesmo caminho de antes: nada a validar nem a trocar.
        if ($escolhido === self::normalize($atual)) {
            return $atual;
        }

        if (self::find($escolhido) === null) {
            throw new RuntimeException('A imagem escolhida não existe mais na biblioteca. Escolha outra.');
        }

        return $escolhido;
    }

    /**
     * Onde cada imagem está anexada, indexado pelo caminho relativo.
     *
     * @return array<string, list<array<string, string>>>
     */
    public static function attachments(): array
    {
        if (self::$attachmentCache !== null) {
            return self::$attachmentCache;
        }

        $mapa = [];
        foreach (self::ATTACHED_TO as $origem) {
            $sql = sprintf(
                'SELECT `%s` AS caminho, `%s` AS titulo FROM `%s` WHERE `%s` <> %s',
                $origem['column'],
                $origem['title'],
                $origem['table'],
                $origem['column'],
                "''"
            );

            foreach (Database::select($sql) as $linha) {
                $caminho = self::normalize((string) $linha['caminho']);
                if ($caminho === '' || preg_match('#^https?://#i', $caminho)) {
                    continue; // URL externa não é item da biblioteca
                }
                $mapa[$caminho][] = [
                    'label' => $origem['label'],
                    'title' => (string) $linha['titulo'],
                    'url' => $origem['url'],
                ];
            }
        }

        return self::$attachmentCache = $mapa;
    }

    /**
     * Caminhos de imagem escritos dentro do código (views e app).
     *
     * @return list<string>
     */
    public static function codeReferences(): array
    {
        if (self::$codeCache !== null) {
            return self::$codeCache;
        }

        $encontrados = [];
        foreach (self::CODE_DIRS as $pasta) {
            $raiz = BASE_PATH . '/' . $pasta;
            if (!is_dir($raiz)) {
                continue;
            }
            foreach (self::scan($raiz, ['php']) as $arquivo) {
                $conteudo = (string) @file_get_contents($arquivo);
                if ($conteudo === '') {
                    continue;
                }
                if (preg_match_all('#assets/img/[A-Za-z0-9._/-]+#', $conteudo, $m)) {
                    foreach ($m[0] as $caminho) {
                        $ext = strtolower(pathinfo($caminho, PATHINFO_EXTENSION));
                        if (in_array($ext, self::EXTENSIONS, true)) {
                            $encontrados[$caminho] = true;
                        }
                    }
                }
            }
        }

        return self::$codeCache = array_keys($encontrados);
    }

    /**
     * Monta os metadados de um arquivo.
     *
     * @param list<array<string, string>> $usos
     * @return array<string, mixed>
     */
    private static function describe(string $absoluto, string $relativo, array $usos, bool $citadoNoCodigo): array
    {
        $bytes = (int) @filesize($absoluto);
        $info = @getimagesize($absoluto);
        $largura = is_array($info) ? (int) $info[0] : 0;
        $altura = is_array($info) ? (int) $info[1] : 0;
        $modificado = (int) @filemtime($absoluto);

        return [
            'path' => $relativo,
            'url' => '/' . $relativo,
            'filename' => basename($relativo),
            'folder' => trim(dirname($relativo), '.'),
            'ext' => strtolower(pathinfo($relativo, PATHINFO_EXTENSION)),
            'bytes' => $bytes,
            'size_label' => $bytes > 0 ? ImageProcessor::humanSize($bytes) : '—',
            'width' => $largura,
            'height' => $altura,
            'dimensions' => $largura > 0 ? $largura . ' × ' . $altura : '—',
            'megapixels' => $largura > 0 ? round($largura * $altura / 1_000_000, 1) : 0.0,
            'modified' => $modificado,
            'modified_label' => $modificado > 0 ? date('d/m/Y H:i', $modificado) : '—',
            'usage' => $usos,
            'usage_count' => count($usos),
            'in_code' => $citadoNoCodigo,
            'orphan' => $usos === [] && !$citadoNoCodigo,
            'deletable' => $usos === [] && !$citadoNoCodigo,
        ];
    }

    /**
     * Lista recursivamente os arquivos com as extensões informadas.
     *
     * @param list<string>|null $extensoes
     * @return list<string>
     */
    private static function scan(string $raiz, ?array $extensoes = null): array
    {
        $extensoes ??= self::EXTENSIONS;
        $arquivos = [];

        $iterador = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($raiz, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterador as $entrada) {
            /** @var \SplFileInfo $entrada */
            if (!$entrada->isFile()) {
                continue;
            }
            if (in_array(strtolower($entrada->getExtension()), $extensoes, true)) {
                $arquivos[] = $entrada->getPathname();
            }
        }

        return $arquivos;
    }

    private static function absoluteRoot(): string
    {
        return BASE_PATH . '/public/' . self::DIR;
    }

    /** Caminho relativo no formato gravado no banco: assets/img/{nome do arquivo}. */
    private static function relativePath(string $absoluto): string
    {
        $prefixo = BASE_PATH . '/public/';
        $normalizado = str_replace('\\', '/', $absoluto);
        $prefixo = str_replace('\\', '/', $prefixo);

        return str_starts_with($normalizado, $prefixo)
            ? substr($normalizado, strlen($prefixo))
            : ltrim($normalizado, '/');
    }

    /** Normaliza o que vem do banco ou do formulário para comparar com o disco. */
    public static function normalize(string $caminho): string
    {
        $caminho = str_replace('\\', '/', trim($caminho));
        $caminho = preg_replace('#^/+#', '', $caminho) ?? $caminho;

        return $caminho;
    }

    /**
     * Converte o caminho relativo em absoluto, garantindo que fica dentro de
     * public/assets/img. Devolve null para qualquer tentativa de sair da pasta.
     */
    private static function toAbsolute(string $relativo): ?string
    {
        $relativo = self::normalize($relativo);
        if ($relativo === '' || str_contains($relativo, '..')) {
            return null;
        }
        if (!str_starts_with($relativo, self::DIR . '/')) {
            return null;
        }

        $candidato = BASE_PATH . '/public/' . $relativo;
        $real = realpath($candidato);
        $raiz = realpath(self::absoluteRoot());
        if ($real === false || $raiz === false) {
            return null;
        }

        $real = str_replace('\\', '/', $real);
        $raiz = str_replace('\\', '/', $raiz);

        return str_starts_with($real, $raiz . '/') ? $real : null;
    }
}
