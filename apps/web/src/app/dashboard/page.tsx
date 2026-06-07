'use client';
import React, { useEffect, useState } from 'react';
import Cookies from 'js-cookie';
import api from '@/lib/api';
import { useRouter } from 'next/navigation';

// Source of Truth: docs/ux-architecture.md -> Canonical Base Roles
type Role =
  | 'FIELD_WORKER'
  | 'HSE_OFFICER'
  | 'HSE_MANAGER'
  | 'EXECUTIVE'
  | 'CONTRACTOR'
  | 'TENANT_ADMIN'
  | 'AUDITOR_EXTERNAL';

// Dummy variable as requested


export default function DashboardPage() {
  const [currentUserRole, setCurrentUserRole] = useState<Role | null>(null);
  const router = useRouter();

  useEffect(() => {
    const token = Cookies.get('token');
    if (!token) {
      router.push('/login');
      return;
    }

    api.get('/auth/me').then(res => {
      const roles = res.data.data.roles;
      if (roles && roles.length > 0) {
        setCurrentUserRole(roles[0]);
      } else {
        setCurrentUserRole('FIELD_WORKER'); // fallback
      }
    }).catch(() => {
      Cookies.remove('token');
      router.push('/login');
    });
  }, [router]);

  if (!currentUserRole) {
    return <div className="p-12 text-center text-slate-400 font-bold animate-pulse text-xl">Loading Dashboard...</div>;
  }
  return (
    <div className="max-w-[1600px] mx-auto pb-12">
      <header className="flex justify-between items-end mb-8">
        <div>
          <h1 className="text-3xl font-extrabold text-slate-900 tracking-tight">Overview</h1>
          <p className="text-slate-500 mt-2 font-medium">
            Active Role: <span className="font-bold text-slate-900 bg-slate-100 px-2 py-0.5 rounded-full">{currentUserRole}</span>
          </p>
        </div>
      </header>

      {currentUserRole === 'FIELD_WORKER' && <FieldWorkerView />}
      {currentUserRole === 'HSE_OFFICER' && <HseOfficerView />}
      {currentUserRole === 'HSE_MANAGER' && <HseManagerView />}
      {currentUserRole === 'EXECUTIVE' && <ExecutiveView />}
      {currentUserRole === 'TENANT_ADMIN' && <TenantAdminView />}
      {currentUserRole === 'CONTRACTOR' && <ContractorView />}
      {currentUserRole === 'AUDITOR_EXTERNAL' && <AuditorExternalView />}
    </div>
  );
}

// -----------------------------------------------------------------------------
// FIELD_WORKER VIEW
// Doctrine: Mobile-first, max 4 primary action buttons, no analytics, no complexity.
// -----------------------------------------------------------------------------
function FieldWorkerView() {
  return (
    <div className="grid grid-cols-12 gap-6">
      <div className="col-span-12 bg-white rounded-3xl shadow-sm p-6">
        <h2 className="text-xl font-extrabold text-slate-900 mb-6 text-center">Quick Actions</h2>
        <div className="grid grid-cols-2 gap-4 max-w-md mx-auto">
          <button className="bg-slate-50 hover:bg-slate-100 rounded-2xl p-6 flex flex-col items-center justify-center gap-3 transition-colors h-32">
            <span className="text-3xl">🎙️</span>
            <span className="font-bold text-slate-900">Laporkan Insiden</span>
          </button>
          <button className="bg-slate-50 hover:bg-slate-100 rounded-2xl p-6 flex flex-col items-center justify-center gap-3 transition-colors h-32">
            <span className="text-3xl">⚠️</span>
            <span className="font-bold text-slate-900">Hazard Observasi</span>
          </button>
          <button className="bg-slate-50 hover:bg-slate-100 rounded-2xl p-6 flex flex-col items-center justify-center gap-3 transition-colors h-32">
            <span className="text-3xl">📋</span>
            <span className="font-bold text-slate-900">Tugas Saya</span>
          </button>
          <button className="bg-slate-50 hover:bg-slate-100 rounded-2xl p-6 flex flex-col items-center justify-center gap-3 transition-colors h-32">
            <span className="text-3xl">🪪</span>
            <span className="font-bold text-slate-900">Izin Kerja</span>
          </button>
        </div>
      </div>
    </div>
  );
}

