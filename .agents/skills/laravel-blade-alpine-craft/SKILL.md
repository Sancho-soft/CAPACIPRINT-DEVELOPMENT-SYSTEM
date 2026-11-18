---
name: laravel-blade-alpine-craft
description: >-
  Use this skill when building or refactoring Blade views, anonymous Blade
  components, or Alpine.js frontend interactions in CAPACIPRINT to keep UI
  markup clean, modular, and compatible with AJAX pagination and live search.
---

# Blade Components & Alpine.js Frontend Craft (CAPACIPRINT)

## 1. Reusable Blade UI Components
- Compose dashboards and inner pages out of shared primitives under `resources/views/components/dashboard/` (`kpi-card`, `workflow-pipeline`, `branch-workload-card`, `production-table`, `attention-center`, `empty-state`) instead of duplicating card or table markup.
- Keep `@props([...])` clean and explicit with sensible defaults so prototype screens can be assembled rapidly without copy-pasting long Tailwind blocks.
- Never embed inline `onmouseenter`, `onmouseleave`, or hardcoded `style="background: rgba(...)"` inside Blade loops; use Tailwind utilities and theme CSS tokens.

## 2. Global AJAX Pagination & Live Search Compatibility
`resources/views/layouts/app.blade.php` automatically handles in-place pagination and live `GET` filtering. Keep frontend markup compatible:
- Give paginated table containers a stable `id` (e.g., `id="press-line-queue"`, `id="requests-queue"`) and include `.rounded-xl`, `.bg-cyber-card`, or `.overflow-hidden`.
- Keep pagination links inside Laravel's `<nav aria-label="Pagination Navigation">` wrapper.
- Use `method="GET"` with an input named `search` (or `id` containing `search`) for live search toolbars.

## 3. Alpine.js Interactive UI State
- Always add `x-cloak` to conditionally rendered modals, slide-overs, tabs, and dropdowns so nothing flashes on page load.
- Keep `x-data` scopes self-contained (e.g., `{ activeTab: 'all' }`, `{ showModal: false }`, `{ view: 'table' }`).
- Use crisp mechanical transitions (`duration-150` / `duration-200`) rather than slow floaty animations.
