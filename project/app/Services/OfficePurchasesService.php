<?php

namespace App\Services;
use DB;
use Log;

class OfficePurchasesService
{

    public function exportCsv($arrayPurchasesValue, $startMonth, $endMonth, $strStartDateYYYYMMDD, $strEndDateYYYYMMDD)
    {

    	Log::debug("[START] OfficePurchasesService::exportCsv()");
    	Log::debug("[INPUT] startMonth = " . $startMonth);
    	Log::debug("[INPUT] endMonth = " . $endMonth);
    	Log::debug("[INPUT] strStartDateYYYYMMDD = " . $strStartDateYYYYMMDD);
    	Log::debug("[INPUT] strEndDateYYYYMMDD = " . $strEndDateYYYYMMDD);

		// CSVファイル名
    	$currentDate = date("Ymd");
        $file_name = $currentDate."-"."purchases"
        			."-".$strStartDateYYYYMMDD."-".$strEndDateYYYYMMDD.".csv";


        $headers = [ //ヘッダー情報
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename='.$file_name,
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];



        $callback = function () use ($arrayPurchasesValue, $startMonth, $endMonth, $strStartDateYYYYMMDD, $strEndDateYYYYMMDD) 
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

            $count = 0;

            foreach ($arrayPurchasesValue as $key => $value) 
            {
		    	Log::debug("arrayPurchasesValue[officeCode] = " . $key);
		    	Log::debug("arrayPurchasesValue[purchasesValue] = " . $value);

            	$subAccountingItem = 0;
            	if($key == "0011")//狭山
            	{
            		$subAccountingItem = 1130;
            	}
            	elseif($key == "0017")//高松
            	{
            		$subAccountingItem = 1250;
            	}
            	elseif($key =="0023")//佐世保
            	{
            		$subAccountingItem = 1400;
            	}
            	elseif($key =="0027")//熊本
            	{
            		$subAccountingItem = 1510;
            	}
            	else
            	{
            		continue;
            	}

				$csv = [
	                0,
	                0,
	                3,
	                0,
	                0,
	                $strEndDateYYYYMMDD,
	                101,
	                $endMonth."月度　直営仕入振替",
	                ++$count,
	                "",
	                "",
	                1495,
	                "",
	                $subAccountingItem,
	                "",
	                intval($value),
	                "",
	                "",
	                "",
	                "",
	                "",
	                "",
	                "",
	                "",
	                8221,
	                "",
	                "",
	                "",
	                intval($value),
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
			}

            fclose($createCsvFile); //ファイル閉じる
        };
        
    	Log::debug("[END] OfficePurchasesService::exportCsv()");
        return response()->stream($callback, 200, $headers); //ここで実行
    }


    public function getOfficePurchasesValue($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $officeCode, $departmentCode)
    {
    	Log::debug("[START] OfficePurchasesService::getOfficePurchasesValue()");

    	$purchasesValue = $this->calculateOfficePurchasesValue($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $officeCode, $departmentCode);

    	Log::debug("[END] OfficePurchasesService::getOfficePurchasesValue()");
        return $purchasesValue;
    }


    private function calculateOfficePurchasesValue($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $officeCode, $strBu_Cd)
    {
    	Log::debug("[START] OfficePurchasesService::calculateOfficePurchasesValue()");

        $strSQL = "SELECT MISE_KBN, HOUJIN_KBN, JIGYO_CD, JIGYO_RMEI AS JIGYO_MEI, SIMEBI_MATU, SYOHIZEI_KBN "
        		."FROM TM_BS102 "
        		."WHERE JIGYO_CD ='" . $officeCode . "' "
        		."AND LEFT(JIGYO_CD, 2) <> '03' "
        		."AND (HEISABI IS NULL OR HEISABI = '') "
        		."ORDER BY JIGYO_CD ASC ";

        $results = DB::select($strSQL);

        $arrayPurchasesValue = $this->calculatePurchasesValueByBranch($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $strBu_Cd, $results);

        $purchasesValue = 0;
        foreach ($arrayPurchasesValue as $key => $value) 
        {
        	$purchasesValue = $value;
        	Log::debug("purchasesValue = " . $purchasesValue);
        }

    	Log::debug("[END] OfficePurchasesService::calculateOfficePurchasesValue()");
    	return $purchasesValue;
    }



