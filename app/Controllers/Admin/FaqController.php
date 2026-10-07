<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Faq;

final class FaqController
{
    public function index(): void
    {
        render('admin/faq', [
            'menu' => 'faq',
            'pageTitle' => 'Perguntas Frequentes (FAQ) | ' . setting('name'),
            'faqs' => Faq::all(),
        ], 'admin');
    }

    public function store(): void
    {
        $question = input_str('question', 255);
        $answer = input_str('answer', 3000);
        $category = input_str('category', 80);

        if ($question === '' || $answer === '') {
            with_old($_POST);
            toast('Preencha a pergunta e a resposta.', 'warning');
            redirect('/painel/faq?novo=1');
        }

        Faq::create($question, $answer, $category !== '' ? $category : 'Geral');
        toast('Pergunta frequente adicionada!');
        redirect('/painel/faq');
    }

    public function update(string $id): void
    {
        $question = input_str('question', 255);
        $answer = input_str('answer', 3000);
        $category = input_str('category', 80);

        if ($question === '' || $answer === '') {
            toast('Preencha a pergunta e a resposta.', 'warning');
            redirect('/painel/faq');
        }

        if (Faq::update((int) $id, $question, $answer, $category !== '' ? $category : 'Geral')) {
            toast('FAQ atualizado.');
        }
        redirect('/painel/faq');
    }

    public function destroy(string $id): void
    {
        Faq::delete((int) $id);
        toast('Pergunta removida.');
        redirect('/painel/faq');
    }
}
