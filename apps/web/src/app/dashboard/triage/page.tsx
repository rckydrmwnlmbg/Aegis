"use client";

import { useEffect, useState } from "react";
import api from "@/lib/api";

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
        const incidents: TriageItem[] = resIncidents.data.data.map((item: any) => ({
          id: item.id,
          type: "incident",
          title: item.title || "Untitled Incident",
          description: item.description || "No description provided",
          status: item.status,
          created_at: item.created_at,
        }));

        // Fetch hazards if API available, skip for now if not to prevent crash
        // Mock hazard for demonstration if empty
        const allItems = [...incidents];

        // Sorting by newest
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
    <div className="max-w-6xl mx-auto space-y-8">
      <div className="flex justify-between items-center">
        <div>
          <h1 className="text-3xl font-bold text-gray-800">Triage Dashboard</h1>
          <p className="text-gray-500 mt-2">Review and approve AI-captured drafts from the field.</p>
        </div>
      </div>

      <div className="bg-white/60 backdrop-blur-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] rounded-[2rem] p-8">
        {loading ? (
          <div className="space-y-4">
            {[1, 2, 3].map((i) => (
              <div key={i} className="animate-pulse flex items-center justify-between p-4 bg-white/40 rounded-3xl">
                <div className="flex flex-col gap-2 w-1/2">
                  <div className="h-5 bg-gray-200 rounded-full w-3/4"></div>
                  <div className="h-4 bg-gray-200 rounded-full w-1/2"></div>
                </div>
                <div className="h-10 bg-gray-200 rounded-full w-24"></div>
              </div>
            ))}
          </div>
        ) : items.length === 0 ? (
          <div className="text-center py-12 text-gray-500">
            <div className="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-50 text-green-500 mb-4">
              <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <p className="text-lg font-medium">All caught up!</p>
            <p className="text-sm">No drafts waiting for triage.</p>
          </div>
        ) : (
          <div className="space-y-4">
            {items.map((item) => (
              <div key={item.id} className="flex flex-col sm:flex-row sm:items-center justify-between p-6 bg-white/80 hover:bg-white transition-colors rounded-[2rem] shadow-sm border border-white/20 gap-4">
                <div className="flex-1 min-w-0">
                  <div className="flex items-center gap-3 mb-1">
                    <span className={`px-3 py-1 rounded-full text-xs font-semibold ${item.type === 'incident' ? 'bg-orange-100 text-orange-700' : 'bg-yellow-100 text-yellow-700'}`}>
                      {item.type.toUpperCase()}
                    </span>
                    <span className="text-xs text-gray-400">
                      {new Date(item.created_at).toLocaleDateString()}
                    </span>
                  </div>
                  <h3 className="text-lg font-bold text-gray-800 truncate">{item.title}</h3>
                  <p className="text-sm text-gray-500 line-clamp-2 mt-1">{item.description}</p>
                </div>
                <div className="flex items-center gap-3">
                  <button className="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-full transition-colors">
                    Reject
                  </button>
                  <button className="px-6 py-2 bg-hse-red hover:bg-red-700 text-white font-medium rounded-full transition-colors shadow-lg shadow-red-500/30">
                    Review
                  </button>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}
