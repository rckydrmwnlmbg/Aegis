interface MetricCardProps {
  title: string;
  value: string | number;
  trend?: string;
  isWarning?: boolean;
}

export function MetricCard({ title, value, trend, isWarning }: MetricCardProps) {
  return (
    <div className="bg-white rounded-3xl shadow-sm p-6 flex flex-col gap-2 border border-slate-100">
      <h3 className="text-sm font-semibold text-slate-500 uppercase tracking-wider">{title}</h3>
      <div className="flex items-end justify-between mt-2">
        <span className="text-4xl font-extrabold text-slate-900">{value}</span>
        {trend && (
          <span className={`px-3 py-1 rounded-full text-xs font-bold ${isWarning ? 'bg-red-50 text-hse-red' : 'bg-emerald-50 text-emerald-600'}`}>
            {trend}
          </span>
        )}
      </div>
      {isWarning && (
        <div className="h-1.5 w-full bg-slate-100 rounded-full mt-4 overflow-hidden">
          <div className="h-full bg-hse-red w-3/4 rounded-full"></div>
        </div>
      )}
    </div>
  );
}
