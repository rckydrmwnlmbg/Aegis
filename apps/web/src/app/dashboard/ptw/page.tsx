'use client';

import React from 'react';

// Komponen StatusBadge dengan warna Tailwind yang relevan
const StatusBadge = ({ status }: { status: string }) => {
  let badgeStyle = 'bg-gray-500/10 text-gray-600'; // Default
  let statusText = status;

  if (status === 'pending_review') {
    badgeStyle = 'bg-yellow-500/20 text-yellow-700';
    statusText = 'Pending Review';
  } else if (status === 'approved') {
    badgeStyle = 'bg-emerald-500/20 text-emerald-700';
    statusText = 'Approved';
  } else if (status === 'rejected') {
    badgeStyle = 'bg-hse-red/20 text-hse-red';
    statusText = 'Rejected';
  }

  return (
    <span className={`px-3 py-1 rounded-full text-xs font-semibold backdrop-blur-sm ${badgeStyle}`}>
      {statusText}
    </span>
  );
};

export default function PtwApprovalDashboard() {
  // Mock data untuk antrean dokumen
  const ptws = [
    { id: 'PTW-2023-001', jobTitle: 'Welding Pipeline A', location: 'Zone B', type: 'Hot Work', status: 'pending_review' },
    { id: 'PTW-2023-002', jobTitle: 'Tank Inspection', location: 'Storage C', type: 'Confined Space', status: 'pending_review' },
    { id: 'PTW-2023-003', jobTitle: 'Electrical Panel Maintenance', location: 'Substation 1', type: 'Electrical', status: 'approved' },
    { id: 'PTW-2023-004', jobTitle: 'Roof Repair', location: 'Main Building', type: 'Working at Height', status: 'rejected' },
  ];

  // (Opsional/Mockup) Fungsi aksi
  const handleAction = async (id: string, action: 'approve' | 'reject') => {
    // Siap memanggil PUT /api/v1/ptw/{id}/status ke backend Laravel
    console.log(`Calling API: PUT /api/v1/ptw/${id}/status with action: ${action}`);
    // const newStatus = action === 'approve' ? 'approved' : 'rejected';

    // fetch(`/api/v1/ptw/${id}/status`, {
    //   method: 'PUT',
    //   headers: { 'Content-Type': 'application/json' },
    //   body: JSON.stringify({ status: newStatus })
    // });

    alert(`Action: ${action} for ${id} (Mockup API call)`);
  };

  return (
    <div className="space-y-8 max-w-7xl mx-auto p-6">
      <header className="flex justify-between items-end">
        <div>
          <h1 className="text-3xl font-bold text-gray-800">PTW Approval Dashboard</h1>
          <p className="text-gray-500 mt-1 text-sm">Review and manage Permit to Work requests</p>
        </div>
      </header>

      {/* Gunakan komponen UI Glassmorphism: glass-panel */}
      <div className="glass-panel overflow-hidden bg-white/60 backdrop-blur-xl rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/40">
        <div className="overflow-x-auto">
          <table className="w-full text-left border-collapse">
            <thead>
              <tr className="border-b border-gray-200/50">
                <th className="p-5 text-sm font-semibold text-gray-600">ID Dokumen</th>
                <th className="p-5 text-sm font-semibold text-gray-600">Pekerjaan</th>
                <th className="p-5 text-sm font-semibold text-gray-600">Lokasi</th>
                <th className="p-5 text-sm font-semibold text-gray-600">Tipe Kerja</th>
                <th className="p-5 text-sm font-semibold text-gray-600">Status</th>
                <th className="p-5 text-sm font-semibold text-gray-600 text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              {ptws.map((doc) => (
                <tr key={doc.id} className="border-b border-gray-100/50 hover:bg-white/40 transition-colors last:border-0">
                  <td className="p-5 text-sm font-medium text-gray-800">{doc.id}</td>
                  <td className="p-5 text-sm text-gray-700">{doc.jobTitle}</td>
                  <td className="p-5 text-sm text-gray-700">{doc.location}</td>
                  <td className="p-5 text-sm text-gray-700">{doc.type}</td>
                  <td className="p-5 text-sm">
                    <StatusBadge status={doc.status} />
                  </td>
                  <td className="p-5 text-sm flex justify-center gap-2">
                    {doc.status === 'pending_review' ? (
                      <>
                        <button
                          onClick={() => handleAction(doc.id, 'approve')}
                          className="px-4 py-2 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-600 hover:bg-emerald-500/20 transition-all shadow-sm border border-emerald-500/20"
                        >
                          Approve
                        </button>
                        <button
                          onClick={() => handleAction(doc.id, 'reject')}
                          className="px-4 py-2 rounded-full text-xs font-semibold bg-hse-red/10 text-hse-red hover:bg-hse-red/20 transition-all shadow-sm border border-hse-red/20"
                        >
                          Reject
                        </button>
                      </>
                    ) : (
                      <span className="text-gray-400 text-xs italic">No actions</span>
                    )}
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
