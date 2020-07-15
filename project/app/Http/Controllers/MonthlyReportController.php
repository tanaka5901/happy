<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use BranchStockService;
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

        $type = $request->input('type');

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
            return $this->exportStockCsv($request);
        }
        else
        {
            return $this->exportStockCsv($request);            
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

        $officeCode = $request->input('office');
        Log::debug("office = " . $officeCode);

        $departmentCode = $request->input('department');
        Log::debug("department = " . $departmentCode);

        $previousStockValue = 0;
        $currentStockValue  = 0;

        [$previousStockValue,$currentStockValue] = BranchStockService::getStockValue($strBeforeDateYYYYMMDD, $strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $officeCode, $departmentCode);


        Log::debug("[END] MonthlyReportController::exportStockCsv()");
		return BranchStockService::exportCsv($previousStockValue, $currentStockValue, $startMonth, $startMonth, $endMonth, $strStartDateYYYYMMDD, $strEndDateYYYYMMDD);
        
    }





    public function exportSalesCsv(Request $request)
    {

        return 1; 
        
    }





    public function exportPurchasesCsv(Request $request)
    {
        return 1; 
    }





    public function exportExpensesCsv(Request $request)
    {
        return 1; 
    }





}
