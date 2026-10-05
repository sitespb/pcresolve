import React, { useState } from 'react';
import { useApp } from '../../context/AppContext';
import { AdminLayout } from '../../components/admin/AdminLayout';
import { HelpCircle, Plus, Edit2, Trash2, X, Save } from 'lucide-react';
import { FaqItem } from '../../types';

export const AdminFaq: React.FC = () => {
  const { faqs, addFaq, updateFaq, deleteFaq, showToast } = useApp();

  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editingFaq, setEditingFaq] = useState<FaqItem | null>(null);

  const [question, setQuestion] = useState('');
  const [answer, setAnswer] = useState('');
  const [category, setCategory] = useState('Geral');

  const handleCreate = (e: React.FormEvent) => {
    e.preventDefault();
    if (!question.trim() || !answer.trim()) return;

    addFaq({
      question,
      answer,
      category,
    });

    setIsModalOpen(false);
    setQuestion('');
    setAnswer('');
  };

  const handleSaveEdit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!editingFaq) return;
    updateFaq(editingFaq.id, {
      question: editingFaq.question,
      answer: editingFaq.answer,
      category: editingFaq.category,
    });
    setEditingFaq(null);
  };

  return (
    <AdminLayout
      title="Perguntas Frequentes (FAQ)"
      subtitle="Gerencie as dúvidas comuns respondidas na área pública do site para reduzir atrito na contratação"
      action={
        <button
          onClick={() => setIsModalOpen(true)}
          className="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer"
        >
          <Plus className="w-3.5 h-3.5" />
          <span>Nova Pergunta</span>
        </button>
      }
    >
      <div className="space-y-4">
        {faqs.map((faq) => (
          <div
            key={faq.id}
            className="bg-white rounded-2xl border border-[#E4E7EC] p-5 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-4"
          >
            <div className="space-y-1.5 flex-1">
              <div className="flex items-center gap-2">
                <span className="text-[10px] font-bold px-2 py-0.5 rounded-full bg-[#F5F6F8] text-[#D71920] border border-[#E4E7EC]">
                  {faq.category}
                </span>
                <h3 className="font-heading font-bold text-sm text-[#202124]">
                  {faq.question}
                </h3>
              </div>
              <p className="text-xs text-[#697386] leading-relaxed">
                {faq.answer}
              </p>
            </div>

            <div className="flex items-center gap-2 shrink-0">
              <button
                onClick={() => setEditingFaq(faq)}
                className="p-2 rounded-lg border border-[#E4E7EC] hover:bg-neutral-50 text-[#202124] transition-colors"
                title="Editar"
              >
                <Edit2 className="w-3.5 h-3.5" />
              </button>
              <button
                onClick={() => {
                  if (confirm('Deseja excluir esta pergunta?')) {
                    deleteFaq(faq.id);
                  }
                }}
                className="p-2 rounded-lg border border-[#E4E7EC] hover:bg-red-50 text-red-600 transition-colors"
                title="Excluir"
              >
                <Trash2 className="w-3.5 h-3.5" />
              </button>
            </div>
          </div>
        ))}
      </div>

      {/* Modal: New FAQ */}
      {isModalOpen && (
        <div className="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl border border-[#E4E7EC] max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div className="flex items-center justify-between pb-3 border-b border-[#E4E7EC]">
              <h3 className="font-heading font-bold text-sm text-[#202124]">
                Adicionar Pergunta Frequente
              </h3>
              <button
                onClick={() => setIsModalOpen(false)}
                className="text-[#697386] hover:text-[#202124]"
              >
                <X className="w-5 h-5" />
              </button>
            </div>

            <form onSubmit={handleCreate} className="space-y-3 text-xs">
              <div>
                <label className="block text-xs font-semibold text-[#202124] mb-1">
                  Pergunta *
                </label>
                <input
                  type="text"
                  required
                  placeholder="Ex: Vocês atendem a domicílio em Cabedelo?"
                  value={question}
                  onChange={(e) => setQuestion(e.target.value)}
                  className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"
                />
              </div>

              <div>
                <label className="block text-xs font-semibold text-[#202124] mb-1">
                  Categoria
                </label>
                <input
                  type="text"
                  value={category}
                  onChange={(e) => setCategory(e.target.value)}
                  className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"
                />
              </div>

              <div>
                <label className="block text-xs font-semibold text-[#202124] mb-1">
                  Resposta Explicativa *
                </label>
                <textarea
                  rows={3}
                  required
                  placeholder="Explicação clara e acessível..."
                  value={answer}
                  onChange={(e) => setAnswer(e.target.value)}
                  className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"
                />
              </div>

              <div className="pt-2 flex justify-end gap-2">
                <button
                  type="button"
                  onClick={() => setIsModalOpen(false)}
                  className="px-4 py-2 rounded-lg border border-[#E4E7EC] text-xs font-semibold"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  className="px-5 py-2 rounded-lg bg-[#D71920] text-white text-xs font-semibold shadow-xs"
                >
                  Salvar
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Modal: Edit FAQ */}
      {editingFaq && (
        <div className="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl border border-[#E4E7EC] max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div className="flex items-center justify-between pb-3 border-b border-[#E4E7EC]">
              <h3 className="font-heading font-bold text-sm text-[#202124]">
                Editar Pergunta Frequente
              </h3>
              <button
                onClick={() => setEditingFaq(null)}
                className="text-[#697386] hover:text-[#202124]"
              >
                <X className="w-5 h-5" />
              </button>
            </div>

            <form onSubmit={handleSaveEdit} className="space-y-3 text-xs">
              <div>
                <label className="block text-xs font-semibold text-[#202124] mb-1">
                  Pergunta
                </label>
                <input
                  type="text"
                  required
                  value={editingFaq.question}
                  onChange={(e) => setEditingFaq({ ...editingFaq, question: e.target.value })}
                  className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"
                />
              </div>

              <div>
                <label className="block text-xs font-semibold text-[#202124] mb-1">
                  Categoria
                </label>
                <input
                  type="text"
                  value={editingFaq.category}
                  onChange={(e) => setEditingFaq({ ...editingFaq, category: e.target.value })}
                  className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"
                />
              </div>

              <div>
                <label className="block text-xs font-semibold text-[#202124] mb-1">
                  Resposta
                </label>
                <textarea
                  rows={3}
                  required
                  value={editingFaq.answer}
                  onChange={(e) => setEditingFaq({ ...editingFaq, answer: e.target.value })}
                  className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"
                />
              </div>

              <div className="pt-2 flex justify-end gap-2">
                <button
                  type="button"
                  onClick={() => setEditingFaq(null)}
                  className="px-4 py-2 rounded-lg border border-[#E4E7EC] text-xs font-semibold"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  className="px-5 py-2 rounded-lg bg-[#D71920] text-white text-xs font-semibold shadow-xs"
                >
                  Salvar
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </AdminLayout>
  );
};
