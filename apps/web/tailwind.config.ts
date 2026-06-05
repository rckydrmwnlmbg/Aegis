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
        'hse-red': '#E63946',
        'soft-bg': '#F0F4F8',
      },
      borderRadius: {
        '3xl': '2rem',
      },
    },
  },
  plugins: [],
};
export default config;
