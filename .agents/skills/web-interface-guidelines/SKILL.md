---
name: web-interface-guidelines
description: >-
  Curated from Aura Skills (vercel-labs/web-interface-guidelines + ibelick/ui-skills
  Baseline UI). Use when auditing or building forms, navigation, focus states,
  typography wrapping, dark/light mode native controls, and layout stability
  in CAPACIPRINT.
---

# Web Interface Guidelines & Baseline UI (Aura Curated)

Adapted from Aura Skills (`vercel-labs/web-interface-guidelines` and `ibelick/ui-skills` Baseline UI) for CAPACIPRINT's Laravel + Blade + Alpine.js + Tailwind CSS v4 prototype.

## 1. Strict "MUST / NEVER" Guardrails (Baseline UI)
- **Viewport Height**: NEVER use `h-screen` on sticky sidebars or full-height drawers; use `h-dvh` so mobile address bars never clip bottom items.
- **Compositor-Only Motion**: Animate only `transform` and `opacity` (`150ms–200ms` `ease-out`). NEVER animate `width`, `height`, `top`, `left`, `margin`, or `padding`.
- **Single Primary Accent**: Limit primary interactive accent to one per view (`#2563EB` light / `#3B82F6` dark); reserve semantic colors (emerald, amber, crimson) strictly for live status.
- **Typography Wrapping**: Use `text-balance` (`text-wrap: balance`) on headings (`h1`–`h3`), `text-pretty` on prose paragraphs, and `tabular-nums` on all numeric metrics, quantities, and prices.

## 2. Native Controls, Color Scheme & Focus
- Set `color-scheme: dark` on `html.dark` and `color-scheme: light` on `html.light-theme` so native `<select>`, `<input type="date">`, and scrollbars render properly on Windows.
- Sync `<meta name="theme-color">` with the active canvas color (`#F8FAFC` light / `#0B0F17` dark).
- Never strip focus outlines without a visible `:focus-visible` replacement (`focus-visible:ring-2 focus-visible:ring-brand-600/40`).
- Apply `touch-action: manipulation` on interactive controls and `overscroll-behavior: contain` inside modals and slide-over drawers.

## 3. Forms, Overflow & Empty States
- Every icon-only button requires `aria-label` and `title`; decorative icons require `aria-hidden="true"`.
- Flex children containing truncating text (`truncate`, `line-clamp-*`) must have `min-w-0` on the flex container so long customer/material names never break grid widths.
- Empty states must present **one clear next action** rather than dead-end text.
- Placeholders end with a proper ellipsis (`…`), and units use non-breaking spaces (`10&nbsp;MB`, `24&nbsp;in`).
