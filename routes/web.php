<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Reports;
use App\Http\Controllers\DispatchController;
use App\Http\Middleware\CheckingUserStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

Route::group(['middleware' => ['language', 'userstatus']], function () {
    Route::get('/', function () {
        return redirect('/admin');
    });

    Route::get('/admin', [App\Http\Controllers\PartyController::class, 'admin_method']);
    Route::get('/getInvoice', [App\Http\Controllers\PartyController::class, 'getInvoice']);
    Route::get('/getInvoice_purchase', [App\Http\Controllers\PartyController::class, 'getInvoice_purchase']);
    Route::get('/getItemCode', [App\Http\Controllers\PartyController::class, 'getItemCode']);


    Route::get('createUser', [App\Http\Controllers\UserController::class, 'createUser_method']);
    Route::get('ManageUser', [App\Http\Controllers\UserController::class, 'ManageUser_method']);
    Route::get('SubscriptionMessage', [App\Http\Controllers\UserController::class, 'SubscriptionMessage_method']);
    Route::post('updateMessage', [App\Http\Controllers\UserController::class, 'updateMessage_method']);

    Route::post('insertCreatedUser', [App\Http\Controllers\UserController::class, 'insertCreatedUser_method']);
    Route::get('createdUserEdit', [App\Http\Controllers\UserController::class, 'createdUserEdit_method']);
    Route::get('createdUserDelete/{id}', [App\Http\Controllers\UserController::class, 'createdUserDelete_method']);
    Route::get('usermanagement/{id}/{status}', [App\Http\Controllers\UserController::class, 'usermanagement_method']);
    Route::post('updateCreatedUser', [App\Http\Controllers\UserController::class, 'updateCreatedUser_method']);
    Route::post('getUserTypeAndRoleFromDB', [App\Http\Controllers\UserController::class, 'getUserTypeAndRoleFromDB_method']);


    Route::get('itemsCategory', [App\Http\Controllers\PartyController::class, 'itemsCategory_method']);
    Route::post('insertItemsCategory', [App\Http\Controllers\PartyController::class, 'insertItemsCategory_method']);
    Route::get('itemsCategoryEdit', [App\Http\Controllers\PartyController::class, 'itemsCategoryEdit_method']);
    Route::post('updateItemsCategory', [App\Http\Controllers\PartyController::class, 'updateItemsCategory_method']);
    Route::get('itemsCategoryDelete/{id}', [App\Http\Controllers\PartyController::class, 'itemsCategoryDelete_method']);
    Route::get('CustomerTypeDelete/{id}', [App\Http\Controllers\PartyController::class, 'CustomerTypeDelete']);

    Route::get('PaymentTypeDelete/{id}', [App\Http\Controllers\PartyController::class, 'PaymentTypeDelete']);


    Route::post('updateCustomerType', [App\Http\Controllers\PartyController::class, 'updateCustomerType']);
    Route::post('updatePaymentType', [App\Http\Controllers\PartyController::class, 'updatePaymentType']);


    Route::get('CustomerTypeEdit', [App\Http\Controllers\PartyController::class, 'CustomerTypeEdit']);
    Route::get('PaymentTypeEdit', [App\Http\Controllers\PartyController::class, 'PaymentTypeEdit']);


    Route::get('items', [App\Http\Controllers\PartyController::class, 'items_method']);
    Route::post('insertItems', [App\Http\Controllers\PartyController::class, 'insertItems_method']);
    Route::get('itemsEdit', [App\Http\Controllers\PartyController::class, 'itemsEdit_method']);
    Route::post('updateItems', [App\Http\Controllers\PartyController::class, 'updateItems_method']);
    Route::get('itemsDelete/{id}', [App\Http\Controllers\PartyController::class, 'itemsDelete_method']);


    Route::post('CustomerType', [App\Http\Controllers\PartyController::class, 'CustomerType']);
    Route::post('PaymentType', [App\Http\Controllers\PartyController::class, 'PaymentType']);

    Route::get('getPaymentType', [App\Http\Controllers\PartyController::class, 'getPaymentType']);

    Route::get('getChartOfAccounts', [App\Http\Controllers\PartyController::class, 'getChartOfAccounts']);
    Route::post('ChartOfAccounts', [App\Http\Controllers\PartyController::class, 'ChartOfAccounts']);
    Route::get('ChartOfAccountDelete/{id}', [App\Http\Controllers\PartyController::class, 'ChartOfAccountDelete']);
    Route::get('ChartOfAccountEdit', [App\Http\Controllers\PartyController::class, 'ChartOfAccountEdit']);
    Route::post('updateChartOfAccount', [App\Http\Controllers\PartyController::class, 'updateChartOfAccount']);


    Route::get('checkDuplication', [
        App\Http\Controllers\UserController::class,
        'checkDuplication_method'
    ]);

    // SaleReturn Code Starts From Here

    Route::get('/SaleReturn', function () {
        if (Auth::guest()) {
            return redirect('login');
        } else {
            $Customers = DB::table('customers')->where('user', Auth::user()->id)->get();
            return view('admin/modules/SalesBook/SaleReturn/SaleReturn', ['Customers' => $Customers]);
        }
    });
    Route::get('edit_SaleReturn', function () {
        if (Auth::guest()) {
            return redirect('login');
        } else {
            return view('admin/modules/SalesBook/SaleReturn/SaleReturnEdit');
        }
    });


    // Salebook code starts from here
    Route::get('/salebook', function () {
        if (Auth::guest()) {
            return redirect('login');
        } else {
            $Customers = DB::table('customers')->where('user', Auth::user()->id)->get(['id','CustomerName']);
            return view('admin/modules/SalesBook/Salebook/salesbook', ['Customers' => $Customers]);
        }
    });
    Route::post('getDateOfSelectedSupplier', [App\Http\Controllers\DispatchController::class, 'getDateOfSelectedSupplier_method']);
    Route::get('checkDate_isPrevious', [App\Http\Controllers\DispatchController::class, 'checkDate_isPrevious']);
    Route::post('dispatch', [App\Http\Controllers\DispatchController::class, 'dispatch_method']);
    Route::get('edit_salesbook', function () {
        if (Auth::guest()) {
            return redirect('login');
        } else {
            return view('admin/modules/SalesBook/Salebook/salesbookedit');
        }
    });
    Route::get('getInvoicesForEdit', [App\Http\Controllers\DispatchController::class, 'getInvoicesForEdit_method']);
    Route::get('edit_invoice', [App\Http\Controllers\DispatchController::class, 'edit_invoice_method']);
    Route::post('update_dispatch', [App\Http\Controllers\DispatchController::class, 'update_dispatch']);
    Route::get('delete_dispatch', [App\Http\Controllers\DispatchController::class, 'delete_dispatch']);

    Route::get(
        'getItemsOfSelectedCategory',
        [App\Http\Controllers\DispatchController::class, 'getItemsOfSelectedCategory']
    );

    Route::get(
        'getCategoriesOfLoggenInUser',
        [App\Http\Controllers\DispatchController::class, 'getCategoriesOfLoggenInUser']
    );
    Route::get('getPriceFromRateTable_sale', [
        App\Http\Controllers\DispatchController::class,
        'getPriceFromRateTable_sale_method'
    ]);

    Route::get('getPrice_with_ItemCode', [App\Http\Controllers\DispatchController::class, 'getPrice_with_ItemCode']);

    Route::get('/getAllItems', [DispatchController::class, 'getAllItems']);

    // Customer code starts from here
    Route::get('Customer', [App\Http\Controllers\PartyController::class, 'Customer_method']);
    Route::post('insertCustomer', [App\Http\Controllers\PartyController::class, 'insertCustomer_method']);
    Route::get('CustomerEdit', [App\Http\Controllers\PartyController::class, 'CustomerEdit_method']);
    Route::post('updateCustomer', [App\Http\Controllers\PartyController::class, 'updateCustomer_method']);
    Route::get('CustomerDelete/{id}', [App\Http\Controllers\PartyController::class, 'CustomerDelete_method']);
    Route::get('getCustomerData', function (Request $request) {
        return DB::table('customers')->where('user', Auth::user()->id)->where('id', $request->CustomerName)->first();
    });


    // Purchaser code starts from here
    Route::get('Purchaser', [App\Http\Controllers\PurchaserController::class, 'Purchaser_method']);
    Route::post('insertPurchaser', [App\Http\Controllers\PurchaserController::class, 'insertPurchaser_method']);
    Route::get('PurchaserEdit', [App\Http\Controllers\PurchaserController::class, 'PurchaserEdit_method']);
    Route::post('updatePurchaser', [App\Http\Controllers\PurchaserController::class, 'updatePurchaser_method']);
    Route::get('PurchaserDelete/{id}', [App\Http\Controllers\PurchaserController::class, 'PurchaserDelete_method']);
    Route::get('getPurchaserData', function (Request $request) {
        return DB::table('purchasers')->where('user', Auth::user()->id)->where('id', $request->id)->first();
    });

    // Quotation code starts from here
    Route::get('/quotation', function () {
        if (Auth::guest()) {
            return redirect('login');
        } else {
            $Customers = DB::table('customers')->where('user', Auth::user()->id)->get();
            return view('admin/modules/SalesBook/Quotation/quotation', ['Customers' => $Customers]);
        }
    });
    Route::post('dispatch_quotation', [App\Http\Controllers\DispatchController::class, 'dispatch_quotation']);
    Route::get('previous_quotation', [App\Http\Controllers\DispatchController::class, 'previous_quotation']);
    Route::get('edit_quotation', function () {
        if (Auth::guest()) {
            return redirect('login');
        } else {
            return view('admin/modules/SalesBook/Quotation/quotationedit');
        }
    });
    Route::post('update_quotation', [App\Http\Controllers\DispatchController::class, 'update_quotation']);
    Route::get('delete_dispatch', [App\Http\Controllers\DispatchController::class, 'delete_dispatch']);
    Route::get('delete_quotation', [App\Http\Controllers\DispatchController::class, 'delete_quotation']);

    // Purchase book code starts from here
    Route::get('/purchasebook', function () {
        if (Auth::guest()) {
            return redirect('login');
        } else {
            $purchasers = DB::table('purchasers')->where('user',Auth::user()->id)->get();
            return view('admin/modules/SalesBook/Purchasebook/purchasebook',['purchasers' => $purchasers]);
        }
    });

    Route::post('dispatch_purchasebook', [App\Http\Controllers\DispatchController::class, 'dispatch_purchasebook']);
    Route::get('edit_purchasebook', function () {
        if (Auth::guest()) {
            return redirect('login');
        } else {
            return view('admin/modules/SalesBook/Purchasebook/purchasebookedit');
        }
    });
    Route::get('getPurchaseInvoicesForEdit', [App\Http\Controllers\DispatchController::class, 'getPurchaseInvoicesForEdit']);
    Route::get('edit_purchasebookInvoice/{id}', [App\Http\Controllers\DispatchController::class, 'edit_purchasebookInvoice']);
    Route::post('update_dispatch_purchasebook', [App\Http\Controllers\DispatchController::class, 'update_dispatch_purchasebook']);
    Route::get('delete_dispatch_purchasebook', [App\Http\Controllers\DispatchController::class, 'delete_dispatch_purchasebook']);

    // Expense book code starts from here
    Route::post('ExpenseReport', [App\Http\Controllers\Reports::class, 'ExpenseReport']);
    Route::get('/getInvoice_expense', [App\Http\Controllers\DispatchController::class, 'getInvoice_expense']);
    Route::get('/expensebook', function () {
        if (Auth::guest()) {
            return redirect('login');
        } else {
            $chart_of_accounts = DB::table('chart_of_accounts')->where('type','E')->where('user', Auth::user()->id)->get();
            $expense_from_account = DB::table('chart_of_accounts')->where('type','A')->where('user', Auth::user()->id)->get();
            return view('admin/modules/SalesBook/Expense/expensebook', 
            [
                'chart_of_accounts' => $chart_of_accounts,
                'expense_from_account' => $expense_from_account,
            ]);
        }
    });

    Route::post('dispatch_expensebook', [App\Http\Controllers\DispatchController::class, 'dispatch_expensebook']);
    Route::get('edit_expensebook', function () {
        if (Auth::guest()) {
            return redirect('login');
        } else {
            return view('admin/modules/SalesBook/Expense/expensebookedit');
        }
    });
    Route::get('getExpenseInvoicesForEdit', [App\Http\Controllers\DispatchController::class, 'getExpenseInvoicesForEdit']);
    Route::get('edit_expensebookInvoice/{id}', [App\Http\Controllers\DispatchController::class, 'edit_expensebookInvoice']);
    Route::post('update_dispatch_expensebook', [App\Http\Controllers\DispatchController::class, 'update_dispatch_expensebook']);
    Route::get('delete_dispatch_expensebook', [App\Http\Controllers\DispatchController::class, 'delete_dispatch_expensebook']);


    // Reports Controller code starts from here
    Route::get('Reports', [App\Http\Controllers\Reports::class, 'Reports']);
    Route::get('printInvoice', [App\Http\Controllers\Reports::class, 'printInvoice']);
    Route::get('printQuotation', [App\Http\Controllers\Reports::class, 'printQuotation']);

    Route::post('VAT_Report', [App\Http\Controllers\Reports::class, 'VAT_Report']);
    Route::post('ExpenseReport', [App\Http\Controllers\Reports::class, 'ExpenseReport']);
    Route::post('BalanceSheet', [App\Http\Controllers\Reports::class, 'BalanceSheet']);
    Route::post('VAT_SaleReport', [Reports::class, 'VAT_SaleReport']);
    Route::post('VAT_PurchaseReport', [Reports::class, 'VAT_PurchaseReport']);
    Route::post('TotalReport', [Reports::class, 'TotalReport']);
    Route::post('CustomerReport', [Reports::class, 'CustomerReport']);



    Route::get('suspended', function () {
        return view('admin/modules/User/suspended');
    })->withoutMiddleware([CheckingUserStatus::class]);

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});
Auth::routes();
