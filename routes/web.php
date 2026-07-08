<?php

use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/invoices');

Route::resource('invoices', InvoiceController::class);
Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])
    ->name('invoices.pdf');
