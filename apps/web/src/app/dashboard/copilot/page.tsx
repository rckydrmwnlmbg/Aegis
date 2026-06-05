import { InputField } from "@/components/InputField";

export default function CopilotPage() {
  return (
    <div className="space-y-8 max-w-4xl mx-auto h-[calc(100vh-8rem)] flex flex-col">
      <header>
        <h1 className="text-3xl font-bold text-gray-800">Aegis Copilot</h1>
        <p className="text-gray-500 mt-1">Your AI Safety Assistant</p>
      </header>

      <div className="flex-1 glass-panel p-6 flex flex-col justify-end gap-4">
        {/* Chat messages would go here */}
        <div className="flex gap-4 mb-auto">
             <div className="glass-panel p-4 max-w-[80%] rounded-tl-none bg-white/80">
                 <p className="text-gray-800">Hello! I am Aegis Copilot. How can I help you with safety and compliance today?</p>
             </div>
        </div>

        <div className="flex gap-2 items-end">
          <InputField placeholder="Ask Copilot anything..." />
          <button className="glass-button-primary mb-1">Send</button>
        </div>
      </div>
    </div>
  );
}
