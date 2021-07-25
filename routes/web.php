<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return view('auth.login');
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('dashboard', function () {
   return view('layouts.master');
});

Route::group(['middleware' => 'auth'], function () {
    Route::resource('categories','CategoryController');
    Route::get('/apiCategories','CategoryController@apiCategories')->name('api.categories');
    Route::get('/exportCategoriesAll','CategoryController@exportCategoriesAll')->name('exportPDF.categoriesAll');
    Route::get('/exportCategoriesAllExcel','CategoryController@exportExcel')->name('exportExcel.categoriesAll');

    Route::resource('customers','CustomerController');
    Route::get('/apiCustomers','CustomerController@apiCustomers')->name('api.customers');
    Route::post('/importCustomers','CustomerController@ImportExcel')->name('import.customers');
    Route::get('/exportCustomersAll','CustomerController@exportCustomersAll')->name('exportPDF.customersAll');
    Route::get('/exportCustomersAllExcel','CustomerController@exportExcel')->name('exportExcel.customersAll');

    Route::resource('sales1','SaleController1');
    Route::get('/apiSales1','SaleController1@apiSales')->name('api.sales1');
    Route::post('/importSales1','SaleController1@ImportExcel')->name('import.sales1');
     Route::get('/exportSalesAll1','SaleController1@exportSalesAll')->name('exportPDF.salesAll1');
    Route::get('/exportSalesAllExcel1','SaleController1@exportExcel')->name('exportExcel.salesAll1');
    Route::get('/generateInvoice/{id}','SaleController1@generateInvoice')->name('generateInvoice');
    Route::get('/salerefund/{id}','SaleController1@refund')->name('refund');




    Route::resource('sales','SaleController');
    Route::get('/apiSales','SaleController@apiProductsOut')->name('api.productsOut1');
    Route::get('/exportSalesAll','SaleController@exportProductOutAll')->name('exportPDF.productOutAll1');
    Route::get('/exportSalesAllExcel','SaleController@exportExcel')->name('exportExcel.productOutAll1');
    Route::get('/exportSales','SaleController@exportProductOut')->name('exportPDF.productOut1');
    Route::get('/checkSalesAvailable/{id}','SaleController@checkAvailable')->name('checkSalesAvailable1');
    // Route::post('/barcodescan/{barcode_name}','SaleController@barcodescan')->name('barcode.scan');
    Route::get('/order_complete','SaleController@order_complete')->name('order_complete');



    Route::resource('suppliers','SupplierController');
    Route::get('/apiSuppliers','SupplierController@apiSuppliers')->name('api.suppliers');
    Route::post('/importSuppliers','SupplierController@ImportExcel')->name('import.suppliers');
    Route::get('/exportSupplierssAll','SupplierController@exportSuppliersAll')->name('exportPDF.suppliersAll');
    Route::get('/exportSuppliersAllExcel','SupplierController@exportExcel')->name('exportExcel.suppliersAll');

    Route::resource('products','ProductController');
    Route::get('/apiProducts','ProductController@apiProducts')->name('api.products');
    Route::get('/checkAvailableName/{id}','ProductController@checkAvailableName')->name('checkAvailableName');


    Route::resource('productsOut','ProductOutController');
    Route::get('/apiProductsOut','ProductOutController@apiProductsOut')->name('api.productsOut');
    Route::get('/exportProductOutAll','ProductOutController@exportProductOutAll')->name('exportPDF.productOutAll');
    Route::get('/exportProductOutAllExcel','ProductOutController@exportExcel')->name('exportExcel.productOutAll');
    Route::get('/exportProductOut','ProductOutController@exportProductOut')->name('exportPDF.productOut');
    Route::get('/checkAvailable/{id}','ProductOutController@checkAvailable')->name('checkAvailable');
    Route::get('/refund/{id}','ProductOutController@refund')->name('refund');


    Route::resource('productsIn','ProductInController');
    Route::get('/apiProductsIn','ProductInController@apiProductsIn')->name('api.productsIn');
    Route::get('/exportProductInAll','ProductInController@exportProductInAll')->name('exportPDF.productInAll');
    Route::get('/exportProductInAllExcel','ProductInController@exportExcel')->name('exportExcel.productInAll');

    Route::resource('users','UserController');
    Route::get('/apiUsers','UserController@apiUsers')->name('api.users');

    Route::resource('company','CompanyController');

    Route::resource('orders','OrderController');
    Route::get('/apiOrders','OrderController@apiOrders')->name('api.orders');
    Route::get('/exportOrderAll','OrderController@exportProductOutAll')->name('exportPDF.orderAll');
    Route::get('/exportOrderAllExcel','OrderController@exportExcel')->name('exportExcel.orderAll');
    Route::get('/exportOrder','OrderController@exportProductOut')->name('exportPDF.order');
    Route::get('/refundOrder/{id}','OrderController@refund')->name('refund');

    // Route::get('/checkAvailable/{id}','OrderController@checkAvailable')->name('checkAvailable');

});

