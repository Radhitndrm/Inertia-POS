<?php

use Illuminate\Support\Facades\Route;

//route home
Route::get('/', function () {
    return \Inertia\Inertia::render('Auth/Login');
})->middleware('guest');

//prefix "apps"
Route::prefix('apps')->group(function () {

    //middleware "auth"
    Route::group(['middleware' => ['auth']], function () {

        //route dashboard
        Route::get('dashboard', App\Http\Controllers\Apps\DashboardController::class)->name('apps.dashboard');

        // Route::get('/pendingTransaction', [\App\Http\Controllers\Apps\PendingTransactionController::class, 'index'])->name('apps.Pending-Transactions.index');

        // //route update pending transaction store
        // Route::get('/pendingTransaction/{transaction}/edit', [\App\Http\Controllers\Apps\PendingTransactionController::class, 'edit'])->name('apps.Pending-Transactions.edit');
        Route::resource('/pendingTransactions', \App\Http\Controllers\Apps\PendingTransactionController::class, ['as' => 'apps']);

        // ->middleware('permission:roles.index|roles.create|roles.edit|roles.delete');


        //route permissions
        Route::get('/permissions', \App\Http\Controllers\Apps\PermissionController::class)->name('apps.permissions.index')
            ->middleware('permission:permissions.index');

        //route resource roles
        Route::resource('/roles', \App\Http\Controllers\Apps\RoleController::class, ['as' => 'apps'])
            ->middleware('permission:roles.index|roles.create|roles.edit|roles.delete');

        //route resource users
        Route::resource('/users', \App\Http\Controllers\Apps\UserController::class, ['as' => 'apps'])
            ->middleware('permission:users.index|users.create|users.edit|users.delete');

        //route resource categories
        Route::resource('/categories', \App\Http\Controllers\Apps\CategoryController::class, ['as' => 'apps'])
            ->middleware('permission:categories.index|categories.create|categories.edit|categories.delete');

        //route resource products
        Route::resource('/products', \App\Http\Controllers\Apps\ProductController::class, ['as' => 'apps'])
            ->middleware('permission:products.index|products.create|products.edit|products.delete');

        //route resource customers
        Route::resource('/customers', \App\Http\Controllers\Apps\CustomerController::class, ['as' => 'apps'])
            ->middleware('permission:customers.index|customers.create|customers.edit|customers.delete');

        //route transaction
        Route::get('/transactions', [\App\Http\Controllers\Apps\TransactionController::class, 'index'])->name('apps.transactions.index');

        //route transaction searchProduct
        Route::post('/transactions/searchProduct', [\App\Http\Controllers\Apps\TransactionController::class, 'searchProduct'])->name('apps.transactions.searchProduct');

        //route transaction addToCart
        Route::post('/transactions/addToCart', [\App\Http\Controllers\Apps\TransactionController::class, 'addToCart'])->name('apps.transactions.addToCart');

        //route transaction destroyCart
        Route::post('/transactions/destroyCart', [\App\Http\Controllers\Apps\TransactionController::class, 'destroyCart'])->name('apps.transactions.destroyCart');

        //route transaction store
        Route::post('/transactions/store', [\App\Http\Controllers\Apps\TransactionController::class, 'store'])->name('apps.transactions.store');

        //route transaction print
        Route::get('/transactions/print', [\App\Http\Controllers\Apps\TransactionController::class, 'print'])->name('apps.transactions.print');

        //route transaction
        Route::get('/pos-transactions', [\App\Http\Controllers\Apps\PosController::class, 'index'])->name('apps.pos.index');

        //route transaction searchProduct
        Route::post('/pos-transactions/searchProduct', [\App\Http\Controllers\Apps\PosController::class, 'searchProduct'])->name('apps.pos.searchProduct');

        //route transaction addToCart
        Route::post('/pos-transactions/addToCart', [\App\Http\Controllers\Apps\PosController::class, 'addToCart'])->name('apps.pos.addToCart');

        //route transaction destroyCart
        Route::post('/pos-transactions/destroyCart', [\App\Http\Controllers\Apps\PosController::class, 'destroyCart'])->name('apps.pos.destroyCart');

        //route transaction store
        Route::post('/pos-transactions/store', [\App\Http\Controllers\Apps\PosController::class, 'store'])->name('apps.pos.store');

        //route transaction print
        Route::get('/pos-transactions/print', [\App\Http\Controllers\Apps\PosController::class, 'print'])->name('apps.pos.print');

        //route transactionApp
        Route::get('/pos-app-transactions', [\App\Http\Controllers\Apps\PosAppController::class, 'index'])->name('apps.pos-app.index');

        //route transaction searchProduct
        Route::post('/pos-app-transactions/searchProduct', [\App\Http\Controllers\Apps\PosAppController::class, 'searchProduct'])->name('apps.pos-app.searchProduct');

        //route transaction addToCart
        Route::post('/pos-app-transactions/addToCart', [\App\Http\Controllers\Apps\PosAppController::class, 'addToCart'])->name('apps.pos-app.addToCart');

        //route transaction edit qty
        Route::post('/pos-app-transactions/editCartQuantity', [\App\Http\Controllers\Apps\PosAppController::class, 'editCartQuantity'])->name('apps.pos-app.editCartQuantity');

        //route transaction destroyCart
        Route::post('/pos-app-transactions/destroyCart', [\App\Http\Controllers\Apps\PosAppController::class, 'destroyCart'])->name('apps.pos-app.destroyCart');

        //route transaction store
        Route::post('/pos-app-transactions/store', [\App\Http\Controllers\Apps\PosAppController::class, 'store'])->name('apps.pos-app.store');


        //route pending transaction store
        Route::post('/pos-app-transactions/pendingStore', [\App\Http\Controllers\Apps\PosAppController::class, 'pendingStore'])->name('apps.pos-app.pendingStore');




        //route transaction print
        Route::get('/pos-app-transactions/print', [\App\Http\Controllers\Apps\PosAppController::class, 'print'])->name('apps.pos-app.print');


        //route sales index
        Route::get('/sales', [\App\Http\Controllers\Apps\SalesController::class, 'index'])->middleware('permission:sales.index')->name('apps.sales.index');

        //route sales index
        Route::get('/sales', [\App\Http\Controllers\Apps\SalesController::class, 'index'])->middleware('permission:sales.index')->name('apps.sales.index');

        //route sales filter
        Route::get('/sales/filter', [\App\Http\Controllers\Apps\SalesController::class, 'filter'])->name('apps.sales.filter');

        //route export excel
        Route::get('sales/export', [\App\Http\Controllers\Apps\SalesController::class, 'export'])->name('apps.sales.export');

        //route sales print pdf
        Route::get('/sales/pdf', [\App\Http\Controllers\Apps\SalesController::class, 'pdf'])->name('apps.sales.pdf');

        //route profits index
        Route::get('/profits', [\App\Http\Controllers\Apps\ProfitController::class, 'index'])->middleware('permission:profits.index')->name('apps.profits.index');

        //route profits filter
        Route::get('/profits/filter', [\App\Http\Controllers\Apps\ProfitController::class, 'filter'])->name('apps.profits.filter');

        //route profits export
        Route::get('/profits/export', [\App\Http\Controllers\Apps\ProfitController::class, 'export'])->name('apps.profits.export');

        //route profits pdf
        Route::get('/profits/pdf', [\App\Http\Controllers\Apps\ProfitController::class, 'pdf'])->name('apps.profits.pdf');
    });
});