// -----------------------------------------------------------------------------
// HSE_OFFICER VIEW
// Doctrine: Action inbox, draft reports, scheduled work, quick stats.
// -----------------------------------------------------------------------------
function HseOfficerView() {
  return (
    <div className="grid grid-cols-12 gap-6">
      {/* Action Inbox */}
      <div className="col-span-12 md:col-span-8 bg-white rounded-3xl shadow-sm p-6">
        <h3 className="text-lg font-bold text-slate-900 mb-6">⚡ Perlu Tindakan Sekarang</h3>
        <div className="space-y-4">
          <div className="flex justify-between items-center p-4 bg-slate-50 rounded-2xl">
             <div className="flex flex-col">
               <span className="font-bold text-slate-900">Review Draft AI: Incident Site B</span>
               <span className="text-xs font-semibold text-slate-500">Drafted 10m ago</span>
             </div>
             <button className="px-4 py-1.5 bg-slate-900 text-white font-bold text-sm rounded-full">Review</button>
          </div>
          <div className="flex justify-between items-center p-4 bg-slate-50 rounded-2xl">
             <div className="flex flex-col">
               <span className="font-bold text-slate-900">Approval PTW: Hot Work (Area 5)</span>
               <span className="text-xs font-semibold text-slate-500">Requested by Contractor XYZ</span>
             </div>
             <button className="px-4 py-1.5 bg-slate-900 text-white font-bold text-sm rounded-full">Review</button>
          </div>
          <div className="flex justify-between items-center p-4 bg-slate-50 rounded-2xl border border-red-100">
             <div className="flex flex-col">
               <span className="font-bold text-red-600">CAPA Overdue: Guardrail Fix</span>
               <span className="text-xs font-semibold text-red-400">Due yesterday</span>
             </div>
             <button className="px-4 py-1.5 bg-red-100 text-red-700 font-bold text-sm rounded-full">Follow Up</button>
          </div>
        </div>
      </div>

      <div className="col-span-12 md:col-span-4 flex flex-col gap-6">
        {/* Today's Work */}
        <div className="bg-white rounded-3xl shadow-sm p-6 flex-1">
          <h3 className="text-lg font-bold text-slate-900 mb-6">📋 Kerja Hari Ini</h3>
          <ul className="space-y-3">
             <li className="flex items-center gap-3 text-sm font-semibold text-slate-700">
                <span className="w-2 h-2 rounded-full bg-blue-500"></span>
                Inspeksi APAR Area 1 (14:00)
             </li>
             <li className="flex items-center gap-3 text-sm font-semibold text-slate-700">
                <span className="w-2 h-2 rounded-full bg-emerald-500"></span>
                Toolbox Talk (Selesai)
             </li>
          </ul>
        </div>
        {/* Quick Stats */}
        <div className="bg-white rounded-3xl shadow-sm p-6 flex-1">
           <h3 className="text-lg font-bold text-slate-900 mb-6">📊 Quick Stats</h3>
           <div className="grid grid-cols-2 gap-4">
              <div className="bg-slate-50 p-4 rounded-2xl text-center">
                 <span className="block text-2xl font-extrabold text-slate-900">3</span>
                 <span className="text-xs font-bold text-slate-500">Incidents (Week)</span>
              </div>
              <div className="bg-slate-50 p-4 rounded-2xl text-center">
                 <span className="block text-2xl font-extrabold text-slate-900">12</span>
                 <span className="text-xs font-bold text-slate-500">Open CAPA</span>
              </div>
           </div>
        </div>
      </div>
    </div>
  );
}

