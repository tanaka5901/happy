<?php

namespace App\Services;
use DB;
use Log;
use OfficePurchasesService,OfficeStockService;


class OfficeExpensesService
{

    public function exportCsv($officeCode, $purchasesValue, $expenses, $startMonth, $endMonth, $strStartDateYYYYMMDD, $strEndDateYYYYMMDD)
    {

    	Log::debug("[START] OfficeExpensesService::exportCsv()");
    	Log::debug("[INPUT] startMonth = " . $startMonth);
    	Log::debug("[INPUT] endMonth = " . $endMonth);
    	Log::debug("[INPUT] strStartDateYYYYMMDD = " . $strStartDateYYYYMMDD);
    	Log::debug("[INPUT] strEndDateYYYYMMDD = " . $strEndDateYYYYMMDD);

		// CSVファイル名
    	$currentDate = date("Ymd");
        $file_name = $currentDate."-"."expenses"
        			."-".$strStartDateYYYYMMDD."-".$strEndDateYYYYMMDD.".csv";


        $headers = [ //ヘッダー情報
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename='.$file_name,
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];



        $callback = function () use ($officeCode, $purchasesValue, $expenses, $startMonth, $endMonth, $strStartDateYYYYMMDD, $strEndDateYYYYMMDD) 
        {
            
            $createCsvFile = fopen('php://output', 'w'); //ファイル作成
            
             $columns = [ //1行目の情報
                "月種別",
                "種類",
                "形式",
                "作成方法",
                "付箋",
                "伝票日付",
                "伝票番号",
                "伝票摘要",
                "枝番",
                "借方部門",
                "借方部門名",
                "借方科目",
                "借方科目名",
                "借方補助",
                "借方補助科目名",
                "借方金額",
                "借方消費税コード",
                "借方消費税業種",
                "借方消費税税率",
                "借方資金区分",
                "借方任意項目１",
                "借方任意項目２",
                "貸方部門",
                "貸方部門名",
                "貸方科目",
                "貸方科目名",
                "貸方補助",
                "貸方補助科目名",
                "貸方金額",
                "貸方消費税コード",
                "貸方消費税業種",
                "貸方消費税税率",
                "貸方資金区分",
                "貸方任意項目１",
                "貸方任意項目２",
                "摘要",
                "期日",
                "証番号",
                "入力マシン",
                "入力ユーザ",
                "入力アプリ",
                "入力会社",
                "入力日付",
            ]; 

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $columns); //文字化け対策    
            fputcsv($createCsvFile, $columns); //1行目の情報を追記

        	$subAccountingItem = 0;
        	if($officeCode == "0011")//狭山
        	{
        		$slipNo = 102;
        	}
        	elseif($officeCode == "0017")//高松
        	{
        		$slipNo = 103;
        	}
        	elseif($officeCode =="0023")//佐世保
        	{
        		$slipNo = 104;
        	}
        	elseif($officeCode =="0027")//熊本
        	{
        		$slipNo = 105;
        	}
        	elseif($officeCode =="0050")//福岡
        	{
        		$slipNo = 106;
        	}
        	else
        	{
        		//TODO:エラー処理
        		return;
        	}

			$csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo,
                $endMonth."月度　直営仕入振替",
                1,
                "",
                "",
                8221,
                "",
                "",
                "",
                intval($purchasesValue - $dGenkaSum),
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                4511,
                "",
                "",
                "",
                intval($purchasesValue),
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
            ];

			$csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo,
                $endMonth."月度　直営仕入振替　\nエステ業務使用商品",
                2,
                "",
                "",
                8490,
                "",
                "",
                "",
                intval($expenses),
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            fputcsv($createCsvFile, $csv); //ファイルに追記する			

