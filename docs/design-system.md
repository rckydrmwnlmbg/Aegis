# Design System & UI/UX Guidelines
**Project:** Aegis AI EHS Platform
**Theme:** True Futuristic Soft UI & Glassmorphism

## 1. Core Philosophy
The visual identity of Aegis EHS strictly relies on a fluid, non-rigid aesthetic inspired by modern, airy dashboards. It entirely avoids harsh, solid dark blocks (like traditional sidebars). Instead, it uses a soft gradient canvas overlaid with highly translucent floating elements. While utilitarian (high contrast, easy to read), it must feel futuristic and light.

## 2. Global Background
- **Gradient Canvas**: Never use solid white or rigid solid colors for the main background. Always use a very soft gradient mesh or linear gradient, for example: `bg-gradient-to-br from-slate-50 to-slate-100`. This creates depth for the glass elements on top.

## 3. Glassmorphism & Surfaces (Cards, Sidebar)
- **Effect**: All main structural containers (Sidebars, Cards, Widgets) must use true glassmorphism.
- **Tailwind Classes**: Implement using highly transparent white backgrounds and strong blurs.
  - Background: `bg-white/60` (or similar low opacity).
  - Blur: `backdrop-blur-xl` or `backdrop-blur-2xl`.
  - Border: Subtle translucent border `border border-white/40` to catch the light.
  - Shadow: Large, soft, and highly transparent shadows, e.g., `shadow-2xl shadow-slate-200/40` or `shadow-[0_8px_30px_rgb(0,0,0,0.04)]`.
- **Floating Sidebar**: The sidebar must not be a solid, edge-to-edge block. It must be a "floating" glassmorphic container with margins separating it from the edges of the screen, or seamlessly blend with the background.

## 4. Shape & Border
- **Extreme Rounded Corners**: Avoid sharp corners entirely.
  - Cards, sidebars, and large containers: Use `rounded-3xl` or `rounded-[2rem]`.
  - Buttons, badges, and small indicators: Use `rounded-full`.

## 5. Color Palette & Accents
- **Primary Accent (HSE Identity)**: **Crimson / Neon Red** (e.g. #DC2626). Used *extremely minimally* and *elegantly*. Only apply it as a thin accent (e.g., small indicator dots, warning text, or a sparkline chart). Do not use it for large solid buttons or blocks unless absolutely critical.
- **Semantic Colors**:
  - Safe / Active: Soft Emerald #16A34A (Use light translucent backgrounds like `bg-emerald-500/10` with `text-emerald-600` for badges).
  - Warning: Soft Amber #D97706.
- **Text Primary**: Navy Blue / Slate 900 (`#0f172a`) for high contrast and readability against the translucent white cards.
- **Text Secondary**: Slate 500 (`#64748B`).

## 6. Typography
- **Web (Next.js)**: Inter (sans-serif). Bersih dan sangat terbaca.
- **Base Size**: 16px untuk teks tubuh.
