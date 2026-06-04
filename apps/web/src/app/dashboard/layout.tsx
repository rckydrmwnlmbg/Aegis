'use client';

import { useEffect, useState } from 'react';
import { useRouter, usePathname } from 'next/navigation';
import Link from 'next/link';
import Cookies from 'js-cookie';
import api from '@/lib/api';
import {
  LayoutDashboard,
  AlertTriangle,
  ShieldAlert,
  FileCheck,
  ClipboardCheck,
  Search,
  LogOut,
  User,
  Menu
} from 'lucide-react';

interface UserProfile {
  id: string;
  name: string;
  email: string;
  role?: string;
}

export default function DashboardLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  const router = useRouter();
  const pathname = usePathname();
  const [user, setUser] = useState<UserProfile | null>(null);
  const [loading, setLoading] = useState(true);
  const [sidebarOpen, setSidebarOpen] = useState(false);

  useEffect(() => {
    const fetchUser = async () => {
      try {
        const response = await api.get('/api/v1/auth/me');
        setUser(response.data.data || response.data);
      } catch {
        // If getting user fails (e.g., token expired), redirect to login
        Cookies.remove('token');
        router.push('/login');
      } finally {
        setLoading(false);
      }
    };

    fetchUser();
  }, [router]);

  const handleLogout = () => {
    Cookies.remove('token');
    router.push('/login');
  };

  const navItems = [
    { name: 'Dashboard', href: '/dashboard', icon: LayoutDashboard },
    { name: 'Incidents', href: '/dashboard/incidents', icon: AlertTriangle },
    { name: 'Hazards', href: '/dashboard/hazards', icon: ShieldAlert },
    { name: 'CAPA', href: '/dashboard/capa', icon: FileCheck },
    { name: 'Permits (PTW)', href: '/dashboard/permits', icon: ClipboardCheck },
    { name: 'Inspections', href: '/dashboard/inspections', icon: Search },
  ];

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100">
        <div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary"></div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 flex p-4 md:p-6 lg:p-8 gap-6 font-sans">
      {/* Mobile Sidebar Overlay */}
      {sidebarOpen && (
        <div
          className="fixed inset-0 z-20 bg-slate-900/20 backdrop-blur-sm md:hidden rounded-3xl"
          onClick={() => setSidebarOpen(false)}
        />
      )}

      {/* Glassmorphic Sidebar */}
      <aside
        className={`fixed inset-y-4 left-4 z-30 w-64 bg-white/60 backdrop-blur-2xl border border-white/40 shadow-[0_8px_30px_rgb(0,0,0,0.04)] rounded-[2rem] transform transition-transform duration-300 md:relative md:translate-x-0 md:inset-auto md:h-auto ${
          sidebarOpen ? 'translate-x-0' : '-translate-x-[120%]'
        } flex flex-col`}
      >
        <div className="flex items-center justify-center h-24 border-b border-slate-200/50 mx-6">
          <div className="flex items-center gap-3">
            <div className="w-8 h-8 rounded-full bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center text-white font-bold shadow-lg">
              A
            </div>
            <h1 className="text-xl font-bold text-slate-800 tracking-tight">Aegis EHS</h1>
          </div>
        </div>

        <nav className="flex-1 p-4 space-y-2 overflow-y-auto">
          {navItems.map((item) => {
            const Icon = item.icon;
            const isActive = pathname === item.href;

            return (
              <Link
                key={item.name}
                href={item.href}
                className={`flex items-center px-4 py-3 min-h-[48px] rounded-2xl transition-all duration-200 ${
                  isActive
                    ? 'bg-white shadow-sm border border-white/50 text-slate-900 font-semibold'
                    : 'text-slate-500 hover:bg-white/50 hover:text-slate-800'
                }`}
              >
                <Icon className={`w-5 h-5 mr-3 ${isActive ? 'text-blue-600' : 'text-slate-400'}`} />
                <span>{item.name}</span>
              </Link>
            );
          })}
        </nav>

        <div className="p-6 border-t border-slate-200/50 mx-2 mb-2">
           <button
              onClick={handleLogout}
              className="flex items-center w-full px-4 py-3 rounded-2xl text-slate-500 hover:bg-white/50 hover:text-danger transition-colors"
            >
              <LogOut className="w-5 h-5 mr-3 text-slate-400 group-hover:text-danger" />
              <span className="font-medium">Logout</span>
            </button>
        </div>
      </aside>

      {/* Main Content Area */}
      <div className="flex-1 flex flex-col min-w-0">
        {/* Topbar / Header (Floating Glass) */}
        <header className="h-20 bg-white/60 backdrop-blur-xl border border-white/40 shadow-[0_8px_30px_rgb(0,0,0,0.02)] rounded-[2rem] z-10 flex items-center justify-between px-6 mb-6">
          <div className="flex items-center gap-4">
            <button
              className="p-2 md:hidden text-slate-500 hover:bg-white/50 rounded-xl transition-colors"
              onClick={() => setSidebarOpen(true)}
            >
              <Menu className="w-6 h-6" />
            </button>
            <div className="hidden md:flex items-center gap-2 text-slate-400 text-sm font-medium">
               <Search className="w-4 h-4" />
               <input
                  type="text"
                  placeholder="Search anything..."
                  className="bg-transparent border-none outline-none text-slate-700 placeholder-slate-400 w-64 focus:ring-0"
                />
            </div>
          </div>

          <div className="flex items-center space-x-4">
            <div className="flex items-center space-x-3 bg-white/50 px-3 py-1.5 rounded-full border border-white/60 shadow-sm">
              <div className="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-slate-600">
                <User className="w-4 h-4" />
              </div>
              <div className="hidden sm:block pr-2">
                <p className="font-semibold text-slate-800 text-sm leading-tight">{user?.name}</p>
                {user?.role && <p className="text-slate-500 text-[11px] leading-tight">{user.role}</p>}
              </div>
            </div>
          </div>
        </header>

        {/* Page Content */}
        <main className="flex-1 overflow-x-hidden overflow-y-auto">
          {children}
        </main>
      </div>
    </div>
  );
}
