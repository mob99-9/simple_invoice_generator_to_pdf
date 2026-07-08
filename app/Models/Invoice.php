<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'company_name',
        'company_address',
        'client_name',
        'client_address',
        'driver_name',
        'payment_terms',
        'due_date',
        'subtotal',
        'tax_bank_charges',
        'total',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_bank_charges' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class)->orderBy('sort_order');
    }

    /**
     * Recalculate subtotal + total from line items and stored tax/bank charges.
     */
    public function recalculateTotals(): void
    {
        $subtotal = $this->items()->sum('total');
        $this->subtotal = $subtotal;
        $this->total = $subtotal + (float) $this->tax_bank_charges;
        $this->save();
    }
}
