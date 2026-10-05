import React from 'react';
import { useApp } from '../../context/AppContext';
import { CheckCircle2, Info, AlertTriangle, XCircle, X } from 'lucide-react';

export const ToastContainer: React.FC = () => {
  const { toasts, removeToast } = useApp();

  if (toasts.length === 0) return null;

  return (
    <div className="fixed top-5 right-5 z-50 flex flex-col gap-2 max-w-sm w-full pointer-events-none">
      {toasts.map((toast) => {
        const icons = {
          success: <CheckCircle2 className="w-5 h-5 text-emerald-600 shrink-0" />,
          info: <Info className="w-5 h-5 text-blue-600 shrink-0" />,
          warning: <AlertTriangle className="w-5 h-5 text-amber-600 shrink-0" />,
          error: <XCircle className="w-5 h-5 text-red-600 shrink-0" />,
        };

        const borders = {
          success: 'border-emerald-200 bg-emerald-50/95 text-emerald-950',
          info: 'border-blue-200 bg-blue-50/95 text-blue-950',
          warning: 'border-amber-200 bg-amber-50/95 text-amber-950',
          error: 'border-red-200 bg-red-50/95 text-red-950',
        };

        return (
          <div
            key={toast.id}
            className={`pointer-events-auto p-3.5 rounded-xl border shadow-lg backdrop-blur-md flex items-center justify-between gap-3 text-xs font-medium transition-all animate-in slide-in-from-top-2 ${
              borders[toast.type]
            }`}
          >
            <div className="flex items-center gap-2.5">
              {icons[toast.type]}
              <span>{toast.text}</span>
            </div>
            <button
              onClick={() => removeToast(toast.id)}
              className="text-neutral-400 hover:text-neutral-700 p-1 rounded transition-colors"
              aria-label="Fechar notificação"
            >
              <X className="w-4 h-4" />
            </button>
          </div>
        );
      })}
    </div>
  );
};
