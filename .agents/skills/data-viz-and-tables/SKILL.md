---
name: data-viz-and-tables
description: >-
  Use this skill when creating or updating Chart.js graphs, capacity progress
  bars, KPI metrics, or data tables across CAPACIPRINT dashboards and report views.
---

# Data Visualization & Operational Tables (CAPACIPRINT)

## 1. Chart.js Theme & Palette Standards
All charts must read the active theme (`const isDark = document.documentElement.classList.contains('dark') || document.documentElement.classList.contains('dark-theme');`) and adapt cleanly:
- **Clean Modern Data Palette**:
  - Primary Series (Primary Blue): `#2563EB` (or `#3B82F6` dark mode)
  - Secondary Series (Indigo): `#6366F1`
  - Healthy / Inflow (Emerald): `#059669`
  - Warning / Pending (Amber): `#D97706`
  - Critical / Outflow / Delay (Crimson): `#DC2626`
- **Chart Surface Contrast**:
  - Doughnut segment border: `isDark ? '#151922' : '#FFFFFF'` (`borderWidth: 2`).
  - Axis ticks & gridlines: `isDark ? '#9BA3B0' : '#475569'` for text, `rgba(255,255,255,0.05)` (dark) / `rgba(17,19,21,0.08)` (light) for gridlines.
  - Font family in Chart.js options: `'Plus Jakarta Sans, sans-serif'` for both labels and numeric axis ticks.
  - Tooltips: theme-aware background, border, and crisp `cornerRadius: 8`.

## 2. Operational Data Table Standards
- Wrap every `<table>` in `<div class="overflow-x-auto">` inside a `bg-cyber-card border border-cyber rounded-2xl shadow-sm overflow-hidden` container.
- Apply `tabular-nums` on the `<table>` so quantities, prices (`₱`), percentages, and dates align vertically.
- Use `<thead class="bg-cyber-sub text-cyber-muted font-sans font-semibold border-b border-cyber text-xs">`. Never use inline `style="background: rgba(...)"` on `<thead>`.
- Use Tailwind hover classes on rows (`hover:bg-black/[0.02] dark:hover:bg-white/[0.025] transition-colors`) instead of `onmouseenter`/`onmouseleave`.
- Align numeric columns and row action columns to the right (`text-right`).

## 3. Capacity & Progress Meters
- Use smooth pill tracks (`h-2 rounded-full bg-black/10 dark:bg-white/10`) with threshold-driven semantic fills (`>=85%` crimson, `>=50%` amber, `<50%` emerald).
