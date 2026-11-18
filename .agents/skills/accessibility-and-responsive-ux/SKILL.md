---
name: accessibility-and-responsive-ux
description: >-
  Use this skill when building or auditing CAPACIPRINT UI screens for WCAG contrast,
  keyboard navigation, mobile/tablet shop-floor usability, and responsive layout
  integrity.
---

# Accessibility & Responsive UX (CAPACIPRINT)

## 1. Contrast Across Both Themes
- Verify text meets WCAG AA (`>= 4.5:1` for body/labels, `>= 3:1` for large headings) in both **Clean Slate Light** (`#F8FAFC` / `#FFFFFF`) and **Clean Dark** (`#0B0F17` / `#151922`).
- Never place dark text on dark surfaces or light text on light surfaces; always use `--text-main` (`text-cyber-main`), `--text-muted` (`text-cyber-muted`), or paired `text-slate-700 dark:text-slate-300` utilities.
- Avoid ultra-low-contrast micro-text (`text-[8px]` / `text-[9px]`); keep operational copy `>= 11px` (`text-[11px]` or `text-xs`).

## 2. Keyboard & Screen-Reader Ergonomics
- Every icon-only button or link (eye detail links, theme toggle, sidebar collapse, modal close `X`) must include an informative `title` and `aria-label`.
- Preserve visible focus rings (`focus:outline-none focus:ring-2 focus:ring-brand-600/40`) on inputs, selects, buttons, and links.
- Use semantic elements: `<button type="button">` for JS actions, `<a href="...">` for navigation, `<th scope="col">` or clean `<thead>` headers for tables.

## 3. Shop-Floor Tablet & Mobile Responsiveness
- CAPACIPRINT is used by press operators on tablets and customers on phones:
  - Wrap wide tables in `overflow-x-auto` so horizontal scrolling never breaks the viewport (`overflow-x-hidden` on shell).
  - Stack page header titles and action button groups cleanly (`flex-col sm:flex-row sm:items-center justify-between gap-4`).
  - Keep interactive tap targets comfortably sized (`h-9` / `py-2 px-3.5` minimum) and ensure the mobile slide-over sidebar closes on backdrop click and route navigation.
- Respect `prefers-reduced-motion`: keep transitions mechanical and minimal (`150ms–200ms`).
