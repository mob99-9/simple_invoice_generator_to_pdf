@extends('layouts.app')

@section('title', 'Invoice ' . $invoice->invoice_number)

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-bold text-slate-900">{{ $invoice->invoice_number }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('invoices.edit', $invoice) }}"
               class="text-sm font-medium text-slate-600 px-3 py-2 rounded-lg border border-gray-300">Edit</a>
            <a href="{{ route('invoices.pdf', $invoice) }}"
               class="text-sm font-medium text-white bg-emerald-500 px-3 py-2 rounded-lg">Download PDF</a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 space-y-4">
        <div class="grid grid-cols-2 gap-3 text-sm">
            <div>
                <p class="text-gray-400">Date</p>
                <p class="font-medium">{{ $invoice->invoice_date->format('M j, Y') }}</p>
            </div>
            <div>
                <p class="text-gray-400">Driver</p>
                <p class="font-medium">{{ $invoice->driver_name ?: '—' }}</p>
            </div>
            <div>
                <p class="text-gray-400">From</p>
                <p class="font-medium">{{ $invoice->company_name }}</p>
                <p class="text-gray-500 whitespace-pre-line">{{ $invoice->company_address }}</p>
            </div>
            <div>
                <p class="text-gray-400">To</p>
                <p class="font-medium">{{ $invoice->client_name }}</p>
                <p class="text-gray-500 whitespace-pre-line">{{ $invoice->client_address }}</p>
            </div>
        </div>

        <div class="border-t pt-4">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400">
                        <th class="pb-2">Qty</th>
                        <th class="pb-2">Description</th>
                        <th class="pb-2 text-right">Rate</th>
                        <th class="pb-2 text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice->items as $item)
                        <tr class="border-t">
                            <td class="py-2">{{ rtrim(rtrim(number_format($item->qty, 2), '0'), '.') }}</td>
                            <td class="py-2">{{ $item->description }}</td>
                            <td class="py-2 text-right">${{ number_format($item->rate, 2) }}</td>
                            <td class="py-2 text-right">${{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="border-t pt-4 space-y-1 text-sm ml-auto max-w-[220px]">
            <div class="flex justify-between">
                <span class="text-gray-500">Subtotal</span>
                <span>${{ number_format($invoice->subtotal, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Tax &amp; bank charges</span>
                <span>${{ number_format($invoice->tax_bank_charges, 2) }}</span>
            </div>
            <div class="flex justify-between font-bold text-base border-t pt-2">
                <span>Total</span>
                <span>${{ number_format($invoice->total, 2) }}</span>
            </div>
        </div>
    </div>

    <form action="{{ route('invoices.destroy', $invoice) }}" method="POST"
          onsubmit="return confirm('Delete this invoice?');" class="mt-4">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-sm text-red-500 font-medium">Delete invoice</button>
    </form>
@endsection
