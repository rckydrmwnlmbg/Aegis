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
    <div className="space-y-8 max-w-6xl mx-auto">
      <header className="flex justify-between items-end">
        <div>
          <h1 className="text-3xl font-bold text-gray-800">Overview</h1>
          <p className="text-gray-500 mt-1">Platform performance and key metrics</p>
        </div>
        <button className="glass-button-primary">Generate Report</button>
      </header>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        <MetricCard title="Open Incidents" value="12" trend="+2 this week" isWarning />
        <MetricCard title="Active PTWs" value="45" trend="Stable" />
        <MetricCard title="Overdue CAPAs" value="3" trend="-1 this week" isWarning />
      </div>

      <section>
        <h2 className="text-xl font-bold text-gray-800 mb-4">Recent Activity</h2>
        <DataGrid columns={columns} data={recentIncidents} />
      </section>
    </div>
  );
}
