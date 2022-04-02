<?php

use App\Http\Controllers\AgingScheduleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustumorController;
use App\Http\Controllers\CoaController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\GeneralLadgerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportDetailSales;
use App\Http\Controllers\BillPaymentController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\GoodsController;
use App\Http\Controllers\PurchaseReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\PaymentVendorController;
// Auth
Route::get('/', [AuthController::class, 'index'])->name('login')->middleware(['guest', 'revalidate']);
Route::post('login', [AuthController::class, 'authenticate']);
Route::any('logout', [AuthController::class, 'logout']);

Route::get('dashboard',[DashboardController::class,'index']);
Route::get('dashboard/notif',[DashboardController::class,'notif']);

// Materdata
Route::resource('COA', CoaController::class)->middleware(['auth', 'revalidate']);
Route::resource('product', ProductController::class)->middleware(['auth', 'revalidate']);
Route::resource('custumor', CustumorController::class)->middleware(['auth', 'revalidate']);
Route::resource('supplier', SupplierController::class)->middleware(['auth', 'revalidate']);
// transaksi
Route::resource('quotation', QuotationController::class)->middleware(['auth', 'revalidate']);
Route::post('quotation_insert', [QuotationController::class, "insert"]);
Route::any('show_data', [QuotationController::class, "show_data"]);
Route::get('deleteq/{id}', [QuotationController::class, "delete"]);
Route::get('quotation/print/{id}', [QuotationController::class, "print"]);

Route::resource('sales', SalesController::class)->middleware(['auth', 'revalidate']);
Route::post('sales_insert', [SalesController::class, "insert"]);
Route::get('sales/detail/{no_tagihan}', [SalesController::class, "detail"])->name('sales.detail');

Route::resource('purchase', PurchaseController::class)->middleware(['auth', 'revalidate']);
Route::post('purchase_insert', [PurchaseController::class, "insert"]);
Route::get('purchase/detail/{no_tagihan}', [PurchaseController::class, "detail"]);
Route::get('purchase/print/{no_tagihan}', [PurchaseController::class, "print"]);

Route::resource('goods', GoodsController::class)->middleware(['auth', 'revalidate']);
Route::post('goods_insert', [GoodsController::class, "insert"]);
Route::get('goods/detail/{no_tagihan}', [GoodsController::class, "detail"]);


Route::resource('delivery', DeliveryController::class)->middleware(['auth', 'revalidate']);
Route::get('delivery/detail/{no_tagihan}', [DeliveryController::class, "detail"]);
Route::get('delivery/print/{no_delivery}',[DeliveryController::class,'print']);

Route::resource('bill', BillPaymentController::class)->middleware(['auth', 'revalidate']);
Route::get('show/{kode}/{id}', [BillPaymentController::class, "show"]);
Route::get('bill/detail/{no_tagihan}', [BillPaymentController::class, "detail"]);
Route::get('bill/print/{no_transaksi}',[BillPaymentController::class,'print']);

Route::resource('payment', PaymentController::class)->middleware(['auth', 'revalidate']);
Route::get('payment/show/{kode}/{tgl}', [PaymentController::class, "show"]);
Route::get('payment/detail/{no_pembayaran}',[PaymentController::class,"detail"]);

Route::resource('paymentvendor', PaymentVendorController::class)->middleware(['auth', 'revalidate']);
Route::get('paymentvendor/show/{kode}/{tgl}', [PaymentVendorController::class, "show"]);
Route::get('paymentvendor/detail/{no_pembayaran}',[PaymentVendorController::class,"detail"]);
Route::get('paymentvendor/report/report',[PaymentVendorController::class,"report"]);

// Laporan

Route::resource('journal', JurnalController::class)->middleware(['auth', 'revalidate']);
Route::resource('aging', AgingScheduleController::class)->middleware(['auth', 'revalidate']);
Route::get('export',[AgingScheduleController::class,'export']);
Route::resource('ledger', GeneralLadgerController::class)->middleware(['auth', 'revalidate']);
Route::resource('SDR', ReportDetailSales::class)->middleware(['auth', 'revalidate']);
Route::resource('PCR', PurchaseReportController::class)->middleware(['auth', 'revalidate']);

// email
Route::get('email/{id}',[EmailController::class,'email']);

// status transaksi
Route::get('status_transaksi',[ReportDetailSales::class, 'status_transaki']);
