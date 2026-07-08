@extends('layouts.app')

@section('title', 'Invoices')

@section('content')
    @if ($invoices->isEmpty())
        <div class="text-center py-16 text-gray-500">
            <p class="mb-4">No invoices yet.</p>
            <a href="{{ route('invoices.create') }}"
               class="inline-block bg-emerald-500 text-white px-4 py-2 rounded-lg font-medium">
                Create invoice
            </a>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($invoices as $invoice)
                <a href="{{ route('invoices.show', $invoice) }}"
                   class="block bg-white rounded-xl shadow-sm border border-gray-100 p-4 active:bg-gray-50">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $invoice->invoice_number }}</p>
                            <p class="text-sm text-gray-500">{{ $invoice->client_name }}</p>
                            @if ($invoice->driver_name)
                                <p class="text-xs text-gray-400">Driver: {{ $invoice->driver_name }}</p>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-slate-900">${{ number_format($invoice->total, 2) }}</p>
                            <p class="text-xs text-gray-400">{{ $invoice->invoice_date->format('M j, Y') }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $invoices->links() }}
        </div>
    @endif
@endsection