    public function getPurchasesValue($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $strKbn, $departmentCode)
    {
    	Log::debug("[START] OfficePurchasesService::getPurchasesValue()");

    	$arrayPurchasesValue = $this->calculatePurchasesValue($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $strKbn, $departmentCode);

    	Log::debug("[END] OfficePurchasesService::getPurchasesValue()");
        return $arrayPurchasesValue;
    }



    private function calculatePurchasesValue($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $strKbn, $strBu_Cd)
    {
    	Log::debug("[START] OfficePurchasesService::calculatePurchasesValue()");

        //'*******************************************************************************
        //'   直営店/代理店部分出力処理
        //'*******************************************************************************

		/*
	    '=============================================================
        '   出力事業所取得処理
        '=============================================================
        '----------------------------------------
        '   取得カラム：事業所コード、事業所名
        '              末日フラグ、消費税区分
        '   対象テーブル：事業所マスターテーブル
        '   条件：閉鎖していないもの
        '        臨時直営店以外のもの
        '        事業所コード順
        '----------------------------------------
        '   2008/12/22 消費税計算対応
        '   直営店㈱の場合は消費税を0とするために[MISE_KBN][HOUJIN_KBN]を取得
        */
        
        $strSQL = "SELECT MISE_KBN, HOUJIN_KBN, JIGYO_CD, JIGYO_RMEI AS JIGYO_MEI, SIMEBI_MATU, SYOHIZEI_KBN "
        		."FROM TM_BS102 "
        		." WHERE MISE_KBN = '02' AND HOUJIN_KBN = '01' ";
            
		//' 2013.12.18 h.azuma
		//' 総務権限以上閉鎖日編集対応
		//' 2015.7.2 h.azuma
		//' 代理店増加に伴うコードの扱い不具合対応(上二桁目'3'→上二桁'03')
        $strSQL = $strSQL . "AND LEFT(JIGYO_CD, 2) <> '03' "
        		."AND (HEISABI IS NULL OR HEISABI = '') "
        		."ORDER BY JIGYO_CD ASC ";

        $results = DB::select($strSQL);

        $arrayPurchasesValue = $this->calculatePurchasesValueByBranch($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $strBu_Cd, $results);

    	Log::debug("[END] OfficePurchasesService::calculatePurchasesValue()");
    	return $arrayPurchasesValue;
    }




