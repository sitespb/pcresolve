import React, { useState } from 'react';
import { useApp } from '../../context/AppContext';
import { AdminLayout } from '../../components/admin/AdminLayout';
import { MapPin, Truck, CheckCircle2, XCircle, Edit2, Save, X } from 'lucide-react';
import { CityArea } from '../../types';

export const AdminCities: React.FC = () => {
  const { cities, toggleCityStatus, updateCityFee, showToast } = useApp();

  const [editingCity, setEditingCity] = useState<CityArea | null>(null);
  const [feeInput, setFeeInput] = useState<number>(0);

  const startEdit = (c: CityArea) => {
    setEditingCity(c);
    setFeeInput(c.deliveryFee);
  };

  const handleSaveFee = () => {
    if (!editingCity) return;
    updateCityFee(editingCity.id, Number(feeInput));
    setEditingCity(null);
  };

  return (
    <AdminLayout
      title="Cidades Atendidas &amp; Logística Leva e Traz"
      subtitle="Definição das cidades com cobertura na Grande João Pessoa e taxas do serviço de coleta em domicílio"
    >
      <div className="bg-white rounded-2xl border border-[#E4E7EC] shadow-2xs overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs">
            <thead>
              <tr className="bg-[#F5F6F8] border-b border-[#E4E7EC] text-[#697386] uppercase tracking-wider text-[10px]">
                <th className="py-3 px-4 font-bold">Município</th>
                <th className="py-3 px-4 font-bold">Bairros e Região</th>
                <th className="py-3 px-4 font-bold">Leva e Traz</th>
                <th className="py-3 px-4 font-bold">Taxa de Coleta</th>
                <th className="py-3 px-4 font-bold">Status</th>
                <th className="py-3 px-4 font-bold text-right">Ação</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-[#E4E7EC]">
              {cities.map((city) => (
                <tr key={city.id} className="hover:bg-[#F5F6F8]/60 transition-colors">
                  <td className="py-3.5 px-4 font-bold text-[#202124] flex items-center gap-2">
                    <MapPin className="w-4 h-4 text-[#D71920]" />
                    <span>{city.name}</span>
                  </td>
                  <td className="py-3.5 px-4 text-[#697386] max-w-xs">{city.coverage}</td>
                  <td className="py-3.5 px-4">
                    {city.deliveryAvailable ? (
                      <span className="inline-flex items-center gap-1 text-[11px] text-emerald-700 font-semibold bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                        <Truck className="w-3 h-3" />
                        Disponível
                      </span>
                    ) : (
                      <span className="text-[#697386] text-[11px]">Sob consulta</span>
                    )}
                  </td>
                  <td className="py-3.5 px-4 font-mono font-bold text-[#202124]">
                    {city.deliveryFee === 0 ? 'Grátis (> R$150)' : `R$ ${city.deliveryFee.toFixed(2)}`}
                  </td>
                  <td className="py-3.5 px-4">
                    <button
                      onClick={() => toggleCityStatus(city.id)}
                      className={`inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold border cursor-pointer transition-colors ${
                        city.active
                          ? 'bg-emerald-50 text-emerald-800 border-emerald-200'
                          : 'bg-neutral-100 text-neutral-600 border-neutral-300'
                      }`}
                    >
                      {city.active ? 'Ativa' : 'Inativa'}
                    </button>
                  </td>
                  <td className="py-3.5 px-4 text-right">
                    <button
                      onClick={() => startEdit(city)}
                      className="px-2.5 py-1 rounded-md border border-[#E4E7EC] hover:bg-neutral-100 text-[11px] font-semibold text-[#202124]"
                    >
                      Ajustar Taxa
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {/* Edit Fee Modal */}
      {editingCity && (
        <div className="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl border border-[#E4E7EC] max-w-sm w-full p-6 shadow-2xl space-y-4">
            <div className="flex items-center justify-between pb-3 border-b border-[#E4E7EC]">
              <h3 className="font-heading font-bold text-sm text-[#202124]">
                Ajustar Taxa de Coleta - {editingCity.name}
              </h3>
              <button
                onClick={() => setEditingCity(null)}
                className="text-[#697386] hover:text-[#202124]"
              >
                <X className="w-4 h-4" />
              </button>
            </div>

            <div className="space-y-3 text-xs">
              <div>
                <label className="block text-xs font-semibold text-[#202124] mb-1">
                  Taxa de Deslocamento / Coleta (R$)
                </label>
                <input
                  type="number"
                  step="5"
                  value={feeInput}
                  onChange={(e) => setFeeInput(Number(e.target.value))}
                  className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"
                />
                <span className="text-[10px] text-[#697386] mt-1 block">
                  Informe 0 para gratuidade promocional ou regras especiais.
                </span>
              </div>

              <div className="pt-2 flex justify-end gap-2">
                <button
                  onClick={() => setEditingCity(null)}
                  className="px-3.5 py-1.5 rounded-lg border border-[#E4E7EC] text-xs font-semibold"
                >
                  Cancelar
                </button>
                <button
                  onClick={handleSaveFee}
                  className="px-4 py-1.5 rounded-lg bg-[#D71920] text-white text-xs font-semibold shadow-xs"
                >
                  Salvar
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
    </AdminLayout>
  );
};
