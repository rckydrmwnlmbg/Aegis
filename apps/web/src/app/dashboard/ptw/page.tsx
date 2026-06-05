'use client';

import React, { useState, useEffect } from 'react';
import { DataGrid } from '../../../components/DataGrid';
import { Skeleton } from '../../../components/Skeleton';

const ptwData = [
  { id: 'PTW-2023-001', title: 'Hot Work at Boiler 3', applicant: 'John Doe', status: 'Pending', date: '2023-10-25' },
  { id: 'PTW-2023-002', title: 'Confined Space Entry', applicant: 'Jane Smith', status: 'Approved', date: '2023-10-24' },
  { id: 'PTW-2023-003', title: 'Working at Heights', applicant: 'Bob Johnson', status: 'Rejected', date: '2023-10-23' },
];

const StatusBadge = ({ status }: { status: string }) => {
  const getStatusStyles = () => {
    switch (status) {
      case 'Pending':
        return 'bg-yellow-500/10 text-yellow-600';
      case 'Approved':
        return 'bg-emerald-500/10 text-emerald-600';
      case 'Rejected':
        return 'bg-hse-red/10 text-hse-red';
      default:
        return 'bg-gray-500/10 text-gray-600';
    }
  };

  return (
    <span className={`px-4 py-1.5 rounded-full text-xs font-semibold backdrop-blur-md ${getStatusStyles()}`}>
      {status}
    </span>
  );
};

export default function PTWDashboard() {
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Simulate API fetch to prevent empty screen flash
    const timer = setTimeout(() => {
      setLoading(false);
    }, 1500);
    return () => clearTimeout(timer);
  }, []);

  const handleApprove = (id: string) => {
    console.log('Approve', id);
  };

  const handleReject = (id: string) => {
    console.log('Reject', id);
  };

  const columns = [
    { key: 'id', header: 'Permit ID' },
    { key: 'title', header: 'Description' },
    { key: 'applicant', header: 'Applicant' },
    { key: 'date', header: 'Date' },
    { key: 'statusBadge', header: 'Status' },
    { key: 'actions', header: 'Actions' },
  ];

  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  const formattedData: any[] = ptwData.map((item) => ({
    ...item,
    statusBadge: <StatusBadge status={item.status} />,
    actions: (
      <div className="flex space-x-2">
        <button
          onClick={() => handleApprove(item.id)}
          className="px-4 py-2 bg-emerald-500/20 text-emerald-700 rounded-3xl backdrop-blur-md hover:bg-emerald-500/30 transition-all font-medium text-sm shadow-sm"
        >
          Approve
        </button>
        <button
          onClick={() => handleReject(item.id)}
          className="px-4 py-2 bg-hse-red/20 text-hse-red rounded-3xl backdrop-blur-md hover:bg-hse-red/30 transition-all font-medium text-sm shadow-sm"
        >
          Reject
        </button>
      </div>
    ),
  }));

  return (
    <div className="min-h-screen bg-gradient-to-br from-blue-50/50 via-purple-50/50 to-pink-50/50 p-8">
      <div className="max-w-6xl mx-auto">
        <h1 className="text-3xl font-bold text-gray-800 mb-8 drop-shadow-sm">Permit to Work (PTW) Approval Queue</h1>

        <div className="bg-white/60 backdrop-blur-xl rounded-[2rem] shadow-[0_8px_32px_rgba(0,0,0,0.05)] border border-white/40 p-8">
          {loading ? (
             <div className="space-y-4">
               <Skeleton className="h-12 w-full rounded-3xl" />
               <Skeleton className="h-12 w-full rounded-3xl" />
               <Skeleton className="h-12 w-full rounded-3xl" />
               <Skeleton className="h-12 w-full rounded-3xl" />
             </div>
          ) : (

             <DataGrid columns={columns} data={formattedData} />
          )}
        </div>
      </div>
    </div>
  );
}
