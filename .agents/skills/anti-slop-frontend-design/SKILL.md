---
name: anti-slop-frontend-design
description: >-
  Use this skill whenever designing, refactoring, or auditing Blade templates,
  CSS tokens, dashboards, landing pages, or UI components to eliminate generic
  AI-slop aesthetics and enforce CAPACIPRINT's Clean Modern & Friendly design
  system.
---

# Anti-Slop Frontend Design Skill

Applies intentional craft, structured visual hierarchy, and CAPACIPRINT's Clean Modern & Friendly design system (generous whitespace, soft cards, single blue primary accent `#2563EB` / `#3B82F6`, and full Light + Dark mode parity).

## 1. Quantitative Aesthetic Calibration (`taste-skill`)
- **`DESIGN_VARIANCE = 6/10`**: Structured asymmetric grid proportions (2:1 executive splits, clear pipeline stages, logical section grouping) rather than cluttered boxes.
- **`MOTION_INTENSITY = 2/10`**: Crisp, responsive transitions (`150ms–200ms cubic-bezier(0.2, 0, 0, 1)`) for hover states, dropdowns, and sidebar collapse. Zero decorative bounce, float, or ambient pulse-glow loops.
- **`VISUAL_DENSITY = 7/10`**: Clear operational readability for print-shop managers, staff, and operators, balanced with generous padding and strict typographic hierarchy.

## 2. Mandatory Pre-Code Design Plan
Before modifying or creating any view, define:
1. **Page Role & Primary Action (The Squint Test)**: Identify the single dominant focal point on the screen (e.g., active press capacity matrix, live job queue, or customer print intake CTA). Secondary controls must visually recede.
2. **Typography Hierarchy**:
   - **Unified Clean Typography (`font-sans` & `font-display`)**: Use **`Plus Jakarta Sans`** everywhere across the entire system (headings `font-bold` / `font-extrabold` `tracking-tight`, UI labels `13px–14px`, landing prose `15px–18px`).
   - **Numeric & ID Alignment (`tabular-nums`)**: Apply `tabular-nums` to Order IDs (`#ORD-001`), Job IDs, Request IDs (`#PR-00001`), quantities, percentages, timestamps, and currency (`₱`).
3. **Color Restraint**:
   - At least 85% neutral canvas/card surfaces (`--bg-base` `#F8FAFC` light / `#0B0F17` dark, `--bg-card` `#FFFFFF` / `#151922`).
   - Primary Blue (`#2563EB` light / `#3B82F6` dark) reserved for primary CTAs and active navigation states.
   - Semantic status colors (Emerald `#059669`, Amber `#D97706`, Crimson `#DC2626`) strictly reserved for live operational state—never decorative rainbow gradients.

## 3. Structural Patterns to Apply
- **Soft Clean Cards**: Use `rounded-2xl` / `rounded-xl` cards with subtle borders (`border-slate-200/80 dark:border-slate-800`, `border-cyber`) and `shadow-sm` over heavy `shadow-2xl` neon containers.
- **Readable KPI Plates**: Use crisp icon containers (`rounded-xl`), clear sentence-case labels, and oversized `tabular-nums` display values—no bouncing/scaling icons.
- **Clean Dots & Badges**: Use `rounded-full` status pills and crisp static dots without distracting `animate-pulse` on static indicators.
- **Clean Light & Dark Parity**: Never hardcode `#111A24`, `#0D1520`, or `rgba(13,21,32,...)` inline. Always bind to CSS variables (`bg-cyber-card`, `bg-cyber-sub`, `border-cyber`, `text-cyber-main`) or paired `light` + `dark:` utilities.
- **Icon Discipline (Zero Emoji Icons)**: Never use Unicode emojis (`🚀`, `✨`, `⚡`, `📊`, `🖨️`, `✅`, `⚠️`, `❌`, etc.) anywhere in labels, headings, status badges, or buttons. Always use FontAwesome 6 (`fa-solid`, `fa-regular`) or crisp SVG icons.

## 4. Self-Critique Before Finishing
1. Does it pass the Squint Test—does one primary element lead each section?
2. Are neon cyan glows, purple gradients, `animate-bounce`, old monospace form labels, and unadapted dark-only inline hexes completely absent?
3. Are all numbers tabular (`tabular-nums`) and readable in both Clean Slate Light (`#F8FAFC`) and Clean Dark (`#0B0F17`) modes?
