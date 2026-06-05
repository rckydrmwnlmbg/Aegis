import { DataGrid } from "@/components/DataGrid";
import { InputField } from "@/components/InputField";

export default function IncidentsPage() {
  const incidents = [
    { id: 'INC-001', type: 'Slip & Fall', date: '2024-05-10', status: 'Open' },
    { id: 'INC-002', type: 'Chemical Spill', date: '2024-05-08', status: 'Pending' },
    { id: 'INC-003', type: 'Near Miss', date: '2024-05-01', status: 'Closed' },
  ];

  const columns = [
    { key: 'id', header: 'Incident ID' },
    { key: 'type', header: 'Type' },
    { key: 'date', header: 'Date Reported' },
    { key: 'status', header: 'Status' },
  ];

  return (
    <div className="space-y-8 max-w-6xl mx-auto">
      <header className="flex justify-between items-end">
        <div>
          <h1 className="text-3xl font-bold text-gray-800">Incidents</h1>
          <p className="text-gray-500 mt-1">Manage and track reported incidents</p>
        </div>
        <button className="glass-button-primary">Report Incident</button>
      </header>

      <div className="flex gap-4">
         <div className="w-1/3">
            <InputField placeholder="Search incidents..." />
         </div>
      </div>

      <DataGrid columns={columns} data={incidents} />
    </div>
  );
}
