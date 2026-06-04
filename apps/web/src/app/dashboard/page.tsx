import { StatWidget } from "@/components/StatWidget";

export default function DashboardPage() {
  return (
    <div className="space-y-8">
      <div>
        <h1 className="text-3xl font-bold text-slate-800 tracking-tight">Dashboard Overview</h1>
        <p className="text-slate-500 mt-2 font-medium">Your EHS operations are performing excellently today ✨</p>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <StatWidget title="OPEN INCIDENTS" value="12" trend="up" trendValue="2" />
        <StatWidget title="PENDING PERMITS" value="5" trend="neutral" trendValue="0" />
        <StatWidget title="OVERDUE CAPA" value="2" trend="down" trendValue="1" />
        <StatWidget title="SAFETY SCORE" value="94%" trend="up" trendValue="1%" />
      </div>

      <div className="bg-white/60 backdrop-blur-xl border border-white/40 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 rounded-[2rem] min-h-[400px]">
        <h3 className="text-lg font-semibold text-slate-700 mb-6 tracking-tight">Recent Activity Trends</h3>
        <div className="flex items-center justify-center h-64 text-slate-400 font-medium bg-white/30 rounded-2xl border border-white/50 border-dashed">
          Interactive Chart Area
        </div>
      </div>
    </div>
  );
}
