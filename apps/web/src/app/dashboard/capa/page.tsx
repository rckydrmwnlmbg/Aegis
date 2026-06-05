import { DataGrid } from "@/components/DataGrid";

export default function CapaPage() {
  const capas = [
    { id: 'CAPA-042', title: 'Fix leaking pipe', dueDate: '2024-06-15', status: 'In Progress' },
    { id: 'CAPA-043', title: 'Update safety manual', dueDate: '2024-05-20', status: 'Overdue' },
  ];

  const columns = [
    { key: 'id', header: 'CAPA ID' },
    { key: 'title', header: 'Title' },
    { key: 'dueDate', header: 'Due Date' },
    { key: 'status', header: 'Status' },
  ];

  return (
    <div className="space-y-8 max-w-6xl mx-auto">
      <header className="flex justify-between items-end">
        <div>
          <h1 className="text-3xl font-bold text-gray-800">CAPA</h1>
          <p className="text-gray-500 mt-1">Corrective and Preventive Actions</p>
        </div>
        <button className="glass-button-primary">New CAPA</button>
      </header>

      <DataGrid columns={columns} data={capas} />
    </div>
  );
}
