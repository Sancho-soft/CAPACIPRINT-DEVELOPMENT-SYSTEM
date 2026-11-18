# CAPACIPRINT Workspace Design & Engineering Rules

All frontend, Blade, CSS, and UI work in this repository must follow CAPACIPRINT's **Clean Modern & Friendly** design system (inspired by Stripe / Notion / Canva) with generous whitespace, soft cards, big readable text, single blue primary accent (`#2563EB` / `#3B82F6`), and full Light + Dark mode parity.

## 1. Typography & Anti-Slop Rules (Strict)
- **Never** use Unicode emojis (`🚀`, `✨`, `⚡`, `📊`, `🖨️`, `✅`, `⚠️`, `❌`, etc.) as UI icons, badges, or bullets. Always use FontAwesome 6 (`fa-solid` / `fa-regular`) or crisp inline SVG icons.
- **Primary & Display Typography:** Use **one clean font everywhere — `Plus Jakarta Sans`** (`font-sans` and `font-display`, weights `400, 500, 600, 700, 800`).
- **Monospace (`JetBrains Mono` / `font-mono`):** Reserved strictly for Order IDs, Job IDs, and Claim Codes (e.g. `#ORD-001`). **Never** use `font-mono` for form labels, navigation links, table headers, badges, or descriptions. **Never** use tiny `10px–11px` uppercase monospace labels.
- **Never** use neon cyan/purple/indigo glow aesthetics (`shadow-[0_0_20px_rgba(6,182,212,...)]`, `animate-pulse-glow`, `animate-float`, `animate-bounce` on static UI icons, or `animate-pulse` on static branch/status dots).
- **Never** hardcode dark-only or light-only inline styles (`style="background: rgba(13,21,32,0.7)"`, `onmouseenter="this.style.background=..."`, or unadapted `bg-[#111A24]`). Use CSS variable tokens (`bg-cyber-card`, `bg-cyber-sub`, `border-cyber`, `text-cyber-main`) or paired Tailwind `dark:` classes.

## 2. Clean Modern Color Tokens (Light & Dark)
- **Light Mode (Default):**
  - Canvas: `#F8FAFC` (`--bg-base`)
  - Surface / Sidebar: `#F1F5F9` (`--bg-surface`)
  - Card: `#FFFFFF` (`--bg-card`)
  - Sub-panel: `#F8FAFC` (`--bg-card-sub`)
  - Border: `#E2E8F0` (`--border-main`) / `#F1F5F9` (`--border-sub`)
  - Ink: `#0F172A` primary, `#475569` muted, `#64748B` secondary
- **Dark Mode:**
  - Canvas: `#0B0F17` (`--bg-base`)
  - Surface / Sidebar: `#101319` (`--bg-surface`)
  - Card: `#151922` (`--bg-card`)
  - Sub-panel: `#1B202B` (`--bg-card-sub`)
  - Border: `#262C38` (`--border-main`) / `#1E232E` (`--border-sub`)
  - Ink: `#F3F2EE` primary, `#9BA3B0` muted, `#6B7280` secondary
- **Accents:**
  - Primary Accent: `#2563EB` (`blue-600`, light) / `#3B82F6` (`blue-500`, dark)
  - Warning / Rush: `#D97706`
  - Critical / Delay: `#DC2626`
  - Healthy / Ready: `#059669`

## 3. Squint Test & Information Hierarchy
- Soft cards (`rounded-xl` / `rounded-2xl`, subtle borders `border-slate-200/80 dark:border-slate-800`, subtle `shadow-sm`).
- Generous whitespace and readable copy (`15px–18px` body text on marketing/landing pages, clear `13px–14px` UI controls).
- Every metric, capacity percentage, price, and count uses `tabular-nums`.
