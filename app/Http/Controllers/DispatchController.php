<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class DispatchController extends Controller
{

    public function getCategoriesOfLoggenInUser()
    {
        return DB::table('category')->where('user', Auth::user()->id)->distinct()->get(['id','Category']);
    }
    public function getItemsOfSelectedCategory(Request $request)
    {
        return DB::table('items')->where('user', Auth::user()->id)->where('id', $request->Category)->distinct()->get(['id','ItemName']);
    }
    public function getPriceFromRateTable_sale_method(Request $request)
    {
        return DB::table('items')->where('id', $request->ItemName)->where('user', Auth::user()->id)->first();
    }
    public  function getPrice_with_ItemCode(Request $request)
    {
        return DB::table('items')->where('ItemCode', $request->ItemCode)->where('user', Auth::user()->id)->first();
    }

    public function getInvoicesForEdit_method(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        if ($request->Ref == 'SB') {
           
            return view('admin/modules/SalesBook/Salebook/showSaleInvoices', ['saleInvoices' => 
            DB::table(sale_book)->join('customers','salebook.CustomerName','customers.id')
            ->where('salebook.Ref', 'SB')
            ->where('salebook.user', Auth::user()->id)->whereBetween('salebook.Date', [$startDate, $endDate])
            ->select('salebook.*','customers.CustomerName')
            ->get()]);
        } elseif ($request->Ref == 'SR') {
            return view('admin/modules/SalesBook/SaleReturn/showSaleReturnInvoices', ['saleInvoices' => DB::table(sale_book)
                ->where('Ref', 'SR')
                ->where('user', Auth::user()->id)->whereBetween('Date', [$startDate, $endDate])->get()]);
        } elseif ($request->Ref == 'QT') {
            return view('admin/modules/SalesBook/Quotation/showQuotationInvoices', ['saleInvoices' => DB::table('quotation')->where('user', Auth::user()->id)->whereBetween('Date', [$startDate, $endDate])->get()]);
        }
    }
    public function edit_invoice_method(Request $request)
    {
        $salebook_detail = [];
        $salebook = DB::table(sale_book)->where('salebook.Invoice', $request->id)->first();
        $salebook_detail = DB::table(sale_book_detail)->join('items','items.id','salebook_detail.ItemName')
        ->where('salebook_detail.invoice', $request->id)
        ->select('items.ItemName as items_ItemName','items.id as item_id','salebook_detail.*')
        ->get();
        $Customers = DB::table('customers')->where('user', Auth::user()->id)->get();
        $paymenttypes = DB::table('paymenttype')->where('user',Auth::user()->id)->get();
        if ($request->Ref == "SB") {
            return view(
                'admin/modules/SalesBook/Salebook/edit_invoice',
                [sale_book => $salebook, sale_book_detail => $salebook_detail, 'Customers' => $Customers,
                'paymenttypes' => $paymenttypes
                ]
            );
        } elseif ($request->Ref == 'SR') {
            return view(
                'admin/modules/SalesBook/SaleReturn/edit_SaleReturnInvoice',
                [sale_book => $salebook, sale_book_detail => $salebook_detail, 'Customers' => $Customers,'paymenttypes' => $paymenttypes]
            );
        } elseif ($request->Ref == 'QT') {
            $salebook = DB::table('quotation')->where('Invoice', $request->id)->get();
            $salebook_detail = DB::table('quotation_detail')->where('invoice', $request->id)->get();
            return view(
                'admin/modules/SalesBook/Quotation/edit_quotation',
                [sale_book => $salebook, sale_book_detail => $salebook_detail, 'Customers' => $Customers]
            );
        }
    }
    public function delete_dispatch()
    {
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];

        $invoice = $_GET['Invoice'];

        $SaleData = DB::table(sale_book_detail)->where('Invoice', $invoice)->get();
        for ($a = 0; $a < count($SaleData); $a++) {
            $sbd_quantity = $SaleData[$a]->Qty;

            DB::table('items')->where([
                'id' => $SaleData[$a]->ItemName, 'user' => Auth::user()->id,

            ])->update([
                'Qty' => DB::raw('Qty +' . $sbd_quantity)
            ]);
        }


        $dispatch_detail =  DB::table(sale_book_detail)
            ->where('Invoice', $invoice)
            ->delete();
        $dispatch =  DB::table(sale_book)
            ->where('Invoice', $invoice)
            ->delete();
            DB::table('ledger')->where('Invoice', $invoice)->delete();
        if ($dispatch_detail && $dispatch) {
            return redirect()->back();
            // return redirect('getInvoicesForEdit?startDate=' . $startDate . '&endDate=' . $endDate);
        }
    }
    public function delete_quotation()
    {
        $invoice = $_GET['Invoice'];
        $dispatch_detail =  DB::table('quotation_detail')
            ->where('Invoice', $invoice)
            ->delete();
        $dispatch =  DB::table('quotation')
            ->where('Invoice', $invoice)
            ->delete();
        if ($dispatch_detail && $dispatch) {
            return redirect()->back();
        }
    }

    public function getAllItems(Request $request)
    {

        return [
            'ItemsByUserName' => DB::table('items')->where('user', Auth::user()->id)->get()
        ];
    }

    public function update_dispatch(Request $request)
    {
        DB::table(sale_book)->where('invoice', $request->invoice_edit)->update([
            'CustomerName' => $request->CustomerName == '' ? '' : $request->CustomerName,
            'VATNO' => $request->VATNO == '' ? '' : $request->VATNO,
            'invoiceDateTime' => $request->Date . ' ' . date("h:i:s"),
            'Date' => $request->Date,
            'Addres' => $request->Address == '' ? '' : $request->Address,
            'Email' => $request->Email == '' ? '' : $request->Email,
            'Cell' => $request->Cell == '' ? '' : $request->Cell,
            'Total' => $request->SaleTotal == '' ? '' : $request->SaleTotal,
            'VATPercentage' => $request->VATPercentage == '' ? '' : $request->VATPercentage,
            'VATPercentageValue' => $request->VATPercentageAmount == '' ? '' : $request->VATPercentageAmount,
            'Discount' => $request->Discount == '' ? '' : $request->Discount,
            'FinalTotal' => $request->FinalTotal == '' ? '' : $request->FinalTotal,
            'Warrenty' => $request->Warrenty == '' ? '' : $request->Warrenty,
            'PaymentType' => $request->PaymentType,
        ]);


        DB::table(sale_book_detail)->where('invoice', $request->invoice_edit)->delete();

        $total_cost_sale_service = 0;
        $abc = [];
        if ($request->obj != '') {
            foreach ($request->obj as $key => $value) {
                if (isset($request->obj[$key]['ItemCode'])) {
                    $ItemCode = $request->obj[$key]['ItemCode'];
                } else {
                    $ItemCode = '';
                }
                $abc = [
                    'Invoice' => $request->invoice_edit,
                    'ItemName' => $request->obj[$key]['ItemName'],
                    'ItemCode' => $ItemCode,
                    'Qty' => $request->obj[$key]['Qty'],
                    'Rate' => $request->obj[$key]['Price'], 'Total' => $request->obj[$key]['Total'],
                ];
                DB::table(sale_book_detail)->insert($abc);
                $total_cost_sale_service += DB::table('items')->where('id',$request->obj[$key]['ItemName'])->value('PurchaseRate');
            }
        }

        DB::table('ledger')->where('Invoice', $request->invoice_edit)->delete();

        try {
            $invoice = $request->invoice_edit;
            $userId = Auth::user()->id;
            $finalTotal = $request->FinalTotal;
            $totalCostSaleService = $total_cost_sale_service;
            $isSaleReturn = $request->Ref == 'SR' ? true : false; // Assuming this is a flag indicating a sales return
        
            $accountIds = DB::table('chart_of_accounts')
                ->whereIn('name', ['Account Receivable', 'Service Sales', 'Stock', 'Cost Of Sale Service', 'Cash Account'])
                ->pluck('id', 'name');
        
            if ($accountIds->count() < 5) {
                throw new \Exception('One or more chart_of_accounts IDs not found');
            }
        
            if ($isSaleReturn) {
                $entries = [
                    [
                        'Invoice' => $invoice,
                        'user' => $userId,
                        'chart_of_accounts_id' => $accountIds['Service Sales'],
                        'debit' => $finalTotal,
                        'credit' => 0,
                    ],
                    [
                        'Invoice' => $invoice,
                        'user' => $userId,
                        'chart_of_accounts_id' => $accountIds['Account Receivable'],
                        'debit' => 0,
                        'credit' => $finalTotal,
                    ],
                    [
                        'Invoice' => $invoice,
                        'user' => $userId,
                        'chart_of_accounts_id' => $accountIds['Stock'],
                        'debit' => $totalCostSaleService,
                        'credit' => 0,
                    ],
                    [
                        'Invoice' => $invoice,
                        'user' => $userId,
                        'chart_of_accounts_id' => $accountIds['Cost Of Sale Service'],
                        'debit' => 0,
                        'credit' => $totalCostSaleService,
                    ],
                ];
        
                if ($request->PaymentType == 1) { // 1 for debit means cash sale and 0 for credit means credit sale
                    $entries = array_merge($entries, [
                        [
                            'Invoice' => $invoice,
                            'user' => $userId,
                            'chart_of_accounts_id' => $accountIds['Account Receivable'],
                            'debit' => $finalTotal,
                            'credit' => 0,
                        ],
                        [
                            'Invoice' => $invoice,
                            'user' => $userId,
                            'chart_of_accounts_id' => $accountIds['Cash Account'],
                            'debit' => 0,
                            'credit' => $finalTotal,
                        ]
                    ]);
                }
        
            } else {
                // Original Sale Entries
                $entries = [
                    [
                        'Invoice' => $invoice,
                        'user' => $userId,
                        'chart_of_accounts_id' => $accountIds['Account Receivable'],
                        'debit' => $finalTotal,
                        'credit' => 0,
                    ],
                    [
                        'Invoice' => $invoice,
                        'user' => $userId,
                        'chart_of_accounts_id' => $accountIds['Service Sales'],
                        'debit' => 0,
                        'credit' => $finalTotal,
                    ],
                    [
                        'Invoice' => $invoice,
                        'user' => $userId,
                        'chart_of_accounts_id' => $accountIds['Stock'],
                        'debit' => 0,
                        'credit' => $totalCostSaleService,
                    ],
                    [
                        'Invoice' => $invoice,
                        'user' => $userId,
                        'chart_of_accounts_id' => $accountIds['Cost Of Sale Service'],
                        'debit' => $totalCostSaleService,
                        'credit' => 0,
                    ],
                ];
        
                if ($request->PaymentType == 1) { // 1 for debit means cash sale and 0 for credit means credit sale
                    $entries = array_merge($entries, [
                        [
                            'Invoice' => $invoice,
                            'user' => $userId,
                            'chart_of_accounts_id' => $accountIds['Cash Account'],
                            'debit' => $finalTotal,
                            'credit' => 0,
                        ],
                        [
                            'Invoice' => $invoice,
                            'user' => $userId,
                            'chart_of_accounts_id' => $accountIds['Account Receivable'],
                            'debit' => 0,
                            'credit' => $finalTotal,
                        ]
                    ]);
                }
            }
        
            foreach ($entries as &$entry) {
                $entry['created_at'] = Carbon::now();
                $entry['updated_at'] = Carbon::now();
            }
        
            DB::table('ledger')->insert($entries);
        
        } catch (\Exception $e) {
            Log::error('Failed to insert ledger entries: ' . $e->getMessage());
        }
        
        

        return ['status' => "inserted", 'Invoice' => $request->invoice_edit];
    }
    public function update_quotation(Request $request)
    {
        $dispatch = '';
        $dispatch_detail = '';
        $dispatch = DB::table('quotation')->where('invoice', $request->invoice_edit)->update([
            'CustomerName' => $request->CustomerName == '' ? '' : $request->CustomerName,
            'VATNO' => $request->VATNO == '' ? '' : $request->VATNO,
            'invoiceDateTime' => $request->Date . ' ' . date("h:i:s"),
            'Date' => $request->Date,
            'dueDate' => $request->dueDate,
            'Addres' => $request->Address == '' ? '' : $request->Address,
            'Email' => $request->Email == '' ? '' : $request->Email,
            'Cell' => $request->Cell == '' ? '' : $request->Cell,
            'Total' => $request->SaleTotal == '' ? '' : $request->SaleTotal,
            'VATPercentage' => $request->VATPercentage == '' ? '' : $request->VATPercentage,
            'VATPercentageValue' => $request->VATPercentageAmount == '' ? '' : $request->VATPercentageAmount,
            'Discount' => $request->Discount == '' ? '' : $request->Discount,
            'FinalTotal' => $request->FinalTotal == '' ? '' : $request->FinalTotal,
            'Cash' => $request->Cash == '' ? '' : $request->Cash,
            'Warrenty' => $request->Warrenty == '' ? '' : $request->Warrenty,
            'PaymentType' => $request->PaymentType == '' ? '' : $request->PaymentType,
        ]);


        $delete = DB::table('quotation_detail')->where('invoice', $request->invoice_edit)->delete();

        $abc = [];
        if ($request->obj != '') {
            foreach ($request->obj as $key => $value) {
                if (isset($request->obj[$key]['Description'])) {
                    $Description = $request->obj[$key]['Description'];
                } else {
                    $Description = '';
                }
                if (isset($request->obj[$key]['ItemCode'])) {
                    $ItemCode = $request->obj[$key]['ItemCode'];
                } else {
                    $ItemCode = '';
                }
                $abc = [
                    'Invoice' => $request->invoice_edit,
                    'ItemName' => $request->obj[$key]['ItemName'],
                    'ItemCode' => $ItemCode,
                    'Qty' => $request->obj[$key]['Qty'],
                    'Rate' => $request->obj[$key]['Price'], 'Total' => $request->obj[$key]['Total'],
                ];
                $salebook_detail = DB::table('quotation_detail')->insert($abc);
            }
        }

        return ['status' => "inserted", 'Invoice' => $request->invoice_edit];
    }

    public function checkDate_isPrevious(Request $request)
    {
        $article = explode('-', $request->Invoice)[0];
        $Invoice =  DB::table(sale_book)->where('user', Auth::user()->id)->where('Invoice', $request->Invoice)->first();
        if ($article != Auth::user()->ArticleNo) {
            return 'Article is not matching';
        } else if ($Invoice) {
            return "invoice duplication";
        } else {
            return "everything is ok";
        }
    }

    public function dispatch_method(Request $request)
    {
        $ArticleNo = DB::table('users')->where('id', Auth::user()->id)->first('ArticleNo')->ArticleNo;
        $InvoiceList = DB::table(sale_book)->where('user', Auth::user()->id)->pluck('Invoice')->toArray();
        $temp = array();
        
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
        

        $Discount = $request->Discount == "" ? 0 : $request->Discount;
        $CustomerName = $request->CustomerName == '' ? '' : $request->CustomerName;
        $PaymentType = $request->PaymentType == '' ? '' : $request->PaymentType;
        $Cell = $request->Cell == '' ? '' : $request->Cell;
        $Address = $request->Address == '' ? '' : $request->Address;
        $Email = $request->Email == '' ? '' : $request->Email;
        $Warrenty = $request->Warrenty == '' ? '' : $request->Warrenty;
        $VATPercentage = $request->VATPercentage == '' ? '' : $request->VATPercentage;
        $VATPercentageAmount = $request->VATPercentageAmount == '' ? '' : $request->VATPercentageAmount;

        if ($request->Ref == 'SB') {
            $Ref = 'SB';
        } elseif ($request->Ref == 'SR') {
            $Ref = "SR";
            $isSaleReturn = true;
        }


        DB::insert("insert into salebook(Ref,Date,invoiceDateTime,Invoice,OrderNo,CustomerName,Addres,Email,Cell,Total,
        VATPercentage,VATPercentageValue,Discount,FinalTotal,user,
        PaymentType,Warrenty)
        values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)", [
            $Ref, $request->Date, $request->Date . ' ' . date("h:i:s"), $Invoice, $request->OrderNo, $CustomerName, $Address, $Email, $Cell,
            $request->SaleTotal, $VATPercentage, $VATPercentageAmount, $Discount, $request->FinalTotal,
            Auth::user()->id, $PaymentType, $Warrenty

        ]);

    
        $total_cost_sale_service = 0;
        $abc = [];
        if ($request->obj != '') {

            foreach ($request->obj as $key => $value) {

                $abc = [
                    'Invoice' => $Invoice,
                    'ItemName' => $request->obj[$key]['ItemName'],
                    'ItemCode' => $request->obj[$key]['ItemCode'],
                    'Rate' => $request->obj[$key]['Price'],
                    'Qty' => $request->obj[$key]['Qty'],
                    'Total' => $request->obj[$key]['Total'],

                ];
                DB::table(sale_book_detail)->insert($abc);
                $total_cost_sale_service += DB::table('items')->where('id',$request->obj[$key]['ItemName'])->value('PurchaseRate');

            }
        }

        try {
            $invoice = $Invoice;
            $userId = Auth::user()->id;
            $finalTotal = $request->FinalTotal;
            $totalCostSaleService = $total_cost_sale_service;
            $isSaleReturn = $isSaleReturn; // Assuming this is a flag indicating a sales return
        
            $accountIds = DB::table('chart_of_accounts')
                ->whereIn('name', ['Account Receivable', 'Service Sales', 'Stock', 'Cost Of Sale Service', 'Cash Account'])
                ->pluck('id', 'name');
        
            if ($accountIds->count() < 5) {
                throw new \Exception('One or more chart_of_accounts IDs not found');
            }
        
            if ($isSaleReturn) {
                $entries = [
                    [
                        'Invoice' => $invoice,
                        'user' => $userId,
                        'chart_of_accounts_id' => $accountIds['Service Sales'],
                        'debit' => $finalTotal,
                        'credit' => 0,
                    ],
                    [
                        'Invoice' => $invoice,
                        'user' => $userId,
                        'chart_of_accounts_id' => $accountIds['Account Receivable'],
                        'debit' => 0,
                        'credit' => $finalTotal,
                    ],
                    [
                        'Invoice' => $invoice,
                        'user' => $userId,
                        'chart_of_accounts_id' => $accountIds['Stock'],
                        'debit' => $totalCostSaleService,
                        'credit' => 0,
                    ],
                    [
                        'Invoice' => $invoice,
                        'user' => $userId,
                        'chart_of_accounts_id' => $accountIds['Cost Of Sale Service'],
                        'debit' => 0,
                        'credit' => $totalCostSaleService,
                    ],
                ];
        
                if ($PaymentType == 1) { // 1 for debit means cash sale and 0 for credit means credit sale
                    $entries = array_merge($entries, [
                        [
                            'Invoice' => $invoice,
                            'user' => $userId,
                            'chart_of_accounts_id' => $accountIds['Account Receivable'],
                            'debit' => $finalTotal,
                            'credit' => 0,
                        ],
                        [
                            'Invoice' => $invoice,
                            'user' => $userId,
                            'chart_of_accounts_id' => $accountIds['Cash Account'],
                            'debit' => 0,
                            'credit' => $finalTotal,
                        ]
                    ]);
                }
        
            } else {
                // Original Sale Entries
                $entries = [
                    [
                        'Invoice' => $invoice,
                        'user' => $userId,
                        'chart_of_accounts_id' => $accountIds['Account Receivable'],
                        'debit' => $finalTotal,
                        'credit' => 0,
                    ],
                    [
                        'Invoice' => $invoice,
                        'user' => $userId,
                        'chart_of_accounts_id' => $accountIds['Service Sales'],
                        'debit' => 0,
                        'credit' => $finalTotal,
                    ],
                    [
                        'Invoice' => $invoice,
                        'user' => $userId,
                        'chart_of_accounts_id' => $accountIds['Stock'],
                        'debit' => 0,
                        'credit' => $totalCostSaleService,
                    ],
                    [
                        'Invoice' => $invoice,
                        'user' => $userId,
                        'chart_of_accounts_id' => $accountIds['Cost Of Sale Service'],
                        'debit' => $totalCostSaleService,
                        'credit' => 0,
                    ],
                ];
        
                if ($PaymentType == 1) { // 1 for debit means cash sale and 0 for credit means credit sale
                    $entries = array_merge($entries, [
                        [
                            'Invoice' => $invoice,
                            'user' => $userId,
                            'chart_of_accounts_id' => $accountIds['Cash Account'],
                            'debit' => $finalTotal,
                            'credit' => 0,
                        ],
                        [
                            'Invoice' => $invoice,
                            'user' => $userId,
                            'chart_of_accounts_id' => $accountIds['Account Receivable'],
                            'debit' => 0,
                            'credit' => $finalTotal,
                        ]
                    ]);
                }
            }
        
            foreach ($entries as &$entry) {
                $entry['created_at'] = Carbon::now();
                $entry['updated_at'] = Carbon::now();
            }
        
            DB::table('ledger')->insert($entries);
        
        } catch (\Exception $e) {
            Log::error('Failed to insert ledger entries: ' . $e->getMessage());
        }
        
        

        $ArticleNo = DB::table('users')->where('id', Auth::user()->id)->first('ArticleNo')->ArticleNo;
        $InvoiceList = DB::table(sale_book)->where('user', Auth::user()->id)->pluck('Invoice')->toArray();
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
            $NewInvoice =  $ArticleNo . "-" . "00" . "1";
        } else {
            $reverse_InvoiceList = array_reverse($InvoiceList);
            $FirstElementOf_reverceInvoiceList = $reverse_InvoiceList[0];
            $NewInvoice = explode('-', $FirstElementOf_reverceInvoiceList);
            $NewInvoice[1]  = $NewInvoice[1] + 1;
            if (strlen(strval($NewInvoice[1])) == 1) {
                $NewInvoice =  $ArticleNo . "-" . "00" . strval($NewInvoice[1]);
            } else  if (strlen(strval($NewInvoice[1])) == 2) {
                $NewInvoice =  $ArticleNo . "-" . "0" . strval($NewInvoice[1]);
            } else if (strlen(strval($NewInvoice[1])) > 2) {
                $NewInvoice =  $ArticleNo . "-" . strval($NewInvoice[1]);
            }
        }

        return ['status' => "inserted", 'Invoice' => $Invoice, 'NewInvoice' => $NewInvoice];
        // }
    }
    public function dispatch_quotation(Request $request)
    {
        if ($request->previous_date == 'no') {
            $ArticleNo = DB::table('users')->where('name', Auth::user()->name)->first('ArticleNo')->ArticleNo;
            $InvoiceList = DB::table('quotation')->where('previous_date', 'no')->where('user', Auth::user()->id)->pluck('Invoice')->toArray();
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
                if ($ArticleNo == 'JH') {
                    $Invoice =  $ArticleNo . "-" . "05" . "0";
                } else {
                    $Invoice =  $ArticleNo . "-" . "00" . "1";
                }
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
        } else {
            $Invoice = $request->Invoice;
        }
        // for user want to enter invoice number manually
        $ArticleNo = DB::table('users')->where('name', Auth::user()->name)->first('ArticleNo')->ArticleNo;
        if ($ArticleNo == 'RA') {
            $Invoice = $request->Invoice;
        }

        // return $Invoice;

        $Discount = $request->Discount == "" ? 0 : $request->Discount;
        $CustomerName = $request->CustomerName == '' ? '' : $request->CustomerName;
        $PaymentType = $request->PaymentType == '' ? '' : $request->PaymentType;
        $VATNO = $request->VATNO == '' ? '' : $request->VATNO;
        $Cell = $request->Cell == '' ? '' : $request->Cell;
        $Address = $request->Address == '' ? '' : $request->Address;
        $Email = $request->Email == '' ? '' : $request->Email;
        $Warrenty = $request->Warrenty == '' ? '' : $request->Warrenty;
        $VATPercentage = $request->VATPercentage == '' ? '' : $request->VATPercentage;
        $VATPercentageAmount = $request->VATPercentageAmount == '' ? '' : $request->VATPercentageAmount;
        $Cash = $request->Cash == "" ? 0 : $request->Cash;




        DB::insert("insert into quotation(Date,dueDate,invoiceDateTime,Invoice,previous_date,CustomerName,VATNO,Addres,Email,Cell,Total,
        VATPercentage,VATPercentageValue,Discount,FinalTotal,Cash,user,
        PaymentType,Warrenty)
        values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)", [
            $request->Date, $request->dueDate, $request->Date . ' ' . date("h:i:s"), $Invoice, $request->previous_date, $CustomerName, $VATNO, $Address, $Email, $Cell,
            $request->SaleTotal, $VATPercentage, $VATPercentageAmount, $Discount, $request->FinalTotal, $Cash,
            Auth::user()->id, $PaymentType, $Warrenty,

        ]);

        $abc = [];
        if ($request->obj != '') {

            foreach ($request->obj as $key => $value) {

                $abc = [
                    'Invoice' => $Invoice,
                    'ItemName' => $request->obj[$key]['ItemName'],
                    'ItemCode' => $request->obj[$key]['ItemCode'],
                    'Rate' => $request->obj[$key]['Price'],
                    'Qty' => $request->obj[$key]['Qty'],
                    'Total' => $request->obj[$key]['Total'],
                    'user' => Auth::user()->id

                ];
                $salebook_detail = DB::table('quotation_detail')->insert($abc);
            }
        }

        $ArticleNo = DB::table('users')->where('name', Auth::user()->name)->first('ArticleNo')->ArticleNo;
        $InvoiceList = DB::table('quotation')->where('user', Auth::user()->id)->pluck('Invoice')->toArray();
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
            $NewInvoice =  $ArticleNo . "-" . "00" . "1";
        } else {
            $reverse_InvoiceList = array_reverse($InvoiceList);
            $FirstElementOf_reverceInvoiceList = $reverse_InvoiceList[0];
            $NewInvoice = explode('-', $FirstElementOf_reverceInvoiceList);
            $NewInvoice[1]  = $NewInvoice[1] + 1;
            if (strlen(strval($NewInvoice[1])) == 1) {
                $NewInvoice =  $ArticleNo . "-" . "00" . strval($NewInvoice[1]);
            } else  if (strlen(strval($NewInvoice[1])) == 2) {
                $NewInvoice =  $ArticleNo . "-" . "0" . strval($NewInvoice[1]);
            } else if (strlen(strval($NewInvoice[1])) > 2) {
                $NewInvoice =  $ArticleNo . "-" . strval($NewInvoice[1]);
            }
        }

        return ['status' => "inserted", 'Invoice' => $Invoice, 'NewInvoice' => $NewInvoice];
        // }
    }
    public function previous_quotation(Request $request)
    {
        $Invoice =  DB::table('quotation')->where('user', Auth::user()->id)->where('Invoice', $request->Invoice)->first();
        if ($Invoice) {
            return "invoice duplication";
        } else {
            return "everything is ok";
        }
    }


    public function dispatch_purchasebook(Request $request)
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
                $firstCharacterValue = substr($Invoice[1], 0, 1);
                if ($firstCharacterValue == 0) {
                    $Invoice =  $ArticleNo . "-" . "0" . strval($Invoice[1]);
                } else {
                    $Invoice =  $ArticleNo . "-" . strval($Invoice[1]);
                }
            }
        }

        $Discount = $request->Discount == "" ? 0 : $request->Discount;
        $PurchaseInvoice = $request->PurchaseInvoice == '' ? '' : $request->PurchaseInvoice;
        $PurchaserName = $request->PurchaserName == '' ? '' : $request->PurchaserName;
        $PurchaserAddress = $request->PurchaserAddress == '' ? '' : $request->PurchaserAddress;
        $VATPercentage = $request->VATPercentage == '' ? '' : $request->VATPercentage;
        $VATPercentageAmount = $request->VATPercentageAmount == '' ? '' : $request->VATPercentageAmount;
        
        DB::table('purchase_book')->insert([
            'Date' => $request->Date,
            'Invoice' => $Invoice,
            'PurchaserName' => $PurchaserName,
            'PurchaserAddress' => $PurchaserAddress,
            'Total' => $request->SaleTotal,
            'Discount' => $Discount,
            'VATPercentage' => $VATPercentage,
            'VATPercentageAmount' => $VATPercentageAmount,
            'FinalTotal' => $request->FinalTotal,
            'user' => Auth::user()->id
        ]);
        
        $abc = [];
        if ($request->obj != '') {

            foreach ($request->obj as $key => $value) {
                $abc = [
                    'Invoice' => $Invoice,
                    'ItemName' => $request->obj[$key]['ItemName'],
                    'Qty' => $request->obj[$key]['Qty'],
                    'Price' => $request->obj[$key]['Price'],
                    'Total' => $request->obj[$key]['Total'],

                ];
                DB::table('purchasebook_detail')->insert($abc);
            }
        }

        try {
            $invoice = $Invoice;
            $userId = Auth::user()->id;
            $finalTotal = $request->FinalTotal;
            $isSaleReturn = $request->PaymentType; // Assuming this is a flag indicating a sales return
        
            $accountIds = DB::table('chart_of_accounts')
                ->whereIn('name', ['Account Payable', 'Stock', 'Cash Account'])
                ->pluck('id', 'name');
        
            if ($accountIds->count() < 3) {
                throw new \Exception('One or more chart_of_accounts IDs not found');
            }
        
                $entries = [
                    [
                        'Invoice' => $invoice,
                        'user' => $userId,
                        'chart_of_accounts_id' => $accountIds['Account Payable'],
                        'debit' => 0,
                        'credit' => $finalTotal,
                    ],
                    [
                        'Invoice' => $invoice,
                        'user' => $userId,
                        'chart_of_accounts_id' => $accountIds['Stock'],
                        'debit' => $finalTotal,
                        'credit' => 0,
                    ]
                   
                ];
        
                if ($request->PaymentType == 1) { // 1 for debit means cash sale and 0 for credit means credit sale
                    $entries = array_merge($entries, [
                        [
                            'Invoice' => $invoice,
                            'user' => $userId,
                            'chart_of_accounts_id' => $accountIds['Account Payable'],
                            'debit' => $finalTotal,
                            'credit' => 0,
                        ],
                        [
                            'Invoice' => $invoice,
                            'user' => $userId,
                            'chart_of_accounts_id' => $accountIds['Cash Account'],
                            'debit' => 0,
                            'credit' => $finalTotal,
                        ]
                    ]);
                }
        
            foreach ($entries as &$entry) {
                $entry['created_at'] = Carbon::now();
                $entry['updated_at'] = Carbon::now();
            }
        
            DB::table('ledger')->insert($entries);
        
        } catch (\Exception $e) {
            Log::error('Failed to insert ledger entries: ' . $e->getMessage());
        }

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
                $firstCharacterValue = substr($Invoice[1], 0, 1);
                if ($firstCharacterValue == 0) {
                    $Invoice =  $ArticleNo . "-" . "0" . strval($Invoice[1]);
                } else {
                    $Invoice =  $ArticleNo . "-" . strval($Invoice[1]);
                }
            }
        }

        return ['status' => "inserted", "NewInvoice" => $Invoice];

    }

    public function getPurchaseInvoicesForEdit(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        return view('admin/modules/SalesBook/Purchasebook/showSaleInvoices', 
        [
            'saleInvoices' => 
            DB::table('purchase_book')->join('users', 'users.id', 'purchasebook.user')
->where('purchasebook.user', Auth::user()->id)
->whereBetween('purchasebook.Date', [$startDate, $endDate])->select('purchasebook.*','users.name')->get()

        ]);
    }
    public function edit_purchasebookInvoice($id)
    {
        $purchasebook = DB::table('purchase_book')->where('Invoice', $id)->first();
        $purchasebook_detail = DB::table('purchasebook_detail')->join('items','items.id','purchasebook_detail.ItemName')
        ->where('purchasebook_detail.Invoice', $id)
        ->select('items.ItemName as items_ItemName','items.id as item_id','purchasebook_detail.*')
        ->get();
        $purchasers = DB::table('purchasers')->where('user',Auth::user()->id)->get();
        return view(
            'admin/modules/SalesBook/Purchasebook/edit_invoice_purchasebook',
            ['purchase_book' => $purchasebook, 'purchasebook_detail' => $purchasebook_detail,'purchasers' => $purchasers]
        );
    }
    public function update_dispatch_purchasebook(Request $request)
    {
        $dispatch = '';
        $dispatch_detail = '';
        $dispatch = DB::table('purchase_book')->where('invoice', $request->invoice_edit)->update([
            'PurchaseInvoice' => $request->PurchaseInvoice,
            'PurchaserName' => $request->PurchaserName == '' ? '' : $request->PurchaserName,
            'PurchaserAddress' => $request->PurchaserAddress == '' ? '' : $request->PurchaserAddress,
            'Total' => $request->SaleTotal,
            'Discount' => $request->Discount,
            'VATPercentage' => $request->VATPercentage == '' ? '' : $request->VATPercentage,
            'VATPercentageAmount' => $request->VATPercentageAmount == '' ? '' : $request->VATPercentageAmount,
            'FinalTotal' => $request->FinalTotal,
        ]);


        $delete = DB::table('purchasebook_detail')->where('invoice', $request->invoice_edit)->delete();
        DB::table('ledger')->where('Invoice', $request->invoice_edit)->delete();

        $abc = [];
        if ($request->obj != '') {
            foreach ($request->obj as $key => $value) {
                $abc = [
                    'Invoice' => $request->invoice_edit,
                    'ItemName' => $request->obj[$key]['ItemName'], 'Qty' => $request->obj[$key]['Qty'],
                    'Price' => $request->obj[$key]['Price'], 'Total' => $request->obj[$key]['Total'],


                ];
                $salebook_detail = DB::table('purchasebook_detail')->insert($abc);
            }
        }
        try {
            $invoice = $request->invoice_edit;
            $userId = Auth::user()->id;
            $finalTotal = $request->FinalTotal;
        
            $accountIds = DB::table('chart_of_accounts')
                ->whereIn('name', ['Account Payable', 'Stock', 'Cash Account'])
                ->pluck('id', 'name');
        
            if ($accountIds->count() < 3) {
                throw new \Exception('One or more chart_of_accounts IDs not found');
            }
        
                $entries = [
                    [
                        'Invoice' => $invoice,
                        'user' => $userId,
                        'chart_of_accounts_id' => $accountIds['Account Payable'],
                        'debit' => 0,
                        'credit' => $finalTotal,
                    ],
                    [
                        'Invoice' => $invoice,
                        'user' => $userId,
                        'chart_of_accounts_id' => $accountIds['Stock'],
                        'debit' => $finalTotal,
                        'credit' => 0,
                    ]
                   
                ];
        
                if ($request->PaymentType == 1) { // 1 for debit means cash sale and 0 for credit means credit sale
                    $entries = array_merge($entries, [
                        [
                            'Invoice' => $invoice,
                            'user' => $userId,
                            'chart_of_accounts_id' => $accountIds['Account Payable'],
                            'debit' => $finalTotal,
                            'credit' => 0,
                        ],
                        [
                            'Invoice' => $invoice,
                            'user' => $userId,
                            'chart_of_accounts_id' => $accountIds['Cash Account'],
                            'debit' => 0,
                            'credit' => $finalTotal,
                        ]
                    ]);
                }
        
            foreach ($entries as &$entry) {
                $entry['created_at'] = Carbon::now();
                $entry['updated_at'] = Carbon::now();
            }
        
            DB::table('ledger')->insert($entries);
        
        } catch (\Exception $e) {
            Log::error('Failed to insert ledger entries: ' . $e->getMessage());
        }
        return ['status' => "inserted", 'Invoice' => $request->invoice_edit];
    }
    public function delete_dispatch_purchasebook()
    {
        $invoice = $_GET['Invoice'];
        $dispatch_detail =  DB::table('purchasebook_detail')
            ->where('Invoice', $invoice)
            ->delete();
        $dispatch =  DB::table('purchase_book')
            ->where('Invoice', $invoice)
            ->delete();
        DB::table('ledger')->where('Invoice', $invoice)->delete();
        return redirect()->back();
    }

    public function getInvoice_expense()
    {
        $ArticleNo = DB::table('users')->where('id', Auth::user()->id)->first('ArticleNo')->ArticleNo;
        $InvoiceList = DB::table('expensebook')->where('user', Auth::user()->id)->pluck('Invoice')->toArray();
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
                $firstCharacterValue = substr($Invoice[1], 0, 1);
                if ($firstCharacterValue == 0) {
                    $Invoice =  $ArticleNo . "-" . "0" . strval($Invoice[1]);
                } else {
                    $Invoice =  $ArticleNo . "-" . strval($Invoice[1]);
                }
            }
        }
        return $Invoice;
    }
    public function dispatch_expensebook(Request $request)
    {
        $ArticleNo = DB::table('users')->where('id', Auth::user()->id)->first('ArticleNo')->ArticleNo;
        $InvoiceList = DB::table('expensebook')->where('user', Auth::user()->id)->pluck('Invoice')->toArray();
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
                $firstCharacterValue = substr($Invoice[1], 0, 1);
                if ($firstCharacterValue == 0) {
                    $Invoice =  $ArticleNo . "-" . "0" . strval($Invoice[1]);
                } else {
                    $Invoice =  $ArticleNo . "-" . strval($Invoice[1]);
                }
            }
        }

        $ExpenseFromAccount = $request->ExpenseFromAccount == '' ? '' : $request->ExpenseFromAccount;
        $Description = $request->Description == '' ? '' : $request->Description;
        $expensebook =  DB::insert("insert into expensebook(Date,Invoice,ExpenseFromAccount
        ,Amount,Description,user,chart_of_account_id)
        values(?,?,?,?,?,?,?)", [
            $request->Date, $Invoice, $ExpenseFromAccount,
            $request->Amount, $Description, Auth::user()->id,$request->ExpenseOf
        ]);

        try {
            $invoice = $Invoice;
            $userId = Auth::user()->id;
            $Amount = $request->Amount;
        
            $entries = [
                [
                    'Invoice' => $invoice,
                    'user' => $userId,
                    'chart_of_accounts_id' => $request->ExpenseFromAccount,
                    'debit' => 0,
                    'credit' => $Amount,
                ],
                [
                    'Invoice' => $invoice,
                    'user' => $userId,
                    'chart_of_accounts_id' => $request->ExpenseOf,
                    'debit' => $Amount,
                    'credit' => 0,
                ]
                
            ];
        
            foreach ($entries as &$entry) {
                $entry['created_at'] = Carbon::now();
                $entry['updated_at'] = Carbon::now();
            }
        
            DB::table('ledger')->insert($entries);
        
        } catch (\Exception $e) {
            Log::error('Failed to insert ledger entries: ' . $e->getMessage());
        }


        $ArticleNo = DB::table('users')->where('id', Auth::user()->id)->first('ArticleNo')->ArticleNo;
        $InvoiceList = DB::table('expensebook')->where('user', Auth::user()->id)->pluck('Invoice')->toArray();
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
                $firstCharacterValue = substr($Invoice[1], 0, 1);
                if ($firstCharacterValue == 0) {
                    $Invoice =  $ArticleNo . "-" . "0" . strval($Invoice[1]);
                } else {
                    $Invoice =  $ArticleNo . "-" . strval($Invoice[1]);
                }
            }
        }

        return ['status' => "inserted", 'NewInvoice' => $Invoice];
    }
    public function getExpenseInvoicesForEdit(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;


        return view('admin/modules/SalesBook/Expense/showSaleInvoices', 
        [
            'saleInvoices' => DB::table('expensebook')->join('users', 'users.id', 'expensebook.user')->select('expensebook.*','users.name')
            ->where('expensebook.user', Auth::user()->id)->whereBetween('Date', [$startDate, $endDate])->get()
        ]);
    }
    public function edit_expensebookInvoice($id)
    {

        $expensebook = DB::table('expensebook')->where('Invoice', $id)->first();

        $chart_of_accounts = DB::table('chart_of_accounts')->where('type','E')->where('user', Auth::user()->id)->get();
        $expense_from_account = DB::table('chart_of_accounts')->where('type','A')->where('user', Auth::user()->id)->get();

        return view(
            'admin/modules/SalesBook/Expense/edit_invoice',
            ['expensebook' => $expensebook,'chart_of_accounts' => $chart_of_accounts,
            'expense_from_account' => $expense_from_account]
        );
    }

    public function update_dispatch_expensebook(Request $request)
    {
        DB::table('expensebook')->where('invoice', $request->invoice_edit)->update([

            'ExpenseFromAccount' => $request->ExpenseFromAccount,
            'chart_of_account_id' => $request->ExpenseOf,
            'Amount' => $request->Amount,
            'Description' => $request->Description,

        ]);

        DB::table('ledger')->where('Invoice', $request->invoice_edit)->delete();

        try {
            $invoice = $request->invoice_edit;
            $userId = Auth::user()->id;
            $Amount = $request->Amount;
        
            $entries = [
                [
                    'Invoice' => $invoice,
                    'user' => $userId,
                    'chart_of_accounts_id' => $request->ExpenseFromAccount,
                    'debit' => 0,
                    'credit' => $Amount,
                ],
                [
                    'Invoice' => $invoice,
                    'user' => $userId,
                    'chart_of_accounts_id' => $request->ExpenseOf,
                    'debit' => $Amount,
                    'credit' => 0,
                ]
                
            ];
        
            foreach ($entries as &$entry) {
                $entry['created_at'] = Carbon::now();
                $entry['updated_at'] = Carbon::now();
            }
        
            DB::table('ledger')->insert($entries);
        
        } catch (\Exception $e) {
            Log::error('Failed to insert ledger entries: ' . $e->getMessage());
        }

        return ['status' => "inserted", 'Invoice' => $request->invoice_edit];
    }
    public function delete_dispatch_expensebook(Request $request)
    {
        $invoice = $request->Invoice;
        $dispatch =  DB::table('expensebook')
            ->where('Invoice', $invoice)
            ->delete();
        DB::table('ledger')->where('Invoice', $invoice)->delete();
        
        return redirect()->back();
    }
}