    private function calculatePurchasesValueByBranch($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $strBu_Cd, $results)
    {
    	Log::debug("[START] OfficePurchasesService::calculatePurchasesValueByBranch()");
    	Log::debug("[INPUT] strStartDateYYYYMMDD = " . $strStartDateYYYYMMDD);
    	Log::debug("[INPUT] strEndDateYYYYMMDD = " . $strEndDateYYYYMMDD);
    	Log::debug("[INPUT] strBu_Cd = " . $strBu_Cd);
    	Log::debug("[INPUT] count(results) = " . count($results));

    	$strViewType = "0";
 		$count = 0;
 		$arrayPurchasesValue = array();

    	foreach ($results as $result) 
    	{
	    	Log::debug("count = " . (++$count));

	    	Log::debug("JIGYO_CD = " . $result->JIGYO_CD);
	    	Log::debug("JIGYO_MEI = " . $result->JIGYO_MEI);

	        $resultJigyo_Cd = $result->JIGYO_CD;
	        $resultSimebi_Matu = $result->SIMEBI_MATU;
	        $resultMise_Kbn = $result->MISE_KBN;
			$resultHoujin_Kbn = $result->HOUJIN_KBN;


	        //'=================================================================================
	        //'   商品仕入テーブルから総売上額を取得
	        //'=================================================================================
	        //CO_HONSYA_CD = "0001"
	        $lUriage = $this->get207Uriage($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $resultJigyo_Cd, "");

	    	$strUriageDispFlg = $lUriage == 0? 0 : 1;

	    	Log::debug("lUriage = " . $lUriage);
	        

	        //'=================================================================================
	        //'   商品仕入テーブルから総返品額を取得
	        //'=================================================================================
			//CO_HONSYA_CD = "0001"
	        $lHenpin = $this->get207Henpin($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $resultJigyo_Cd, "");

	    	$strHenpinDispFlg = $lHenpin == 0? 0 : 1;

	    	Log::debug("lHenpin = " . $lHenpin);

	        //'=================================================================================
	        //'   商品仕入テーブルから消費税額取得処理
	        //'=================================================================================
	        //CO_FUND_YMD20080921 = "20080921"
	        //CO_CODE_MATUJITU = "01"
	        if (("20080921" <= $strStartDateYYYYMMDD) or ($strViewType == "1" and $resultSimebi_Matu == "01"))
	        {
			    $lSyohizei = $this->get207Syohizei($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $resultJigyo_Cd, $strBu_Cd);
	        }
	        else
	        {
	            if ($strViewType == "0" and $resultSimebi_Matu == "01")
	            {
	                $sYm = substr($strStartDateYYYYMMDD, 0, -2);
	                $eYm = substr($strEndDateYYYYMMDD, 0, -2);
	            }
	            else
	            {
	                $sYm = substr($strEndDateYYYYMMDD, 0, -2);
	                $eYm = substr($strEndDateYYYYMMDD, 0, -2);
	            }
	  
	            $lSyohizei = $this->get121Syohizei($sYm, $eYm, $resultJigyo_Cd);
	        }
	                            
	        
	        //'   2008/12/22
	        //'   消費税計算対応
	        //'   直営店㈱の場合は消費税は込みとして計算されている

	        //CO_CODE_TYOKU_IPPAN = "02"
	        //CO_CODE_KABU = "01"
	        if ($resultMise_Kbn == "02" and $resultHoujin_Kbn == "01")
	        {
	        	$lSyohizei = 0;
	        }

	    	$lTaxDispFlg = $lSyohizei == 0? 0 : 1;

	    	Log::debug("lSyohizei = " . $lSyohizei);


	        //'------------------------------------
	        //'   純売上額計算
	        //'------------------------------------
	        $lJunUriage = $lUriage + $lHenpin;
	    	Log::debug("lJunUriage = lUriage + lHenpin = " . $lJunUriage);

            //'------------------------------------
            //'   当月売掛残高計算
            //'------------------------------------
            //$lTouUrikakeZan = $lKurikosi + $lSyohizei + $lJunUriage;

	    	Log::debug("strUriageDispFlg = " . $strUriageDispFlg);
	    	Log::debug("strHenpinDispFlg = " . $strHenpinDispFlg);
	    	Log::debug("lTaxDispFlg = " . $lTaxDispFlg);

	    	if($strUriageDispFlg == 1 or $strHenpinDispFlg == 1 or $lTaxDispFlg ==1)
    		{
		        $purchasesValue = $lSyohizei + $lJunUriage;
		    	Log::debug("purchasesValue = lSyohizei + lJunUriage = " . $purchasesValue);
		    	Log::debug("JIGYO_CD = " . $result->JIGYO_CD);
		    	Log::debug("JIGYO_MEI = " . $result->JIGYO_MEI);
		        
		        $arrayPurchasesValue[$result->JIGYO_CD] = $purchasesValue;
    		}
		}

    	Log::debug("[END] OfficePurchasesService::calculatePurchasesValueByBranch()");
    	return $arrayPurchasesValue;
    }








    private function get121Syohizei($sDay, $eDay, $JigyoCd, $BuCd)
    {
    	Log::debug("[START] OfficePurchasesService::get121Syohizei()");

    	$lSyohizei = 0;

	    //'	消費税計算額取得処理
	    $strSQL = "SELECT SUM(SYOHIZEI_SUM) AS TAX "
	    		."FROM TW_BS121 "
	    		."WHERE SIME_MTH BETWEEN '" . $sYm . "' AND '" . $eYm . "' "
	    		."AND JIGYO_CD = '" . $JigyoCd . "' ";

	    $results = DB::select($strSQL);

		if (count($results) > 0) 
		{
			$tax = $results[0]->TAX;

			if(!(empty($tax) or is_null($tax)))
			{
				$lSyohizei = $tax;
			}
		}
    	

    	Log::debug("lSyohizei = " . $lSyohizei);
    	Log::debug("[END] OfficePurchasesService::get121Syohizei()");
	    return $lSyohizei;
    }



	private function getTaxRate($pDate)
	{
    	Log::debug("[START] OfficePurchasesService::getTaxRate()");
		$taxRate = 0;
		
		//'開始日と終了日を元に消費税率抽出
		$strSQL = "SELECT ZEIRITU FROM TM_BS107 "
				."WHERE START_YMD <= '" . $pDate . "' "
				."AND (END_YMD  >= '" . $pDate . "' OR END_YMD IS NULL)";

		$results = DB::select($strSQL);

		if (count($results) > 0)
		{
			$taxRate = $results[0]->ZEIRITU;
		}

    	Log::debug("taxRate = " . $taxRate);
    	Log::debug("[END] OfficePurchasesService::getTaxRate()");
		return $taxRate;
	}
	



