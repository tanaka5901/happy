<?php

namespace App\Services;
use DB;
use Log;

class OfficeStockService
{
    public function exportCsv($previousStockValue, $currentStockValue, $startMonth, $endMonth, $strStartDateYYYYMMDD, $strEndDateYYYYMMDD)
    {

    	Log::debug("[START] OfficeStockService::exportCsv()");
    	Log::debug("[INPUT] previousStockValue = " . $previousStockValue);
    	Log::debug("[INPUT] currentStockValue = " . $currentStockValue);
    	Log::debug("[INPUT] startMonth = " . $startMonth);
    	Log::debug("[INPUT] endMonth = " . $endMonth);
    	Log::debug("[INPUT] strStartDateYYYYMMDD = " . $strStartDateYYYYMMDD);
    	Log::debug("[INPUT] strEndDateYYYYMMDD = " . $strEndDateYYYYMMDD);

		// CSVファイル名
    	$currentDate = date("Ymd");
        $file_name = $currentDate."-"."stock"
        			."-".$strStartDateYYYYMMDD."-".$strEndDateYYYYMMDD.".csv";


        $headers = [ //ヘッダー情報
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename='.$file_name,
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($previousStockValue, $currentStockValue, $startMonth, $endMonth, $strStartDateYYYYMMDD, $strEndDateYYYYMMDD) 
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
        
            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                100,
                $startMonth."月度　エステ（サロン）在庫計上",
                1,
                "",
                "",
                8240,
                "",
                "",
                "",
                intval($previousStockValue),
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                1215,
                "",
                "",
                "",
                intval($previousStockValue),
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

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                100,
                $endMonth."月度　エステ（サロン）在庫計上",
                2,
                "",
                "",
                1215,
                "",
                "",
                "",
                intval($currentStockValue),
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                8240,
                "",
                "",
                "",
                intval($currentStockValue),
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
        
    	Log::debug("[END] OfficeStockService::exportCsv()");
        return response()->stream($callback, 200, $headers); //ここで実行

    }





    public function getStockValue($strBeforeDateYYYYMMDD, $strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $officeCode, $departmentCode)
    {
    	Log::debug("[START] OfficeStockService::getStockValue()");

    	[$previousStockValue,$currentStockValue] = $this->calculateStockValue($strBeforeDateYYYYMMDD, $strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $officeCode, $departmentCode);

    	Log::debug("[END] OfficeStockService::getStockValue()");
        return [$previousStockValue,$currentStockValue];
    }




    private function calculateStockValue($strBeforeDateYYYYMMDD, $strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $strJigyo_Cd, $strBu_Cd)
    {
    	Log::debug("[START] OfficeStockService::calculateStockValue()");

    	if ($strBu_Cd <> "00") {
		//'-------------------------------------------------------------------
		//'	[全部]が選択されていないとき
		//'-------------------------------------------------------------------

    		/*************************************************************************
			'	在庫残テーブルから開始年月までのデータ抽出処理
			'*************************************************************************
			'=========================================================================
			'	堺・川内で開始日が2005年6月21日以降→2005年6月20日時点の残高取得
			'	堺・川内で開始日が2005年6月21日以前→2004年8月20日時点の残高取得
			'	札幌で開始日が2005年7月21日以降→2005年7月20日時点の残高取得
			'	札幌で開始日が2005年7月21日以前→2004年8月20日時点の残高取得
			'	堺・川内・札幌以外→2004年8月20日時点の残高取得
			'=========================================================================
			'---------------------------------------------------------
			'	取得カラム：商品区分コード(203)、商品コード(203)
			'			   商品名(103)、部コード(203)
			'	対象テーブル：在庫残テーブル
			'				 商品マスターテーブル
			'				 商品売上テーブル
			'				 社員マスターテーブル
			'	条件：事業所コードが任意のもの
			'		 部コードが任意のもの 
			'		 締め年月が2004年8月度のもの
			'		 雑品以外のもの
			'		 エステ商品以外のもの
			'		 販売担当者の所属部署が代理店2・代理店3以外のもの
			'		 商品区分コード、商品コード順
			'---------------------------------------------------------
			*/
			$strSQL01 = "SELECT TB203.SYOHIN_KBN AS SYOHIN_KBN, "
					."TB203.SYOHIN_CD AS SYOHIN_CD, "
					."TB103.SYOHIN_MEI AS SYOHIN_MEI, "
					."TB203.BU_CD AS BU_CD "
					."FROM TW_HB203 TB203, TM_BS103 TB103 "
					."WHERE TB203.JIGYO_CD='". $strJigyo_Cd ."' "
					."AND TB203.SYOHIN_KBN = TB103.SYOHIN_KBN AND TB203.SYOHIN_CD = TB103.SYOHIN_CD ";

			//CO_HONSYA_CD = "0001"
			//CO_SAKAI_CD = "0003"
			//CO_SAPPORO_CD = "0015"
			//CO_SENDAI_CD = "0061"
			//CO_FUND_YMD20050621 = "20050621"
			//CO_FUND_YMD20050721 = "20050721"
			if ($strJigyo_Cd == "0001" or 
			(($strJigyo_Cd == "0003" or $strJigyo_Cd == "0061") and $strStartDateYYYYMMDD >= "20050621") or 
			($strJigyo_Cd == "0015" and $strStartDateYYYYMMDD >= '20050721')) {

				$strSQL01 = $strSQL01 ."AND TB203.BU_CD ='" . $strBu_Cd . "' ";
			}

			//CO_FUND_YM200506 = "200506"
			//CO_FUND_YM200507 = "200507"
			//CO_FUND_YM200408 = "200408"
			if (($strJigyo_Cd == "0003" or $strJigyo_Cd == "0061") and $strStartDateYYYYMMDD >= "20050621") {
				$strSQL01 = $strSQL01 . "AND TB203.SIMEMTH = '200506' ";
			} 
			elseif ($strJigyo_Cd == "0015" and $strStartDateYYYYMMDD >= "20050721") {
				$strSQL01 = $strSQL01 . "AND TB203.SIMEMTH = '200507' ";
			}
			else {
				$strSQL01 = $strSQL01 . "AND TB203.SIMEMTH = '200408' ";
			}

			//CO_CODE_ZAPPIN = "96"
			$strSQL01 = $strSQL01 . "AND TB103.BUNBETU_CD <> '96' "
					."AND TB103.JIGYO_CD IS NULL "
					."GROUP BY TB203.SYOHIN_KBN, TB203.SYOHIN_CD, TB103.SYOHIN_MEI, TB203.BU_CD ";



			$strSQL02 = $strSQL01 ."UNION "
					."SELECT TB214.SYOHIN_KBN AS SYOHIN_KBN, "
					."TB214.SYOHIN_CD AS SYOHIN_CD, "
					."TB103.SYOHIN_MEI AS SYOHIN_MEI, "
					."TB214.BU_CD AS BU_CD "
					."FROM TW_HB214 TB214, TM_BS103 TB103, TW_HB204 TB204, TM_BS101 TB101 "
 		 		 	."WHERE TB214.JIGYO_CD='". $strJigyo_Cd ."' "
		 		 	."AND TB214.SYOHIN_KBN = TB103.SYOHIN_KBN AND TB214.SYOHIN_CD = TB103.SYOHIN_CD "
					."AND TB214.URIAGE_CD = TB204.URIAGE_CD "
 		 		 	."AND TB204.TANTOU_CD = TB101.SYAIN_CD ";

			//CO_HONSYA_CD = "0001"
			//CO_SAKAI_CD = "0003"
			//CO_SAPPORO_CD = "0015"
			//CO_SENDAI_CD = "0061"
			//CO_FUND_YMD20050621 = "20050621"
			//CO_FUND_YMD20050721 = "20050721"
			//CO_FUND_YMD20040821 = "20040821"
			if ($strJigyo_Cd == "0001" or 
			(($strJigyo_Cd == "0003" or $strJigyo_Cd == "0061") and $strStartDateYYYYMMDD >= '20050621') or 
			($strJigyo_Cd == "0015" and $strStartDateYYYYMMDD >= '20050721')) {

				$strSQL02 = $strSQL02 . "AND TB214.BU_CD ='" . $strBu_Cd . "' ";
			}

			if (($strJigyo_Cd == "0003" or $strJigyo_Cd == "0061") and $strStartDateYYYYMMDD >= '20050621') {
				$strSQL02 = $strSQL02 . "AND TB214.RIREKIBI BETWEEN '20050621' AND '" . $strEndDateYYYYMMDD . "' ";
			}
			elseif ($strJigyo_Cd == "0015" and $strStartDateYYYYMMDD >= '20050721') {
				$strSQL02 = $strSQL02 . "AND TB214.RIREKIBI BETWEEN '20050721' AND '" . $strEndDateYYYYMMDD . "' ";
			}
			else {
				$strSQL02 = $strSQL02 . "AND TB214.RIREKIBI BETWEEN '20040821' AND '" . $strEndDateYYYYMMDD . "' ";
			}

			//CO_CODE_ZAPPIN = "96"
			//CO_DAIRI2_CD = "10"
			//CO_DAIRI3_CD = "11"
			$strSQL02 = $strSQL02 ."AND TB103.bunbetu_cd <> '96' "
					."AND TB103.JIGYO_CD IS NULL "
					."AND TB101.syozoku1_cd <> '10' "
					."AND TB101.syozoku1_cd <> '11' "
					."GROUP BY TB214.SYOHIN_KBN, TB214.SYOHIN_CD, TB103.SYOHIN_MEI, TB214.BU_CD ";


			$strSQL03 = $strSQL02 ."UNION "
					."SELECT TB214.SYOHIN_KBN AS SYOHIN_KBN, ".
					"TB214.SYOHIN_CD AS SYOHIN_CD, ".
					"TB103.SYOHIN_MEI AS SYOHIN_MEI, "
					."TB214.BU_CD AS BU_CD "
					."FROM TW_HB214 TB214, TM_BS103 TB103, TW_HB207 TB207 "
					."WHERE TB214.JIGYO_CD='". $strJigyo_Cd ."' "
					."AND TB214.SYOHIN_KBN = TB103.SYOHIN_KBN AND TB214.SYOHIN_CD = TB103.SYOHIN_CD "
					."AND TB214.SIIRE_CD = TB207.SIIRE_CD ";


			//CO_HONSYA_CD = "0001"
			//CO_SAKAI_CD = "0003"
			//CO_SAPPORO_CD = "0015"
			//CO_SENDAI_CD = "0061"
			//CO_FUND_YMD20050621 = "20050621"
			//CO_FUND_YMD20050721 = "20050721"
			//CO_FUND_YMD20040821 = "20040821"
			if ($strJigyo_Cd == "0001" or
			(($strJigyo_Cd == "0003" or $strJigyo_Cd == "0061") and $strStartDateYYYYMMDD >= "20050621") or
			($strJigyo_Cd == "0015" and $strStartDateYYYYMMDD >= "20050721")){
				$strSQL03 = $strSQL03 . "AND TB214.BU_CD ='" . $strBu_Cd . "' ";
			}

			if (($strJigyo_Cd == "0003" or $strJigyo_Cd == "0061") and $strStartDateYYYYMMDD >= "20050621") {
				$strSQL03 = $strSQL03 . "AND TB214.RIREKIBI BETWEEN '20050621' AND '" . $strEndDateYYYYMMDD . "' ";
			} 	 	
			elseif ($strJigyo_Cd == "0015" and $strStartDateYYYYMMDD >= "20050721") {
				$strSQL03 = $strSQL03 . "AND TB214.RIREKIBI BETWEEN '20050721' AND '" . $strEndDateYYYYMMDD . "' ";
			}
			else {
				$strSQL03 = $strSQL03 . "AND TB214.RIREKIBI BETWEEN '20040821' AND '" . $strEndDateYYYYMMDD . "' ";
			}

			//CO_CODE_ZAPPIN = "96"
			$strSQL03 = $strSQL03 . "AND TB103.bunbetu_cd <> '96' "
					."AND TB103.JIGYO_CD IS NULL "
					."GROUP BY TB214.SYOHIN_KBN, TB214.SYOHIN_CD, TB103.SYOHIN_MEI, TB214.BU_CD ";



			$strSQL04 = $strSQL03 ."UNION "
					."SELECT TB214.SYOHIN_KBN AS SYOHIN_KBN, "
					."TB214.SYOHIN_CD AS SYOHIN_CD, "
					."TB103.SYOHIN_MEI AS SYOHIN_MEI, "
					."TB214.BU_CD AS BU_CD "
					."FROM TW_HB214 TB214, TM_BS103 TB103, TW_HB204 AS TB204 "
					."WHERE TB214.JIGYO_CD ='" . $strJigyo_Cd ."' "
					."AND TB214.SYOHIN_KBN = TB103.SYOHIN_KBN AND TB214.SYOHIN_CD = TB103.SYOHIN_CD "
					."AND TB214.URIAGE_CD = TB204.URIAGE_CD ";


			//CO_HONSYA_CD = "0001"
			//CO_SAKAI_CD = "0003"
			//CO_SAPPORO_CD = "0015"
			//CO_SENDAI_CD = "0061"
			//CO_FUND_YMD20050621 = "20050621"
			//CO_FUND_YMD20050721 = "20050721"
			//CO_FUND_YMD20040821 = "20040821"
			if ($strJigyo_Cd == "0001" or
			(($strJigyo_Cd == "0003" or $strJigyo_Cd == "0061") and $strStartDateYYYYMMDD >= "20050621") or
			($strJigyo_Cd == "0015" and $strStartDateYYYYMMDD >= "20050721")){
				$strSQL04 = $strSQL04 . "AND TB214.BU_CD ='" . $strBu_Cd . "' ";
			}

			if (($strJigyo_Cd == "0003" or $strJigyo_Cd == "0061") and $strStartDateYYYYMMDD >= '20050621') {
				$strSQL04 = $strSQL04 . "AND TB214.RIREKIBI BETWEEN '20050621' AND '" . $strEndDateYYYYMMDD . "' ";
			}
			elseif ($strJigyo_Cd == "0015" and $strStartDateYYYYMMDD >= '20050721') {
				$strSQL04 = $strSQL04 . "AND TB214.RIREKIBI BETWEEN '20050721' AND '" . $strEndDateYYYYMMDD . "' ";
			}
			else {
				$strSQL04 = $strSQL04 . "AND TB214.RIREKIBI BETWEEN '20040821' AND '" . $strEndDateYYYYMMDD . "' ";
			}

			//CO_CODE_ZAPPIN = "96"
			$strSQL04 = $strSQL04 . "AND TB103.bunbetu_cd <> '96' "
					."AND TB103.JIGYO_CD IS NULL "
					."GROUP BY TB214.SYOHIN_KBN, TB214.SYOHIN_CD, TB103.SYOHIN_MEI, TB214.BU_CD "
					."ORDER BY SYOHIN_KBN, SYOHIN_CD ";

			$results = DB::select($strSQL04);
		
    	}
    	else{
		//'-------------------------------------------------------------------
		//'	[全部]が選択されているとき
		//'-------------------------------------------------------------------
    	}


    	Log::debug("count(results) =" . count($results));

		[$lZenZanKingaku_Sum ,$lTouZanKingaku_Sum] = $this->calculateStockValueByProducts($strBeforeDateYYYYMMDD, $strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $strJigyo_Cd, $strBu_Cd, $results);

		//'	前月/日在庫金額加算: $lZenZanKingaku_Sum
		$previousStockValue = $lZenZanKingaku_Sum;
		//'	当月/日在庫金額加算: lTouZanKingaku_Sum
		$currentStockValue = $lTouZanKingaku_Sum;

    	Log::debug("previousStockValue =" . $previousStockValue);
    	Log::debug("currentStockValue =" . $currentStockValue);
		

    	Log::debug("[END] OfficeStockService::calculateStockValue()");
        return [$previousStockValue ,$currentStockValue];
    }



	private function calculateStockValueByProducts($strBeforeDateYYYYMMDD, $strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $strJigyo_Cd, $strBu_Cd, $results)
	{
    	Log::debug("[START] BranchStockService::calculateStockValue()");
		Log::debug("[INPUT] strBeforeDateYYYYMMDD = " . $strBeforeDateYYYYMMDD);
		Log::debug("[INPUT] strStartDateYYYYMMDD = " . $strStartDateYYYYMMDD);
		Log::debug("[INPUT] strEndDateYYYYMMDD = " . $strEndDateYYYYMMDD);
		Log::debug("[INPUT] strJigyo_Cd = " . $strJigyo_Cd);
		Log::debug("[INPUT] strBu_Cd = " . $strBu_Cd);

		$dZenZan_Sum = 0;
		$lZenZanKingaku_Sum = 0;
		$dNyuko_Sum = 0;
		$dSyuko_Sum = 0;
		$dTouZan_Sum = 0;
		$lTouZanKingaku_Sum = 0;
		$count = 0;

		foreach($results as $result) {
			$resultSyohinKbn = $result->SYOHIN_KBN;
			$resultSyohinCd = $result->SYOHIN_CD;
			$resultBuCd = $result->BU_CD;
	
	    	Log::debug("counte = " . ++$count);			
			Log::debug("[INPUT] resultSyohinKbn =" . $resultSyohinKbn);
			Log::debug("[INPUT] resultSyohinCd =" . $resultSyohinCd);
			Log::debug("[INPUT] resultBuCd =" . $resultBuCd);
			
			/*'================================================================
			'	原価取得処理
			'================================================================
			*/

			$dGenka = $this->getGenka($strJigyo_Cd, $resultSyohinKbn, $resultSyohinCd);
			Log::debug($count . " dGenka =" . $dGenka);

			if ($dGenka == NULL or $dGenka == "") {
				//TODO:エラー処理
				continue;
			}

			//CO_FUND_YMD20040821="20040821"
			//TODO:日付比較を確認
			if ($strStartDateYYYYMMDD >= "20040821") {
				/*'======================================================
				'	2004年8月20日時点での在庫残取得処理
				'	堺と川内は2005年6月20日
				'	札幌は2005年7月20日
				'======================================================
				*/

				if ($strBu_Cd <> "00") {
				/*'-------------------------------------------------------------------
				'	[全部]が選択されていないとき
				'-------------------------------------------------------------------					
					'---------------------------------------------------
					'	取得カラム：在庫残合計
					'	対象テーブル：在庫残テーブル
					'	条件：履歴日が2004年8月20日のもの
					'		 事業所コードが任意のもの
					'		 部コードが任意のもの
					'		 商品区分コードが任意のもの
					'		 商品コードが任意のもの 
					'---------------------------------------------------
				*/
					$strSQL = "SELECT zaikonum AS GOUKEI "
								."FROM TW_HB203 "
								."WHERE JIGYO_CD ='" . $strJigyo_Cd . "' "
								."AND BU_CD ='" . $resultBuCd . "' " 
								."AND SYOHIN_KBN = '" . $resultSyohinKbn . "' "
								."AND SYOHIN_CD = '" . $resultSyohinCd ."' ";

					//CO_HONSYA_CD = "0001"
					//CO_SAKAI_CD = "0003"
					//CO_SAPPORO_CD = "0015"
					//CO_SENDAI_CD = "0061"
					//CO_FUND_YMD20050621 = "20050621"
					//CO_FUND_YMD20050721 = "20050721"
					//CO_FUND_YMD20040821 = "20040821"
					//CO_FUND_YM200506 = "200506"
					//CO_FUND_YM200507 = "200507"
					//CO_FUND_YM200408 = "200408"
					if (($strJigyo_Cd == "0003" or $strJigyo_Cd == "0061") and $strStartDateYYYYMMDD >= "20050621") {
						$strSQL = $strSQL . "AND simemth = '200506' ";
					}  	
					elseif ($strJigyo_Cd == "0015" and $strStartDateYYYYMMDD >= "20050721") {
						$strSQL = $strSQL . "AND simemth = '200507' ";
					}
					else{
						$strSQL = $strSQL . "AND simemth = '200408' ";							
					}
	

				}
				else {
				/*'-------------------------------------------------------------------
				'	[全部]が選択されているとき
				'-------------------------------------------------------------------
				*/
				}

				$resultCost = DB::select($strSQL);
				
				if (is_null($resultCost) or empty($resultCost)) {
					$lZen_Goukei = 0;
					Log::debug($count . " NullorEmpty lZen_Goukei =" . $lZen_Goukei);

				}
				else {
					$lZen_Goukei = $resultCost[0]->GOUKEI;
					Log::debug($count . " lZen_Goukei =" . $lZen_Goukei);
				}
			

				/*							
				'======================================================
				'	2004年8月21日から開始日前日までの在庫残取得処理
				'	堺と川内は2005年6月21日から
				'	札幌は2005年7月21日から
				'======================================================
				'-----------------------------------
				'	入庫合計取得処理
				'-----------------------------------
				'---------------------------------------------------
				'	取得カラム：在庫残合計
				'	対象テーブル：在庫履歴テーブル
				'	条件：履歴日の範囲が任意のもの
				'		 事業所コードが任意のもの
				'		 部コードが任意のもの
				'		 商品区分コードが任意のもの
				'		 商品コードが任意のもの 
				'		 入庫のもの
				'---------------------------------------------------
				*/

				$strSQL = "SELECT SUM(RIREKI_NUM) AS GOUKEI "
						."FROM TW_HB214 "
						."WHERE JIGYO_CD = '" . $strJigyo_Cd . "' "
						."AND SYOHIN_KBN ='" . $resultSyohinKbn . "' "
						."AND SYOHIN_CD ='" . $resultSyohinCd . "' ";
				
				//'-------------------------------------------------------------------
				//'	[全部]が選択されていないとき
				//'-------------------------------------------------------------------
				if ($strBu_Cd <> "00") {
					$strSQL = $strSQL . "AND BU_CD ='" . $resultBuCd  ."' ";
				}

				//CO_HONSYA_CD = "0001"
				//CO_SAKAI_CD = "0003"
				//CO_SAPPORO_CD = "0015"
				//CO_SENDAI_CD = "0061"
				//CO_FUND_YMD20050621 = "20050621"
				//CO_FUND_YMD20050721 = "20050721"
				//CO_FUND_YMD20040821 = "20040821"

				if (($strJigyo_Cd == "0003" or $strJigyo_Cd == "0061") and $strStartDateYYYYMMDD >= "20050621" ) {
					$strSQL = $strSQL . "AND RIREKIBI BETWEEN '20050621' AND '" . $strBeforeDateYYYYMMDD . "' ";
				}
				elseif ($strJigyo_Cd == "0015" and $strStartDateYYYYMMDD >= "20050721") {
					$strSQL = $strSQL . "AND RIREKIBI BETWEEN '20050721' AND '" . $strBeforeDateYYYYMMDD . "' ";
				}
				else {
					$strSQL = $strSQL . "AND RIREKIBI BETWEEN '20040821' AND '" . $strBeforeDateYYYYMMDD . "' ";
				}
			

				//CO_CODE_NYUKO = "01"
				$strSQL = $strSQL . "AND RIREKI_KBN = '01' ";


				$resultStock = DB::select($strSQL);

				if (is_null($resultStock) or empty($resultStock)) {
					$lNyuko_Goukei = 0;
					Log::debug($count . " NullorEmpty lNyuko_Goukei =" . $lNyuko_Goukei);

				}
				else {
					$lNyuko_Goukei = $resultStock[0]->GOUKEI;
					Log::debug($count . " lNyuko_Goukei =" . $lNyuko_Goukei);

				}



				/*
				'-----------------------------------
				'	出庫合計取得処理(対顧客)
				'-----------------------------------
				'---------------------------------------------------------
				'	取得カラム：在庫残合計
				'	対象テーブル：在庫履歴テーブル
				'				 商品売上テーブル
				'				 社員マスターテーブル
				'	条件：履歴日の範囲が任意のもの
				'		 事業所コードが任意のもの
				'		 部コードが任意のもの
				'		 商品区分コードが任意のもの
				'		 商品コードが任意のもの 
				'		 販売担当者の所属部署が代理店2・代理店3以外のもの
				'		 出庫のもの
				'---------------------------------------------------------
				*/

				$strSQL = "SELECT SUM(TB214.RIREKI_NUM) AS GOUKEI "
						."FROM TW_HB214 TB214, TW_HB204 TB204, TM_BS101 TB101 "
						."WHERE TB214.JIGYO_CD = '" . $strJigyo_Cd . "' "
						."AND TB214.URIAGE_CD = TB204.URIAGE_CD "
						."AND TB204.TANTOU_CD = TB101.SYAIN_CD "
						."AND TB214.SYOHIN_KBN ='" . $resultSyohinKbn . "' "
						."AND TB214.SYOHIN_CD ='" . $resultSyohinCd ."' ";
							
				//'-------------------------------------------------------------------
				//'	[全部]が選択されていないとき
				//'-------------------------------------------------------------------

				if ($strBu_Cd <> "00") {
					$strSQL = $strSQL . "AND TB214.BU_CD ='" . $resultBuCd  ."' ";
				}
						
				//CO_HONSYA_CD = "0001"
				//CO_SAKAI_CD = "0003"
				//CO_SAPPORO_CD = "0015"
				//CO_SENDAI_CD = "0061"
				//CO_FUND_YMD20050621 = "20050621"
				//CO_FUND_YMD20050721 = "20050721"
				//CO_FUND_YMD20040821 = "20040821"
				if (($strJigyo_Cd == "0003" or $strJigyo_Cd == "0061") and $strStartDateYYYYMMDD >= "20050621" ) {
					$strSQL = $strSQL . "AND TB214.RIREKIBI BETWEEN '20050621' AND '" . $strBeforeDateYYYYMMDD	. "' ";
				}
				elseif ($strJigyo_Cd == "0015" and $strStartDateYYYYMMDD >= "20050721") {
					$strSQL = $strSQL . "AND TB214.RIREKIBI BETWEEN '20050721' AND '" . $strBeforeDateYYYYMMDD	. "' ";
				}
				else {
					$strSQL = $strSQL . "AND TB214.RIREKIBI BETWEEN '20040821' AND '" . $strBeforeDateYYYYMMDD	. "' ";
				}

				//CO_DAIRI2_CD = "10"
				//CO_DAIRI3_CD = "11"
				//CO_CODE_SYUKO = "02"
				$strSQL = $strSQL 
						."AND TB101.syozoku1_cd <> '10' "
						."AND TB101.syozoku1_cd <> '11' " 
						."AND TB214.rireki_kbn = '02'";

		
				$resultStock = DB::select($strSQL);

				if (is_null($resultStock) or empty($resultStock)) {
					$lSyuko_Goukei = 0;
					Log::debug($count . " NullorEmpty lSyuko_Goukei =" . $lSyuko_Goukei);
				}
				else {
					$lSyuko_Goukei = $resultStock[0]->GOUKEI;
					Log::debug($count . " lSyuko_Goukei =" . $lSyuko_Goukei);
				}
			

				/*
				'-----------------------------------
				'	出庫合計取得処理(対事業所)
				'-----------------------------------
				'---------------------------------------------------
				'	取得カラム：在庫残合計
				'	対象テーブル：在庫履歴テーブル
				'	条件：履歴日の範囲が任意のもの
				'		 事業所コードが任意のもの
				'		 部コードが任意のもの
				'		 商品区分コードが任意のもの
				'		 商品コードが任意のもの 
				'		 出庫のもの
				'---------------------------------------------------
				*/

				$strSQL = "SELECT SUM(TB214.rireki_num) AS GOUKEI "
						."FROM TW_HB214 TB214, TW_HB204 TB204 "
						."WHERE TB214.JIGYO_CD = '" . $strJigyo_Cd . "' "
						."AND TB214.URIAGE_CD = TB204.URIAGE_CD "
						."AND TB214.SYOHIN_KBN ='" . $resultSyohinKbn . "' "
						."AND TB214.SYOHIN_CD ='" . $resultSyohinCd ."' ";

				//'-------------------------------------------------------------------
				//'	[全部]が選択されていないとき
				//'-------------------------------------------------------------------
				
				if ($strBu_Cd <> "00") {
					$strSQL = $strSQL . "AND TB214.BU_CD ='" . $resultBuCd  ."' ";
				}

				//CO_HONSYA_CD = "0001"
				//CO_SAKAI_CD = "0003"
				//CO_SAPPORO_CD = "0015"
				//CO_SENDAI_CD = "0061"
				//CO_FUND_YMD20050621 = "20050621"
				//CO_FUND_YMD20050721 = "20050721"
				//CO_FUND_YMD20040821 = "20040821"

				if (($strJigyo_Cd == "0003" or $strJigyo_Cd == "0061") and $strStartDateYYYYMMDD >= "20050621" ) {
					$strSQL = $strSQL . "AND TB214.RIREKIBI BETWEEN '20050621' AND '" . $strBeforeDateYYYYMMDD	. "' ";
				}
				elseif ($strJigyo_Cd == "0015" and $strStartDateYYYYMMDD >= "20050721") {
					$strSQL = $strSQL . "AND TB214.RIREKIBI BETWEEN '20050721' AND '" . $strBeforeDateYYYYMMDD	. "' ";
				}
				else {
					$strSQL = $strSQL . "AND TB214.RIREKIBI BETWEEN '20040821' AND '" . $strBeforeDateYYYYMMDD	. "' ";
				}

				//CO_CODE_JIGYO_URIAGE = "02"
				//CO_CODE_SYUKO = "02"
				$strSQL = $strSQL 
						."AND TB204.URIAGE_KBN = '02'"
						."AND TB214.RIREKI_KBN = '02'"; 			


				$resultStock = DB::select($strSQL);

				if (is_null($resultStock) or empty($resultStock)) {
					$lSyuko_Goukei2 = 0;
					Log::debug($count . " NullorEmpty lSyuko_Goukei2 =" . $lSyuko_Goukei2);
				}
				else {
					$lSyuko_Goukei2 = $resultStock[0]->GOUKEI;
					Log::debug($count . " lSyuko_Goukei2 =" . $lSyuko_Goukei2);
				}


			
				//'	入出庫合計
				$lGoukei = $lNyuko_Goukei - ($lSyuko_Goukei + $lSyuko_Goukei2);
				
				//'	前残合計
				$dZenZan = $lZen_Goukei + $lGoukei;
			
				//'	前月/日残数量加算
				$dZenZan_Sum = $dZenZan_Sum + $dZenZan;
			
				//'	在庫金額計算
				$lZenZanKingaku = $dGenka * $dZenZan;
			
				//'	前月/日在庫金額加算
				$lZenZanKingaku_Sum = $lZenZanKingaku_Sum + $lZenZanKingaku;


				Log::debug($count . " lGoukei =" . $lGoukei);
	    		Log::debug($count . " dZenZan =" . $dZenZan);
	    		Log::debug($count . " lZenZanKingaku =" . $lZenZanKingaku);
				Log::debug($count . " dZenZan_Sum =" . $dZenZan_Sum);
	    		Log::debug($count . " lZenZanKingaku_Sum =" . $lZenZanKingaku_Sum);

			}
			else 
			{
				$dZenZan = 0;
				$lZenZanKingaku = 0;

				Log::debug($count . " ELSE dZenZan =" . $dZenZan);
	    		Log::debug($count . " ELSE lZenZanKingaku =" . $lZenZanKingaku);
			}











			/*
			'======================================================================
			'	期間実績入庫数量取得処理
			'======================================================================
			'-----------------------------------------------
			'	取得カラム：入庫数合計
			'	対象テーブル：在庫履歴テーブル
			'	条件：商品区分コードが任意のもの
			'		 商品コードが任意のもの
			'		 履歴日が任意の範囲のもの
			'		 部が任意のもの
			'		 事業所コードが任意のもの
			'		 入庫のもの
			'--------------------------------------------------
			*/

			$strSQL = "SELECT SUM(RIREKI_NUM) AS NYUKO_SUM "
					."FROM TW_HB214 "
					."WHERE JIGYO_CD = '" . $strJigyo_Cd . "' "
					."AND SYOHIN_KBN = '" . $resultSyohinKbn . "' "
					."AND SYOHIN_CD = '" . $resultSyohinCd . "' "
					."AND RIREKIBI BETWEEN '" . $strStartDateYYYYMMDD . "' AND '" . $strEndDateYYYYMMDD . "' ";
							
			//'-------------------------------------------------------------------
			//'	[全部]が選択されていないとき
			//'-------------------------------------------------------------------

			if ($strBu_Cd <> "00") {
				$strSQL = $strSQL . "AND BU_CD ='" . $resultBuCd  ."' ";
			}
			
			//CO_CODE_NYUKO = "01"
			$strSQL = $strSQL . "AND RIREKI_KBN = '01'";


			$resultStock = DB::select($strSQL);

			if (is_null($resultStock) or empty($resultStock)) {
				$dNyuko = 0;
				Log::debug($count . " NullorEmpty dNyuko =" . $dNyuko);
			}
			else {
				$dNyuko = $resultStock[0]->NYUKO_SUM;
				Log::debug($count . " dNyuko =" . $dNyuko);
			}
			
			//'	期間実績入庫数量加算
			$dNyuko_Sum = $dNyuko_Sum + $dNyuko;
			

			/*
			'======================================================================
			'	期間実績出庫数量取得処理
			'======================================================================
			'----------------------------
			'	出庫合計取得処理(対顧客)
			'----------------------------
			'---------------------------------------------------------
			'	取得カラム：出庫数合計
			'	対象テーブル：在庫履歴テーブル
			'				 商品売上テーブル
			'				 社員マスターテーブル
			'	条件：商品区分コードが任意のもの
			'		 商品コードが任意のもの
			'		 履歴日が任意の範囲のもの
			'		 部が任意のもの
			'		 事業所コードが任意のもの
			'		 販売担当者の所属部署が代理店2・代理店3以外のもの
			'		 出庫のもの
			'---------------------------------------------------------
			*/

			$strSQL = "SELECT SUM(TB214.RIREKI_NUM) AS SYUKO_SUM "
					."FROM TW_HB214 TB214, TW_HB204 AS TB204, TM_BS101 AS TB101 "
					."WHERE TB214.JIGYO_CD = '" . $strJigyo_Cd . "' "
					."AND TB214.URIAGE_CD = TB204.URIAGE_CD "
					."AND TB204.TANTOU_CD = TB101.SYAIN_CD "
					."AND TB214.SYOHIN_KBN = '" . $resultSyohinKbn ."' "
					."AND TB214.SYOHIN_CD = '" . $resultSyohinCd ."' "
					."AND TB214.RIREKIBI BETWEEN '" . $strStartDateYYYYMMDD . "' AND '" . $strEndDateYYYYMMDD . "' ";

			
			//'-------------------------------------------------------------------
			//'	[全部]が選択されていないとき
			//'-------------------------------------------------------------------
			if ($strBu_Cd <> "00") {
				$strSQL = $strSQL . "AND TB214.BU_CD ='" . $resultBuCd  ."' ";
			}

			//CO_DAIRI2_CD = "10"
			//CO_DAIRI3_CD = "11"
			//CO_CODE_SYUKO = "02"
			$strSQL = $strSQL 
					."AND TB101.SYOZOKU1_CD <> '10' "
					."AND TB101.SYOZOKU1_CD <> '11' "
					."AND TB214.RIREKI_KBN = '02' ";

			
			$resultStock = DB::select($strSQL);

			if (is_null($resultStock) or empty($resultStock)) {
				$dSyuko = 0;
				Log::debug($count . " NullorEmpty dSyuko =" . $dSyuko);				
			}
			else {
				$dSyuko = $resultStock[0]->SYUKO_SUM;
				Log::debug($count . " dSyuko =" . $dSyuko);				
			}
			

			/*				
			'----------------------------
			'	出庫合計取得処理(対事業所)
			'----------------------------
			'-----------------------------------------------
			'	取得カラム：出庫数合計
			'	対象テーブル：在庫履歴テーブル
			'	条件：商品区分コードが任意のもの
			'		 商品コードが任意のもの
			'		 履歴日が任意の範囲のもの
			'		 部が任意のもの
			'		 事業所コードが任意のもの
			'		 出庫のもの
			'--------------------------------------------------
			*/

			$strSQL = "SELECT SUM(TB214.rireki_num) AS SYUKO_SUM "
					."FROM TW_HB214 AS TB214, TW_HB204 AS TB204 "
					."WHERE TB214.JIGYO_CD = '" . $strJigyo_Cd . "' "
					."AND TB214.URIAGE_CD = TB204.URIAGE_CD "
					."AND TB214.RIREKIBI BETWEEN '" . $strStartDateYYYYMMDD . "' AND '" . $strEndDateYYYYMMDD . "' "
					."AND TB214.SYOHIN_KBN ='" . $resultSyohinKbn . "' "
					."AND TB214.SYOHIN_CD ='" . $resultSyohinCd . "' ";

							
			//'-------------------------------------------------------------------
			//'	[全部]が選択されていないとき
			//'-------------------------------------------------------------------
			if ($strBu_Cd <> "00")
			{
				$strSQL = $strSQL . "AND TB214.BU_CD ='" . $resultBuCd  ."' ";
			}

			//CO_CODE_JIGYO_URIAGE = "02"
			//CO_CODE_SYUKO = "02"
			$strSQL = $strSQL 
					."AND TB204.URIAGE_KBN = '02' "
					."AND TB214.RIREKI_KBN = '02' ";				 	
			
			$resultStock = DB::select($strSQL);

			if (is_null($resultStock) or empty($resultStock))
			{
				$dSyuko2 = 0;
				Log::debug($count . " NullorEmpty dSyuko2 =" . $dSyuko2);				

			}
			else
			{
				$dSyuko2 = $resultStock[0]->SYUKO_SUM;
				Log::debug($count . " dSyuko2 =" . $dSyuko2);				
			}
			

			//'	期間実績出庫数量加算
			$dSyuko_Sum = $dSyuko_Sum + $dSyuko + $dSyuko2;
			//$dSyuko_Sum = CCur(dSyuko_Sum)
			
			//'	当月/日残数量計算
			$dTouZan = $dZenZan + $dNyuko - ($dSyuko + $dSyuko2);
			//dTouZan = CCur(dTouZan)
			
			//'	当月/日残数量加算
			$dTouZan_Sum = $dTouZan_Sum + $dTouZan;
			//dTouZan_Sum = CCur(dTouZan_Sum)
			
			//'	当月/日在庫金額計算
			$lTouZanKingaku = $dGenka * $dTouZan;
			//lTouZanKingaku = CCur(lTouZanKingaku)
			
			//'	当月/日在庫金額加算
			$lTouZanKingaku_Sum = $lTouZanKingaku_Sum + $lTouZanKingaku;
			//lTouZanKingaku_Sum = CCur(lTouZanKingaku_Sum)


	   		Log::debug($count . " dSyuko_Sum =" . $dSyuko_Sum);
 			Log::debug($count . " dNyuko_Sum =" . $dNyuko_Sum);

	   		Log::debug($count . " dTouZan =" . $dTouZan);
 			Log::debug($count . " lTouZanKingaku =" . $lTouZanKingaku);


			Log::debug($count . " dTouZan_Sum =" . $dTouZan_Sum);
    		Log::debug($count . " lTouZanKingaku_Sum =" . $lTouZanKingaku_Sum);

		}

    	Log::debug("[END] BranchStockService::calculateStockValue()");
		return [$lZenZanKingaku_Sum ,$lTouZanKingaku_Sum];
	}



	public function getGenka($strJigyo_Cd, $resultSyohinKbn, $resultSyohinCd)
	{
    	Log::debug("[START] BranchStockService::getGenka()");

		$results = DB::select("SELECT MISE_KBN, HOUJIN_KBN FROM TM_BS102 WHERE JIGYO_CD = '". $strJigyo_Cd ."'");

		if (count($results) > 0) 
		{
			$strMise_Kbn = $results[0]->MISE_KBN;
			$strHoujin_Kbn = $results[0]->HOUJIN_KBN;
		}
		else
		{
			Log::debug("lngZyodai =''");
	    	Log::debug("[END] BranchStockService::getGenka()");
			return "";
		}
		
		//CO_CODE_HONSYA_TOUKATU = "01"
		//CO_CODE_TYOKU_IPPAN = "02"
		if($strMise_Kbn == "01")
		{
			$results = DB::select("SELECT GENKA1 AS GENKA FROM TM_BS103 WHERE SYOHIN_KBN = '". $resultSyohinKbn ."' AND SYOHIN_CD = '" . $resultSyohinCd ."'");	
		}
		elseif($strMise_Kbn == "02")
		{
			//CO_CODE_KABU = "01"
			$results = DB::select("SELECT GENKA1 AS GENKA FROM TM_BS103 WHERE SYOHIN_KBN = '". $resultSyohinKbn ."' AND SYOHIN_CD = '" . $resultSyohinCd ."'");	
		}


		if (count($results) > 0) 
		{
			$lngZyodai = $results[0]->GENKA;
		}
		else
		{
			$resultsZappin = DB::select("SELECT BUNBETU_CD, ZYODAI FROM TM_BS103 WHERE SYOHIN_KBN = '". $resultSyohinKbn ."' AND SYOHIN_CD = '" . $resultSyohinCd ."'");	

			if(count($resultsZappin) > 0)
			{
				$bunbetu_cd = $resultZappin[0]->BUNBETU_CD;

				//CO_CODE_ZAPPIN = "96"
				if($bunbetu_cd == "96")
				{
					$lngZyodai = $resultZappin[0]->ZYODAI;
				}
				else
				{
					$lngZyodai = 0;
				}
			}
			else
			{
				Log::debug("lngZyodai =''");
		    	Log::debug("[END] BranchStockService::getGenka()");
				return "";
			}
		}

		Log::debug("lngZyodai =" . $lngZyodai);
    	Log::debug("[END] BranchStockService::getGenka()");
		return $lngZyodai;
	}


}