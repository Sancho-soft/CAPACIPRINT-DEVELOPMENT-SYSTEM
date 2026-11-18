---
name: interactive-prototype-flows
description: >-
  Use this skill when designing or enhancing interactive frontend prototype
  flows in CAPACIPRINT such as multi-step print request wizards, modals,
  slide-over drawers, filter tabs, live previews, and feedback states.
---

# Interactive Prototype Flows (CAPACIPRINT Frontend)

## 1. Make Every Click Feel Responsive (Aura Interaction Design)
- **4 Roles of Motion**: Motion must serve **Feedback** (confirm click/submit), **Orientation** (drawer/dropdown origin), **Focus** (highlight active step), or **Continuity** (smooth tab transitions)—never idle decoration.
- **Timing Scale**: `100–150ms` for hover/press micro-feedback; `180–250ms` `ease-out` for drawers, modals, and dropdowns. Never exceed `200ms` for primary UI feedback.
- **Instant Visual Feedback**: Disable submit buttons while processing (`:disabled="isSubmitting"`), swap button text to a crisp spinner (`fa-circle-notch fa-spin`), and show clear inline confirmation or error banners (`aria-live="polite"`).
- **Filter Tabs & View Switchers**: Use Alpine.js (`x-data`, `x-show`, `@click`) or URL-synced `GET` query links (`request()->query()`) with clear active-tab indicators (`bg-brand-600 text-white` vs. `bg-cyber-sub text-cyber-main`).
- **Multi-Step Wizards** (e.g., Print Request intake, Quotation builder):
  - Display numbered step progress (`01 Service`, `02 Specs`, `03 Artwork`, `04 Schedule`, `05 Review`) using crisp hairline connectors.
  - Keep primary `Next` / `Submit` actions anchored consistently on the bottom-right and `Back` on the bottom-left.

## 2. Modals, Drawers & Confirmations
- Add `overscroll-behavior: contain` on scrollable modal/drawer bodies so scrolling inside doesn't scroll the page behind.
- Place backdrop overlays (`bg-black/60`) with `@click` and `@keydown.escape.window` dismiss behavior and `x-cloak`.
- Use crisp `rounded-2xl border border-cyber bg-cyber-card shadow-lg` dialog frames—avoid heavy `backdrop-blur-2xl` or `rounded-3xl` bubble popups.
- Keep destructive confirmations (`Cancel Request`, `Decline Quote`, `Delete`) explicitly labeled in crimson (`bg-rose-600 text-white`) and gated behind a confirmation dialog.

## 3. Empty, Loading & Zero-Data States
- Never leave blank white/dark voids when a queue or filter returns zero items; render `<x-dashboard.empty-state />` with a helpful next-step action button.