    private function get207Syohizei($sDay, $eDay, $JigyoCd, $BuCd)
    {
    	Log::debug("[START] OfficePurchasesService::get207Syohizei()");
	    $lSyohizei = 0;
	    //$dZeiritu = get_TaxRate($eDay);	//'	消費税率取得
    	
	    /*
	    '=================================================================
	    '	指定の範囲の売上を取得
	    '=================================================================
	    '----------------------------------------------------------
	    '	取得カラム：仕入日(207)、ページNO(215/225/207)
	    '	対象テーブル：商品仕入テーブル
	    '				 商品注文テーブル
	    '				 代理店商品注文テーブル
	    '	条件：注文日の範囲が任意のもの
	    '		 確定されているもの
	    '		 仕入元事業所コードが任意のもの
	    '	　　 注文日順
	    '----------------------------------------------------------	
	    */
	    //CO_CODE_KAKUTEI = "02"
	    $strSQL = "SELECT TB207.SIIREBI, TB225.PAGE_NO, 1 AS KBN "
	    	    ."FROM TW_HB207 TB207, TW_HB225 TB225 "
	    	    ."WHERE TB207.SIIREBI BETWEEN '" . $sDay . "' AND '" . $eDay . "' "
	    	    ."AND TB225.KAKUTEI_FLG ='02' "
	    	    ."AND TB207.SIIREMOTO_CD ='" . $JigyoCd . "' ";
    					
	    //CO_HONSYA_CD = "0001"
	    if ($JigyoCd == "0001")
	    {
		    $strSQL = $strSQL . "AND TB207.MOTOBU_CD = '" . $BuCd . "' ";					
	    }
		
	    //CO_CODE_DAIRISYOHIN_TYUMON = "02"
	    //CO_CODE_KAKUTEI = "02"
		$strSQL = $strSQL . "AND TB207.SIIRETYUMON_KBN = '02' "
				."AND TB207.TYUMON_CD = TB225.TYUMON_CD "
				."GROUP BY TB207.SIIREBI, TB225.PAGE_NO "
				."UNION ALL "
				."SELECT TB207.SIIREBI, TB215.PAGE_NO, 2 AS KBN "
				."FROM TW_HB207 TB207, TW_HB215 TB215 "
				."WHERE TB207.SIIREBI BETWEEN '" . $sDay . "' AND '" . $eDay . "' "
				."AND TB215.KAKUTEI_FLG ='02' "
				."AND TB207.SIIREMOTO_CD ='" . $JigyoCd . "' ";
    	
	    //CO_HONSYA_CD = "0001"
	    if ($JigyoCd == "0001")
	    {
		    $strSQL = $strSQL . "AND TB207.MOTOBU_CD = '" . $BuCd . "' ";					
	    }

    	//CO_CODE_SYOHIN_TYUMON = "01"		
		$strSQL = $strSQL . "AND TB207.SIIRETYUMON_KBN = '01' "
				."AND TB207.TYUMON_CD = TB215.TYUMON_CD "
				."GROUP BY TB207.SIIREBI, TB215.PAGE_NO "
				."UNION ALL "
				."SELECT TB207.SIIREBI, TB207.PAGE_NO, 3 AS KBN "
				."FROM TW_HB207 TB207 "
				."WHERE TB207.SIIREBI BETWEEN '" . $sDay . "' AND '" . $eDay . "' "
				."AND TB207.SIIREMOTO_CD ='" . $JigyoCd . "' ";
    					
	    //CO_HONSYA_CD = "0001"
	    if ($JigyoCd == "0001")
	    {
		    $strSQL = $strSQL . "AND TB207.MOTOBU_CD = '" . $BuCd . "' ";					
	    }

    			
		$strSQL = $strSQL . "AND TB207.TYUMON_CD = '' "
			    ."GROUP BY TB207.SIIREBI, TB207.PAGE_NO "
			    ."ORDER BY TB207.SIIREBI ASC ";	
    	

    	$results = DB::select($strSQL);

    	foreach ($results as $result) 
    	{
		    $lZeinuki_Sum = 0;

		    $resultKBN = $result->KBN;
		    $resultPAGE_NO = $result->PAGE_NO;
		    $resultSIIREBI = $result->SIIREBI;

		    Log::debug("resultKBN = " . $resultKBN);
		    Log::debug("resultPAGE_NO = " . $resultPAGE_NO);
		    Log::debug("resultSIIREBI = " . $resultSIIREBI);
    		
		    //'=================================================================
		    //'	指定の範囲の売上を取得
		    //'=================================================================
		    if ($resultKBN == 1)
		    {
    			Log::debug("赤黒の場合");
   
			    //'-------------------------------------------
			    //'	赤黒の場合
			    //'-------------------------------------------
		    	/* 		
			    '----------------------------------------------------------
			    '	取得カラム：税抜き額(207)
			    '	対象テーブル：商品仕入テーブル
			    '				 代理店商品注文テーブル
			    '				 商品マスターテーブル
			    '	条件：注文日が任意のもの
			    '		 確定されているもの
			    '		 仕入元事業所コードが任意のもの
			    '		 ページNOが任意のもの
			    '----------------------------------------------------------	
			    */
			    //CO_CODE_KAKUTEI = "02"
			    $strSQL = "SELECT TB207.ZEINUKI "
			    		."FROM TW_HB207 TB207, TW_HB225 TB225, TM_BS103 TB103 "
			    		."WHERE TB225.PAGE_NO = '" . $resultPAGE_NO . "' "
			    		."AND TB207.SIIREBI = '" . $resultSIIREBI . "' "
			    		."AND TB225.KAKUTEI_FLG ='02' "
			    		."AND TB207.SIIREMOTO_CD ='" . $JigyoCd . "' ";
    							
			    
			    //CO_HONSYA_CD = "0001"
			    if ($JigyoCd == "0001")
			    {
				    $strSQL = $strSQL . "AND TB207.MOTOBU_CD = '" . $BuCd . "' ";					
			    }

			    //CO_CODE_DAIRISYOHIN_TYUMON = "02"
				$strSQL = $strSQL . "AND TB207.SIIRETYUMON_KBN = '02' "
						."AND TB207.TYUMON_CD = TB225.TYUMON_CD "
						."AND TB225.SYOHIN_KBN = TB103.SYOHIN_KBN "
						."AND TB225.SYOHIN_CD =	TB103.SYOHIN_CD	";	

		    }
		    elseif ($resultKBN == 2)
		    {
    			Log::debug("注文の場合");

		    	//'-------------------------------------------
		    	//'	注文の場合
		    	//'-------------------------------------------
				/*
				'----------------------------------------------------------
			    '	取得カラム：税抜き額(207)
			    '	対象テーブル：商品仕入テーブル
			    '				 商品注文テーブル
			    '				 商品マスターテーブル
			    '	条件：注文日が任意のもの
			    '		 確定されているもの
			    '		 仕入元事業所コードが任意のもの
			    '		 ページNOが任意のもの
			    '----------------------------------------------------------	
			    */

			    //CO_CODE_KAKUTEI = "02"
			    $strSQL = "SELECT TB207.ZEINUKI "
			    		."FROM TW_HB207 TB207, TW_HB215 TB215, TM_BS103 TB103 "
			    		."WHERE TB215.PAGE_NO = '" . $resultPAGE_NO . "' "
			    		."AND TB207.SIIREBI = '" . $resultSIIREBI . "' "
			    		."AND TB215.KAKUTEI_FLG ='02' "
			    		."AND TB207.SIIREMOTO_CD ='" . $JigyoCd . "' ";
    							
			    //CO_HONSYA_CD = "0001"
			    if ($JigyoCd == "0001")
			    {
				    $strSQL = $strSQL . "AND TB207.MOTOBU_CD = '" . $BuCd . "' ";					
			    }
    					
			    //CO_CODE_DAIRISYOHIN_TYUMON = "02"
				$strSQL = $strSQL . "AND TB207.SIIRETYUMON_KBN = '02' "
						."AND TB207.TYUMON_CD = TB215.TYUMON_CD "
						."AND TB215.SYOHIN_KBN = TB103.SYOHIN_KBN "
						."AND TB215.SYOHIN_CD =	TB103.SYOHIN_CD	";    							

		    }
    		else
    		{
    			Log::debug("直送の場合");
			    //'-------------------------------------------
			    //'	直送の場合
			    //'-------------------------------------------
				/*
				'----------------------------------------------------------
			    '	取得カラム：税抜き額(207)
			    '	対象テーブル：商品仕入テーブル
			    '				 商品マスターテーブル
			    '	条件：注文日が任意のもの
			    '		 確定されているもの
			    '		 仕入元事業所コードが任意のもの
			    '		 ページNOが任意のもの
			    '----------------------------------------------------------	
			    */
			    
			    $strSQL = "SELECT TB207.ZEINUKI "
			    		."FROM TW_HB207 TB207, TM_BS103 TB103 "
			    		."WHERE TB207.PAGE_NO = '" . $resultPAGE_NO . "' "
			    		."AND TB207.SIIREBI = '" . $resultSIIREBI . "' "
			    		."AND TB207.SIIREMOTO_CD ='" . $JigyoCd ."' ";

    							
			    //CO_HONSYA_CD = "0001"
			    if ($JigyoCd == "0001")
			    {
				    $strSQL = $strSQL . "AND TB207.MOTOBU_CD = '" . $BuCd . "' ";					
			    }
    			
			    $strSQL = $strSQL . " AND TB207.TYUMON_CD = '' "
			    		."AND TB207.SYOHIN_KBN = TB103.SYOHIN_KBN "
			    		."AND TB207.SYOHIN_CD =	TB103.SYOHIN_CD	";
    		}
    		
    		$results207A = DB::select($strSQL);
    		
    		foreach ($results207A as $result207A)
    		{
    			$lZeinuki_Sum = $lZeinuki_Sum + $results207A->ZEINUKI;
    		}

		    
		    //'	消費税合計計算
			//'	2014.4.8 消費税計算ロジック修正
			$dZeiritu = $this->getTaxRate($resultSIIREBI);
		    $lSyohizei = $lSyohizei + intval($lZeinuki_Sum * $dZeiritu);

    	}

    	Log::debug("lSyohizei = " . $lSyohizei);
    	Log::debug("[END] OfficePurchasesService::get207Syohizei()");    	
        return $lSyohizei;
    }






