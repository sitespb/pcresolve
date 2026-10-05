import React from 'react';

interface LogoProps {
  variant?: 'light' | 'dark';
  size?: 'sm' | 'md' | 'lg';
  className?: string;
}

export const Logo: React.FC<LogoProps> = ({ variant = 'light', size = 'md', className = '' }) => {
  const isDark = variant === 'dark';

  const iconSizes = {
    sm: 'w-8 h-8 rounded-lg',
    md: 'w-10 h-10 rounded-xl',
    lg: 'w-12 h-12 rounded-xl',
  };

  const titleSizes = {
    sm: 'text-lg',
    md: 'text-2xl',
    lg: 'text-3xl',
  };

  const subSizes = {
    sm: 'text-[7.5px]',
    md: 'text-[9px]',
    lg: 'text-[10.5px]',
  };

  return (
    <div className={`flex items-center gap-3 select-none ${className}`}>
      {/* Icon: Red rounded square with circuit traces and central checkmark */}
      <div
        className={`${iconSizes[size]} bg-[#D71920] flex items-center justify-center text-white shrink-0 shadow-sm relative overflow-hidden`}
        aria-hidden="true"
      >
        {/* Subtle circuit pin lines */}
        <div className="absolute inset-0 flex items-center justify-center opacity-90">
          <div className="w-full h-[2px] bg-white/40 absolute"></div>
          <div className="h-full w-[2px] bg-white/40 absolute"></div>
        </div>

        {/* Inner white pill/circle with red check */}
        <div className="w-5 h-5 rounded-md bg-white flex items-center justify-center shadow-xs z-10">
          <svg
            className="w-3.5 h-3.5 text-[#D71920]"
            viewBox="0 0 20 20"
            fill="currentColor"
          >
            <path
              fillRule="evenodd"
              d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
              clipRule="evenodd"
            />
          </svg>
        </div>
      </div>

      {/* Typography: PC RESOLVE */}
      <div className="flex flex-col leading-none">
        <span
          className={`font-heading font-extrabold tracking-tight ${titleSizes[size]} ${
            isDark ? 'text-white' : 'text-[#202124]'
          }`}
        >
          PC <span className="text-[#D71920]">RESOLVE</span>
        </span>
        <span
          className={`${subSizes[size]} font-bold tracking-[0.16em] uppercase mt-0.5 ${
            isDark ? 'text-neutral-400' : 'text-[#697386]'
          }`}
        >
          Tecnologia &amp; Assistência Técnica
        </span>
      </div>
    </div>
  );
};
