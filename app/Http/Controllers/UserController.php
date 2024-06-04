<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use function PHPSTORM_META\type;

class UserController extends Controller
{

    // Create user code starts from here

    public function createUser_method()
    {
        if (Auth::guest()) {
            return redirect('login');
        } else {
            if (Auth::user()->UserType == "Admin") {
                $users = DB::table('users')->get();
                return view('admin/modules/User/createUser', ['users' => $users]);
            } else {
                $users = DB::table('users')->where('name', Auth::user()->id)->get();
                return view('admin/modules/User/createUser', ['users' => $users]);
            }
        }
    }
    public function ManageUser_method()
    {
        if (Auth::guest()) {
            return redirect('login');
        } else {
            $users = DB::table('users')->get();
            return view('admin/modules/User/ManageUser', ['users' => $users]);
        }
    }

    public function insertCreatedUser_method(Request $request)
    {
        $checkDuplicateRecord = DB::table('users')->where('name', $request->name)->orWhere('email', $request->email)->first();
        if ($checkDuplicateRecord) {
            return redirect('createUser')->with('status', 'Duplicate value trying to insert');
        } else {
            if ($request->hasfile('image')) {
                $filenameWithExt = $request->file('image')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('image')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $image = $request->file('image')->move('images', $fileNameToStore);
            } else {
                $image = '';
            }
            $sale_type = $request->SaleType;
            $customer_industry = $request->CustomerIndustry;
            $abc = [
                'name' => $request->name == '' ? '' : $request->name,
                'email' => $request->email == '' ? '' : $request->email,
                'password' => Hash::make($request->password),
                'created_at' => date('Y-m-d h:i:s'),
                'userType' => '',
                'CRNO' => $request->CRNO == '' ? '' : $request->CRNO,
                'CompanyName' => $request->CompanyName == '' ? '' : $request->CompanyName,
                'CompanyNameArabic' => $request->CompanyNameArabic == '' ? '' : $request->CompanyNameArabic,
                'VATNO' => $request->VATNO == '' ? '' : $request->VATNO,
                'cell' => $request->Cell == '' ? '' : $request->Cell,
                'image' => $image,
                'Addres' => $request->Address == '' ? '' : $request->Address,
                'ArticleNo' => $request->ArticleNo == '' ? '' : $request->ArticleNo,
                'user_template' => 'fsct',
                'Detail_English' => "something in english",
                'Detail_Arabic' => 'something in arabic',
                'Side_Detail_English' => "Side_Detail_English",
                'BusinessTypeEnglish' =>  'Busuness Type English',
                'BusinessTypeArabic' => 'Business Type Arabic',
                'BusinessDescriptionEnglish' => 'BusinessDescriptionEnglish',
                'BusinessDescriptionArabic' => 'BusinessDescriptionArabic',
                'domainName' => 'fsct',
                'VATNO_Arabic' => 'VATNO_Arabic',
                'Side_Detail_Arabic' => "Side_Detail_Arabic",
                'Invoice_pic' => "Invoice_pic",
                'CustomerIndustry' => $customer_industry,
                'SaleType' => $sale_type,
                'ShowEmailOnInvoice' => $request->ShowEmailOnInvoice,
                'VAT_Calculation' => $request->VAT_Calculation
            ];
            $inserted = DB::table('users')->insert($abc);
            if ($inserted) {
                return  redirect('createUser');
            }
        }
    }
    public function createdUserEdit_method(Request $request)
    {

        return  DB::table('users')->where('id', $request->id)->first();
    }

    public function createdUserDelete_method($id)
    {
        $user = DB::table('users')->where('id', $id)->first('UserType')->UserType;
        if (Auth::user()->UserType == "Admin" && $user != 'Admin') {
            $deleted =  DB::table('users')
                ->where('id', $id)
                ->delete();
        }
        return redirect('createUser');
    }
    public function SubscriptionMessage_method(Request $request)
    {

        $roleData = DB::table('users')->where('id', $request->id)->first();
        return $roleData;
    }
    public function updateMessage_method(Request $request)
    {
        $inserted = DB::table('users')->where('id', $request->id)->update(['SubscriptionMessage' => $request->modal_SubscriptionMessage]);
        if ($inserted) {
            return  redirect('ManageUser');
        }
    }

