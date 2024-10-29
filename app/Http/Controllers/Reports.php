<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Reports extends Controller
{
    public function Reports()
    {
        $Customers = DB::table('customers')->where('user', Auth::user()->id)->get();
        return view('admin/modules/Reports/Reports', ['Customers' => $Customers]);
    }

    public function convert_number_to_words($number)
    {
        $hyphen      = ' ';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' Riyals and ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'fourty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion',
        );
        if (!is_numeric($number)) {
            return false;
        }
        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }
        if ($number < 0) {
            return $negative . self::convert_number_to_words(abs($number));
        }
        $string = $fraction = null;
        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }
        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string    = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . self::convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit     = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder    = $number % $baseUnit;
                $string       = self::convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= self::convert_number_to_words($remainder);
                }
                break;
        }
        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }
        return ucwords($string);
    }

    public function printInvoice(Request $request)
    {
        function enToAr($string)
        {
            $str = strtr($string, array('0' => '٠', '1' => '١', '2' => '٢', '3' => '٣', '4' => '٤', '5' => '٥', '6' => '٦', '7' => '٧', '8' => '٨', '9' => '٩'));
            return $str;
        }
        $cr_number = DB::table('users')->where('name', Auth::user()->name)->pluck('CRNO');
        $cr_number_1 = enToAr($cr_number);
        $cr_number_arabic = substr("$cr_number_1", 2, -2);


        // function convertNumber($num = false)
        // {
        //     $num = str_replace(array(',', ''), '', trim($num));
        //     if (!$num) {
        //         return false;
        //     }
        //     $num = (int) $num;
        //     $words = array();
        //     $list1 = array(
        //         '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
        //         'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
        //     );
        //     $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
        //     $list3 = array(
        //         '', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
        //         'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
        //         'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
        //     );
        //     $num_length = strlen($num);
        //     $levels = (int) (($num_length + 2) / 3);
        //     $max_length = $levels * 3;
        //     $num = substr('00' . $num, -$max_length);
        //     $num_levels = str_split($num, 3);
        //     for ($i = 0; $i < count($num_levels); $i++) {
        //         $levels--;
        //         $hundreds = (int) ($num_levels[$i] / 100);
        //         $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ($hundreds == 1 ? '' : '') . ' ' : '');
        //         $tens = (int) ($num_levels[$i] % 100);
        //         $singles = '';
        //         if ($tens < 20) {
        //             $tens = ($tens ? ' and ' . $list1[$tens] . ' ' : '');
        //         } elseif ($tens >= 20) {
        //             $tens = (int)($tens / 10);
        //             $tens = ' and ' . $list2[$tens] . ' ';
        //             $singles = (int) ($num_levels[$i] % 10);
        //             $singles = ' ' . $list1[$singles] . ' ';
        //         }
        //         $words[] = $hundreds . $tens . $singles . (($levels && (int) ($num_levels[$i])) ? ' ' . $list3[$levels] . ' ' : '');
        //     } //end for loop
        //     $commas = count($words);
        //     if ($commas > 1) {
        //         $commas = $commas - 1;
        //     }
        //     $words = implode(' ',  $words);
        //     $words = preg_replace('/^\s\b(and)/', '', $words);
        //     $words = trim($words);
        //     $words = ucfirst($words);
        //     $words = $words . ".";
        //     return $words;
        // }

        $salebook = DB::table('sale_book')->where('Invoice', $request->Invoice)->first();
        $lastwords = sprintf('%0.2f', ($salebook->FinalTotal));
        $final_total_ld = substr("$lastwords", -2); #final total last digit
        if ($final_total_ld == '00') {
            # code...
            $AmountInWords =  $this->convert_number_to_words($salebook->FinalTotal);
        } else {
            $AmountInWords =  $this->convert_number_to_words($salebook->FinalTotal) . ' halalas';
        }


        $Invoice_Design = Auth::user()->user_template;

        // return $salebook->PartyName;

        $salebook_detail = DB::table('sale_book_detail')->where('Invoice', $request->Invoice)->get();
        $value1 = Auth::user()->CompanyNameArabic;
        $tag1 = pack('H*', sprintf("%02X", 1));
        $length1 = pack('H*', sprintf("%02X", strlen($value1)));
        $value2 = Auth::user()->VATNO;
        $tag2 = pack('H*', sprintf("%02X", 2));
        $length2 = pack('H*', sprintf("%02X", strlen($value2)));
        $value3 = $salebook->invoiceDateTime;
        $tag3 = pack('H*', sprintf("%02X", 3));
        $length3 = pack('H*', sprintf("%02X", strlen($value3)));
        $value4 = $salebook->FinalTotal;
        $tag4 = pack('H*', sprintf("%02X", 4));
        $length4 = pack('H*', sprintf("%02X", strlen($value4)));
        $value5 = $salebook->VATPercentageValue;
        $tag5 = pack('H*', sprintf("%02X", 5));
        $length5 = pack('H*', sprintf("%02X", strlen($value5)));
        $str = base64_encode($tag1 . $length1 . $value1 . $tag2 . $length2 . $value2 . $tag3 . $length3 . $value3 . $tag4 . $length4 . $value4 . $tag5 . $length5 . $value5);
        $isPdf = $request->Pdf;
        return view('admin/modules/Reports/' . $Invoice_Design, [
            'sale_book' => $salebook, 'sale_book_detail' => $salebook_detail, 'strr' => $str, 'AmountInWords' => $AmountInWords, 'isPdf' => $isPdf, 'cr_number_arabic' => $cr_number_arabic
        ]);
    }
    public function printQuotation(Request $request)
    {
        function enToAr1($string)
        {
            $str = strtr($string, array('0' => '٠', '1' => '١', '2' => '٢', '3' => '٣', '4' => '٤', '5' => '٥', '6' => '٦', '7' => '٧', '8' => '٨', '9' => '٩'));
            return $str;
        }

        $cr_number = DB::table('users')->where('name', Auth::user()->name)->pluck('CRNO');
        $cr_number_1 = enToAr1($cr_number);
        $cr_number_arabic = substr("$cr_number_1", 2, -2);
        $quotation = DB::table('quotation')->where('Invoice', $request->Invoice)->first();
        $lastwords = sprintf('%0.2f', ($quotation->FinalTotal));
        $final_total_ld = substr("$lastwords", -2); #final total last digit
        if ($final_total_ld == '00') {
            $AmountInWords =  $this->convert_number_to_words($quotation->FinalTotal);
        } else {
            $AmountInWords =  $this->convert_number_to_words($quotation->FinalTotal) . ' halalas';
        }
        $quotation_detail = DB::table('quotation_detail')->where('Invoice', $request->Invoice)->get();
        $isPdf = $request->Pdf;
        return view('admin/modules/Reports/quotation', [
            'sale_book' => $quotation, 'sale_book_detail' => $quotation_detail,  'AmountInWords' => $AmountInWords, 'isPdf' => $isPdf, 'cr_number_arabic' => $cr_number_arabic
        ]);
    }

    public function VAT_Report(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $salebook = DB::table('sale_book')->where('user', Auth::user()->id)->where('Ref', 'SB')->whereBetween('Date', [$startDate, $endDate])->get();
        $purchasebook = DB::table('purchase_book')->where('user', Auth::user()->id)->whereBetween('Date', [$startDate, $endDate])->get();

        $TotalQtyArray = [];
        for ($a = 0; $a < count($salebook); $a++) {
            $Qty = DB::table('sale_book_detail')->where(['Invoice' => $salebook[$a]->Invoice])->count('Invoice');
            array_push($TotalQtyArray, $Qty);
        }

        $TotalQtyArray_purchase = [];
        for ($a = 0; $a < count($purchasebook); $a++) {
            $Qty = DB::table('purchasebook_detail')->where(['Invoice' => $purchasebook[$a]->Invoice])->count('Invoice');
            array_push($TotalQtyArray_purchase, $Qty);
        }

        if (Auth::user()->domainName == 'fsct') {
            return view('admin/modules/Reports/FSCT_VAT_Report', [
                'sale_book' => $salebook, 'purchase_book' => $purchasebook,
                'startDate' => $startDate, 'endDate' => $endDate,
                'TotalQtyArray_purchase' => $TotalQtyArray_purchase,
                'TotalQtyArray' => $TotalQtyArray
            ]);
        } else {
            return view('admin/modules/Reports/VAT_Report', [
                'sale_book' => $salebook, 'purchase_book' => $purchasebook,
                'startDate' => $startDate, 'endDate' => $endDate
            ]);
        }
    }
    public function ExpenseReport(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $expensebook = DB::table('expensebook')->where('user', Auth::user()->id)->whereBetween('Date', [$startDate, $endDate])->get();

        return view('admin/modules/Reports/ExpenseReport', [
            'expensebook' => $expensebook,
            'startDate' => $startDate, 'endDate' => $endDate
        ]);
    }

    public function VAT_SaleReport(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $salebook = DB::table('sale_book')->where('user', Auth::user()->id)->where('Ref', 'SB')->whereBetween('Date', [$startDate, $endDate])->get();
        $TotalQtyArray = [];
        for ($a = 0; $a < count($salebook); $a++) {
            $Qty = DB::table('sale_book_detail')->where(['Invoice' => $salebook[$a]->Invoice])->sum('Qty');
            array_push($TotalQtyArray, $Qty);
        }
        // return TotalQtyArray;
        if (Auth::user()->domainName == 'fsct') {
            return view('admin/modules/Reports/FSCT_VAT_SaleReport', [
                'sale_book' => $salebook,
                'startDate' => $startDate, 'endDate' => $endDate, 'TotalQtyArray' => $TotalQtyArray
            ]);
        } else {
            return view('admin/modules/Reports/VAT_SaleReport', [
                'sale_book' => $salebook,
                'startDate' => $startDate, 'endDate' => $endDate
            ]);
        }
    }
    public function CustomerReport(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $CustomerName = $request->CustomerName;
        $salebook = DB::table('sale_book')->where('CustomerName', $CustomerName)->where('user', Auth::user()->id)->where('Ref', 'SB')->whereBetween('Date', [$startDate, $endDate])->get();

        if (Auth::user()->domainName == 'fsct') {
            return view('admin/modules/Reports/CustomerReport', [
                'sale_book' => $salebook,
                'startDate' => $startDate, 'endDate' => $endDate, 'CustomerName' => $CustomerName
            ]);
        } else {
            return view('admin/modules/Reports/CustomerReport', [
                'sale_book' => $salebook,
                'startDate' => $startDate, 'endDate' => $endDate
            ]);
        }
    }
    public function VAT_PurchaseReport(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $purchasebook = DB::table('purchase_book')->where('user', Auth::user()->id)->whereBetween('Date', [$startDate, $endDate])->get();


        $TotalQtyArray = [];
        for ($a = 0; $a < count($purchasebook); $a++) {
            $Qty = DB::table('purchasebook_detail')->where(['Invoice' => $purchasebook[$a]->Invoice])->sum('Qty');
            array_push($TotalQtyArray, $Qty);
        }

        if (Auth::user()->domainName == 'fsct') {
            return view('admin/modules/Reports/FSCT_VAT_PurchaseReport', [
                'purchase_book' => $purchasebook,
                'startDate' => $startDate, 'endDate' => $endDate, 'TotalQtyArray' => $TotalQtyArray
            ]);
        } else {
            return view('admin/modules/Reports/VAT_PurchaseReport', [
                'purchase_book' => $purchasebook,
                'startDate' => $startDate, 'endDate' => $endDate
            ]);
        }
    }
    public function TotalReport(Request $request)
    {

        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $salebook = DB::table('sale_book')->where('user', Auth::user()->id)->where('Ref', 'SB')->whereBetween('Date', [$startDate, $endDate])->get();
        $purchasebook = DB::table('purchase_book')->where('user', Auth::user()->id)->whereBetween('Date', [$startDate, $endDate])->get();

        return view('admin/modules/Reports/TotalReport', [
            'sale_amount' => $salebook->sum('Total'), 'sale_vat' => $salebook->sum('VATPercentageValue'),
            'sale_total' => $salebook->sum('FinalTotal'),
            'purchase_amount' => $purchasebook->sum('Total'), 'purchase_vat' => $purchasebook->sum('VATPercentageAmount'),
            'purchase_total' => $purchasebook->sum('FinalTotal'),
            'startDate' => $startDate, 'endDate' => $endDate,
        ]);
    }
   
}
