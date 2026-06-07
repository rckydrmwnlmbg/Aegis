"use client";

import React from "react";
import Link from "next/link";
import { useParams } from "next/navigation";

export default function IncidentDetailPage() {
  const params = useParams();
  const id = params.id as string;

  return (
    <div className="space-y-6 max-w-7xl mx-auto p-6">

      {/* HEADER CARD */}
      <div className="bg-white rounded-3xl shadow-sm p-6 flex justify-between items-center">
        <div>
          <div className="flex items-center gap-3 mb-1">
            <Link href="/dashboard/incidents" className="text-slate-400 hover:text-slate-600 font-bold text-sm">
              &larr; Back to Incidents
            </Link>
            <span className="text-slate-300">|</span>
            <span className="text-slate-500 font-bold text-sm">{id}</span>
          </div>
          <h1 className="text-3xl font-extrabold text-slate-900 flex items-center gap-4">
            Slip & Fall at Plant A
            <span className="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-extrabold align-middle">
              Open
            </span>
          </h1>
        </div>
        <div className="flex gap-3">
          <button className="bg-slate-100 hover:bg-slate-200 text-slate-800 px-6 py-2 rounded-3xl font-bold transition-colors">
            Print Report
          </button>
          <button className="bg-slate-900 hover:bg-slate-800 text-white px-6 py-2 rounded-3xl font-bold transition-colors shadow-sm">
            Mark as Resolved
          </button>
        </div>
      </div>

      {/* BENTO BOX GRID */}
      <div className="grid grid-cols-12 gap-6">

        {/* LEFT COLUMN (Media & Metadata) */}
        <div className="col-span-8 space-y-6">

          {/* METADATA CARD */}
          <div className="bg-white rounded-3xl shadow-sm p-6">
            <h2 className="text-xl font-bold text-slate-900 mb-4 border-b border-slate-100 pb-2">Incident Details</h2>
            <div className="grid grid-cols-2 gap-6">
              <div>
                <p className="text-sm font-bold text-slate-500 uppercase">Date & Time</p>
                <p className="font-medium text-slate-800 mt-1">May 10, 2024 - 14:30 WIB</p>
              </div>
              <div>
                <p className="text-sm font-bold text-slate-500 uppercase">Location</p>
                <p className="font-medium text-slate-800 mt-1">Plant A - Zone 3 (Stairs to Level 2)</p>
              </div>
              <div>
                <p className="text-sm font-bold text-slate-500 uppercase">Reported By</p>
                <p className="font-medium text-slate-800 mt-1">John Doe (Safetyman)</p>
              </div>
              <div>
                <p className="text-sm font-bold text-slate-500 uppercase">Severity</p>
                <p className="mt-1"><span className="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">High</span></p>
              </div>
              <div className="col-span-2">
                <p className="text-sm font-bold text-slate-500 uppercase">Chronology / Description</p>
                <p className="font-medium text-slate-800 mt-1 leading-relaxed bg-slate-50 p-4 rounded-2xl">
                  Worker slipped on a wet surface on the stairs leading to Level 2. The surface was wet due to a recent cleaning operation, but no warning signs were placed. The worker sustained minor bruising on the left arm and was immediately taken to the onsite clinic for a checkup.
                </p>
              </div>
            </div>
          </div>

          {/* MEDIA / EVIDENCE CARD */}
          <div className="bg-white rounded-3xl shadow-sm p-6">
            <h2 className="text-xl font-bold text-slate-900 mb-4 border-b border-slate-100 pb-2">Evidence & Photos</h2>
            <div className="grid grid-cols-3 gap-4">
              <div className="bg-slate-100 h-40 rounded-2xl flex items-center justify-center text-slate-400 font-bold border-2 border-dashed border-slate-200">
                [Photo 1 Placeholder]
              </div>
              <div className="bg-slate-100 h-40 rounded-2xl flex items-center justify-center text-slate-400 font-bold border-2 border-dashed border-slate-200">
                [Photo 2 Placeholder]
              </div>
              <div className="bg-slate-50 hover:bg-slate-100 cursor-pointer transition-colors h-40 rounded-2xl flex flex-col items-center justify-center text-slate-500 font-bold border-2 border-dashed border-slate-300">
                <span className="text-2xl mb-1">+</span>
                <span className="text-sm">Upload Photo</span>
              </div>
            </div>
          </div>

        </div>

        {/* RIGHT COLUMN (Timeline / Audit Log) */}
        <div className="col-span-4">
          <div className="bg-white rounded-3xl shadow-sm p-6 h-full">
            <h2 className="text-xl font-bold text-slate-900 mb-6 border-b border-slate-100 pb-2">Audit Log</h2>

            <div className="relative border-l-2 border-slate-200 ml-3 space-y-8">

              <div className="relative">
                <div className="absolute -left-[21px] bg-white border-4 border-blue-500 w-4 h-4 rounded-full mt-1"></div>
                <div className="pl-6">
                  <p className="font-bold text-slate-800">Status changed to Open</p>
                  <p className="text-sm text-slate-500">System (Automated)</p>
                  <p className="text-xs font-bold text-slate-400 mt-1">May 10, 2024 14:35</p>
                </div>
              </div>

              <div className="relative">
                <div className="absolute -left-[21px] bg-white border-4 border-slate-300 w-4 h-4 rounded-full mt-1"></div>
                <div className="pl-6">
                  <p className="font-bold text-slate-800">Incident Reported</p>
                  <p className="text-sm text-slate-500">John Doe (Safetyman)</p>
                  <p className="text-xs font-bold text-slate-400 mt-1">May 10, 2024 14:30</p>
                </div>
              </div>

            </div>
          </div>
        </div>

      </div>
    </div>
  );
}
