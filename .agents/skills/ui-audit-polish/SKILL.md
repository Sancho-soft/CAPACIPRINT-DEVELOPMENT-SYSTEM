---
name: ui-audit-polish
description: >-
  Use this skill after modifying any Blade view, layout, or CSS file to audit
  contrast, light/dark mode parity, anti-slop compliance, responsiveness, and
  DOM/Alpine integrity.
---

# UI Audit & Polish Checklist

Run this systematic audit after editing any UI file in `resources/views/` or `resources/css/`.

## 1. Anti-Slop Pattern Scan
Search modified files for banned patterns:
- Inline dark hexes or inline RGBA backgrounds (`#111A24`, `#0D1520`, `rgba(13,21,32`)
- Inline JS hover hacks (`onmouseenter=`, `onmouseleave=`)
- Excessive AI-slop classes (`animate-bounce`, `animate-pulse-glow`, `animate-float`, `rounded-3xl`, `shadow-2xl` on standard cards)
- Legacy font references (`Inter`, `Outfit`)
- Unicode emoji icons (`🚀`, `✨`, `⚡`, `✅`, `⚠️`, `❌`, etc.) instead of FontAwesome / SVG icons

## 2. Theme Parity & Contrast Verification
- Verify every `thead`, `tbody tr:hover`, modal, dropdown, badge, and input works cleanly in **both** `html.light-theme` (Clean Slate Light `#F8FAFC`) and `html.dark-theme` (Clean Dark `#0B0F17`).
- Verify Chart.js tooltips, borders, and donut cutouts adapt to `isDark`.
- Check text contrast on tinted badges (`bg-emerald-500/10`, `bg-amber-500/10`, `bg-rose-500/10`, `bg-blue-500/10`).

## 3. Functional & Blade Integrity
- Confirm `@extends`, `@section`, `@props`, `route(...)` helpers, Alpine `x-data`/`x-show` bindings, and pagination containers (`id="press-line-queue"`, `nav[aria-label="Pagination Navigation"]`) remain intact so global live search and AJAX pagination continue working.
- Run `php -l` or `php artisan view:cache` / `view:clear` after editing Blade views to verify zero syntax errors.
