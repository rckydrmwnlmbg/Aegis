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
    <div className="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
      <div className="overflow-x-auto">
        <table className="w-full text-left border-collapse">
          <thead>
            <tr className="border-b border-slate-100 bg-slate-50/50">
              {columns.map((col) => (
                <th key={col.key} className="p-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">
                  {col.header}
                </th>
              ))}
            </tr>
          </thead>
          <tbody>
            {data.map((row, i) => (
              <tr key={i} className="border-b border-slate-100 hover:bg-slate-50 transition-colors last:border-0">
                {columns.map((col) => (
                  <td key={col.key} className="p-4 px-6 text-sm font-medium text-slate-700">
                    {col.key === 'status' ? (
                      <span className={`px-3 py-1 rounded-full text-xs font-bold ${
                        row[col.key] === 'Open' || row[col.key] === 'Pending' ? 'bg-red-50 text-hse-red' : 'bg-emerald-50 text-emerald-600'
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
