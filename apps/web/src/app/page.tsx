import Link from 'next/link';

export default function Home() {
  return (
    <main className="min-h-screen relative overflow-hidden bg-slate-50 text-slate-800">
      {/* Soft gradient mesh backgrounds */}
      <div className="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-blue-100/60 blur-[100px] pointer-events-none" />
      <div className="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-indigo-100/60 blur-[100px] pointer-events-none" />
      <div className="absolute top-[20%] right-[10%] w-[30%] h-[30%] rounded-full bg-red-50/40 blur-[80px] pointer-events-none" />

      <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-16 flex flex-col items-center min-h-screen justify-center space-y-16">

        {/* Hero Section */}
        <section className="text-center space-y-8 max-w-4xl">
          <div className="inline-flex items-center space-x-2 px-4 py-2 rounded-full bg-white/60 backdrop-blur-xl border border-white/40 shadow-sm text-sm font-medium text-slate-600 mb-4">
            <span className="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
            <span>Next Generation HSE Platform</span>
          </div>

          <h1 className="text-5xl md:text-7xl font-bold tracking-tight text-slate-900 drop-shadow-sm">
            <span className="bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-600">
              Aegis
            </span>
            <br />
            AI-Driven Enterprise HSE System
          </h1>

          <p className="text-xl text-slate-600 leading-relaxed max-w-2xl mx-auto font-light">
            Empower your workforce with intelligent safety management. Predictive insights, seamless compliance, and total visibility in one unified platform.
          </p>

          <div className="pt-8">
            <Link
              href="/dashboard"
              className="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold rounded-[2rem] text-white bg-slate-900 hover:bg-slate-800 shadow-xl shadow-slate-900/20 transition-all hover:-translate-y-0.5 hover:shadow-2xl hover:shadow-slate-900/30 ring-1 ring-slate-900/50 relative overflow-hidden group"
            >
              <span className="relative z-10">Enter Dashboard</span>
              <div className="absolute inset-0 bg-gradient-to-r from-slate-800 to-slate-900 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </Link>
          </div>
        </section>

        {/* Features Section */}
        <section className="w-full grid grid-cols-1 md:grid-cols-3 gap-8 px-4 mt-20">

          {/* Feature 1 */}
          <div className="group relative rounded-3xl bg-white/60 backdrop-blur-xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 hover:bg-white/80 transition-all hover:-translate-y-1">
            <div className="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-6 shadow-sm border border-blue-100/50">
               <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <h3 className="text-xl font-bold text-slate-900 mb-3">Smart Capture Triage</h3>
            <p className="text-slate-600 font-light leading-relaxed">
              AI-assisted incident reporting. Capture hazards with photos and let the system auto-categorize and prioritize in real-time.
            </p>
          </div>

          {/* Feature 2 */}
          <div className="group relative rounded-3xl bg-white/60 backdrop-blur-xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 hover:bg-white/80 transition-all hover:-translate-y-1">
            <div className="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-6 shadow-sm border border-indigo-100/50">
              <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 className="text-xl font-bold text-slate-900 mb-3">Digital Permit to Work</h3>
            <p className="text-slate-600 font-light leading-relaxed">
              Streamline high-risk operations. Fully digital PTW workflows with automated clash detection and remote approvals.
            </p>
          </div>

          {/* Feature 3 */}
          <div className="group relative rounded-3xl bg-white/60 backdrop-blur-xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 hover:bg-white/80 transition-all hover:-translate-y-1">
            <div className="w-12 h-12 rounded-2xl bg-slate-50 text-slate-700 flex items-center justify-center mb-6 shadow-sm border border-slate-200/50 relative">
               <div className="absolute top-[-4px] right-[-4px] w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></div>
               <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 className="text-xl font-bold text-slate-900 mb-3">Offline-First Inspections</h3>
            <p className="text-slate-600 font-light leading-relaxed">
              Conduct comprehensive safety audits anywhere. True offline-first capability ensures you never lose data in remote sites.
            </p>
          </div>

        </section>
      </div>
    </main>
  );
}
