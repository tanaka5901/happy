<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OfficeStockService;
use OfficePurchasesService;
use OfficeExpensesService;
use Log;

class MonthlyReportController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('output_csv_for_monthly_report');
    }

    public function exportCsv(Request $request)
    {
        Log::debug("[START] MonthlyReportController::exportCsv()");

        $type = $request->input('type');
        Log::debug("[INPUT] type = ". $type);

        if ($type == "stock")
        {
            return $this->exportStockCsv($request);
        }
        elseif ($type == "sales")
        {
            return $this->exportStockCsv($request);
        }
        elseif ($type == "purchases")
        {
            return $this->exportPurchasesCsv($request);
        }
        elseif ($type == "expenses")
        {
            return $this->exportExpensesCsv($request);            
        }
        
    }



    private function exportStockCsv(Request $request)
    {
    	Log::debug("[START] MonthlyReportController::exportStockCsv()");

        $endDateYYYYMM = $request->input('month');//format "yyyy-mm"

        Log::debug("month = " . $endDateYYYYMM);

        $endDay = "20";
        $endDateYYYYMMDD = $endDateYYYYMM ."-".$endDay;//format "yyyy-mm-dd"
        $strEndDateYYYYMMDD = date('Ymd',strtotime($endDateYYYYMMDD)); //format "yyyymmdd"

        $startDateYYYYMM = date('Y-m',strtotime('-1 month',strtotime($endDateYYYYMMDD)));//format "yyyy-mm"
        $startDay = "21";
        $startDateYYYYMMDD = $startDateYYYYMM ."-".$startDay;//format "yyyy-mm-dd"
        $strStartDateYYYYMMDD = date('Ymd',strtotime($startDateYYYYMMDD)); //format "yyyymmdd"

        $beforeDay = "20";
        $beforeDateYYYYMMDD = $startDateYYYYMM ."-".$beforeDay;//format "yyyy-mm-dd"
        $strBeforeDateYYYYMMDD = date('Ymd',strtotime($beforeDateYYYYMMDD)); //format "yyyymmdd"

        $endDate = explode("-",$endDateYYYYMM);
        $endMonth = $endDate[1];

        $startDate = explode("-",$startDateYYYYMM);
        $startMonth = $startDate[1];


        Log::debug("strBeforeDateYYYYMMDD = " . $strBeforeDateYYYYMMDD);
        Log::debug("strStartDateYYYYMMDD = " . $strStartDateYYYYMMDD);
        Log::debug("strEndDateYYYYMMDD = " . $strEndDateYYYYMMDD);
        Log::debug("startMonth = " . $startMonth);
        Log::debug("endMonth = " . $endMonth);


        $officeCode = $request->input('office');
        Log::debug("office = " . $officeCode);

        $departmentCode = $request->input('department');
        Log::debug("department = " . $departmentCode);

        $previousStockValue = 0;
        $currentStockValue  = 0;

        [$previousStockValue,$currentStockValue] = OfficeStockService::getStockValue($strBeforeDateYYYYMMDD, $strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $officeCode, $departmentCode);


        Log::debug("[END] MonthlyReportController::exportStockCsv()");
		return OfficeStockService::exportCsv($previousStockValue, $currentStockValue, $startMonth, $endMonth, $strStartDateYYYYMMDD, $strEndDateYYYYMMDD);
        
    }





    private function exportSalesCsv(Request $request)
    {

        return 1; 
        
    }





    private function exportPurchasesCsv(Request $request)
    {
        Log::debug("[START] MonthlyReportController::exportPurchasesCsv()");

        $endDateYYYYMM = $request->input('month');//format "yyyy-mm"

        Log::debug("month = " . $endDateYYYYMM);

        $endDay = "20";
        $endDateYYYYMMDD = $endDateYYYYMM ."-".$endDay;//format "yyyy-mm-dd"
        $strEndDateYYYYMMDD = date('Ymd',strtotime($endDateYYYYMMDD)); //format "yyyymmdd"

        $startDateYYYYMM = date('Y-m',strtotime('-1 month',strtotime($endDateYYYYMMDD)));//format "yyyy-mm"
        $startDay = "21";
        $startDateYYYYMMDD = $startDateYYYYMM ."-".$startDay;//format "yyyy-mm-dd"
        $strStartDateYYYYMMDD = date('Ymd',strtotime($startDateYYYYMMDD)); //format "yyyymmdd"

        $endDate = explode("-",$endDateYYYYMM);
        $endMonth = $endDate[1];

        $startDate = explode("-",$startDateYYYYMM);
        $startMonth = $startDate[1];

        $strKbn = "2";
        $departmentCode = "05";

        Log::debug("strStartDateYYYYMMDD = " . $strStartDateYYYYMMDD);
        Log::debug("strEndDateYYYYMMDD = " . $strEndDateYYYYMMDD);
        Log::debug("startMonth = " . $startMonth);
        Log::debug("endMonth = " . $endMonth);

        $arrayPurchasesValue = array();
        $arrayPurchasesValue = OfficePurchasesService::getPurchasesValue($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $strKbn, $departmentCode);

        Log::debug("[END] MonthlyReportController::exportPurchasesCsv()");
        return OfficePurchasesService::exportCsv($arrayPurchasesValue, $startMonth, $endMonth, $strStartDateYYYYMMDD, $strEndDateYYYYMMDD);
        
    }





    private function exportExpensesCsv(Request $request)
    {
        Log::debug("[START] MonthlyReportController::exportExpensesCsv()");

        $endDateYYYYMM = $request->input('month');//format "yyyy-mm"

        Log::debug("month = " . $endDateYYYYMM);

        $endDay = "20";
        $endDateYYYYMMDD = $endDateYYYYMM ."-".$endDay;//format "yyyy-mm-dd"
        $strEndDateYYYYMMDD = date('Ymd',strtotime($endDateYYYYMMDD)); //format "yyyymmdd"

        $startDateYYYYMM = date('Y-m',strtotime('-1 month',strtotime($endDateYYYYMMDD)));//format "yyyy-mm"
        $startDay = "21";
        $startDateYYYYMMDD = $startDateYYYYMM ."-".$startDay;//format "yyyy-mm-dd"
        $strStartDateYYYYMMDD = date('Ymd',strtotime($startDateYYYYMMDD)); //format "yyyymmdd"

        $endDate = explode("-",$endDateYYYYMM);
        $endMonth = $endDate[1];

        $startDate = explode("-",$startDateYYYYMM);
        $startMonth = $startDate[1];

        Log::debug("strStartDateYYYYMMDD = " . $strStartDateYYYYMMDD);
        Log::debug("strEndDateYYYYMMDD = " . $strEndDateYYYYMMDD);
        Log::debug("startMonth = " . $startMonth);
        Log::debug("endMonth = " . $endMonth);

        $officeCode = $request->input('officeExpenses');
        Log::debug("officeExpenses = " . $officeCode);

        $departmentCode = $request->input('departmentExpenses');
        Log::debug("departmentExpenses = " . $departmentCode);

        $purchasesValue = 0;
        $expenses  = 0;

        [$purchasesValue, $expenses] = OfficeExpensesService::getExpensesValue($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $officeCode, $departmentCode);


        Log::debug("[END] MonthlyReportController::exportExpensesCsv()");
        return OfficeExpensesService::exportCsv($officeCode, $purchasesValue, $expenses, $startMonth, $endMonth, $strStartDateYYYYMMDD, $strEndDateYYYYMMDD);
    }

}
