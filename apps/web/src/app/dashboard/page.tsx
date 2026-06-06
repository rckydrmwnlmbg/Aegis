import { MetricCard } from "@/components/MetricCard";
import { DataGrid } from "@/components/DataGrid";

export default function Dashboard() {
  const recentIncidents = [
    { id: 'INC-001', type: 'Slip & Fall', severity: 'High', status: 'Open' },
    { id: 'INC-002', type: 'Equipment Failure', severity: 'Medium', status: 'Investigating' },
    { id: 'INC-003', type: 'Near Miss', severity: 'Low', status: 'Closed' },
  ];

  const columns = [
    { key: 'id', header: 'ID' },
    { key: 'type', header: 'Type' },
    { key: 'severity', header: 'Severity' },
    { key: 'status', header: 'Status' },
  ];

  return (
    <div className="space-y-8 max-w-6xl mx-auto pb-12">
      <header className="flex justify-between items-end">
        <div>
          <h1 className="text-3xl font-extrabold text-slate-900 tracking-tight">Overview</h1>
          <p className="text-slate-500 mt-2 font-medium">Platform performance and key metrics</p>
        </div>
        <button className="bg-slate-900 hover:bg-slate-800 text-white rounded-full px-6 py-2.5 transition-all shadow-sm hover:shadow-md font-semibold text-sm">
          Generate Report
        </button>
      </header>

      {/* Bento Box Grid */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        <MetricCard title="Open Incidents" value="12" trend="+2 this week" isWarning />
        <MetricCard title="Active PTWs" value="45" trend="Stable" />
        <MetricCard title="Overdue CAPAs" value="3" trend="-1 this week" isWarning />

        {/* Dummy Chart Bento Box */}
        <div className="md:col-span-2 bg-white rounded-3xl shadow-sm border border-slate-100 p-6 flex flex-col">
           <h3 className="text-lg font-bold text-slate-900 mb-6">Incident Trends</h3>
           <div className="flex-1 flex items-end gap-4 h-48 mt-auto pb-2">
             {[40, 70, 45, 90, 65, 80, 55].map((height, i) => (
                <div key={i} className="flex-1 bg-slate-100 rounded-t-lg relative group transition-all duration-300 hover:bg-slate-200">
                   <div
                     className="absolute bottom-0 w-full bg-slate-800 rounded-t-lg transition-all duration-500"
                     style={{ height: `${height}%` }}
                   ></div>
                </div>
             ))}
           </div>
           <div className="flex justify-between text-xs font-semibold text-slate-400 mt-4 px-2">
              <span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span><span>Sun</span>
           </div>
        </div>

        {/* Small Action Box */}
        <div className="bg-hse-red rounded-3xl shadow-sm p-6 text-white flex flex-col justify-center items-start">
           <h3 className="text-xl font-bold mb-2">Critical Alerts</h3>
           <p className="text-red-100 text-sm font-medium mb-6">2 new high-severity incidents reported today.</p>
           <button className="bg-white text-hse-red px-5 py-2 rounded-full font-bold text-sm shadow-sm hover:shadow transition-all mt-auto">
             Review Now
           </button>
        </div>
      </div>

      <section>
        <h2 className="text-xl font-bold text-slate-900 mb-6">Recent Activity</h2>
        <DataGrid columns={columns} data={recentIncidents} />
      </section>
    </div>
  );
}