// -----------------------------------------------------------------------------
// HSE_MANAGER VIEW
// Doctrine: Governance and oversight. Analytics, Compliance, Queue. No ops.
// -----------------------------------------------------------------------------
function HseManagerView() {
  return (
    <div className="grid grid-cols-12 gap-6">
      {/* Governance Queue (Approval required) */}
      <div className="col-span-12 bg-white rounded-3xl shadow-sm p-6">
         <div className="flex justify-between items-center mb-6">
           <h3 className="text-lg font-bold text-slate-900">⚙️ Governance Queue</h3>
           <span className="bg-amber-100 text-amber-700 text-xs font-bold px-3 py-1 rounded-full">5 Pending Approvals</span>
         </div>
         <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div className="bg-slate-50 p-4 rounded-2xl">
               <span className="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-700 mb-2 inline-block">SOP Update</span>
               <h4 className="font-bold text-slate-900 mb-1">Confined Space SOP v2</h4>
               <p className="text-xs font-semibold text-slate-500 mb-4">Drafted by: A. Wijaya</p>
               <button className="w-full py-2 bg-slate-900 text-white font-bold text-sm rounded-xl hover:bg-slate-800">Review</button>
            </div>
            <div className="bg-slate-50 p-4 rounded-2xl">
               <span className="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 mb-2 inline-block">High-Risk PTW</span>
               <h4 className="font-bold text-slate-900 mb-1">Heavy Lifting (Crane)</h4>
               <p className="text-xs font-semibold text-slate-500 mb-4">Site: Sector 7G</p>
               <button className="w-full py-2 bg-slate-900 text-white font-bold text-sm rounded-xl hover:bg-slate-800">Review</button>
            </div>
            <div className="bg-slate-50 p-4 rounded-2xl">
               <span className="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700 mb-2 inline-block">Incident Report</span>
               <h4 className="font-bold text-slate-900 mb-1">INC-2023-004 Final</h4>
               <p className="text-xs font-semibold text-slate-500 mb-4">Requires Manager Sign-off</p>
               <button className="w-full py-2 bg-slate-900 text-white font-bold text-sm rounded-xl hover:bg-slate-800">Review</button>
            </div>
         </div>
      </div>

      {/* Safety Performance Index */}
      <div className="col-span-12 lg:col-span-8 bg-white rounded-3xl shadow-sm p-6 flex flex-col min-h-[350px]">
         <div className="flex justify-between items-center mb-6">
           <h3 className="text-lg font-bold text-slate-900">📊 Safety Performance Index</h3>
           <span className="bg-slate-100 text-slate-600 text-xs font-bold px-3 py-1 rounded-full">30 Days Trend</span>
         </div>
         <div className="flex-1 bg-slate-50 rounded-2xl flex items-center justify-center">
            {/* Placeholder for actual chart component */}
            <p className="text-slate-400 font-bold text-sm">[Trend Chart: LTIR / TRIR / DART vs Target]</p>
         </div>
      </div>

      {/* Perhatian Diperlukan */}
      <div className="col-span-12 lg:col-span-4 bg-white rounded-3xl shadow-sm p-6">
         <h3 className="text-lg font-bold text-slate-900 mb-6">🔴 Perhatian Diperlukan</h3>
         <div className="space-y-4">
            <div className="p-4 bg-red-50 rounded-2xl">
               <h4 className="font-bold text-red-800">Site Anomali: Kaltim Area</h4>
               <p className="text-xs font-semibold text-red-600 mt-1">Spike in Near Miss reports (+400%)</p>
            </div>
            <div className="p-4 bg-amber-50 rounded-2xl">
               <h4 className="font-bold text-amber-800">Vendor CSMS Expired</h4>
               <p className="text-xs font-semibold text-amber-600 mt-1">PT Maju Bersama (Scaffolding)</p>
            </div>
            <div className="p-4 bg-amber-50 rounded-2xl">
               <h4 className="font-bold text-amber-800">Compliance Item Due</h4>
               <p className="text-xs font-semibold text-amber-600 mt-1">Laporan Triwulanan Disnaker (3 days)</p>
            </div>
         </div>
      </div>
    </div>
  );
}

