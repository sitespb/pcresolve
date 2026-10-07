<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Testimonial;

final class TestimonialController
{
    public function index(): void
    {
        render('admin/testimonials', [
            'menu' => 'depoimentos',
            'pageTitle' => 'Depoimentos & Avaliações | ' . setting('name'),
            'testimonials' => Testimonial::all(),
        ], 'admin');
    }

    public function store(): void
    {
        $author = input_str('author', 150);
        $text = input_str('text', 2000);

        if ($author === '' || $text === '') {
            with_old($_POST);
            toast('Informe o nome do cliente e o texto do depoimento.', 'warning');
            redirect('/painel/depoimentos?novo=1');
        }

        Testimonial::create([
            'author' => $author,
            'location' => input_str('location', 150),
            'rating' => max(1, min(5, (int) input('rating', 5))),
            'service_title' => input_str('service_title', 190),
            'text' => $text,
        ]);

        toast('Avaliação enviada com sucesso! Ela será publicada após aprovação.');
        redirect('/painel/depoimentos');
    }

    public function approve(string $id): void
    {
        if (Testimonial::setApproved((int) $id, true)) {
            toast('Depoimento aprovado e publicado no site público!');
        }
        redirect('/painel/depoimentos');
    }

    public function hide(string $id): void
    {
        if (Testimonial::setApproved((int) $id, false)) {
            toast('Depoimento despublicado.', 'info');
        }
        redirect('/painel/depoimentos');
    }
}
