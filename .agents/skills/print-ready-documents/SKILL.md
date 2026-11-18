---
name: print-ready-documents
description: >-
  Use this skill when designing or editing printable quotations, invoices, job
  tickets, QR claim slips, or printable reports in CAPACIPRINT.
---

# Print-Ready Documents & Reports (CAPACIPRINT)

## 1. Strict `@media print` Isolation
- Mark all screen-only toolbars, back buttons, sidebars, and topbars with `.no-print` or Tailwind `print:hidden`.
- Inside `@media print`:
  - Force white paper background (`background: #ffffff !important`) and carbon ink (`color: #000000 !important`).
  - Strip screen borders, rounded card frames, and shadows from `.print-card` (`border: none !important; box-shadow: none !important; padding: 0 !important;`).
  - Set `@page { margin: 12mm 15mm; size: auto; }` and prevent awkward row splitting (`tr { break-inside: avoid; }`).

## 2. Typographic & Tabular Precision
- Render all line-item quantities, dimensions, unit rates, subtotals, VAT, and PHP (`₱`) totals in `font-sans tabular-nums` with right-aligned currency columns.
- Use crisp `1px solid #111315` cell rules on official forms (e.g., Morning Star Printing Press Co. quotation sheets in `resources/views/staff/quotations/download.blade.php` and `customer/quotations/download.blade.php`).
- Include clear document identifiers (`#QT-...`, `#ORD-...`, issue date, branch name, and customer details) in the top header block.

## 3. Screen Preview Action Bar
- Keep the screen action bar clean and uncluttered: company/document identifier on the left, `Back` + primary `Print / Save as PDF` (`bg-brand-600 text-white`) on the right.
