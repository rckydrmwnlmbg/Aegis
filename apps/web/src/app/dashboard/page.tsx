export default function DashboardPage() {
  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-2xl font-bold text-text-primary">Dashboard</h1>
        <p className="text-text-secondary mt-1">Overview of your EHS operations.</p>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        {/* Placeholder metric cards */}
        <div className="bg-surface p-6 rounded-lg shadow-sm border border-gray-100">
          <h3 className="text-sm font-medium text-text-secondary mb-2">Open Incidents</h3>
          <p className="text-3xl font-bold text-danger">12</p>
        </div>

        <div className="bg-surface p-6 rounded-lg shadow-sm border border-gray-100">
          <h3 className="text-sm font-medium text-text-secondary mb-2">Pending Permits</h3>
          <p className="text-3xl font-bold text-warning">5</p>
        </div>

        <div className="bg-surface p-6 rounded-lg shadow-sm border border-gray-100">
          <h3 className="text-sm font-medium text-text-secondary mb-2">Overdue CAPA</h3>
          <p className="text-3xl font-bold text-danger">2</p>
        </div>

        <div className="bg-surface p-6 rounded-lg shadow-sm border border-gray-100">
          <h3 className="text-sm font-medium text-text-secondary mb-2">Safety Score</h3>
          <p className="text-3xl font-bold text-safe">94%</p>
        </div>
      </div>

      <div className="bg-surface p-6 rounded-lg shadow-sm border border-gray-100 min-h-[400px]">
        <h3 className="text-lg font-medium text-text-primary mb-4">Recent Activity</h3>
        <div className="flex items-center justify-center h-64 text-text-secondary">
          Activity chart placeholder
        </div>
      </div>
    </div>
  );
}
