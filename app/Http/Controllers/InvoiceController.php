<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(): View
    {
        $invoices = Invoice::latest('invoice_date')->paginate(15);

        return view('invoices.index', compact('invoices'));
    }

    public function create(): View
    {
        return view('invoices.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateInvoice($request);

        $invoice = Invoice::create([
            'invoice_number' => $data['invoice_number'],
            'invoice_date' => $data['invoice_date'],
            'company_name' => $data['company_name'],
            'company_address' => $data['company_address'] ?? null,
            'client_name' => $data['client_name'],
            'client_address' => $data['client_address'] ?? null,
            'driver_name' => $data['driver_name'] ?? null,
            'payment_terms' => $data['payment_terms'] ?? null,
            'due_date' => $data['due_date'] ?? null,
            'tax_bank_charges' => $data['tax_bank_charges'] ?? 0,
        ]);

        foreach ($data['items'] as $index => $item) {
            $qty = (float) $item['qty'];
            $rate = (float) $item['rate'];
            $lineTotal = $item['total'] !== null && $item['total'] !== ''
                ? (float) $item['total']
                : $qty * $rate;

            $invoice->items()->create([
                'qty' => $qty,
                'description' => $item['description'],
                'rate' => $rate,
                'total' => $lineTotal,
                'sort_order' => $index,
            ]);
        }

        $invoice->recalculateTotals();

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('status', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load('items');

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice): View
    {
        $invoice->load('items');

        return view('invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $data = $this->validateInvoice($request, $invoice->id);

        $invoice->update([
            'invoice_number' => $data['invoice_number'],
            'invoice_date' => $data['invoice_date'],
            'company_name' => $data['company_name'],
            'company_address' => $data['company_address'] ?? null,
            'client_name' => $data['client_name'],
            'client_address' => $data['client_address'] ?? null,
            'driver_name' => $data['driver_name'] ?? null,
            'payment_terms' => $data['payment_terms'] ?? null,
            'due_date' => $data['due_date'] ?? null,
            'tax_bank_charges' => $data['tax_bank_charges'] ?? 0,
        ]);

        $invoice->items()->delete();

        foreach ($data['items'] as $index => $item) {
            $qty = (float) $item['qty'];
            $rate = (float) $item['rate'];
            $lineTotal = $item['total'] !== null && $item['total'] !== ''
                ? (float) $item['total']
                : $qty * $rate;

            $invoice->items()->create([
                'qty' => $qty,
                'description' => $item['description'],
                'rate' => $rate,
                'total' => $lineTotal,
                'sort_order' => $index,
            ]);
        }

        $invoice->recalculateTotals();

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('status', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->delete();

        return redirect()
            ->route('invoices.index')
            ->with('status', 'Invoice deleted.');
    }

    /**
     * Stream the invoice as a downloadable PDF.
     */
    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load('items');

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }

    private function validateInvoice(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'invoice_number' => [
                'required', 'string', 'max:50',
                'unique:invoices,invoice_number' . ($ignoreId ? ",{$ignoreId}" : ''),
            ],
            'invoice_date' => ['required', 'date'],
            'company_name' => ['required', 'string', 'max:255'],
            'company_address' => ['nullable', 'string', 'max:1000'],
            'client_name' => ['required', 'string', 'max:255'],
            'client_address' => ['nullable', 'string', 'max:1000'],
            'driver_name' => ['nullable', 'string', 'max:255'],
            'payment_terms' => ['nullable', 'string', 'max:255'],
            'due_date' => ['nullable', 'date'],
            'tax_bank_charges' => ['nullable', 'numeric'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.qty' => ['required', 'numeric', 'min:0'],
            'items.*.description' => ['required', 'string', 'max:500'],
            'items.*.rate' => ['required', 'numeric', 'min:0'],
            'items.*.total' => ['nullable', 'numeric', 'min:0'],
        ]);
    }
}
