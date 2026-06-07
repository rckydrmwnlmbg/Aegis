"use client";

import { DataGrid, Column } from "@/components/DataGrid";
import { InputField } from "@/components/InputField";
import Link from "next/link";
import React from "react";

export default function CapaPage() {
  const capas = [
    { id: 'CAPA-042', title: 'Fix leaking pipe', dueDate: '2024-06-15', status: 'In Progress' },
    { id: 'CAPA-043', title: 'Update safety manual', dueDate: '2024-05-20', status: 'Overdue' },
    { id: 'CAPA-044', title: 'Repair faulty wiring', dueDate: '2024-06-10', status: 'Open' },
    { id: 'CAPA-045', title: 'Replace missing fire extinguisher', dueDate: '2024-05-15', status: 'Closed' },
  ];

  const columns: Column[] = [
    {
      key: 'id',
      header: 'CAPA ID',
      render: (row) => (
        <Link href={`/dashboard/capa/${row.id}`} className="text-blue-600 hover:text-blue-800 font-bold">
          {row.id}
        </Link>
      )
    },
    { key: 'title', header: 'Title' },
    { key: 'dueDate', header: 'Due Date' },
    {
      key: 'status',
      header: 'Status',
      render: (row) => {
        let badgeColor = 'bg-slate-100 text-slate-700';
        if (row.status === 'Open' || row.status === 'In Progress') {
          badgeColor = 'bg-blue-100 text-blue-700';
        } else if (row.status === 'Closed') {
          badgeColor = 'bg-emerald-100 text-emerald-700';
        } else if (row.status === 'Overdue') {
          badgeColor = 'bg-red-100 text-red-700';
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
          <h1 className="text-3xl font-extrabold text-slate-900">CAPA</h1>
          <p className="text-slate-500 mt-1 font-medium">Corrective and Preventive Actions</p>
        </div>
        <button className="bg-slate-900 hover:bg-slate-800 text-white px-6 py-2 rounded-3xl font-bold shadow-sm transition-colors">
          + New CAPA
        </button>
      </header>

      <div className="flex gap-4 items-center bg-white p-4 rounded-3xl shadow-sm">
         <div className="w-1/3">
            <InputField placeholder="Search CAPAs..." />
         </div>
         <div className="w-1/4">
            <select className="w-full border-slate-200 rounded-3xl py-2 px-4 text-slate-700 font-medium focus:ring-slate-900 focus:border-slate-900 shadow-sm border bg-slate-50 appearance-none">
              <option value="">All Statuses</option>
              <option value="Open">Open</option>
              <option value="In Progress">In Progress</option>
              <option value="Closed">Closed</option>
              <option value="Overdue">Overdue</option>
            </select>
         </div>
      </div>

      <DataGrid columns={columns} data={capas} />
    </div>
  );
}
