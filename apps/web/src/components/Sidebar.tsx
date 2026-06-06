import Link from 'next/link';

export function Sidebar() {
  return (
    <aside className="w-64 h-[calc(100vh-2rem)] bg-white rounded-3xl shadow-sm m-4 p-6 flex flex-col gap-6 sticky top-4 shrink-0">
      <div className="flex items-center gap-3 px-2">
        <div className="w-8 h-8 rounded-full bg-hse-red flex items-center justify-center">
          <span className="text-white font-bold text-sm">A</span>
        </div>
        <h1 className="text-xl font-extrabold text-slate-900">Aegis</h1>
      </div>

      <nav className="flex flex-col gap-2">
        <Link href="/dashboard" className="px-4 py-3 rounded-2xl hover:bg-slate-50 transition-colors font-semibold text-slate-700">Dashboard</Link>
        <Link href="/dashboard/triage" className="px-4 py-3 rounded-2xl hover:bg-slate-50 transition-colors font-semibold text-slate-700 flex items-center justify-between">
          Triage Dashboard
          <span className="w-2 h-2 rounded-full bg-hse-red"></span>
        </Link>
        <Link href="/dashboard/incidents" className="px-4 py-3 rounded-2xl hover:bg-slate-50 transition-colors font-semibold text-slate-700">Incidents</Link>
        <Link href="/dashboard/capa" className="px-4 py-3 rounded-2xl hover:bg-slate-50 transition-colors font-semibold text-slate-700">CAPA</Link>
        <Link href="/dashboard/ptw" className="px-4 py-3 rounded-2xl hover:bg-slate-50 transition-colors font-semibold text-slate-700">PTW</Link>
        <Link href="/dashboard/copilot" className="px-4 py-3 rounded-2xl hover:bg-slate-50 transition-colors font-semibold text-slate-700">Copilot</Link>
      </nav>
    </aside>
  );
}
