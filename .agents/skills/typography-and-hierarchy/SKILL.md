---
name: typography-and-hierarchy
description: >-
  Use this skill when styling headings, KPI metrics, badges, navigation labels,
  or dense informational screens in CAPACIPRINT to ensure crisp Clean Modern
  typographic hierarchy and readability.
---

# Typography & Visual Hierarchy (CAPACIPRINT)

## 1. Unified Single-Font System (`Plus Jakarta Sans`)
- **Primary & Display Typography (`font-display` & `font-sans`)**: **`Plus Jakarta Sans`** (`400, 500, 600, 700, 800`) everywhere across the system (`tracking-tight` on headings; `13px–14px` for UI controls/tables; `15px–18px` for landing/marketing prose).
- **Tabular Numerals (`tabular-nums`)**: Apply `tabular-nums` to Order IDs (`ORD-001`), Request Codes (`#PR-00001`), quantities, dimensions, percentages, dates, and PHP (`₱`) prices while keeping `Plus Jakarta Sans` as the typeface.

## 2. The Squint Test
- Every screen section must have **one clear focal hierarchy**:
  1. **Eyebrow context** (`text-xs font-sans font-semibold text-cyber-muted`)
  2. **Primary page heading** (`text-xl sm:text-2xl font-bold font-display text-cyber-main`)
  3. **Primary action CTA** (single `bg-brand-600 text-white` button; secondary actions stay neutral `bg-cyber-card border border-cyber`)
- Avoid overusing `font-black` (`900` weight) on every label—reserve bold (`600`–`700`) for headings and key figures so hierarchy breathes naturally.

## 3. Legibility Floor
- Keep operational body text and table metadata at `text-xs` (`12px`) or `text-[11px]`. Never use `text-[8px]`, `text-[9px]`, or `text-[10px]` micro-labels.
- Always apply `tabular-nums` to numeric counters, tables, and progress percentages so digits never jitter.
