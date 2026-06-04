import React from "react";

interface StatWidgetProps {
  title: string;
  value: string;
  trend: "up" | "down" | "neutral";
  trendValue: string;
}

export const StatWidget: React.FC<StatWidgetProps> = ({ title, value, trend, trendValue }) => {
  return (
    <div className="bg-white/60 backdrop-blur-xl border border-white/40 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 rounded-[2rem] flex flex-col gap-4 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300">
      <div className="flex justify-between items-center">
         <div className="flex items-center gap-2">
           <div className={`w-2 h-2 rounded-full ${trend === 'up' ? 'bg-danger' : trend === 'down' ? 'bg-safe' : 'bg-warning'} shadow-sm`} />
           <h3 className="text-slate-500 font-medium text-sm tracking-wide">{title}</h3>
         </div>
      </div>

      <div className="flex items-end justify-between">
        <span className="text-slate-800 text-4xl font-bold tracking-tight">{value}</span>

        <div className={`flex items-center px-2.5 py-1 rounded-full text-xs font-semibold ${
          trend === 'up' ? 'text-danger bg-danger/10' :
          trend === 'down' ? 'text-safe bg-safe/10' :
          'text-warning bg-warning/10'
        }`}>
          {trend === "up" && <span>↑ {trendValue}</span>}
          {trend === "down" && <span>↓ {trendValue}</span>}
          {trend === "neutral" && <span>— {trendValue}</span>}
        </div>
      </div>
    </div>
  );
};
