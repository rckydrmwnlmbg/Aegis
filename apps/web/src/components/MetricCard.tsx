interface MetricCardProps {
  title: string;
  value: string | number;
  trend?: string;
  isWarning?: boolean;
}

export function MetricCard({ title, value, trend, isWarning }: MetricCardProps) {
  return (
    <div className="glass-panel p-6 flex flex-col gap-2">
      <h3 className="text-sm font-medium text-gray-500">{title}</h3>
      <div className="flex items-end justify-between">
        <span className="text-3xl font-bold text-gray-800">{value}</span>
        {trend && (
          <span className={`text-sm font-medium ${isWarning ? 'text-hse-red' : 'text-emerald-500'}`}>
            {trend}
          </span>
        )}
      </div>
      {isWarning && (
        <div className="h-1 w-full bg-red-100 rounded-full mt-2 overflow-hidden">
          <div className="h-full bg-hse-red w-3/4 rounded-full"></div>
        </div>
      )}
    </div>
  );
}