// -----------------------------------------------------------------------------
// EXECUTIVE VIEW
// Doctrine: Read-only, <60s readability, no ops forms, single prominent metric.
// -----------------------------------------------------------------------------
function ExecutiveView() {
  return (
    <div className="grid grid-cols-12 gap-6">
      {/* Top Banner Metric */}
      <div className="col-span-12 bg-slate-900 rounded-3xl shadow-sm p-8 text-white flex justify-between items-center">
         <div>
            <h2 className="text-slate-400 font-bold uppercase tracking-wider text-sm mb-2">Platform Safety Score</h2>
            <div className="flex items-baseline gap-4">
               <span className="text-6xl font-extrabold">98.4</span>
               <span className="text-emerald-400 font-bold text-lg">↑ 1.2%</span>
            </div>
         </div>
         <button className="bg-white/10 hover:bg-white/20 text-white px-6 py-3 rounded-xl font-bold transition-colors">
            Download Executive Summary
         </button>
      </div>

      {/* Site Performance */}
      <div className="col-span-12 lg:col-span-8 bg-white rounded-3xl shadow-sm p-6">
         <h3 className="text-lg font-bold text-slate-900 mb-6">Site Performance Comparison</h3>
         <div className="space-y-6">
            <div>
               <div className="flex justify-between text-sm font-bold text-slate-700 mb-2">
                  <span>Site A (Headquarters)</span>
                  <span>99%</span>
               </div>
               <div className="h-4 bg-slate-100 rounded-full overflow-hidden">
                  <div className="h-full bg-emerald-500 w-[99%]"></div>
               </div>
            </div>
            <div>
               <div className="flex justify-between text-sm font-bold text-slate-700 mb-2">
                  <span>Site C (Offshore)</span>
                  <span>94%</span>
               </div>
               <div className="h-4 bg-slate-100 rounded-full overflow-hidden">
                  <div className="h-full bg-emerald-500 w-[94%]"></div>
               </div>
            </div>
            <div>
               <div className="flex justify-between text-sm font-bold text-slate-700 mb-2">
                  <span>Site B (Manufacturing)</span>
                  <span>82%</span>
               </div>
               <div className="h-4 bg-slate-100 rounded-full overflow-hidden">
                  <div className="h-full bg-amber-500 w-[82%]"></div>
               </div>
            </div>
         </div>
      </div>

      {/* Key Alerts */}
      <div className="col-span-12 lg:col-span-4 bg-white rounded-3xl shadow-sm p-6">
         <h3 className="text-lg font-bold text-slate-900 mb-6">Key Alerts</h3>
         <div className="space-y-4">
            <div className="p-4 bg-red-50 rounded-2xl flex gap-3">
               <span className="text-xl">⚠️</span>
               <div>
                  <h4 className="font-bold text-red-800 text-sm">LTI at Site B</h4>
                  <p className="text-xs font-semibold text-red-600 mt-1">Lost Time Injury recorded yesterday. Investigation ongoing.</p>
               </div>
            </div>
            <div className="p-4 bg-amber-50 rounded-2xl flex gap-3">
               <span className="text-xl">📋</span>
               <div>
                  <h4 className="font-bold text-amber-800 text-sm">Audit Upcoming</h4>
                  <p className="text-xs font-semibold text-amber-600 mt-1">ISO 45001 External Audit in 14 days.</p>
               </div>
            </div>
         </div>
      </div>
    </div>
  );
}

// -----------------------------------------------------------------------------
// TENANT_ADMIN VIEW
// Doctrine: Configuration modules, User/Access, System settings. No HSE data.
// -----------------------------------------------------------------------------
function TenantAdminView() {
  return (
    <div className="grid grid-cols-12 gap-6">
      <div className="col-span-12">
         <h2 className="text-xl font-extrabold text-slate-900 mb-6">System Configuration Dashboard</h2>
      </div>

      {/* Cluster User & Access */}
      <div className="col-span-12 md:col-span-6 lg:col-span-4 bg-white rounded-3xl shadow-sm p-6">
         <h3 className="text-lg font-bold text-slate-900 mb-4 border-b border-slate-100 pb-2">User & Access</h3>
         <ul className="space-y-2">
            <li className="font-semibold text-slate-600 hover:text-slate-900 cursor-pointer py-2 px-3 rounded-xl hover:bg-slate-50">User Management</li>
            <li className="font-semibold text-slate-600 hover:text-slate-900 cursor-pointer py-2 px-3 rounded-xl hover:bg-slate-50">Role Assignment</li>
            <li className="font-semibold text-slate-600 hover:text-slate-900 cursor-pointer py-2 px-3 rounded-xl hover:bg-slate-50">Delegation Rules</li>
         </ul>
      </div>

      {/* Cluster Site & Organization */}
      <div className="col-span-12 md:col-span-6 lg:col-span-4 bg-white rounded-3xl shadow-sm p-6">
         <h3 className="text-lg font-bold text-slate-900 mb-4 border-b border-slate-100 pb-2">Site & Organization</h3>
         <ul className="space-y-2">
            <li className="font-semibold text-slate-600 hover:text-slate-900 cursor-pointer py-2 px-3 rounded-xl hover:bg-slate-50">Site & Area Hierarchy</li>
            <li className="font-semibold text-slate-600 hover:text-slate-900 cursor-pointer py-2 px-3 rounded-xl hover:bg-slate-50">Department Structure</li>
         </ul>
      </div>

      {/* Cluster Workflow */}
      <div className="col-span-12 md:col-span-6 lg:col-span-4 bg-white rounded-3xl shadow-sm p-6">
         <h3 className="text-lg font-bold text-slate-900 mb-4 border-b border-slate-100 pb-2">Workflow & Forms</h3>
         <ul className="space-y-2">
            <li className="font-semibold text-slate-600 hover:text-slate-900 cursor-pointer py-2 px-3 rounded-xl hover:bg-slate-50">Workflow Configurability</li>
            <li className="font-semibold text-slate-600 hover:text-slate-900 cursor-pointer py-2 px-3 rounded-xl hover:bg-slate-50">Custom Form Builder</li>
         </ul>
      </div>

       {/* Cluster System */}
       <div className="col-span-12 md:col-span-6 lg:col-span-4 bg-white rounded-3xl shadow-sm p-6">
         <h3 className="text-lg font-bold text-slate-900 mb-4 border-b border-slate-100 pb-2">System</h3>
         <ul className="space-y-2">
            <li className="font-semibold text-slate-600 hover:text-slate-900 cursor-pointer py-2 px-3 rounded-xl hover:bg-slate-50">Integration Settings</li>
            <li className="font-semibold text-slate-600 hover:text-slate-900 cursor-pointer py-2 px-3 rounded-xl hover:bg-slate-50">Data Retention Policy</li>
         </ul>
      </div>
    </div>
  );
}

