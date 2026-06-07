"use client";

import { ArrowUpIcon, SparklesIcon } from '@heroicons/react/24/solid';

export default function CopilotPage() {
  return (
    <div className="max-w-5xl mx-auto space-y-6">
      <header>
        <h1 className="text-3xl font-bold text-slate-900 tracking-tight">Aegis Copilot</h1>
        <p className="text-slate-500 mt-1">Your AI Safety Assistant</p>
      </header>

      {/* Main Container */}
      <div className="bg-white rounded-3xl shadow-sm h-[85vh] flex flex-col overflow-hidden border border-slate-100">

        {/* Chat History Area (Scrollable) */}
        <div className="flex-grow overflow-y-auto p-6 space-y-6">

          {/* AI Bubble */}
          <div className="flex items-start gap-4 max-w-[85%]">
            <div className="flex-shrink-0 w-10 h-10 bg-slate-900 rounded-full flex items-center justify-center shadow-sm">
              <SparklesIcon className="w-5 h-5 text-white" />
            </div>
            <div className="bg-slate-50 text-slate-800 px-6 py-4 rounded-3xl rounded-tl-none">
              <p className="leading-relaxed">
                Hello! I am Aegis Copilot. Please upload your incident photo or ask me about safety compliance.
              </p>
            </div>
          </div>

          {/* User Bubble */}
          <div className="flex items-start gap-4 max-w-[85%] ml-auto flex-row-reverse">
            <div className="flex-shrink-0 w-10 h-10 bg-slate-200 rounded-full flex items-center justify-center shadow-sm">
              <span className="text-sm font-bold text-slate-700">US</span>
            </div>
            <div className="bg-slate-900 text-white px-6 py-4 rounded-3xl rounded-tr-none">
              <p className="leading-relaxed">
                Tolong analisis foto scaffolding ini, apakah ada pelanggaran standar LOTO atau JSA?
              </p>
            </div>
          </div>

        </div>

        {/* Sticky Input Bar */}
        <div className="p-4 bg-white border-t border-slate-50">
          <form className="relative flex items-center w-full" onSubmit={(e) => e.preventDefault()}>
            <input
              type="text"
              placeholder="Message Aegis Copilot..."
              className="w-full bg-slate-50 border border-slate-200 rounded-full pl-6 pr-14 py-4 text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-shadow"
            />
            <button
              type="submit"
              className="absolute right-2 top-1/2 -translate-y-1/2 bg-slate-900 text-white rounded-full p-2.5 hover:bg-slate-800 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900"
            >
              <ArrowUpIcon className="w-5 h-5" />
            </button>
          </form>
          <div className="text-center mt-3">
             <p className="text-xs text-slate-400">Aegis Copilot can make mistakes. Consider verifying important information.</p>
          </div>
        </div>

      </div>
    </div>
  );
}