    public function updateCreatedUser_method(Request $request)
    {
        if ($request->hasfile('modal_image')) {
            $filenameWithExt = $request->file('modal_image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('modal_image')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $image = $request->file('modal_image')->move('images', $fileNameToStore);
        } else {
            if ($request->hidden_UserPic == '') {
                $image = '';
            } else {
                $image = $request->hidden_UserPic;
            }
        }

        if ($request->hasfile('InvoicePic')) {
            $filenameWithExt = $request->file('InvoicePic')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('InvoicePic')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $InvoicePic = $request->file('InvoicePic')->move('images', $fileNameToStore);
        } else {
            if ($request->hidden_InvoicePic == '') {
                $InvoicePic = '';
            } else {
                $InvoicePic = $request->hidden_InvoicePic;
            }
        }
        if ($request->modal_password != '') {
            $password = Hash::make($request->modal_password);
        } else {
            $password = $request->hidden_UserPassword;
        }
        $updatedUser = DB::table('users')->where('id', $request->modal_id)->update([
            'name' => $request->modal_name == '' ? '' : $request->modal_name,
            'email' => $request->modal_email == '' ? '' : $request->modal_email,
            'updated_at' => date('Y-m-d h:i:s'),
            'CRNO' => $request->modal_CRNO == '' ? '' : $request->modal_CRNO,
            'CompanyName' => $request->modal_CompanyName == '' ? '' : $request->modal_CompanyName,
            'CompanyNameArabic' => $request->modal_CompanyNameArabic == '' ? '' : $request->modal_CompanyNameArabic,
            'VATNO' => $request->modal_VATNO == '' ? '' : $request->modal_VATNO,
            'Cell' => $request->modal_Cell == '' ? '' : $request->modal_Cell,
            'Addres' => $request->modal_Address == '' ? '' : $request->modal_Address,
            'ArticleNo' => $request->modal_ArticleNo == '' ? '' : $request->modal_ArticleNo,
            'image' => $image,
            'password' => $password,
            'Detail_English' => $request->English_Description == '' ? '' : $request->English_Description,
            'Detail_Arabic' => $request->Arabic_Description == '' ? '' : $request->Arabic_Description,

            'Side_Detail_English' => $request->modal_Side_English_Description == '' ? '' : $request->modal_Side_English_Description,
            'Side_Detail_Arabic' => $request->modal_Side_Arabic_Description == '' ? '' : $request->modal_Side_Arabic_Description,
            'Invoice_pic' => $InvoicePic,
            'BusinessTypeEnglish' => $request->BusinessTypeEnglish == '' ? '' : $request->BusinessTypeEnglish,
            'BusinessTypeArabic' => $request->BusinessTypeArabic == '' ? '' : $request->BusinessTypeArabic,
            'BusinessDescriptionEnglish' => $request->BusinessDescriptionEnglish == '' ? '' : $request->BusinessDescriptionEnglish,
            'BusinessDescriptionArabic' => $request->BusinessDescriptionArabic == '' ? '' : $request->BusinessDescriptionArabic,
            'VATNO_Arabic' => $request->VATNO_Arabic == '' ? '' : $request->VATNO_Arabic,
            'VATPercentage' => $request->VATPercentage,
            'language' => $request->language,
            'CustomerIndustry' => $request->CustomerIndustry,
            'SaleType' => $request->SaleType,
            'ShowEmailOnInvoice' => $request->ShowEmailOnInvoice,
            'VAT_Calculation' => $request->VAT_Calculation
        ]);

        DB::table('category')->where('user', $request->modal_idname)->update([
            'user' => $request->modal_name,
        ]);
        DB::table('customertype')->where('user', $request->modal_idname)->update([
            'user' => $request->modal_name,
        ]);
        DB::table('paymenttype')->where('user', $request->modal_idname)->update([
            'user' => $request->modal_name,
        ]);
        DB::table('items')->where('user', $request->modal_idname)->update([
            'user' => $request->modal_name,
        ]);
        DB::table('expensebook')->where('user', $request->modal_idname)->update([
            'user' => $request->modal_name,
        ]);
        DB::table('purchasebook')->where('user', $request->modal_idname)->update([
            'user' => $request->modal_name,
        ]);
        DB::table('salebook')->where('user', $request->modal_idname)->update([
            'user' => $request->modal_name,
        ]);

        return redirect('createUser');
    }
    public function usermanagement_method($id, $status)
    {
        // $user = DB::table('users')->where('id', $id)->first('UserType')->UserType;


        if (Auth::user()->UserType == "Admin") {
            if ($status == 'suspend') {
                $updatestatus =  DB::table('users')
                    ->where('id', $id)
                    ->update(['UserStatus' => 'suspended']);
            } elseif ($status == 'activate') {
                $updatestatus =  DB::table('users')
                    ->where('id', $id)
                    ->update(['UserStatus' => 'Active']);
            } elseif ($status == 'FreeTrial') {
                $updatestatus =  DB::table('users')
                    ->where('id', $id)
                    ->update(['UserStatus' => 'FreeTrial']);
            }
        }
        // } else if (Auth::user()->UserType == 'Parent' && $user != 'Parent') {
        //     if ($status == 'suspend') {
        //         $updatestatus =  DB::table('users')
        //             ->where('id', $id)
        //             ->update(['UserStatus' => 'suspended']);
        //     } elseif ($status == 'activate') {
        //         $updatestatus =  DB::table('users')
        //             ->where('id', $id)
        //             ->update(['UserStatus' => 'Active']);
        //     } elseif ($status == 'FreeTrial') {
        //         $updatestatus =  DB::table('users')
        //             ->where('id', $id)
        //             ->update(['UserStatus' => 'FreeTrial']);
        //     }
        // }
        return redirect('ManageUser');
    }

    public function setting_method()
    {
        if (Auth::guest()) {
            return redirect('login');
        } else {
            if (Auth::user()->UserType == "Admin") {
                $users = DB::table('users')->get();
                return view('admin/modules/User/createUser', ['users' => $users]);
            } else {
                $users = DB::table('users')->where('name', Auth::user()->id)->get();
                return view('admin/modules/User/createUser', ['users' => $users]);
            }
        }
    }

    public function checkDuplication_method(Request $request)
    {
        if ($request->type == 'createUserNameCheck') {
            $duplicate = DB::table('users')->where('name', $request->value)->first();
            if ($duplicate) {
                return "Duplicate";
            } else {
                return "Not";
            }
        } else if ($request->type == 'createUserEmailCheck') {
            $duplicate = DB::table('users')->where('email', $request->value)->first();
            if ($duplicate) {
                return "Duplicate";
            } else {
                return "Not";
            }
        } else if ($request->type == 'categoryCheck') {
            $duplicate = DB::table('category')->where('Category', $request->value)->where('user', Auth::user()->id)->first();
            if ($duplicate) {
                return "Duplicate";
            } else {
                return "Not";
            }
        } else if ($request->type == 'ItemNameCheck') {
            // ['ItemName'=>$request->ItemName,'CompanyName'=>$request->CompanyName];
            $duplicate = DB::table('items')->where(['ItemName' => $request->ItemName, 'user' => Auth::user()->id])->first();
            if ($duplicate) {
                return "Duplicate";
            } else {
                return "Not";
            }
        } else if ($request->type == 'CustomerTypeCheck') {
            // ['ItemName'=>$request->ItemName,'CompanyName'=>$request->CompanyName];
            $duplicate = DB::table('customertype')->where(['CustomerType' => $request->value, 'user' => Auth::user()->id])->first();
            if ($duplicate) {
                return "Duplicate";
            } else {
                return "Not";
            }
        } else if ($request->type == 'PaymentTypeCheck') {
            // ['ItemName'=>$request->ItemName,'CompanyName'=>$request->CompanyName];
            $duplicate = DB::table('paymenttype')->where(['PaymentType' => $request->value, 'user' => Auth::user()->id])->first();
            if ($duplicate) {
                return "Duplicate";
            } else {
                return "Not";
            }
        }
    }
}
