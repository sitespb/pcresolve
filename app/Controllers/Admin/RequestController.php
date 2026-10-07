<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Lead;

/** Ordens de Serviço & Leads. */
final class RequestController
{
    private const PER_PAGE = 25;

    public function index(): void
    {
        $status = (string) input('status', 'todos');
        if ($status !== 'todos' && !array_key_exists($status, lead_statuses())) {
            $status = 'todos';
        }
        $search = input_str('q', 100);
        $page = max(1, (int) input('pagina', 1));

        $result = Lead::search($search, $status, $page, self::PER_PAGE);

        $open = null;
        if ((int) input('abrir', 0) > 0) {
            $open = Lead::find((int) input('abrir'));
        }

        render('admin/requests', [
            'menu' => 'solicitacoes',
            'pageTitle' => 'Ordens de Serviço & Leads | ' . setting('name'),
            'leads' => $result['items'],
            'total' => $result['total'],
            'page' => $page,
            'pages' => max(1, (int) ceil($result['total'] / self::PER_PAGE)),
            'status' => $status,
            'search' => $search,
            'openLead' => $open,
        ], 'admin');
    }

    public function store(): void
    {
        $name = input_str('customer_name', 150);
        $phone = input_str('phone', 40);

        if ($name === '' || $phone === '') {
            with_old($_POST);
            toast('Nome e telefone são obrigatórios.', 'warning');
            redirect('/painel/solicitacoes?novo=1');
        }

        $device = input_str('device_type', 20);
        $description = input_str('description', 3000);
        $service = input_str('service_type', 190);
        $city = input_str('city', 150);

        $protocol = Lead::create([
            'customer_name' => $name,
            'phone' => $phone,
            'city' => $city !== '' ? $city : 'João Pessoa',
            'device_type' => in_array($device, device_types(), true) ? $device : 'outro',
            'service_type' => $service !== '' ? $service : 'Manutenção de Notebooks',
            'description' => $description !== '' ? $description : 'Cadastrado manualmente via painel administrativo.',
            'source' => 'admin',
            'ip_address' => null,
        ]);

        toast("Solicitação registrada! Protocolo $protocol", 'success');
        redirect('/painel/solicitacoes');
    }

    public function update(string $id): void
    {
        $lead = Lead::find((int) $id);
        if ($lead === null) {
            toast('Ordem de serviço não encontrada.', 'error');
            redirect('/painel/solicitacoes');
        }

        $status = (string) input('status', $lead['status']);
        if (!array_key_exists($status, lead_statuses())) {
            $status = (string) $lead['status'];
        }
        $budget = input_money('budget') ?? 0.0;
        $notes = input_str('internal_notes', 5000);

        Lead::updateManagement((int) $lead['id'], $status, $budget, $notes);

        toast('Status da Ordem de Serviço atualizado!', 'info');
        toast('Orçamento definido: R$ ' . fixed2($budget), 'success');
        toast('Anotação técnica salva.', 'success');

        $back = previous_url('/painel/solicitacoes');
        if (!str_starts_with($back, '/painel/solicitacoes')) {
            $back = '/painel/solicitacoes';
        }
        // Não reabre o modal após salvar.
        $back = preg_replace('/([?&])abrir=\d+&?/', '$1', $back) ?? $back;
        redirect(rtrim($back, '?&'));
    }
}
