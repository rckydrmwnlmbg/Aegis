"use client";

import { useEffect, useState } from "react";
import { useRouter, useSearchParams } from "next/navigation";
import Link from "next/link";
import api from "@/lib/api";
import { use } from "react";

type ItemDetail = {
  id: string;
  title: string;
  summary?: string;
  description?: string;
  status: string;
  created_at: string;
  ai_confidence_score?: number;
  metadata?: Record<string, unknown>;
};

export default function ReviewTriagePage({ params }: { params: Promise<{ id: string }> }) {
  const resolvedParams = use(params);
  const { id } = resolvedParams;
  const searchParams = useSearchParams();
  const type = searchParams.get("type");
  const router = useRouter();

  const [item, setItem] = useState<ItemDetail | null>(null);
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);

  // Form fields
  const [title, setTitle] = useState("");
  const [summary, setSummary] = useState("");

  useEffect(() => {
    const fetchItem = async () => {
      if (!id || !type) return;
      try {
        setLoading(true);
        const endpoint = type === "incident" ? `/api/v1/incidents/${id}` : `/api/v1/hazards/${id}`;
        const res = await api.get(endpoint);
        const data = res.data.data;
        setItem(data);
        setTitle(data.title || "");
        setSummary(data.summary || data.description || "");
              } catch (error) {
        console.error("Failed to fetch triage item details:", error);
      } finally {
        setLoading(false);
      }
    };

    fetchItem();
  }, [id, type]);

  const handleUpdate = async (newStatus: string) => {
    if (!id || !type) return;
    try {
      setSaving(true);
      const endpoint = type === "incident" ? `/api/v1/incidents/${id}` : `/api/v1/hazards/${id}`;
      const payload = {
        title,
        ...(type === "incident" ? { summary } : { description: summary }),
        status: newStatus
      };
      await api.put(endpoint, payload);
      router.push("/dashboard/triage");
    } catch (error) {
      console.error("Failed to update item:", error);
      alert("Failed to update item.");
    } finally {
      setSaving(false);
    }
  };

  if (loading) {
    return (
      <div className="max-w-4xl mx-auto space-y-8 p-6">
        <div className="h-8 bg-gray-200 rounded-full w-1/4 animate-pulse"></div>
        <div className="bg-white/60 backdrop-blur-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] rounded-[2rem] p-8 space-y-6">
          <div className="h-6 bg-gray-200 rounded-full w-1/3 animate-pulse"></div>
          <div className="h-12 bg-gray-200 rounded-2xl w-full animate-pulse"></div>
          <div className="h-32 bg-gray-200 rounded-2xl w-full animate-pulse"></div>
        </div>
      </div>
    );
  }

  if (!item) {
    return (
      <div className="max-w-4xl mx-auto text-center py-20">
        <h2 className="text-2xl font-bold text-gray-800">Item not found</h2>
        <Link href="/dashboard/triage" className="text-blue-500 hover:underline mt-4 inline-block">
          Return to Triage
        </Link>
      </div>
    );
  }

  return (
    <div className="max-w-4xl mx-auto space-y-8 p-6">
      <div className="flex items-center gap-4">
        <Link href="/dashboard/triage" className="p-2 bg-white/60 hover:bg-white/80 rounded-full transition-colors shadow-sm text-gray-600">
          <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </Link>
        <div>
          <h1 className="text-3xl font-bold text-gray-800">Review {type === 'incident' ? 'Incident' : 'Hazard'}</h1>
          <p className="text-gray-500 mt-1">Review AI-structured draft and approve.</p>
        </div>
      </div>

      <div className="bg-white/60 backdrop-blur-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] rounded-[2rem] p-8 space-y-6">
        <div className="flex items-center justify-between">
          <div className="flex items-center gap-3">
             <span className={`px-3 py-1 rounded-full text-sm font-semibold ${type === 'incident' ? 'bg-orange-100 text-orange-700' : 'bg-yellow-100 text-yellow-700'}`}>
                {type?.toUpperCase()}
              </span>
              {item.ai_confidence_score !== undefined && (
                <span className="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                  AI Confidence: {(item.ai_confidence_score * 100).toFixed(0)}%
                </span>
              )}
          </div>
          <span className="text-sm text-gray-500">
            Created: {new Date(item.created_at).toLocaleString()}
          </span>
        </div>

        <div className="space-y-4">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-2">Title</label>
            <input
              type="text"
              value={title}
              onChange={(e) => setTitle(e.target.value)}
              className="w-full px-4 py-3 rounded-2xl border border-gray-200 bg-white/50 focus:bg-white focus:ring-2 focus:ring-hse-red focus:border-transparent transition-all outline-none"
            />
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-2">
              {type === 'incident' ? 'Summary' : 'Description'}
            </label>
            <textarea
              value={summary}
              onChange={(e) => setSummary(e.target.value)}
              rows={5}
              className="w-full px-4 py-3 rounded-2xl border border-gray-200 bg-white/50 focus:bg-white focus:ring-2 focus:ring-hse-red focus:border-transparent transition-all outline-none resize-y"
            />
          </div>

          {item.metadata && (
             <div>
               <label className="block text-sm font-medium text-gray-700 mb-2">AI Extracted Metadata (Read-only)</label>
               <pre className="w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50 text-sm overflow-auto max-h-60 text-gray-600">
                 {JSON.stringify(item.metadata, null, 2)}
               </pre>
             </div>
          )}
        </div>

        <div className="pt-6 mt-6 border-t border-gray-100 flex items-center justify-end gap-4">
          <button
            onClick={() => handleUpdate("closed")}
            disabled={saving}
            className="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-2xl transition-colors disabled:opacity-50"
          >
            Reject / Close
          </button>
          <button
            onClick={() => handleUpdate("open")}
            disabled={saving}
            className="px-8 py-3 bg-hse-red hover:bg-red-700 text-white font-bold rounded-2xl transition-colors shadow-lg shadow-red-500/30 disabled:opacity-50 flex items-center gap-2"
          >
            {saving ? (
              <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle><path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            ) : null}
            Approve & Open
          </button>
        </div>
      </div>
    </div>
  );
}