            fclose($createCsvFile); //ファイル閉じる
        };
        
    	Log::debug("[END] OfficeExpensesService::exportCsv()");
        return response()->stream($callback, 200, $headers); //ここで実行

	}


	public function getExpensesValue($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $officeCode, $departmentCode)
    {
		Log::debug("[START] OfficeExpensesService::getExpensesValue()");

	   	$purchasesValue = 0;

	   	$purchasesValue = OfficePurchasesService::getOfficePurchasesValue($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $officeCode, $departmentCode);

        if ($officeCode == "0011") //狭山:エステサンプル
        {
        	$strSalesPersonCode = "0301010011999999";
        }
        elseif ($officeCode == "0017") //高松:エステサンプル
        {
        	$strSalesPersonCode = "0301010017999999";
        }
        elseif ($officeCode == "0023") //佐世保:エステサンプル
        {
	    	$strSalesPersonCode = "0708240023999995";
        }
        elseif ($officeCode == "0027") //熊本:エステサンプル
        {
        	$strSalesPersonCode = "1809210027999999";
        }
        elseif ($officeCode == "0050") //福岡：その他訪販
        {
            $strSalesPersonCode = "0603280050000006";
        }
	    
	    $strZappinCode = "01";

	    $expenses = $this->calculateSalesPersonExpensesValue($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $officeCode, $strSalesPersonCode, $strZappinCode);

		Log::debug("purchasesValue = " . $purchasesValue);
		Log::debug("expenses = " . $expenses);
		Log::debug("[END] OfficeExpensesService::getExpensesValue()");
	    return [$purchasesValue, $expenses];
    }


    private function calculateSalesPersonExpensesValue($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $strJigyo_Cd, $strHeadTantou_Cd, $strZappin_Cd)
    {
		Log::debug("[START] OfficeExpensesService::calculateSalesPersonExpensesValue()");

    	/*
    	'=============================================================
		'	商品売上テーブルから指定の範囲の売上を取得
		'=============================================================
		'----------------------------------------------------
		'	取得カラム：商品区分コード(204)、商品コード(204)
		'			   商品名(103)
		'	対象テーブル：商品売上テーブル
		'				 商品マスターテーブル
		'	条件：販売担当者が任意のもの
		'		 売上日の範囲が任意のもの
		'		 対顧客売上のもの
		'	     集計期間1と集計期間2を結合する
		'----------------------------------------------------
		*/
		$strSQL = "SELECT TB204.SYOHIN_KBN, TB204.SYOHIN_CD, TB103.SYOHIN_MEI "
				."FROM TW_HB204 TB204, TM_BS103 TB103 "
				."WHERE TB204.URIAGEBI BETWEEN '" . $strStartDateYYYYMMDD . "' AND '" . $strEndDateYYYYMMDD	. "' "
				."AND TB204.TANTOU_CD = '" . $strHeadTantou_Cd . "' ";
		
		if ($strZappin_Cd == "01")
		{
			//CO_CODE_ZAPPIN = "96"
			$strSQL = $strSQL . " AND TB204.BUNBETU_CD NOT IN ('96')";
		}
		elseif($strZappin_Cd == "02")
		{
			//CO_CODE_ZAPPIN = "96"
			$strSQL = $strSQL & " AND TB204.BUNBETU_CD = '96'";
		}
		
		//CO_CODE_KOKYAKU_URIAGE = "01"
		$strSQL = $strSQL . "AND TB204.URIAGE_KBN = '01' "
				."AND TB204.SYOHIN_KBN = TB103.SYOHIN_KBN "
				."AND TB204.SYOHIN_CD = TB103.SYOHIN_CD "
				."GROUP BY TB204.SYOHIN_KBN, TB204.SYOHIN_CD, TB103.SYOHIN_MEI "
				."ORDER BY TB204.SYOHIN_KBN, TB204.SYOHIN_CD ";										
		
		$results = DB::select($strSQL);

		$dGenka_Sum1 = $this->calculateSalesPersonExpensesValueByProduct($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $strJigyo_Cd, $strHeadTantou_Cd, $results);

		Log::debug("dGenka_Sum1 = " . $dGenka_Sum1);
		Log::debug("[END] OfficeExpensesService::calculateSalesPersonExpensesValue()");
		return $dGenka_Sum1;
    }



    private function calculateSalesPersonExpensesValueByProduct($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $strJigyo_Cd, $strHeadTantou_Cd, $results)
    {
		Log::debug("[START] OfficeExpensesService::calculateSalesPersonExpensesValueByProduct()");
    	$dGenka_Sum1 = 0;

    	foreach ($results as $result) 
    	{
    		$resultSYOHIN_KBN = $result->SYOHIN_KBN;
    		$resultSYOHIN_CD = $result->SYOHIN_CD;
			Log::debug("resultSYOHIN_KBN = " . $resultSYOHIN_KBN);
			Log::debug("resultSYOHIN_CD = " . $resultSYOHIN_CD);

    		/*
			'=============================================================
			'	商品売上テーブルから指定の範囲の売上を取得
			'=============================================================
			'--------------------------------------------------
			'	取得カラム：個数合計、税抜き合計額
			'	対象テーブル：商品売上テーブル
			'				 商品マスターテーブル
			'	条件：商品区分コードが任意のもの
			'		 商品コードが任意のもの
			'		 売上日の範囲が任意のもの
			'		 販売担当者が任意のもの
			'		 対顧客売上のもの
			'--------------------------------------------------
			*/
			//CO_CODE_KOKYAKU_URIAGE = "01"
			$strSQL = "SELECT SYOHINNUM "
					."FROM TW_HB204 "
					."WHERE URIAGEBI BETWEEN '" . $strStartDateYYYYMMDD . "' AND '" . $strEndDateYYYYMMDD . "' "
					."AND SYOHIN_KBN = '" . $resultSYOHIN_KBN . "' "
					."AND SYOHIN_CD = '" . $resultSYOHIN_CD . "' "
					."AND TANTOU_CD = '" . $strHeadTantou_Cd . "' "
					."AND URIAGE_KBN = '01' ";

			$resultsProduct = DB::select($strSQL);
			
			$dKosuu1 = 0;
			foreach($resultsProduct as $resultProduct)
			{
				$dKosuu1 = $dKosuu1 + $resultProduct->SYOHINNUM;
			}

			
			//'-------------------------------------------
			//'	原価取得処理
			//'-------------------------------------------
			$dGenka1 = OfficeStockService::getGenka($strJigyo_Cd, $resultSYOHIN_KBN ,$resultSYOHIN_CD);


			if ($dGenka1 == "")
			{
				//TODO: エラー処理
				continue;
			}

			if ($dGenka1 * $dKosuu1 < 0)
			{
				$dGenka_Num1 = intval(($dGenka1 * $dKosuu1 * 100) - 0.5) / 100;
			}
			else
			{
				$dGenka_Num1 = intval(($dGenka1 * $dKosuu1 * 100) + 0.5) / 100;
			}

			Log::debug("dKosuu1 = " . $dKosuu1);
			Log::debug("dGenka1 = " . $dGenka1);			
			Log::debug("dGenka_Num1 = " . $dGenka_Num1);
		
			//'	原価合計
			$dGenka_Sum1 = $dGenka_Sum1 + $dGenka_Num1;			
    	}

		Log::debug("dGenka_Sum1 = " . $dGenka_Sum1);
		Log::debug("[END] OfficeExpensesService::calculateSalesPersonExpensesValueByProduct()");
    	return $dGenka_Sum1;
    }

}