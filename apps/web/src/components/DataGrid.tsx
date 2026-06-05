import React from 'react';

interface Column {
  key: string;
  header: string;
}

interface DataGridProps {
  columns: Column[];
  data: Record<string, string | number>[];
}

export function DataGrid({ columns, data }: DataGridProps) {
  return (
    <div className="glass-panel overflow-hidden">
      <div className="overflow-x-auto">
        <table className="w-full text-left border-collapse">
          <thead>
            <tr className="border-b border-white/20">
              {columns.map((col) => (
                <th key={col.key} className="p-4 text-sm font-semibold text-gray-600">
                  {col.header}
                </th>
              ))}
            </tr>
          </thead>
          <tbody>
            {data.map((row, i) => (
              <tr key={i} className="border-b border-white/10 hover:bg-white/30 transition-colors last:border-0">
                {columns.map((col) => (
                  <td key={col.key} className="p-4 text-sm text-gray-800">
                    {col.key === 'status' ? (
                      <span className={`px-3 py-1 rounded-full text-xs font-medium ${
                        row[col.key] === 'Open' || row[col.key] === 'Pending' ? 'bg-hse-red/10 text-hse-red' : 'bg-emerald-500/10 text-emerald-600'
                      }`}>
                        {row[col.key]}
                      </span>
                    ) : (
                      row[col.key]
                    )}
                  </td>
                ))}
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
