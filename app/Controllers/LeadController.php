<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Lead;
use App\Models\Service;

/** Formulários públicos de solicitação (Home, Contato e detalhe do serviço). */
final class LeadController
{
    private const MAX_PER_IP = 5;
    private const WINDOW_MINUTES = 10;

    /** Retorna o protocolo recém-criado se o formulário de origem for o informado. */
    public static function pullSuccess(string $form): ?string
    {
        $success = $_SESSION['_flash']['lead_success'] ?? null;
        if (is_array($success) && ($success['form'] ?? null) === $form) {
            unset($_SESSION['_flash']['lead_success']);

            return (string) $success['protocol'];
        }

        return null;
    }

    public function store(): void
    {
        $origin = (string) input('origem', 'contato');
        if (!in_array($origin, ['home', 'contato', 'servico'], true)) {
            $origin = 'contato';
        }

        $service = null;
        if ($origin === 'servico') {
            $service = Service::findActiveBySlug(input_str('servico', 160));
            if ($service === null) {
                redirect('/servicos');
            }
        }

        $redirect = match ($origin) {
            'home' => '/#contato',
            'servico' => '/servicos/' . $service['slug'] . '#solicitar',
            default => '/contato#formulario',
        };

        // Honeypot anti-spam: campo invisível que só robôs preenchem.
        if (input_str('website') !== '') {
            redirect($redirect);
        }

        $name = input_str('customer_name', 150);
        $phone = input_str('phone', 40);
        $email = input_str('email', 190);
        $city = input_str('city', 150);
        $device = input_str('device_type', 20);
        $description = input_str('description', 3000);
        $consent = input('consent') !== null;

        $fail = function (string $message) use ($redirect): never {
            with_old($_POST);
            toast($message, 'warning');
            redirect($redirect);
        };

        if ($name === '' || $phone === '') {
            $fail(match ($origin) {
                'home' => 'Por favor, informe seu nome e telefone/WhatsApp.',
                'servico' => 'Por favor, informe seu nome e WhatsApp.',
                default => 'Por favor, informe seu nome e telefone.',
            });
        }

        if ($origin !== 'servico' && !$consent) {
            $fail($origin === 'home'
                ? 'É necessário concordar com o tratamento dos dados.'
                : 'Por favor, assinale o consentimento para prosseguir.');
        }

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $fail('Informe um e-mail válido ou deixe o campo em branco.');
        }

        if (Lead::recentFromIp(client_ip(), self::WINDOW_MINUTES) >= self::MAX_PER_IP) {
            $fail('Recebemos várias solicitações em sequência. Aguarde alguns minutos ou fale conosco pelo WhatsApp.');
        }

        if (!in_array($device, device_types(), true)) {
            $device = 'outro';
        }

        if ($service !== null) {
            $serviceType = (string) $service['title'];
            $description = $description !== '' ? $description : 'Solicitação direta para ' . $service['title'];
        } else {
            $serviceType = input_str('service_type', 190);
            if (!in_array($serviceType, lead_service_options(), true)) {
                $serviceType = 'Diagnóstico geral de falha';
            }
        }

        $protocol = Lead::create([
            'customer_name' => $name,
            'phone' => $phone,
            'email' => $email,
            'city' => $city !== '' ? $city : 'João Pessoa',
            'device_type' => $device,
            'service_type' => $serviceType,
            'description' => $description,
            'source' => $origin,
            'ip_address' => client_ip(),
        ]);

        toast("Solicitação registrada! Protocolo $protocol", 'success');
        flash('lead_success', ['form' => $origin, 'protocol' => $protocol]);
        redirect($redirect);
    }
}
