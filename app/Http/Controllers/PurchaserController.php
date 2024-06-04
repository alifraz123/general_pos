<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchaserController extends Controller
{
    // Purchaser code starts from here

    public function Purchaser_method()
    {
        if (Auth::guest()) {
            return redirect('login');
        } else {
            $Purchasers = DB::table('purchasers')->where('user', Auth::user()->id)->get();

            return view('admin/modules/Purchasers/Purchaser', [

                'purchasers' => $Purchasers,
            ]);
        }
    }

    public function insertPurchaser_method(Request $request)
    {

        $checkDuplicateRecord = DB::table('purchasers')->where(['id' => $request->id, 'user' => Auth::user()->id])->first();
        if ($checkDuplicateRecord) {
            return redirect('Purchaser')->with('status', 'Duplicate value trying to insert');
        } else {

            $Purchaser = DB::insert(
                "insert into purchasers(PurchaserName,Contact,Address,user)values(?,?,?,?)",
                [$request->PurchaserName, $request->Contact, $request->Address, Auth::user()->id]
            );
            if ($Purchaser) {
                if ($request->requestFrom == 'purchase_book') {
                    return redirect('purchase_book');
                } else {

                    return redirect('Purchaser');
                }
            }
        }
    }

    public function PurchaserEdit_method(Request $request)
    {
        return DB::table('purchasers')->where('id', $request->id)->first();
    }

    public function  updatePurchaser_method(Request $request)
    {

        $itemsTable_ItemName = DB::table('purchasers')->where('id', $request->id)->first();
        $updatedRole = DB::table('purchasers')->where('id', $request->id)->update([
            'PurchaserName' => $request->PurchaserName,
            'Contact' => $request->Contact,
            'Address' => $request->Address,

        ]);

        return redirect('Purchaser');
    }

    public function PurchaserDelete_method($id)
    {

        $deleted =  DB::table('purchasers')
            ->where('id', $id)
            ->delete();
        if ($deleted) {
            return redirect('Purchaser');
        }
    }

}
