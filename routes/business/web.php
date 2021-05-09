<?php

use LocalheroPortal\Business\Feature\PaymentOption\PaymentOptionWebController;
use LocalheroPortal\Business\Feature\Product\ProductWebController;
use LocalheroPortal\Business\Feature\Sale\SaleReviewWebController;
use LocalheroPortal\Business\Feature\Sale\SaleWebController;

Route::get('/sales', [SaleWebController::class, 'makeSales'])->name('business.sales.appointment');
Route::resources([
    'products' => ProductWebController::class,
    'payment_options' => PaymentOptionWebController::class
]);
Route::get('new-sales', SaleReviewWebController::class)->name('sales.reviews.index');