// -----------------------------------------------------------------------------
// CONTRACTOR VIEW
// -----------------------------------------------------------------------------
function ContractorView() {
  return (
    <div className="grid grid-cols-12 gap-6">
      <div className="col-span-12 bg-white rounded-3xl shadow-sm p-6 text-center py-20">
         <span className="text-5xl mb-4 block">👷</span>
         <h2 className="text-2xl font-extrabold text-slate-900 mb-2">Contractor Portal</h2>
         <p className="text-slate-500 font-semibold max-w-md mx-auto">Manage your PTWs, submit required documentation, and review safety notices.</p>
         <button className="mt-8 bg-slate-900 text-white font-bold px-6 py-3 rounded-xl">View Active Permits</button>
      </div>
    </div>
  );
}

// -----------------------------------------------------------------------------
// AUDITOR_EXTERNAL VIEW
// Doctrine: Read-only, time-limited, evidence collection.
// -----------------------------------------------------------------------------
function AuditorExternalView() {
  return (
    <div className="grid grid-cols-12 gap-6">
      <div className="col-span-12 bg-blue-50 border border-blue-100 rounded-3xl shadow-sm p-6 mb-2">
         <div className="flex gap-4 items-center">
            <span className="text-3xl">🔍</span>
            <div>
               <h3 className="text-lg font-bold text-blue-900">Auditor Read-Only Mode</h3>
               <p className="text-sm font-semibold text-blue-700">Access expires in: 48h 20m. No modifications allowed.</p>
            </div>
         </div>
      </div>
      <div className="col-span-12 bg-white rounded-3xl shadow-sm p-6">
         <h3 className="text-lg font-bold text-slate-900 mb-4">Evidence Repository Access</h3>
         <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div className="p-4 bg-slate-50 rounded-2xl flex flex-col items-center justify-center text-center">
               <span className="text-2xl mb-2">📄</span>
               <span className="font-bold text-slate-700 text-sm">Policy Documents</span>
            </div>
            <div className="p-4 bg-slate-50 rounded-2xl flex flex-col items-center justify-center text-center">
               <span className="text-2xl mb-2">📋</span>
               <span className="font-bold text-slate-700 text-sm">Inspection Logs</span>
            </div>
            <div className="p-4 bg-slate-50 rounded-2xl flex flex-col items-center justify-center text-center">
               <span className="text-2xl mb-2">🚑</span>
               <span className="font-bold text-slate-700 text-sm">Incident Records</span>
            </div>
            <div className="p-4 bg-slate-50 rounded-2xl flex flex-col items-center justify-center text-center">
               <span className="text-2xl mb-2">⚙️</span>
               <span className="font-bold text-slate-700 text-sm">CAPA History</span>
            </div>
         </div>
      </div>
    </div>
  );
}
