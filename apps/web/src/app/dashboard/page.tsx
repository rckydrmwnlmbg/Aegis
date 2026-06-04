'use client';

import { useEffect, useState } from 'react';
import { StatWidget } from "@/components/StatWidget";
import { Skeleton } from "@/components/Skeleton";
import api from "@/lib/api";

interface DashboardData {
  total_open_incidents: number;
  total_overdue_capas: number;
  active_ptws: number;
  hazard_participation_rate: number;
}

export default function DashboardPage() {
  const [data, setData] = useState<DashboardData | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchSummary = async () => {
      try {
        const response = await api.get('/api/v1/analytics/summary');
        setData(response.data.data);
        setError(null);
      } catch (err) {
        console.error('Failed to fetch dashboard data:', err);
        setError('Failed to load dashboard data.');
      } finally {
        setLoading(false);
      }
    };

    fetchSummary();
  }, []);

  if (loading) {
    return (
      <div className="space-y-8">
        <div>
          <h1 className="text-3xl font-bold text-slate-800 tracking-tight">Dashboard Overview</h1>
          <p className="text-slate-500 mt-2 font-medium">Your EHS operations are performing excellently today ✨</p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <Skeleton className="h-[148px]" />
          <Skeleton className="h-[148px]" />
          <Skeleton className="h-[148px]" />
          <Skeleton className="h-[148px]" />
        </div>

        <Skeleton className="h-[400px] w-full" />
      </div>
    );
  }

  if (error || !data) {
    return (
      <div className="p-8 text-center text-slate-500 bg-white/60 backdrop-blur-xl border border-white/40 shadow-sm rounded-[2rem]">
        {error || 'No data available'}
      </div>
    );
  }

  return (
    <div className="space-y-8">
      <div>
        <h1 className="text-3xl font-bold text-slate-800 tracking-tight">Dashboard Overview</h1>
        <p className="text-slate-500 mt-2 font-medium">Your EHS operations are performing excellently today ✨</p>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <StatWidget
          title="OPEN INCIDENTS"
          value={data.total_open_incidents.toString()}
          trend={data.total_open_incidents > 0 ? "up" : "neutral"}
          trendValue={data.total_open_incidents > 0 ? `+${data.total_open_incidents}` : "0"}
        />
        <StatWidget
          title="PENDING PERMITS"
          value={data.active_ptws.toString()}
          trend="neutral"
          trendValue="0"
        />
        <StatWidget
          title="OVERDUE CAPA"
          value={data.total_overdue_capas.toString()}
          trend={data.total_overdue_capas > 0 ? "down" : "neutral"}
          trendValue={data.total_overdue_capas > 0 ? `+${data.total_overdue_capas}` : "0"}
        />
        <StatWidget
          title="SAFETY SCORE"
          value={`${data.hazard_participation_rate}%`}
          trend={data.hazard_participation_rate >= 80 ? "up" : data.hazard_participation_rate < 50 ? "down" : "neutral"}
          trendValue="N/A"
        />
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
