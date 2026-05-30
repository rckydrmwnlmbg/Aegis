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
      <div className="min-h-screen flex items-center justify-center bg-background">
        <div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary"></div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-background flex">
      {/* Mobile Sidebar Overlay */}
      {sidebarOpen && (
        <div
          className="fixed inset-0 z-20 bg-black/50 md:hidden"
          onClick={() => setSidebarOpen(false)}
        />
      )}

      {/* Sidebar */}
      <aside
        className={`fixed inset-y-0 left-0 z-30 w-64 bg-primary text-white transform transition-transform duration-300 md:relative md:translate-x-0 ${
          sidebarOpen ? 'translate-x-0' : '-translate-x-full'
        }`}
      >
        <div className="flex items-center justify-center h-16 border-b border-white/10">
          <h1 className="text-xl font-bold">Aegis EHS</h1>
        </div>

        <nav className="p-4 space-y-2">
          {navItems.map((item) => {
            const Icon = item.icon;
            const isActive = pathname === item.href;

            return (
              <Link
                key={item.name}
                href={item.href}
                className={`flex items-center px-4 py-3 min-h-[48px] rounded-md transition-colors ${
                  isActive
                    ? 'bg-white/20 text-white'
                    : 'text-white/80 hover:bg-white/10 hover:text-white'
                }`}
              >
                <Icon className="w-5 h-5 mr-3" />
                <span className="font-medium">{item.name}</span>
              </Link>
            );
          })}
        </nav>
      </aside>

      {/* Main Content */}
      <div className="flex-1 flex flex-col overflow-hidden">
        {/* Topbar */}
        <header className="h-16 bg-surface shadow-sm z-10 flex items-center justify-between px-4 sm:px-6">
          <button
            className="p-2 md:hidden text-text-secondary hover:bg-gray-100 rounded-md"
            onClick={() => setSidebarOpen(true)}
          >
            <Menu className="w-6 h-6" />
          </button>

          <div className="hidden md:block">
            {/* Breadcrumb or Page Title could go here */}
          </div>

          <div className="flex items-center space-x-4">
            <div className="flex items-center space-x-3">
              <div className="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center text-primary">
                <User className="w-5 h-5" />
              </div>
              <div className="hidden sm:block text-sm">
                <p className="font-medium text-text-primary">{user?.name}</p>
                {user?.role && <p className="text-text-secondary text-xs">{user.role}</p>}
              </div>
            </div>

            <button
              onClick={handleLogout}
              className="p-2 text-text-secondary hover:text-danger hover:bg-danger/10 rounded-md transition-colors"
              title="Logout"
            >
              <LogOut className="w-5 h-5" />
            </button>
          </div>
        </header>

        {/* Page Content */}
        <main className="flex-1 overflow-x-hidden overflow-y-auto bg-background p-4 sm:p-6 lg:p-8">
          {children}
        </main>
      </div>
    </div>
  );
}