    private function get207Henpin($sDay, $eDay, $JigyoCd, $BuCd)
    {
    	Log::debug("[START] OfficePurchasesService::get207Henpin()");    	
        $summary = 0;
        
	    //'=================================================================================
	    //'	商品仕入テーブルから返品額を取得
	    //'=================================================================================
	    //CO_HONSYA_CD = "0001"
	    //CO_SYOKN_CD = "02"
	    $strSQL = "SELECT SUM(ZEINUKI) AS HENPINGAKU "
	    	    ."FROM TW_HB207 "
	    	    ."WHERE SIIREBI BETWEEN '" . $sDay . "' AND '" . $eDay . "' "
	    	    ."AND SIIRESAKI_CD = '0001' "
	    	    ."AND SIIREMOTO_CD ='" . $JigyoCd . "' "
	    	    ."AND SAKIBU_CD = '02' "
	    	    ."AND SYOHINNUM < 0 ";


		if ($BuCd <> "")
		{
			$strSQL = $strSQL . "AND MOTOBU_CD = '" . $BuCd . "' ";
		}

		$results = DB::select($strSQL);

		if (count($results) > 0) 
		{
			$henpingaku = $results[0]->HENPINGAKU;
			
			if(!(empty($henpingaku) or is_null($henpingaku)))
			{
				$summary = $henpingaku;	
			}
		}


    	Log::debug("summary = " . $summary);    	
    	Log::debug("[END] OfficePurchasesService::get207Henpin()");
        return $summary;
    }




