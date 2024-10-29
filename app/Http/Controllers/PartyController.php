<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PartyController extends Controller
{
    public function admin_method(Request $request)
    {
        if (Auth::guest()) {
            return redirect('login');
        } else {
            $SubscriptionMessage = DB::table('users')->where('name', Auth::user()->id)->pluck('SubscriptionMessage')->first();
            $UserStatus = DB::table('users')->where('name', Auth::user()->id)->pluck('UserStatus')->first();
            return view('admin/modules/dashboard/index2', ['SubscriptionMessage' => $SubscriptionMessage, 'UserStatus' => $UserStatus]);
        }
    }

    // Customer code starts from here

    public function Customer_method()
    {
        if (Auth::guest()) {
            return redirect('login');
        } else {
            $Customers = DB::table('customers')->where('user', Auth::user()->id)->get();

            return view('admin/modules/Customer/Customer', [

                'Customers' => $Customers,
            ]);
        }
    }

    public function insertCustomer_method(Request $request)
    {

        $checkDuplicateRecord = DB::table('customers')->where(['CustomerName' => $request->CustomerName, 'user' => Auth::user()->id])->first();
        if ($checkDuplicateRecord) {
            return redirect('Customer')->with('status', 'Duplicate value trying to insert');
        } else {

            $Customer = DB::insert(
                "insert into customers(CustomerName,Contact,Address,user)values(?,?,?,?)",
                [$request->CustomerName, $request->Contact, $request->Address, Auth::user()->id]
            );
            if ($Customer) {
                if ($request->requestFrom == 'sale_book') {
                    return redirect('sale_book');
                } else {

                    return redirect('Customer');
                }
            }
        }
    }

    public function CustomerEdit_method(Request $request)
    {
        return DB::table('customers')->where('id', $request->id)->first();
    }

    public function  updateCustomer_method(Request $request)
    {

        $itemsTable_ItemName = DB::table('customers')->where('id', $request->id)->first();
        $updatedRole = DB::table('customers')->where('id', $request->id)->update([
            'CustomerName' => $request->CustomerName,
            'Contact' => $request->Contact,
            'Address' => $request->Address,

        ]);

        $updatedRole = DB::table('sale_book')
            ->where(['user' => Auth::user()->id])->update([
                'CustomerName' => $request->CustomerName,
            ]);


        return redirect('Customer');
    }

    public function CustomerDelete_method($id)
    {

        $deleted =  DB::table('customers')
            ->where('id', $id)
            ->delete();
        if ($deleted) {
            return redirect('Customer');
        }
    }

    //  items category code starts from here
    public function itemsCategory_method()
    {
        if (Auth::guest()) {
            return redirect('login');
        } else {
            $categories = DB::table('category')->where('user', Auth::user()->id)->get();
            return view('admin/modules/Party/itemsCategory', ['categories' => $categories]);
        }
    }

    public function insertItemsCategory_method(Request $request)
    {

        $checkDuplicateRecord = DB::table('category')->where('user', Auth::user()->id)->where('Category', $request->CategoryName)->first();
        if ($checkDuplicateRecord) {

            return redirect('itemsCategory')->with('status', 'Duplicate value trying to insert');
        } else {
            
            $insertRole = DB::insert(
                "insert into category(Category,user)values(?,?)",
                [$request->CategoryName,Auth::user()->id]
            );
            if ($insertRole) {
                return redirect('itemsCategory');
            }
        }
    }

    public function getPaymentType()
    {
        if (Auth::guest()) {
            return redirect('login');
        } else {
            $categories = DB::table('paymenttype')->where('user', Auth::user()->id)->get();
            return view('admin/modules/Party/PaymentType', ['categories' => $categories]);
        }
    }

    public function getChartOfAccounts()
    {
        if (Auth::guest()) {
            return redirect('login');
        } else {
            $chartofaccounts = DB::table('chart_of_accounts')->where('user', Auth::user()->id)->get();
            return view('admin/modules/Party/ChartOfAccounts', ['chartofaccounts' => $chartofaccounts]);
        }
    }

    public function ChartOfAccounts(Request $request)
    {

        $checkDuplicateRecord = DB::table('chart_of_accounts')->where('id', $request->id)->where('user', Auth::user()->id)->first();
        if ($checkDuplicateRecord) {

            return redirect('getChartOfAccounts')->with('status', 'Duplicate value trying to insert');
        } else {

            $insertRole = DB::insert(
                "insert into chart_of_accounts(name,type,user,created_at,updated_at)values(?,?,?,?,?)",
                [$request->name,$request->type, Auth::user()->id,Carbon::now(),Carbon::now()]
            );
            if ($insertRole) {
                return redirect('getChartOfAccounts');
            }
        }
    }

    public function ChartOfAccountDelete($id)
    {

        $deleted =  DB::table('chart_of_accounts')
            ->where('id', $id)
            ->delete();
        if ($deleted) {
            return redirect('getChartOfAccounts');
        }
    }
    public function ChartOfAccountEdit(Request $request)
    {
        $chart_of_account_data = DB::table('chart_of_accounts')->where('id', $request->id)->first();
        return ['chart_of_accounts_data' => $chart_of_account_data];
    }

    public function  updateChartOfAccount(Request $request)
    {

        $updatedRole = DB::table('chart_of_accounts')->where('id', $request->id)->where('user', Auth::user()->id)->update([
            'name' => $request->name,
            'type' => $request->type,
            'updated_at' => Carbon::now()
        ]);

        return redirect('getChartOfAccounts');
    }

    public function CustomerType(Request $request)
    {

        $checkDuplicateRecord = DB::table('customertype')->where('CustomerType', $request->CustomerType)->first();
        if ($checkDuplicateRecord) {

            return redirect('CustomerType')->with('status', 'Duplicate value trying to insert');
        } else {

            $insertRole = DB::insert(
                "insert into customertype(CustomerType,user)values(?,?)",
                [$request->CustomerType, Auth::user()->id]
            );
            if ($insertRole) {
                return redirect('getCustomerType');
            }
        }
    }

    public function PaymentType(Request $request)
    {

        $checkDuplicateRecord = DB::table('paymenttype')->where('PaymentType', $request->PaymentType)->where('user', Auth::user()->id)->first();
        if ($checkDuplicateRecord) {

            return redirect('getPaymentType')->with('status', 'Duplicate value trying to insert');
        } else {

            $insertRole = DB::insert(
                "insert into paymenttype(PaymentType,user)values(?,?)",
                [$request->PaymentType, Auth::user()->id]
            );
            if ($insertRole) {
                return redirect('getPaymentType');
            }
        }
    }

    public function itemsCategoryEdit_method(Request $request)
    {
        $roleData = DB::table('category')->where('id', $request->CategoryName)->first();
        return $roleData;
    }
    public function CustomerTypeEdit(Request $request)
    {
        $roleData = DB::table('customertype')->where('id', $request->CategoryName)->first();
        return $roleData;
    }

    public function PaymentTypeEdit(Request $request)
    {
        $roleData = DB::table('paymenttype')->where('id', $request->CategoryName)->first();
        return $roleData;
    }

    public function  updateItemsCategory_method(Request $request)
    {
    
        DB::table('category')->where('id', $request->id)->update([
            'Category' => $request->CategoryName,

        ]);

        return redirect('itemsCategory');
    }

    public function  updateCustomerType(Request $request)
    {

        $updatedRole = DB::table('customertype')->where('id', $request->id)->where('user', Auth::user()->id)->update([
            'CustomerType' => $request->CustomerType,

        ]);
        DB::table('sale_book')->where('CustomerType', $request->modal_CustomerType_id)->where('user', Auth::user()->id)->update([
            'CustomerType' => $request->CustomerType
        ]);

        return redirect('getCustomerType');
    }

    public function  updatePaymentType(Request $request)
    {

        $updatedRole = DB::table('paymenttype')->where('id', $request->id)->where('user', Auth::user()->id)->update([
            'PaymentType' => $request->PaymentType,

        ]);
       

        return redirect('getPaymentType');
    }



    public function itemsCategoryDelete_method($id)
    {

        $deleted =  DB::table('category')
            ->where('id', $id)
            ->delete();
        if ($deleted) {
            return redirect('itemsCategory');
        }
    }
    public function CustomertypeDelete($id)
    {

        $deleted =  DB::table('customertype')
            ->where('id', $id)
            ->delete();
        if ($deleted) {
            return redirect('getCustomerType');
        }
    }

    public function PaymentTypeDelete($id)
    {

        $deleted =  DB::table('paymenttype')
            ->where('id', $id)
            ->delete();
        if ($deleted) {
            return redirect('getPaymentType');
        }
    }



    // items code starts from here

    public function getCategoriesOfLoggenInUser()
    {
        return DB::table('category')->where('user', Auth::user()->id)->get('Category');
    }
    public function items_method()
    {
        if (Auth::guest()) {
            return redirect('login');
        } else {
            $items = DB::table('items')->where('user', Auth::user()->id)->get();

            return view('admin/modules/Party/items', [

                'items' => $items,
            ]);
        }
    }

    public function insertItems_method(Request $request)
    {

        $checkDuplicateRecord = DB::table('items')->where(['ItemName' => $request->ItemName, 'user' => Auth::user()->id])->first();
        if ($checkDuplicateRecord) {
            return redirect('items')->with('status', 'Duplicate value trying to insert');
        } else {

            $insertRole = DB::insert(
                "insert into items(ItemName,Rate,PurchaseRate,user,Category,ItemCode,BarCode)values(?,?,?,?,?,?,?)",
                [
                    $request->ItemName, $request->Rate, $request->purchase_rate,  Auth::user()->id, $request->Category,
                    $request->ItemCode, $request->BarCode
                ]
            );
            if ($insertRole) {
                return redirect('items');
            }
        }
    }


    public function itemsEdit_method(Request $request)
    {
        $item_data = DB::table('items')->where('id', $request->id)->first();
        return ['item_data' => $item_data, 'categories' => DB::table('category')->where('user', Auth::user()->id)->get()];
    }

    public function  updateItems_method(Request $request)
    {

        $itemsTable_ItemName = DB::table('items')->where('id', $request->id)->first();
        $updatedRole = DB::table('items')->where('id', $request->id)->update([
            'ItemName' => $request->ItemName,
            'Rate' => $request->Rate,
            'PurchaseRate' => $request->PurchaseRate,
            'Category' => $request->Category,
            'ItemCode' => $request->ItemCode,
            'BarCode' => $request->BarCode

        ]);

        $updatedRole = DB::table('sale_book_detail')
            ->where(['ItemName' => $request->hidden_ItemName])->update([
                'ItemName' => $request->ItemName,
                'ItemCode' => $request->ItemCode
            ]);


        return redirect('items');
    }

    public function itemsDelete_method($id)
    {

        $deleted =  DB::table('items')
            ->where('id', $id)
            ->delete();
        if ($deleted) {
            return redirect('items');
        }
    }

    // this is funciton is for makiing invoice for sale invoice
    public function getInvoice(Request $request)
    {
        $ArticleNo = DB::table('users')->where('id', Auth::user()->id)->first('ArticleNo')->ArticleNo;
        // return Auth::user()->id;
        $InvoiceList = DB::table('sale_book')->where('user', Auth::user()->id)->pluck('Invoice')->toArray();
        if ($request->Ref == "QT") {
            $InvoiceList = DB::table('quotation')->where('user', Auth::user()->id)->pluck('Invoice')->toArray();
        }
        $temp = array();
        for ($i = 0; $i < count($InvoiceList); $i++) {
            for ($j = $i + 1; $j < count($InvoiceList); $j++) {
                // return $InvoiceList[$j];
                $FirstInvoice = explode('-', $InvoiceList[$j]);
                $SecondInvoice = explode('-', $InvoiceList[$i]);
                if ($FirstInvoice < $SecondInvoice) {

                    $temp = $InvoiceList[$j];
                    $InvoiceList[$j] = $InvoiceList[$i];
                    $InvoiceList[$i] = $temp;
                }
            }
        }
        // return $InvoiceList;
        if (count($InvoiceList) == 0) {
          
            $Invoice =  $ArticleNo . "-" . "00" . "1";
            
        } else {
            $reverse_InvoiceList = array_reverse($InvoiceList);
            $FirstElementOf_reverceInvoiceList = $reverse_InvoiceList[0];
            $Invoice = explode('-', $FirstElementOf_reverceInvoiceList);
            $Invoice[1]  = $Invoice[1] + 1;
            if (strlen(strval($Invoice[1])) == 1) {
                $Invoice =  $ArticleNo . "-" . "00" . strval($Invoice[1]);
            } else  if (strlen(strval($Invoice[1])) == 2) {
                $Invoice =  $ArticleNo . "-" . "0" . strval($Invoice[1]);
            } else if (strlen(strval($Invoice[1])) > 2) {
                $Invoice =  $ArticleNo . "-" . strval($Invoice[1]);
            }
        }
        return $Invoice;
    }
    public function getItemCode(Request $request)
    {

        $Invoice = $request->category . "-" . "00" . 1;
        while (true) {
            $InvoiceExist = DB::table('items')->where('ItemCode', $Invoice)->where('user', Auth::user()->id)->first();
            if ($InvoiceExist) {
                $Invoice = explode('-', $Invoice);
                $Invoice[1]  = $Invoice[1] + 1;
                if (strlen(strval($Invoice[1])) == 1) {
                    $Invoice =  $request->category . "-" . "00" . strval($Invoice[1]);
                } else  if (strlen(strval($Invoice[1])) == 2) {
                    $Invoice =  $request->category . "-" . "0" . strval($Invoice[1]);
                } else if (strlen(strval($Invoice[1])) > 2) {
                    $Invoice =  $request->category . "-" . strval($Invoice[1]);
                }
            } else {
                break;
            }
        }
        return $Invoice;
    }

    public function getInvoice_purchase()
    {
        $ArticleNo = DB::table('users')->where('id', Auth::user()->id)->first('ArticleNo')->ArticleNo;
        $InvoiceList = DB::table('purchase_book')->where('user', Auth::user()->id)->pluck('Invoice')->toArray();
        $temp = array();
        for ($i = 0; $i < count($InvoiceList); $i++) {
            for ($j = $i + 1; $j < count($InvoiceList); $j++) {
                // return $InvoiceList[$j];
                $FirstInvoice = explode('-', $InvoiceList[$j]);
                $SecondInvoice = explode('-', $InvoiceList[$i]);
                if ($FirstInvoice < $SecondInvoice) {

                    $temp = $InvoiceList[$j];
                    $InvoiceList[$j] = $InvoiceList[$i];
                    $InvoiceList[$i] = $temp;
                }
            }
        }
        if (count($InvoiceList) == 0) {
            $Invoice =  $ArticleNo . "-" . "00" . "1";
        } else {
            $reverse_InvoiceList = array_reverse($InvoiceList);
            $FirstElementOf_reverceInvoiceList = $reverse_InvoiceList[0];
            $Invoice = explode('-', $FirstElementOf_reverceInvoiceList);
            $Invoice[1]  = $Invoice[1] + 1;
            if (strlen(strval($Invoice[1])) == 1) {
                $Invoice =  $ArticleNo . "-" . "00" . strval($Invoice[1]);
            } else  if (strlen(strval($Invoice[1])) == 2) {
                $Invoice =  $ArticleNo . "-" . "0" . strval($Invoice[1]);
            } else if (strlen(strval($Invoice[1])) > 2) {
                $Invoice =  $ArticleNo . "-" . strval($Invoice[1]);
            }
        }
        return $Invoice;
    }
}
