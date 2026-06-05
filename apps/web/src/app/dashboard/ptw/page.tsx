import { DataGrid } from "@/components/DataGrid";

export default function PtwPage() {
  const ptws = [
    { id: 'PTW-101', type: 'Hot Work', location: 'Zone A', status: 'Active' },
    { id: 'PTW-102', type: 'Confined Space', location: 'Zone C', status: 'Pending Approval' },
  ];

  const columns = [
    { key: 'id', header: 'Permit ID' },
    { key: 'type', header: 'Type' },
    { key: 'location', header: 'Location' },
    { key: 'status', header: 'Status' },
  ];

  return (
    <div className="space-y-8 max-w-6xl mx-auto">
      <header className="flex justify-between items-end">
        <div>
          <h1 className="text-3xl font-bold text-gray-800">Permit to Work</h1>
          <p className="text-gray-500 mt-1">Manage work permits</p>
        </div>
        <button className="glass-button-primary">Request PTW</button>
      </header>

      <DataGrid columns={columns} data={ptws} />
    </div>
  );
}
