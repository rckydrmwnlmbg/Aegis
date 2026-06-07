"use client";

import { useEffect, useState } from "react";
import Link from "next/link";
import api from "@/lib/api";
import {
  CheckCircle,
  AlertTriangle,
  Clock,
  ShieldCheck,
  BarChart3,
  ArrowRight
} from "lucide-react";

type TriageItem = {
  id: string;
  type: "incident" | "hazard";
  title: string;
  description: string;
  status: string;
  created_at: string;
};

export default function TriageDashboardPage() {
  const [items, setItems] = useState<TriageItem[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchTriageItems = async () => {
      try {
        setLoading(true);
        // Fetch incidents
        const resIncidents = await api.get("/api/v1/incidents?status=draft_ready");
        const incidents: TriageItem[] = resIncidents.data.data.map((item: { id: string; title: string; description: string; status: string; created_at: string; } ) => ({
          id: String(item.id),
          type: "incident",
          title: String(item.title || "") || "Untitled Incident",
          description: String(item.description || "") || "No description provided",
          status: String(item.status),
          created_at: String(item.created_at),
        }));

        const allItems = [...incidents];
        allItems.sort((a, b) => new Date(b.created_at).getTime() - new Date(a.created_at).getTime());
        setItems(allItems);
      } catch (error) {
        console.error("Failed to fetch triage items:", error);
      } finally {
        setLoading(false);
      }
    };

    fetchTriageItems();
  }, []);

  return (
    <div className="max-w-7xl mx-auto space-y-6 text-slate-800">
      <div className="flex justify-between items-end">
        <div>
          <h1 className="text-3xl font-extrabold tracking-tight">Triage Dashboard</h1>
          <p className="text-slate-500 font-medium mt-1">Real-time K3 operational analytics and approvals.</p>
        </div>
        <div className="flex items-center space-x-3">
            <span className="text-sm font-semibold text-slate-500">Live Status</span>
            <span className="flex h-3 w-3">
              <span className="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-emerald-400 opacity-75"></span>
              <span className="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
            </span>
        </div>
      </div>

      {/* KPI Cards (Top Row) */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div className="bg-white rounded-3xl p-6 shadow-sm flex flex-col justify-between">
          <div className="flex justify-between items-start">
            <div>
              <p className="text-sm font-bold text-slate-500 mb-1">Active PTW</p>
              <h2 className="text-4xl font-extrabold">24</h2>
            </div>
            <div className="bg-emerald-50 text-emerald-600 p-3 rounded-2xl">
              <CheckCircle size={24} strokeWidth={2.5} />
            </div>
          </div>
          <div className="mt-4 text-sm font-semibold text-emerald-600 flex items-center">
            <span className="mr-1">↑ 12%</span> vs last week
          </div>
        </div>

        <div className="bg-white rounded-3xl p-6 shadow-sm flex flex-col justify-between">
          <div className="flex justify-between items-start">
            <div>
              <p className="text-sm font-bold text-slate-500 mb-1">Open Incidents</p>
              <h2 className="text-4xl font-extrabold">3</h2>
            </div>
            <div className="bg-red-50 text-red-600 p-3 rounded-2xl">
              <AlertTriangle size={24} strokeWidth={2.5} />
            </div>
          </div>
          <div className="mt-4 text-sm font-semibold text-red-600 flex items-center">
             <span className="mr-1">Requires attention</span>
          </div>
        </div>

        <div className="bg-white rounded-3xl p-6 shadow-sm flex flex-col justify-between">
          <div className="flex justify-between items-start">
            <div>
              <p className="text-sm font-bold text-slate-500 mb-1">Pending CAPA</p>
              <h2 className="text-4xl font-extrabold">8</h2>
            </div>
            <div className="bg-amber-50 text-amber-600 p-3 rounded-2xl">
              <Clock size={24} strokeWidth={2.5} />
            </div>
          </div>
          <div className="mt-4 text-sm font-semibold text-slate-500 flex items-center">
            3 nearing SLA deadline
          </div>
        </div>

        <div className="bg-slate-900 rounded-3xl p-6 shadow-sm flex flex-col justify-between text-white">
          <div className="flex justify-between items-start">
            <div>
              <p className="text-sm font-bold text-slate-400 mb-1">Safe Work Days</p>
              <h2 className="text-4xl font-extrabold text-emerald-400">142</h2>
            </div>
            <div className="bg-slate-800 text-emerald-400 p-3 rounded-2xl">
              <ShieldCheck size={24} strokeWidth={2.5} />
            </div>
          </div>
          <div className="mt-4 text-sm font-semibold text-slate-400 flex items-center">
            Target: 365 days
          </div>
        </div>
      </div>

      {/* Main Content Grid */}
      <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {/* Chart Section (Middle Row / Left Side) */}
        <div className="lg:col-span-8 bg-white rounded-3xl p-8 shadow-sm flex flex-col min-h-[400px]">
          <div className="flex justify-between items-center mb-6">
            <h3 className="text-xl font-extrabold flex items-center gap-2">
               <BarChart3 className="text-slate-400" size={20} />
               Incidents by Category (YTD)
            </h3>
            <select className="bg-slate-50 border-none text-sm font-semibold rounded-full px-4 py-2 focus:ring-0 cursor-pointer">
              <option>This Year</option>
              <option>Last 6 Months</option>
            </select>
          </div>

          {/* Dummy Bar Chart Layout using CSS */}
          <div className="flex-1 flex items-end justify-around gap-2 mt-4 relative">
             {/* Y-axis lines */}
             <div className="absolute inset-0 flex flex-col justify-between pointer-events-none">
                <div className="w-full h-px bg-slate-100"></div>
                <div className="w-full h-px bg-slate-100"></div>
                <div className="w-full h-px bg-slate-100"></div>
                <div className="w-full h-px bg-slate-100"></div>
                <div className="w-full h-px bg-slate-100"></div>
             </div>

             {/* Bars */}
             <div className="relative flex flex-col items-center group w-full max-w-[60px]">
               <div className="w-full bg-slate-200 rounded-t-lg h-24 group-hover:bg-indigo-500 transition-colors"></div>
               <span className="text-xs font-bold text-slate-500 mt-3">Jan</span>
             </div>
             <div className="relative flex flex-col items-center group w-full max-w-[60px]">
               <div className="w-full bg-slate-200 rounded-t-lg h-32 group-hover:bg-indigo-500 transition-colors"></div>
               <span className="text-xs font-bold text-slate-500 mt-3">Feb</span>
             </div>
             <div className="relative flex flex-col items-center group w-full max-w-[60px]">
               <div className="w-full bg-slate-200 rounded-t-lg h-16 group-hover:bg-indigo-500 transition-colors"></div>
               <span className="text-xs font-bold text-slate-500 mt-3">Mar</span>
             </div>
             <div className="relative flex flex-col items-center group w-full max-w-[60px]">
               <div className="w-full bg-slate-200 rounded-t-lg h-48 group-hover:bg-indigo-500 transition-colors"></div>
               <span className="text-xs font-bold text-slate-500 mt-3">Apr</span>
             </div>
             <div className="relative flex flex-col items-center group w-full max-w-[60px]">
               <div className="w-full bg-slate-200 rounded-t-lg h-20 group-hover:bg-indigo-500 transition-colors"></div>
               <span className="text-xs font-bold text-slate-500 mt-3">May</span>
             </div>
             <div className="relative flex flex-col items-center group w-full max-w-[60px]">
               <div className="w-full bg-indigo-500 rounded-t-lg h-56 transition-colors shadow-sm shadow-indigo-200"></div>
               <span className="text-xs font-bold text-indigo-600 mt-3">Jun</span>
             </div>
          </div>
        </div>

        {/* Action Feed (Side) */}
        <div className="lg:col-span-4 bg-white rounded-3xl p-6 shadow-sm flex flex-col">
          <div className="flex justify-between items-center mb-6">
            <h3 className="text-lg font-extrabold">Recent Flagged Triage</h3>
            <span className="bg-red-100 text-red-700 text-xs font-bold px-2 py-1 rounded-full">{items.length} Action</span>
          </div>

          <div className="flex-1 overflow-y-auto pr-2 space-y-4">
            {loading ? (
              <div className="space-y-4">
                {[1, 2, 3].map((i) => (
                  <div key={i} className="animate-pulse bg-slate-50 rounded-2xl p-4">
                    <div className="h-4 bg-slate-200 rounded-full w-1/3 mb-2"></div>
                    <div className="h-3 bg-slate-200 rounded-full w-2/3"></div>
                  </div>
                ))}
              </div>
            ) : items.length === 0 ? (
               <div className="flex flex-col items-center justify-center h-full text-slate-500 py-10">
                  <ShieldCheck size={48} className="text-slate-200 mb-3" />
                  <p className="font-bold">All caught up</p>
                  <p className="text-sm text-slate-400">No drafts waiting for triage.</p>
               </div>
            ) : (
              items.map((item) => (
                <div key={String(item.id)} className="group border border-slate-100 bg-slate-50 rounded-2xl p-4 hover:bg-slate-100 transition-colors">
                  <div className="flex items-center gap-2 mb-2">
                    <span className={`px-2.5 py-1 rounded-full text-[10px] uppercase tracking-wider font-extrabold ${item.type === 'incident' ? 'bg-orange-100 text-orange-700' : 'bg-yellow-100 text-yellow-700'}`}>
                      {item.type}
                    </span>
                    <span className="text-xs font-semibold text-slate-400">
                      {new Date(String(item.created_at)).toLocaleDateString()}
                    </span>
                  </div>
                  <h4 className="font-bold text-sm text-slate-800 line-clamp-1 mb-1">{String(item.title || "")}</h4>
                  <p className="text-xs text-slate-500 line-clamp-2 mb-3">{String(item.description || "")}</p>

                  <Link
                    href={`/dashboard/triage/${String(item.id)}?type=${item.type}`}
                    className="inline-flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-800"
                  >
                    Review Now <ArrowRight size={14} className="ml-1" />
                  </Link>
                </div>
              ))
            )}
          </div>

          <button className="w-full mt-4 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-2xl text-sm transition-colors">
            View All Pending
          </button>
        </div>

      </div>
    </div>
  );
}
