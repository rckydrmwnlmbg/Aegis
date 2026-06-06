export default function Dashboard() {


  return (
    <div className="max-w-[1600px] mx-auto pb-12">
      <header className="flex justify-between items-end mb-8">
        <div>
          <h1 className="text-3xl font-extrabold text-slate-900 tracking-tight">Overview</h1>
          <p className="text-slate-500 mt-2 font-medium">Platform performance and key metrics</p>
        </div>
        <button className="bg-slate-900 hover:bg-slate-800 text-white rounded-full px-6 py-2.5 transition-all shadow-sm hover:shadow-md font-semibold text-sm">
          Generate Report
        </button>
      </header>

      {/* Bento Box Grid */}
      <div className="grid grid-cols-12 gap-6">

        {/* KARTU 1 (col-span-8): HSE Performance Overview */}
        <div className="col-span-12 lg:col-span-8 bg-white rounded-3xl shadow-sm border border-slate-100 p-6 flex flex-col min-h-[400px]">
           <div className="flex justify-between items-center mb-6">
             <h3 className="text-lg font-bold text-slate-900">HSE Performance Overview</h3>
             <select className="bg-slate-50 border-none text-sm font-bold text-slate-600 rounded-full px-4 py-2 focus:ring-0">
               <option>Last 6 Months</option>
             </select>
           </div>

           <div className="flex-1 flex items-end gap-6 h-full mt-auto pb-2 relative">
             {/* Y-axis labels dummy */}
             <div className="absolute left-0 top-0 bottom-8 flex flex-col justify-between text-xs font-bold text-slate-400">
               <span>100</span>
               <span>75</span>
               <span>50</span>
               <span>25</span>
               <span>0</span>
             </div>

             {/* Chart area */}
             <div className="flex-1 ml-8 flex items-end justify-around h-full border-b border-slate-100 pb-2">
                {[
                  { m: 'Jan', i: 40, n: 60 },
                  { m: 'Feb', i: 30, n: 70 },
                  { m: 'Mar', i: 50, n: 45 },
                  { m: 'Apr', i: 20, n: 85 },
                  { m: 'May', i: 60, n: 40 },
                  { m: 'Jun', i: 25, n: 80 }
                ].map((data, i) => (
                  <div key={i} className="flex flex-col items-center gap-2 group h-full justify-end">
                    <div className="flex items-end gap-1.5 h-full relative">
                      {/* Incident Bar */}
                      <div className="w-8 bg-hse-red rounded-t-lg transition-all duration-300 group-hover:opacity-80" style={{ height: `${data.i}%` }}></div>
                      {/* Near Miss Bar */}
                      <div className="w-8 bg-slate-800 rounded-t-lg transition-all duration-300 group-hover:opacity-80" style={{ height: `${data.n}%` }}></div>

                      {/* Tooltip on hover */}
                      <div className="absolute -top-10 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-xs font-bold px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10 pointer-events-none">
                        {data.i} Incidents / {data.n} Near Miss
                      </div>
                    </div>
                    <span className="text-sm font-bold text-slate-500">{data.m}</span>
                  </div>
                ))}
             </div>
           </div>
           <div className="flex justify-center gap-6 mt-4">
              <div className="flex items-center gap-2">
                <span className="w-3 h-3 rounded-full bg-hse-red"></span>
                <span className="text-xs font-bold text-slate-600">Incident</span>
              </div>
              <div className="flex items-center gap-2">
                <span className="w-3 h-3 rounded-full bg-slate-800"></span>
                <span className="text-xs font-bold text-slate-600">Near Miss</span>
              </div>
           </div>
        </div>

        {/* KARTU 2 (col-span-4): Compliance Rate */}
        <div className="col-span-12 lg:col-span-4 bg-white rounded-3xl shadow-sm border border-slate-100 p-6 flex flex-col items-center justify-center relative overflow-hidden min-h-[400px]">
           <h3 className="text-lg font-bold text-slate-900 absolute top-6 left-6">Compliance Rate</h3>

           <div className="relative w-48 h-48 flex items-center justify-center mt-8">
             {/* Dummy SVG Donut Chart */}
             <svg className="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
               <path
                 className="text-slate-100"
                 d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                 fill="none"
                 stroke="currentColor"
                 strokeWidth="3.5"
               />
               <path
                 className="text-emerald-500"
                 strokeDasharray="94, 100"
                 d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                 fill="none"
                 stroke="currentColor"
                 strokeWidth="3.5"
                 strokeLinecap="round"
               />
             </svg>
             <div className="absolute inset-0 flex flex-col items-center justify-center">
               <span className="text-5xl font-extrabold text-slate-900">94<span className="text-2xl text-slate-400">%</span></span>
               <span className="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full mt-1">+2.4%</span>
             </div>
           </div>

           <p className="text-sm font-bold text-slate-500 mt-6 text-center max-w-[80%]">
             Across 12 active sites and 480 personnel
           </p>
        </div>

        {/* KARTU 3 (col-span-4): Active PTW Pipeline */}
        <div className="col-span-12 lg:col-span-4 bg-white rounded-3xl shadow-sm border border-slate-100 p-6 flex flex-col">
           <div className="flex justify-between items-center mb-6">
             <h3 className="text-lg font-bold text-slate-900">Active PTW Pipeline</h3>
             <span className="bg-slate-100 text-slate-600 text-xs font-bold px-2 py-1 rounded-full">View All</span>
           </div>

           <div className="flex flex-col gap-4">
             {[
               { title: 'Hot Work', loc: 'Site A - Sector 4', status: 'Approved', color: 'emerald' },
               { title: 'Confined Space', loc: 'Tank Farm B', status: 'Pending Review', color: 'amber' },
               { title: 'Working at Height', loc: 'Tower C', status: 'Active', color: 'blue' },
             ].map((ptw, i) => (
               <div key={i} className="flex items-center justify-between p-4 rounded-2xl bg-slate-50 hover:bg-slate-100 transition-colors cursor-pointer">
                 <div>
                   <h4 className="font-bold text-slate-900">{ptw.title}</h4>
                   <p className="text-xs font-semibold text-slate-500">{ptw.loc}</p>
                 </div>
                 <span className={`px-3 py-1 rounded-full text-xs font-bold ${
                   ptw.color === 'emerald' ? 'bg-emerald-100 text-emerald-700' :
                   ptw.color === 'amber' ? 'bg-amber-100 text-amber-700' :
                   'bg-blue-100 text-blue-700'
                 }`}>
                   {ptw.status}
                 </span>
               </div>
             ))}
           </div>
           <button className="mt-auto pt-4 text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors w-full text-center">
              + Request New PTW
           </button>
        </div>

        {/* KARTU 4 (col-span-4): Latest Triage Captures */}
        <div className="col-span-12 lg:col-span-4 bg-white rounded-3xl shadow-sm border border-slate-100 p-6 flex flex-col">
           <div className="flex justify-between items-center mb-6">
             <h3 className="text-lg font-bold text-slate-900">Latest Triage Captures</h3>
             <span className="flex h-3 w-3 relative">
                <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-hse-red opacity-75"></span>
                <span className="relative inline-flex rounded-full h-3 w-3 bg-hse-red"></span>
             </span>
           </div>

           <div className="grid grid-cols-2 gap-4">
             {[
               { title: 'Oil Spill', tag: 'High', time: '10m ago' },
               { title: 'Exposed Wire', tag: 'Medium', time: '1h ago' },
             ].map((cap, i) => (
               <div key={i} className="relative aspect-square rounded-2xl bg-slate-200 overflow-hidden group cursor-pointer">
                 {/* Dummy Image Placeholder */}
                 <div className="absolute inset-0 bg-slate-800 flex items-center justify-center">
                    <svg className="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                 </div>
                 {/* Overlay */}
                 <div className="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent opacity-90 group-hover:opacity-100 transition-opacity"></div>

                 <div className="absolute bottom-3 left-3 right-3">
                   <div className="flex justify-between items-end">
                     <div>
                       <span className={`px-2 py-0.5 rounded-full text-[10px] font-bold mb-1 inline-block ${
                         cap.tag === 'High' ? 'bg-hse-red text-white' : 'bg-amber-500 text-white'
                       }`}>
                         {cap.tag}
                       </span>
                       <h4 className="text-white text-xs font-bold truncate">{cap.title}</h4>
                     </div>
                   </div>
                 </div>
                 <span className="absolute top-2 right-2 text-[10px] font-bold text-white/80 bg-black/30 px-1.5 py-0.5 rounded backdrop-blur-sm">{cap.time}</span>
               </div>
             ))}
           </div>
        </div>

        {/* KARTU 5 (col-span-4): Safety Certifications Expiring */}
        <div className="col-span-12 lg:col-span-4 bg-white rounded-3xl shadow-sm border border-slate-100 p-6 flex flex-col">
           <div className="flex justify-between items-center mb-6">
             <h3 className="text-lg font-bold text-slate-900">Certifications Expiring</h3>
             <span className="bg-red-50 text-hse-red text-xs font-bold px-2 py-1 rounded-full">3 Critical</span>
           </div>

           <div className="flex flex-col gap-4">
             {[
               { name: 'Budi Santoso', cert: 'Confined Space Entry', days: 2 },
               { name: 'Ahmad Yani', cert: 'Working at Height', days: 5 },
               { name: 'Siti Nurhaliza', cert: 'First Aid CPR', days: 12 },
             ].map((cert, i) => (
               <div key={i} className="flex items-center gap-4">
                 <div className="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center shrink-0">
                    <span className="text-slate-600 font-bold text-sm">{cert.name.split(' ').map(n=>n[0]).join('')}</span>
                 </div>
                 <div className="flex-1 min-w-0">
                   <h4 className="text-sm font-bold text-slate-900 truncate">{cert.name}</h4>
                   <p className="text-xs font-semibold text-slate-500 truncate">{cert.cert}</p>
                 </div>
                 <div className="text-right shrink-0">
                   <span className={`text-xs font-bold px-2 py-1 rounded-full ${
                     cert.days <= 7 ? 'bg-red-50 text-hse-red' : 'bg-amber-50 text-amber-600'
                   }`}>
                     In {cert.days} days
                   </span>
                 </div>
               </div>
             ))}
           </div>
           <button className="mt-auto pt-6 text-sm font-bold text-slate-900 hover:text-slate-700 transition-colors w-full text-center">
              View All Certifications →
           </button>
        </div>

      </div>
    </div>
  );
}
