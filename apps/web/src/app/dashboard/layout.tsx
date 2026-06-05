import { Sidebar } from "@/components/Sidebar";

export default function DashboardLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <div className="flex h-screen overflow-hidden">
      <Sidebar />
      <main className="flex-1 overflow-y-auto p-4 pl-0">
        <div className="h-full glass-panel overflow-y-auto p-8 relative">
          {children}
        </div>
      </main>
    </div>
  );
}
