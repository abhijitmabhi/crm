<?php

use LocalheroPortal\Business\Feature\PaymentOption\PaymentOptionApiController;
use LocalheroPortal\Business\Feature\Product\ProductApiController;
use LocalheroPortal\Business\Feature\Sale\SaleApiController;

Route::apiResources([
    'products' => ProductApiController::class,
    'payment_options' => PaymentOptionApiController::class
]);
Route::post('/sales', [SaleApiController::class, 'store'])->name('sale.store');
Route::patch('sales/{sale}', [SaleApiController::class, 'update']);
