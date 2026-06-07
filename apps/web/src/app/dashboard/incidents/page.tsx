"use client";

import { DataGrid, Column } from "@/components/DataGrid";
import { InputField } from "@/components/InputField";
import Link from "next/link";
import React from "react";

export default function IncidentsPage() {
  const incidents = [
    { id: 'INC-001', date: '2024-05-10', category: 'Slip & Fall', location: 'Plant A - Zone 3', severity: 'High', status: 'Open' },
    { id: 'INC-002', date: '2024-05-08', category: 'Chemical Spill', location: 'Warehouse B', severity: 'Critical', status: 'In Progress' },
    { id: 'INC-003', date: '2024-05-01', category: 'Near Miss', location: 'Loading Dock', severity: 'Low', status: 'Resolved' },
    { id: 'INC-004', date: '2024-04-28', category: 'Equipment Failure', location: 'Plant B - Zone 1', severity: 'Medium', status: 'Closed' },
  ];

  const columns: Column[] = [
    {
      key: 'id',
      header: 'ID',
      render: (row) => (
        <Link href={`/dashboard/incidents/${row.id}`} className="text-blue-600 hover:text-blue-800 font-bold">
          {row.id}
        </Link>
      )
    },
    { key: 'date', header: 'Date' },
    { key: 'category', header: 'Category' },
    { key: 'location', header: 'Location' },
    {
      key: 'severity',
      header: 'Severity',
      render: (row) => {
        let badgeColor = 'bg-slate-100 text-slate-700';
        if (row.severity === 'Critical' || row.severity === 'High') {
          badgeColor = 'bg-red-100 text-red-700';
        } else if (row.severity === 'Medium') {
          badgeColor = 'bg-yellow-100 text-yellow-700';
        } else if (row.severity === 'Low') {
          badgeColor = 'bg-green-100 text-green-700';
        }

        return (
          <span className={`px-3 py-1 rounded-full text-xs font-bold ${badgeColor}`}>
            {row.severity}
          </span>
        );
      }
    },
    {
      key: 'status',
      header: 'Status',
      render: (row) => {
        let badgeColor = 'bg-slate-100 text-slate-700';
        if (row.status === 'Open' || row.status === 'In Progress') {
          badgeColor = 'bg-blue-100 text-blue-700';
        } else if (row.status === 'Resolved' || row.status === 'Closed') {
          badgeColor = 'bg-emerald-100 text-emerald-700';
        }

        return (
          <span className={`px-3 py-1 rounded-full text-xs font-extrabold ${badgeColor}`}>
            {row.status}
          </span>
        );
      }
    },
  ];

  return (
    <div className="space-y-8 max-w-7xl mx-auto p-6">
      <header className="flex justify-between items-end">
        <div>
          <h1 className="text-3xl font-extrabold text-slate-900">Incidents</h1>
          <p className="text-slate-500 mt-1 font-medium">Manage and track reported incidents</p>
        </div>
        <button className="bg-slate-900 hover:bg-slate-800 text-white px-6 py-2 rounded-3xl font-bold shadow-sm transition-colors">
          + New Record
        </button>
      </header>

      <div className="flex gap-4 items-center bg-white p-4 rounded-3xl shadow-sm">
         <div className="w-1/3">
            <InputField placeholder="Search incidents..." />
         </div>
         <div className="w-1/4">
            <select className="w-full border-slate-200 rounded-3xl py-2 px-4 text-slate-700 font-medium focus:ring-slate-900 focus:border-slate-900 shadow-sm border bg-slate-50 appearance-none">
              <option value="">All Statuses</option>
              <option value="Open">Open</option>
              <option value="In Progress">In Progress</option>
              <option value="Resolved">Resolved</option>
              <option value="Closed">Closed</option>
            </select>
         </div>
         <div className="w-1/4">
            <select className="w-full border-slate-200 rounded-3xl py-2 px-4 text-slate-700 font-medium focus:ring-slate-900 focus:border-slate-900 shadow-sm border bg-slate-50 appearance-none">
              <option value="">All Severities</option>
              <option value="Critical">Critical</option>
              <option value="High">High</option>
              <option value="Medium">Medium</option>
              <option value="Low">Low</option>
            </select>
         </div>
      </div>

      <DataGrid columns={columns} data={incidents} />
    </div>
  );
}
