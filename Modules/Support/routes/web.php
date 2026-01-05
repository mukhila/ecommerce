<?php

use Illuminate\Support\Facades\Route;
use Modules\Support\Http\Controllers\TicketController;

Route::middleware(['auth:admin'])->prefix('admin/support')->name('admin.support.')->group(function () {
    Route::get('tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');
    Route::post('tickets/{id}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
    Route::put('tickets/{id}/status', [TicketController::class, 'update'])->name('tickets.update');
    Route::delete('tickets/{id}', [TicketController::class, 'destroy'])->name('tickets.destroy');
});