    private function get207Uriage($sDay, $eDay, $JigyoCd, $BuCd)
    {
    	Log::debug("[START] OfficePurchasesService::get207Uriage()");
        
        $summary = 0;
        
	    //'=================================================================================
	    //'	商品仕入テーブルから総売上額を取得
	    //'=================================================================================
	    //CO_HONSYA_CD = "0001"
	    //CO_SYOKN_CD = "02"
	    $strSQL = "SELECT SUM(ZEINUKI) AS URIAGEGAKU "
	    	    ."FROM TW_HB207 "
	    	    ."WHERE SIIREBI BETWEEN '" . $sDay . "' AND '" . $eDay	. "' "
	    	    ."AND SIIRESAKI_CD = '0001' "
	    	    ."AND SIIREMOTO_CD ='" . $JigyoCd ."' "
			    ."AND SAKIBU_CD = '02' "
			    ."AND SYOHINNUM >= 0 ";

		if ($BuCd <> "")
		{
			$strSQL = $strSQL . "AND MOTOBU_CD = '" . $BuCd . "' ";
		}

		$results = DB::select($strSQL);

		if (count($results) > 0) 
		{
			$uriagegaku = $results[0]->URIAGEGAKU;
			if(!(empty($uriagegaku) or is_null($uriagegaku)))
			{
				$summary = $uriagegaku;	
			}
		}

    	Log::debug("summary = " . $summary);    	
    	Log::debug("[END] OfficePurchasesService::get207Uriage()");
        return $summary;
    }

}