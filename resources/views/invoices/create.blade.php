@extends('layouts.app')

@section('title', 'New Invoice')

@section('content')
    <h1 class="text-xl font-bold text-slate-900 mb-4">New Invoice</h1>

    <form action="{{ route('invoices.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Invoice meta --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Invoice #</label>
                    <input type="text" name="invoice_number" value="{{ old('invoice_number') }}" required
                           class="w-full rounded-lg border-gray-300 p-3">
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" required
                           class="w-full rounded-lg border-gray-300 p-3">
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Driver name</label>
                    <input type="text" name="driver_name" value="{{ old('driver_name') }}"
                           class="w-full rounded-lg border-gray-300 p-3">
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment terms</label>
                    <input type="text" name="payment_terms" value="{{ old('payment_terms') }}"
                           class="w-full rounded-lg border-gray-300 p-3">
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Due date</label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}"
                           class="w-full rounded-lg border-gray-300 p-3">
                </div>
            </div>
        </div>

        {{-- Issuer (your company) --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 space-y-4">
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Your company (from)</p>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Company name</label>
                <input type="text" name="company_name" value="{{ old('company_name') }}" required
                       class="w-full rounded-lg border-gray-300 p-3">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <textarea name="company_address" rows="2"
                          class="w-full rounded-lg border-gray-300 p-3">{{ old('company_address') }}</textarea>
            </div>
        </div>

        {{-- Client (bill to) --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 space-y-4">
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Bill to</p>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Client / company name</label>
                <input type="text" name="client_name" value="{{ old('client_name') }}" required
                       class="w-full rounded-lg border-gray-300 p-3">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <textarea name="client_address" rows="2"
                          class="w-full rounded-lg border-gray-300 p-3">{{ old('client_address') }}</textarea>
            </div>
        </div>

        {{-- Line items --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Items</p>
                <button type="button" onclick="addRow()"
                        class="text-emerald-600 text-sm font-medium">+ Add row</button>
            </div>

            <div id="items-wrapper" class="space-y-3"></div>

            <template id="item-row-template">
                <div class="item-row border border-gray-200 rounded-lg p-3 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-semibold text-gray-400 row-index">Item</span>
                        <button type="button" onclick="removeRow(this)" class="text-red-500 text-xs font-medium">Remove</button>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="col-span-1">
                            <label class="block text-xs text-gray-500 mb-1">Qty</label>
                            <input type="number" step="0.01" min="0" name="items[__i__][qty]" value="1" required
                                   class="qty w-full rounded-lg border-gray-300 p-2 text-sm" oninput="recalcRow(this)">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-500 mb-1">Description</label>
                            <input type="text" name="items[__i__][description]" required
                                   class="w-full rounded-lg border-gray-300 p-2 text-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Day / Rate or cost</label>
                            <input type="number" step="0.01" min="0" name="items[__i__][rate]" value="0" required
                                   class="rate w-full rounded-lg border-gray-300 p-2 text-sm" oninput="recalcRow(this)">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Total</label>
                            <input type="number" step="0.01" min="0" name="items[__i__][total]" value="0"
                                   class="total w-full rounded-lg border-gray-300 p-2 text-sm">
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- Totals --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 space-y-3">
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Totals</p>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Subtotal</span>
                <span id="subtotal-display" class="font-medium">0.00</span>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tax &amp; bank charges</label>
                <input type="number" step="0.01" min="0" name="tax_bank_charges" id="tax-input"
                       value="{{ old('tax_bank_charges', 0) }}"
                       class="w-full rounded-lg border-gray-300 p-3" oninput="recalcTotals()">
            </div>
            <div class="flex justify-between text-base font-bold border-t pt-3">
                <span>Total</span>
                <span id="total-display">0.00</span>
            </div>
        </div>

        <button type="submit"
                class="w-full bg-emerald-500 active:bg-emerald-600 text-white font-semibold py-3 rounded-xl shadow">
            Save invoice
        </button>
    </form>

    <script>
        let rowCount = 0;

        function addRow() {
            const template = document.getElementById('item-row-template');
            const clone = template.content.cloneNode(true);
            const html = clone.querySelector('.item-row').outerHTML.replaceAll('__i__', rowCount);
            document.getElementById('items-wrapper').insertAdjacentHTML('beforeend', html);
            rowCount++;
            updateRowLabels();
        }

        function removeRow(btn) {
            btn.closest('.item-row').remove();
            updateRowLabels();
            recalcTotals();
        }

        function updateRowLabels() {
            document.querySelectorAll('#items-wrapper .item-row').forEach((row, idx) => {
                row.querySelector('.row-index').textContent = 'Item ' + (idx + 1);
            });
        }

        function recalcRow(el) {
            const row = el.closest('.item-row');
            const qty = parseFloat(row.querySelector('.qty').value) || 0;
            const rate = parseFloat(row.querySelector('.rate').value) || 0;
            row.querySelector('.total').value = (qty * rate).toFixed(2);
            recalcTotals();
        }

        function recalcTotals() {
            let subtotal = 0;
            document.querySelectorAll('#items-wrapper .total').forEach(input => {
                subtotal += parseFloat(input.value) || 0;
            });
            const tax = parseFloat(document.getElementById('tax-input').value) || 0;
            document.getElementById('subtotal-display').textContent = subtotal.toFixed(2);
            document.getElementById('total-display').textContent = (subtotal + tax).toFixed(2);
        }

        // start with one row
        addRow();
    </script>
@endsection
