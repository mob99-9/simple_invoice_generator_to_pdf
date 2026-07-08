#  Minimalist Laravel Invoice Generator

A lightweight, straight-to-the-point Invoice Generator built with **Laravel 11**. This application bypasses databases, authentication, and dashboards entirely—providing a single-page utility form where you enter invoice details and instantly download a professional, styled PDF.

---

##  Features

- **No Overhead:** No database required (no migrations, no user setups, no storage dependencies).
- **Interactive Form:** Add or remove line items dynamically.
- **Instant PDF Generation:** Renders and downloads professional, print-ready PDFs on the fly using `barryvdh/laravel-dompdf`.
- **Automated Calculations:** Computes line totals, sub-totals, customizable tax percentages, and final balances directly during processing.
