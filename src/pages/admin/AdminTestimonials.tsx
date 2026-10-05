import React, { useState } from 'react';
import { useApp } from '../../context/AppContext';
import { AdminLayout } from '../../components/admin/AdminLayout';
import { Star, CheckCircle2, XCircle, Plus, X } from 'lucide-react';

export const AdminTestimonials: React.FC = () => {
  const { testimonials, approveTestimonial, rejectTestimonial, addTestimonial } = useApp();

  const [isModalOpen, setIsModalOpen] = useState(false);
  const [author, setAuthor] = useState('');
  const [location, setLocation] = useState('Manaíra, João Pessoa');
  const [rating, setRating] = useState(5);
  const [serviceTitle, setServiceTitle] = useState('Manutenção de Notebook');
  const [text, setText] = useState('');

  const handleCreate = (e: React.FormEvent) => {
    e.preventDefault();
    if (!author.trim() || !text.trim()) return;

    addTestimonial({
      author,
      location,
      rating,
      serviceTitle,
      text,
    });

    setIsModalOpen(false);
    setAuthor('');
    setText('');
  };

  return (
    <AdminLayout
      title="Moderação de Avaliações &amp; Depoimentos"
      subtitle="Aprovação e controle das opiniões verificadas de clientes publicadas no website"
      action={
        <button
          onClick={() => setIsModalOpen(true)}
          className="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer"
        >
          <Plus className="w-3.5 h-3.5" />
          <span>Novo Depoimento</span>
        </button>
      }
    >
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {testimonials.map((test) => (
          <div
            key={test.id}
            className="bg-white rounded-2xl border border-[#E4E7EC] p-5 shadow-2xs flex flex-col justify-between space-y-4"
          >
            <div className="space-y-3">
              <div className="flex items-center justify-between">
                <div className="flex items-center gap-1 text-amber-500">
                  {[...Array(test.rating)].map((_, i) => (
                    <Star key={i} className="w-3.5 h-3.5 fill-amber-500" />
                  ))}
                </div>
                <span
                  className={`px-2 py-0.5 rounded-full text-[10px] font-semibold border ${
                    test.approved
                      ? 'bg-emerald-50 text-emerald-800 border-emerald-200'
                      : 'bg-amber-50 text-amber-800 border-amber-200'
                  }`}
                >
                  {test.approved ? 'Aprovado & Público' : 'Aguardando Aprovação'}
                </span>
              </div>

              <p className="text-xs text-[#202124] italic leading-relaxed">
                "{test.text}"
              </p>

              <div className="text-[11px] text-[#697386]">
                <strong className="text-[#202124] block">{test.author}</strong>
                <span>{test.location} · {test.date}</span>
                <span className="block text-[#D71920] font-medium">{test.serviceTitle}</span>
              </div>
            </div>

            <div className="pt-3 border-t border-[#E4E7EC] flex items-center justify-end gap-2">
              {test.approved ? (
                <button
                  onClick={() => rejectTestimonial(test.id)}
                  className="px-3 py-1.5 rounded-lg border border-[#E4E7EC] hover:bg-neutral-50 text-xs font-semibold text-neutral-600 transition-colors"
                >
                  Ocultar do Site
                </button>
              ) : (
                <button
                  onClick={() => approveTestimonial(test.id)}
                  className="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition-colors flex items-center gap-1"
                >
                  <CheckCircle2 className="w-3.5 h-3.5" />
                  <span>Aprovar Publicação</span>
                </button>
              )}
            </div>
          </div>
        ))}
      </div>

      {/* Modal: New Testimonial */}
      {isModalOpen && (
        <div className="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl border border-[#E4E7EC] max-w-md w-full p-6 shadow-2xl space-y-4">
            <div className="flex items-center justify-between pb-3 border-b border-[#E4E7EC]">
              <h3 className="font-heading font-bold text-sm text-[#202124]">
                Cadastrar Depoimento
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
                  Nome do Cliente
                </label>
                <input
                  type="text"
                  required
                  placeholder="Ex: Dra. Mariana Costa"
                  value={author}
                  onChange={(e) => setAuthor(e.target.value)}
                  className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"
                />
              </div>

              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    Bairro / Cidade
                  </label>
                  <input
                    type="text"
                    value={location}
                    onChange={(e) => setLocation(e.target.value)}
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    Nota (Estrelas)
                  </label>
                  <select
                    value={rating}
                    onChange={(e) => setRating(Number(e.target.value))}
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"
                  >
                    <option value={5}>5 estrelas (Excelente)</option>
                    <option value={4}>4 estrelas (Muito bom)</option>
                    <option value={3}>3 estrelas (Regular)</option>
                  </select>
                </div>
              </div>

              <div>
                <label className="block text-xs font-semibold text-[#202124] mb-1">
                  Serviço Realizado
                </label>
                <input
                  type="text"
                  value={serviceTitle}
                  onChange={(e) => setServiceTitle(e.target.value)}
                  className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"
                />
              </div>

              <div>
                <label className="block text-xs font-semibold text-[#202124] mb-1">
                  Texto do Depoimento
                </label>
                <textarea
                  rows={3}
                  required
                  placeholder="Feedback relatado pelo cliente..."
                  value={text}
                  onChange={(e) => setText(e.target.value)}
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
    </AdminLayout>
  );
};
