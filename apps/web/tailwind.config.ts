import type { Config } from "tailwindcss";

const config: Config = {
  content: [
    "./src/pages/**/*.{js,ts,jsx,tsx,mdx}",
    "./src/components/**/*.{js,ts,jsx,tsx,mdx}",
    "./src/app/**/*.{js,ts,jsx,tsx,mdx}",
  ],
  theme: {
    extend: {
      colors: {
        primary: "#0F172A",
        brand: "#0F172A",
        background: "#F8FAFC",
        surface: "#FFFFFF",
        "text-primary": "#0F172A",
        "text-secondary": "#64748B",
        danger: "#DC2626",
        warning: "#D97706",
        safe: "#16A34A",
        info: "#2563EB",
      },
      fontFamily: {
        sans: ["Inter", "sans-serif"],
      },
    },
  },
  plugins: [],
};
export default config;
