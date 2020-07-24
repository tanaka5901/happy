<?php

namespace App\Services;

use DB;
use Log;
use OfficePurchasesService,OfficeStockService;


class OfficeSalesService
{

    public function exportCommodityManagementCsv($arrayResults, $startMonth, $endMonth, $strStartDateYYYYMMDD, $strEndDateYYYYMMDD)
    {

    	Log::debug("[START] OfficeSalesService::exportCommodityManagementCsv()");
    	Log::debug("[INPUT] startMonth = " . $startMonth);
    	Log::debug("[INPUT] endMonth = " . $endMonth);
    	Log::debug("[INPUT] strStartDateYYYYMMDD = " . $strStartDateYYYYMMDD);
    	Log::debug("[INPUT] strEndDateYYYYMMDD = " . $strEndDateYYYYMMDD);
        Log::debug(print_r($arrayResults,true));

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



        $callback = function () use ($arrayResults, $startMonth, $endMonth, $strStartDateYYYYMMDD, $strEndDateYYYYMMDD) 
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
                "伝票摘要",//""
                "枝番",
                "借方部門",
                "借方部門名",//""
                "借方科目",
                "借方科目名",//""
                "借方補助",
                "借方補助科目名",//""
                "借方金額",
                "借方消費税コード",
                "借方消費税業種",
                "借方消費税税率",
                "借方資金区分",
                "借方任意項目１",//""
                "借方任意項目２",//""
                "貸方部門",
                "貸方部門名",//""
                "貸方科目",
                "貸方科目名",//""
                "貸方補助",
                "貸方補助科目名",//""
                "貸方金額",
                "貸方消費税コード",
                "貸方消費税業種",
                "貸方消費税税率",
                "貸方資金区分",
                "貸方任意項目１",//""
                "貸方任意項目２",//""
                "摘要",//""
                "期日",
                "証番号",//""
                "入力マシン",//""
                "入力ユーザ",//""
                "入力アプリ",//""
                "入力会社",//""
                "入力日付",
            ]; 

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $columns); //文字化け対策    
            fputcsv($createCsvFile, $columns); //1行目の情報を追記

            $slipNo1 = 131;

			$csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo1,
                '',
                1,
                '',
                '',
                1131,
                '売掛金２代理店',
                0,
                '',
                intval($arrayResults['EmployeeSalesResult08']['total'] + $arrayResults['EmployeeSalesResult10']['total']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['EmployeeSalesResult08']['total'] + $arrayResults['EmployeeSalesResult10']['total']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　代理店売上計上\n社 販（軽税*8%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo1,
                '',
                1,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['EmployeeSalesResult08']['product']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                8112,
                '売上高２代理店',
                0,
                '',
                intval($arrayResults['EmployeeSalesResult08']['product']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　代理店売上計上\n社 販（軽税*8%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          


            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo1,
                '',
                2,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['EmployeeSalesResult10']['product']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                8112,
                '売上高２代理店',
                0,
                '',
                intval($arrayResults['EmployeeSalesResult10']['product']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　代理店売上計上\n社 販（税*10%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo1,
                '',
                3,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['EmployeeSalesResult08']['miscGoods']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                8113,
                '売上高３雑品',//TODO
                0,
                '',
                intval($arrayResults['EmployeeSalesResult08']['miscGoods']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　代理店売上計上\n雑　費（軽税*8%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo1,
                '',
                4,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['EmployeeSalesResult10']['miscGoods']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                8113,
                '売上高３雑品',//TODO
                0,
                '',
                intval($arrayResults['EmployeeSalesResult10']['miscGoods']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　代理店売上計上\n雑　費（税*10%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する


            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo1,
                '',
                5,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['EmployeeSalesResult08']['tax']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                5000,
                '仮受消費税',
                0,
                '',
                intval($arrayResults['EmployeeSalesResult08']['tax']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　代理店売上計上\n消費税（軽税*8%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo1,
                '',
                6,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['EmployeeSalesResult10']['tax']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                5000,
                '仮受消費税',
                0,
                '',
                intval($arrayResults['EmployeeSalesResult10']['tax']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　代理店売上計上\n消費税（税*10%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する


            fclose($createCsvFile); //ファイル閉じる
        };
        
    	Log::debug("[END] OfficeSalesService::exportCommodityManagementCsv()");
        return response()->stream($callback, 200, $headers); //ここで実行

	}



    public function getCommodityManagementSalesValue($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $officeCode, $departmentCode, $miscGoodsCode)
    {
        Log::debug("[START] OfficeSalesService::getCommodityManagementSalesValue()");

        $arrayResults = $this->calculateCommodityManagementSalesValue($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $officeCode, $departmentCode, $miscGoodsCode);

        Log::debug("[END] OfficeSalesService::getCommodityManagementSalesValue()");
        return $arrayResults;
    }


    private function calculateCommodityManagementSalesValue($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $officeCode, $departmentCode,  $miscGoodsCode)
    {
        Log::debug("[START] OfficeSalesService::calculateCommodityManagementSalesValue()");

        $strHenpin_Kbn = "00";
        $strSyahan_Cd = "00";
        $strTuhan_Kbn = "01";
        $strHeadTantou_cd = "0301010001999998";//社販本社1
        $strHeadKokyaku_cd ="";
        $strJigyo_Cd = $officeCode;
        $strBu_cd = $departmentCode;
        $strSyozokuJigyo_Cd = "";
        $strSyozokuBu_Cd = "00";


        //10%　社販結果
        $arrayEmployeeSalesResult10 = array('product'=> 0, 'miscGoods'=> 0, 'tax'=> 0, 'total'=> 0);

        //8%　社販結果
        $arrayEmployeeSalesResult08 = array('product'=> 0, 'miscGoods'=> 0, 'tax'=> 0, 'total'=> 0);

        $arrayResults = array('EmployeeSalesResult10'=> $arrayEmployeeSalesResult10 , 'EmployeeSalesResult08'=> $arrayEmployeeSalesResult08);


        //雑品
        $strZappin_cd = "02";

        $results = $this->getSalesHeaderRecords($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $strHenpin_Kbn, $strZappin_cd, $strSyahan_Cd, $strTuhan_Kbn, $strHeadTantou_cd, $strHeadKokyaku_cd, $strJigyo_Cd, $strBu_cd, $strSyozokuJigyo_Cd, $strSyozokuBu_Cd);

        $arrayResults = $this->calculateCommodityManagementSalesValueByProduct($strZappin_cd, $miscGoodsCode, $results, $arrayResults);


        //雑品以外
        $strZappin_cd = "01";

        $results = $this->getSalesHeaderRecords($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $strHenpin_Kbn, $strZappin_cd, $strSyahan_Cd, $strTuhan_Kbn, $strHeadTantou_cd, $strHeadKokyaku_cd, $strJigyo_Cd, $strBu_cd, $strSyozokuJigyo_Cd, $strSyozokuBu_Cd);

        $arrayResults = $this->calculateCommodityManagementSalesValueByProduct($strZappin_cd, $miscGoodsCode, $results, $arrayResults);


        Log::debug("[END] OfficeSalesService::calculateCommodityManagementSalesValue()");
        return $arrayResults;
    }



    private function calculateCommodityManagementSalesValueByProduct($strZappin_cd, $miscGoodsCode, $results, $arrayResults)
    {
        Log::debug("[START] OfficeSalesService::calculateBueatySalonSalesValueByProduct()");


        foreach ($results as $result) 
        {
            $resultURIAGEBI = $result->URIAGEBI;
            $resultTANTOU_CD = $result->TANTOU_CD;
            $resultKOKYAKU_CD = $result->KOKYAKU_CD;
            $resultBU_CD = $result->BU_CD;
            $resultHENPIN_KBN = $result->HENPIN_KBN;
            $resultKBN = $result->KBN;
            $resultJIGYO_CD = $result->JIGYO_CD;
            //'========================================================
            //'   売上データ取得処理
            //'========================================================
            /*
            '--------------------------------------------------------
            '   取得カラム：売上日(204)、担当者漢字姓(101)
            '              担当者漢字名(101)、顧客漢字姓(201)
            '              個顧客漢字名(201)、商品略称(103)
            '              上代(204)、商品個数(204)
            '              税抜き額(204)、 消費税額(204)
            '              税込み額(204)、販売担当者コード(204)
            '              顧客コード(204)、商品区分コード(204)
            '              商品コード(204)、売上コード(204)
            '              部略称(110)、返品区分コード(904)
            '              返品日(904)、備考(904)
            '              事業所略称(102)
            '              フォローNO(204)
            '   対象テーブル：商品売上テーブル
            '                社員マスターテーブル
            '                顧客マスターテーブル
            '                商品マスターテーブル
            '                部マスターテーブル
            '                事業所マスターテーブル
            '   条件：売上日が任意のもの
            '         販売担当者コードが任意のもの
            '         顧客コードが任意のもの
            '         事業所コードが任意のもの
            '         部コードが任意のもの
            '         返品区分コードが任意のもの
            '---------------------------------------------------------
            */
            $strSQL = "SELECT TB204.URIAGEBI, TB101.KANJI_SEI AS TANTOU_SEI, TB101.KANJI_MEI AS TANTOU_MEI, TB201.KANJI_SEI AS KOKYAKU_SEI, TB201.KANJI_MEI AS KOKAYKU_MEI, "
                    ."TB103.SYOHIN_RMEI, TB204.ZYODAI, TB204.SYOHINNUM, TB204.ZEINUKI, TB204.SYOHIZEI, TB204.ZEIKOMI, "
                    ."TB204.TANTOU_CD, TB204.KOKYAKU_CD, TB204.SYOHIN_KBN, TB204.SYOHIN_CD, TB204.URIAGE_CD, TB110.BU_RMEI, TB204.HENPIN_KBN, TB204.HENPINBI, TB204.TEKIYOU, TB102.JIGYO_RMEI, TB204.FOLLOW_NO "
                    ."FROM TW_HB204 TB204, TM_BS101 TB101, TM_HB201 TB201, TM_BS103 TB103, TM_BS110 TB110, TM_BS102 TB102 "
                    ."WHERE TB204.URIAGEBI ='" . $resultURIAGEBI . "' "
                    ."AND TB204.TANTOU_CD ='" . $resultTANTOU_CD . "' "
                    ."AND TB204.KOKYAKU_CD ='" . $resultKOKYAKU_CD . "' "
                    ."AND TB204.BU_CD ='" . $resultBU_CD . "' "
                    ."AND TB204.HENPIN_KBN ='" . $resultHENPIN_KBN . "' "
                    ."AND ";                                                                      
                
            if ($resultKBN == 1)
            {
                $strSQL = $strSQL . "TB204.JIGYO_CD ='" . $resultJIGYO_CD . "' ";   
            }
            else
            {
                $strSQL = $strSQL . "TB101.JIGYO_CD ='" . $resultJIGYO_CD . "' ";           
            }

            
            if ($strZappin_cd == "01")
            {
                //CO_CODE_ZAPPIN = "96"
                $strSQL = $strSQL . " AND TB204.BUNBETU_CD NOT IN ('96') ";
            }
                            
            if ($strZappin_cd == "02")
            {
                //CO_CODE_ZAPPIN = "96"
                $strSQL = $strSQL . " AND TB204.BUNBETU_CD = '96' ";
            }
                
            $strSQL = $strSQL . "AND TB101.SYAIN_CD = TB204.TANTOU_CD "
                    ."AND TB201.KOKYAKU_CD = TB204.KOKYAKU_CD "
                    ."AND TB103.SYOHIN_KBN = TB204.SYOHIN_KBN "
                    ."AND TB103.SYOHIN_CD = TB204.SYOHIN_CD "
                    ."AND TB110.BU_CD = TB204.BU_CD "
                    ."AND TB204.JIGYO_CD = TB102.JIGYO_CD "
                    ."ORDER BY TB204.HENPIN_KBN ";
            

            $resultsProduct = DB::select($strSQL);


            foreach ($resultsProduct as $resultProduct)
            {                                                        
                $resultProductJIGYO_RMEI = $resultProduct->JIGYO_RMEI;//売上事業所
                $resultProductBU_RMEI = $resultProduct->BU_RMEI;//売上部署
                Log::debug("resultProductJIGYO_RMEI = " . $resultProductJIGYO_RMEI);
                Log::debug("resultProductBU_RMEI = " . $resultProductBU_RMEI);

                $resultProductSYOHIN_RMEI = $resultProduct->SYOHIN_RMEI;//商品名
                $resultProductZEINUKI = $resultProduct->ZEINUKI;//金額
                $resultProductSYOHIZEI = $resultProduct->SYOHIZEI;//消費税額
                $resultProductZEIKOMI = $resultProduct->ZEIKOMI;//小計
                Log::debug("resultProductSYOHIN_RMEI = " . $resultProductSYOHIN_RMEI);
                Log::debug("resultProductZEIKOMI = " . $resultProductZEIKOMI);
                
                $resultProductTANTOU_SEI = $resultProduct->TANTOU_SEI;//販売担当者姓
                $resultProductTANTOU_MEI = $resultProduct->TANTOU_MEI;//販売担当者名
                $strTantou_Mei = $resultProductTANTOU_SEI . "　" . $resultProductTANTOU_MEI;
                Log::debug("strTantou_Mei = " . $strTantou_Mei);


                $resultProductHENPIN_KBN = $resultProduct->HENPIN_KBN;//返品区分
                $resultProductHENPINBI = $resultProduct->HENPINBI;//返品日
                if ($resultProductHENPIN_KBN == "01")//CO_CODE_URIAGE="01"
                {
                    $strKbn = "売上";
                }
                elseif ($resultProductHENPIN_KBN == "02")//CO_CODE_HENPIN="02"
                {
                    $strKbn = "返品";
                }
                elseif ($resultProductHENPIN_KBN == "03")//CO_CODE_MIHON="03"
                {
                    $strKbn = "見本";
                }
                Log::debug("strKbn = " . $strKbn);
                Log::debug("resultProductHENPINBI = " . $resultProductHENPINBI);


                // 雑品以外
                if ($strZappin_cd == "01")
                {
                    if($resultProductZEIKOMI != 0)
                    {
                        if($strKbn != '返品') // 返品以外
                        {
                            if(strpos($resultProductSYOHIN_RMEI,"※") !== false) // 消費税8％商品
                            {
                                Log::debug("arrayResults['EmployeeSalesResult08']['product']");
                                $arrayResults['EmployeeSalesResult08']['product'] = $arrayResults['EmployeeSalesResult08']['product'] + $resultProductZEIKOMI;

                                $arrayResults['EmployeeSalesResult08']['total'] = $arrayResults['EmployeeSalesResult08']['total'] + $resultProductZEIKOMI;
                            }
                            else // 消費税10％商品
                            {
                                Log::debug("arrayResults['EmployeeSalesResult10']['product']");
                                $arrayResults['EmployeeSalesResult10']['product'] = $arrayResults['EmployeeSalesResult10']['product'] + $resultProductZEIKOMI;

                                $arrayResults['EmployeeSalesResult10']['total'] = $arrayResults['EmployeeSalesResult10']['total'] + $resultProductZEIKOMI;
                            }                            
                        }

                    }
                }
                

                // 雑品
                if ($strZappin_cd == "02")
                {
                    if($strKbn != '返品')
                    {
                        if($resultProductSYOHIN_RMEI == '消費税(8%)')
                        {
                            Log::debug("arrayResults['EmployeeSalesResult08']['tax']");
                            $arrayResults['EmployeeSalesResult08']['tax'] = $arrayResults['EmployeeSalesResult08']['tax'] + $resultProductZEIKOMI;

                            $arrayResults['EmployeeSalesResult08']['total'] = $arrayResults['EmployeeSalesResult08']['total'] + $resultProductZEIKOMI;
                        }
                        elseif($resultProductSYOHIN_RMEI == '消費税(10%)')
                        {
                            Log::debug("arrayResults['EmployeeSalesResult10']['tax']");
                            $arrayResults['EmployeeSalesResult10']['tax'] = $arrayResults['EmployeeSalesResult10']['tax'] + $resultProductZEIKOMI;

                            $arrayResults['EmployeeSalesResult10']['total'] = $arrayResults['EmployeeSalesResult10']['total'] + $resultProductZEIKOMI;
                        }
                        elseif($resultProductSYOHIN_RMEI == 'その他（雑品）')
                        {
                            if($miscGoodsCode == "01") // 消費税8％雑品
                            {
                                Log::debug("arrayResults['EmployeeSalesResult08']['miscGoods']");
                                $arrayResults['EmployeeSalesResult08']['miscGoods'] = $arrayResults['EmployeeSalesResult08']['miscGoods'] + $resultProductZEIKOMI;

                                $arrayResults['EmployeeSalesResult08']['total'] = $arrayResults['EmployeeSalesResult08']['total'] + $resultProductZEIKOMI;
                            }
                            else //消費税10％雑品
                            {
                                Log::debug("arrayResults['EmployeeSalesResult10']['miscGoods']");
                                $arrayResults['EmployeeSalesResult10']['miscGoods'] = $arrayResults['EmployeeSalesResult10']['miscGoods'] + $resultProductZEIKOMI;

                                $arrayResults['EmployeeSalesResult10']['total'] = $arrayResults['EmployeeSalesResult10']['total'] + $resultProductZEIKOMI;
                            }
                        }
                        else //その他
                        {
                            if($resultProductZEIKOMI != 0)
                            {
                                if(strpos($resultProductSYOHIN_RMEI,"※") !== false) // 消費税8％雑品
                                {
                                    Log::debug("arrayResults['EmployeeSalesResult08']['miscGoods']");
                                    $arrayResults['EmployeeSalesResult08']['miscGoods'] = $arrayResults['EmployeeSalesResult08']['miscGoods'] + $resultProductZEIKOMI;

                                    $arrayResults['EmployeeSalesResult08']['total'] = $arrayResults['EmployeeSalesResult08']['total'] + $resultProductZEIKOMI;
                                }
                                else // 消費税10％雑品
                                {
                                    Log::debug("arrayResults['EmployeeSalesResult10']['miscGoods']");
                                    $arrayResults['EmployeeSalesResult10']['miscGoods'] = $arrayResults['EmployeeSalesResult10']['miscGoods'] + $resultProductZEIKOMI;

                                    $arrayResults['EmployeeSalesResult10']['total'] = $arrayResults['EmployeeSalesResult10']['total'] + $resultProductZEIKOMI;
                                }
                            }
                        }                        
                    }

                }

                Log::debug(print_r($arrayResults,true));
            }
            
        }

        Log::debug("[END] OfficeSalesService::calculateBueatySalonSalesValueByProduct()");
        return $arrayResults;
    }








    public function exportDoorToDoorSellingCsv($arrayResults, $startMonth, $endMonth, $strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $officeCode)
    {

        Log::debug("[START] OfficeSalesService::exportDoorToDoorSellingCsv()");
        Log::debug("[INPUT] startMonth = " . $startMonth);
        Log::debug("[INPUT] endMonth = " . $endMonth);
        Log::debug("[INPUT] strStartDateYYYYMMDD = " . $strStartDateYYYYMMDD);
        Log::debug("[INPUT] strEndDateYYYYMMDD = " . $strEndDateYYYYMMDD);
        Log::debug("[INPUT] officeCode = " . $officeCode);
        Log::debug(print_r($arrayResults,true));

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



        $callback = function () use ($arrayResults, $startMonth, $endMonth, $strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $officeCode) 
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
                "伝票摘要",//""
                "枝番",
                "借方部門",
                "借方部門名",//""
                "借方科目",
                "借方科目名",//""
                "借方補助",
                "借方補助科目名",//""
                "借方金額",
                "借方消費税コード",
                "借方消費税業種",
                "借方消費税税率",
                "借方資金区分",
                "借方任意項目１",//""
                "借方任意項目２",//""
                "貸方部門",
                "貸方部門名",//""
                "貸方科目",
                "貸方科目名",//""
                "貸方補助",
                "貸方補助科目名",//""
                "貸方金額",
                "貸方消費税コード",
                "貸方消費税業種",
                "貸方消費税税率",
                "貸方資金区分",
                "貸方任意項目１",//""
                "貸方任意項目２",//""
                "摘要",//""
                "期日",
                "証番号",//""
                "入力マシン",//""
                "入力ユーザ",//""
                "入力アプリ",//""
                "入力会社",//""
                "入力日付",
            ]; 

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $columns); //文字化け対策    
            fputcsv($createCsvFile, $columns); //1行目の情報を追記

            $slipNo = 0;
            if ($officeCode == "0001")
            {
                $slipNo1 = 141;
                $slipNo2 = 142;
            }
            elseif ($officeCode == "0050")
            {
                $slipNo1 = 143;
                $slipNo2 = 144;
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
                $slipNo1,
                '',
                1,
                '',
                '',
                1130,
                '売掛金１販員',
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult08']['total']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult08']['total']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n訪販商品（軽税*8%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo1,
                '',
                1,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult08']['product']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                8111,
                '売上高１販員',//TODO
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult08']['product']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n訪販商品（軽税*8%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo1,
                '',
                2,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult08']['productArea']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                8112,
                '売上高２代理店',
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult08']['productArea']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n訪販エリア商品（軽税*8%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo1,
                '',
                3,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult08']['miscGoods']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                8113,
                '売上高３雑品',//TODO
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult08']['miscGoods']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n訪販雑品（軽税*8%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo1,
                '',
                4,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult08']['miscGoodsArea']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                8113,
                '売上高３雑品',//TODO
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult08']['miscGoodsArea']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n訪販エリア雑品（軽税*8%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo1,
                '',
                5,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult08']['tax']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                5000,
                '仮受消費税',
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult08']['tax']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n訪販消費税（軽税*8%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する


            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo1,
                '',
                6,
                '',
                '',
                8112,
                '売上高２代理店',
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult08']['returnedProduct']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult08']['returnedProduct']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n訪販商品　返品（軽税*8%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo1,
                '',
                7,
                '',
                '',
                5000,
                '仮受消費税',
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult08']['returnedTax']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult08']['returnedTax']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n訪販消費税　返品（軽税*8%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する



            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo2,
                '',
                1,
                '',
                '',
                1130,
                '売掛金１販員',
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult10']['total']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult10']['total']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n訪販商品（税*10%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo2,
                '',
                1,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult10']['product']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                8111,
                '売上高１販員',//TODO
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult10']['product']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n訪販商品（税*10%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo2,
                '',
                2,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult10']['productArea']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                8112,
                '売上高２代理店',
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult10']['productArea']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n訪販エリア商品（税*10%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo2,
                '',
                3,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult10']['miscGoods']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                8113,
                '売上高３雑品',//TODO
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult10']['miscGoods']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n訪販雑品（税*10%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo2,
                '',
                4,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult10']['miscGoodsArea']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                8113,
                '売上高３雑品',//TODO
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult10']['miscGoodsArea']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n訪販エリア雑品（税*10%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo2,
                '',
                5,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult10']['tax']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                5000,
                '仮受消費税',
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult10']['tax']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n訪販消費税（税*10%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する


            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo2,
                '',
                6,
                '',
                '',
                8112,
                '売上高２代理店',
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult10']['returnedProduct']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult10']['returnedProduct']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n訪販商品　返品（税*10%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo2,
                '',
                7,
                '',
                '',
                5000,
                '仮受消費税',
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult10']['returnedTax']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['DoorToDoorSellingResult10']['returnedTax']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n訪販消費税　返品（税*10%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する


            fclose($createCsvFile); //ファイル閉じる
        };
        
        Log::debug("[END] OfficeSalesService::exportDoorToDoorSellingCsv()");
        return response()->stream($callback, 200, $headers); //ここで実行

    }


    public function getDoorToDoorSellingSalesValue($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $officeCode, $departmentCode, $miscGoodsCode)
    {
        Log::debug("[START] OfficeSalesService::getDoorToDoorSellingSalesValue()");

        $arrayResults = $this->calculateDoorToDoorSellingSalesValue($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $officeCode, $departmentCode, $miscGoodsCode);

        Log::debug("[END] OfficeSalesService::getDoorToDoorSellingSalesValue()");
        return $arrayResults;
    }


    private function calculateDoorToDoorSellingSalesValue($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $officeCode, $departmentCode, $miscGoodsCode)
    {
        Log::debug("[START] OfficeSalesService::calculateDoorToDoorSellingSalesValue()");

        $strHenpin_Kbn = "00";
        $strSyahan_Cd = "00";
        $strTuhan_Kbn = "01";
        $strHeadTantou_cd = "";
        $strHeadKokyaku_cd ="";
        $strJigyo_Cd = $officeCode;
        $strBu_cd = $departmentCode;
        $strSyozokuJigyo_Cd = "";
        $strSyozokuBu_Cd = "00";


        //10%　社販結果
        $arrayDoorToDoorSellingResult10 = array('product'=> 0, 'productArea'=> 0, 'miscGoods'=> 0, 'miscGoodsArea'=> 0, 'tax'=> 0, 'returnedProduct'=> 0, 'returnedTax'=> 0, 'total'=>0);

        //8%　社販結果
        $arrayDoorToDoorSellingResult08 = array('product'=> 0, 'productArea'=> 0, 'miscGoods'=> 0, 'miscGoodsArea'=> 0, 'tax'=> 0, 'returnedProduct'=> 0, 'returnedTax'=> 0, 'total'=>0);

        $arrayResults = array('DoorToDoorSellingResult10'=> $arrayDoorToDoorSellingResult10 , 'DoorToDoorSellingResult08'=> $arrayDoorToDoorSellingResult08);


        //雑品
        $strZappin_cd = "02";

        $results = $this->getSalesHeaderRecords($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $strHenpin_Kbn, $strZappin_cd, $strSyahan_Cd, $strTuhan_Kbn, $strHeadTantou_cd, $strHeadKokyaku_cd, $strJigyo_Cd, $strBu_cd, $strSyozokuJigyo_Cd, $strSyozokuBu_Cd);

        $arrayResults = $this->calculateDoorToDoorSellingSalesValueByProduct($strZappin_cd, $miscGoodsCode, $results, $arrayResults);


        //雑品以外
        $strZappin_cd = "01";

        $results = $this->getSalesHeaderRecords($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $strHenpin_Kbn, $strZappin_cd, $strSyahan_Cd, $strTuhan_Kbn, $strHeadTantou_cd, $strHeadKokyaku_cd, $strJigyo_Cd, $strBu_cd, $strSyozokuJigyo_Cd, $strSyozokuBu_Cd);

        $arrayResults = $this->calculateDoorToDoorSellingSalesValueByProduct($strZappin_cd, $miscGoodsCode, $results, $arrayResults);


        Log::debug("[END] OfficeSalesService::calculateDoorToDoorSellingSalesValue()");
        return $arrayResults;
    }



    private function calculateDoorToDoorSellingSalesValueByProduct($strZappin_cd, $miscGoodsCode, $results, $arrayResults)
    {
        Log::debug("[START] OfficeSalesService::calculateDoorToDoorSellingSalesValueByProduct()");


        foreach ($results as $result) 
        {
            $resultURIAGEBI = $result->URIAGEBI;
            $resultTANTOU_CD = $result->TANTOU_CD;
            $resultKOKYAKU_CD = $result->KOKYAKU_CD;
            $resultBU_CD = $result->BU_CD;
            $resultHENPIN_KBN = $result->HENPIN_KBN;
            $resultKBN = $result->KBN;
            $resultJIGYO_CD = $result->JIGYO_CD;
            //'========================================================
            //'   売上データ取得処理
            //'========================================================
            /*
            '--------------------------------------------------------
            '   取得カラム：売上日(204)、担当者漢字姓(101)
            '              担当者漢字名(101)、顧客漢字姓(201)
            '              個顧客漢字名(201)、商品略称(103)
            '              上代(204)、商品個数(204)
            '              税抜き額(204)、 消費税額(204)
            '              税込み額(204)、販売担当者コード(204)
            '              顧客コード(204)、商品区分コード(204)
            '              商品コード(204)、売上コード(204)
            '              部略称(110)、返品区分コード(904)
            '              返品日(904)、備考(904)
            '              事業所略称(102)
            '              フォローNO(204)
            '   対象テーブル：商品売上テーブル
            '                社員マスターテーブル
            '                顧客マスターテーブル
            '                商品マスターテーブル
            '                部マスターテーブル
            '                事業所マスターテーブル
            '   条件：売上日が任意のもの
            '         販売担当者コードが任意のもの
            '         顧客コードが任意のもの
            '         事業所コードが任意のもの
            '         部コードが任意のもの
            '         返品区分コードが任意のもの
            '---------------------------------------------------------
            */
            $strSQL = "SELECT TB204.URIAGEBI, TB101.KANJI_SEI AS TANTOU_SEI, TB101.KANJI_MEI AS TANTOU_MEI, TB201.KANJI_SEI AS KOKYAKU_SEI, TB201.KANJI_MEI AS KOKAYKU_MEI, "
                    ."TB103.SYOHIN_RMEI, TB204.ZYODAI, TB204.SYOHINNUM, TB204.ZEINUKI, TB204.SYOHIZEI, TB204.ZEIKOMI, "
                    ."TB204.TANTOU_CD, TB204.KOKYAKU_CD, TB204.SYOHIN_KBN, TB204.SYOHIN_CD, TB204.URIAGE_CD, TB110.BU_RMEI, TB204.HENPIN_KBN, TB204.HENPINBI, TB204.TEKIYOU, TB102.JIGYO_RMEI, TB204.FOLLOW_NO "
                    ."FROM TW_HB204 TB204, TM_BS101 TB101, TM_HB201 TB201, TM_BS103 TB103, TM_BS110 TB110, TM_BS102 TB102 "
                    ."WHERE TB204.URIAGEBI ='" . $resultURIAGEBI . "' "
                    ."AND TB204.TANTOU_CD ='" . $resultTANTOU_CD . "' "
                    ."AND TB204.KOKYAKU_CD ='" . $resultKOKYAKU_CD . "' "
                    ."AND TB204.BU_CD ='" . $resultBU_CD . "' "
                    ."AND TB204.HENPIN_KBN ='" . $resultHENPIN_KBN . "' "
                    ."AND ";                                                                      
                
            if ($resultKBN == 1)
            {
                $strSQL = $strSQL . "TB204.JIGYO_CD ='" . $resultJIGYO_CD . "' ";   
            }
            else
            {
                $strSQL = $strSQL . "TB101.JIGYO_CD ='" . $resultJIGYO_CD . "' ";           
            }

            
            if ($strZappin_cd == "01")
            {
                //CO_CODE_ZAPPIN = "96"
                $strSQL = $strSQL . " AND TB204.BUNBETU_CD NOT IN ('96') ";
            }
                            
            if ($strZappin_cd == "02")
            {
                //CO_CODE_ZAPPIN = "96"
                $strSQL = $strSQL . " AND TB204.BUNBETU_CD = '96' ";
            }
                
            $strSQL = $strSQL . "AND TB101.SYAIN_CD = TB204.TANTOU_CD "
                    ."AND TB201.KOKYAKU_CD = TB204.KOKYAKU_CD "
                    ."AND TB103.SYOHIN_KBN = TB204.SYOHIN_KBN "
                    ."AND TB103.SYOHIN_CD = TB204.SYOHIN_CD "
                    ."AND TB110.BU_CD = TB204.BU_CD "
                    ."AND TB204.JIGYO_CD = TB102.JIGYO_CD "
                    ."ORDER BY TB204.HENPIN_KBN ";
            

            $resultsProduct = DB::select($strSQL);


            foreach ($resultsProduct as $resultProduct)
            {                                                        
                $resultProductJIGYO_RMEI = $resultProduct->JIGYO_RMEI;//売上事業所
                $resultProductBU_RMEI = $resultProduct->BU_RMEI;//売上部署
                Log::debug("resultProductJIGYO_RMEI = " . $resultProductJIGYO_RMEI);
                Log::debug("resultProductBU_RMEI = " . $resultProductBU_RMEI);

                $resultProductSYOHIN_RMEI = $resultProduct->SYOHIN_RMEI;//商品名
                $resultProductZEINUKI = $resultProduct->ZEINUKI;//金額
                $resultProductSYOHIZEI = $resultProduct->SYOHIZEI;//消費税額
                $resultProductZEIKOMI = $resultProduct->ZEIKOMI;//小計
                Log::debug("resultProductSYOHIN_RMEI = " . $resultProductSYOHIN_RMEI);
                Log::debug("resultProductZEIKOMI = " . $resultProductZEIKOMI);
                
                $resultProductTANTOU_SEI = $resultProduct->TANTOU_SEI;//販売担当者姓
                $resultProductTANTOU_MEI = $resultProduct->TANTOU_MEI;//販売担当者名
                $strTantou_Mei = $resultProductTANTOU_SEI . "　" . $resultProductTANTOU_MEI;
                Log::debug("strTantou_Mei = " . $strTantou_Mei);

                //$resultProductKOKYAKU_SEI = $resultProduct->KOKYAKU_SEI;//顧客姓
                //$resultProductKOKYAKU_MEI = $resultProduct->KOKYAKU_MEI;//顧客名
                //$strKokyaku_Mei = $resultProductKOKYAKU_SEI . "　" . $resultProductKOKYAKU_MEI;
                //Log::debug("strKokyaku_Mei = " . $strKokyaku_Mei);


                $resultProductHENPIN_KBN = $resultProduct->HENPIN_KBN;//返品区分
                $resultProductHENPINBI = $resultProduct->HENPINBI;//返品日
                if ($resultProductHENPIN_KBN == "01")//CO_CODE_URIAGE="01"
                {
                    $strKbn = "売上";
                }
                elseif ($resultProductHENPIN_KBN == "02")//CO_CODE_HENPIN="02"
                {
                    $strKbn = "返品";
                }
                elseif ($resultProductHENPIN_KBN == "03")//CO_CODE_MIHON="03"
                {
                    $strKbn = "見本";
                }
                Log::debug("strKbn = " . $strKbn);
                Log::debug("resultProductHENPINBI = " . $resultProductHENPINBI);



                // 雑品以外
                if ($strZappin_cd == "01")
                {
                    if($resultProductZEIKOMI != 0)
                    {
                        if($strKbn != '返品') // 返品以外
                        {
                            if(strpos($resultProductSYOHIN_RMEI,'※') !== false) // 消費税8％商品
                            {
                                if (strpos($strTantou_Mei,'（エリア）') !== false) // エリア
                                {
                                    Log::debug("arrayResults['DoorToDoorSellingResult08']['productArea']");
                                    $arrayResults['DoorToDoorSellingResult08']['productArea'] = $arrayResults['DoorToDoorSellingResult08']['productArea'] + $resultProductZEIKOMI;

                                    $arrayResults['DoorToDoorSellingResult08']['total'] = $arrayResults['DoorToDoorSellingResult08']['total'] + $resultProductZEIKOMI;
                                }
                                else //その他
                                {                                    
                                    Log::debug("arrayResults['DoorToDoorSellingResult08']['product']");
                                    $arrayResults['DoorToDoorSellingResult08']['product'] = $arrayResults['DoorToDoorSellingResult08']['product'] + $resultProductZEIKOMI;

                                    $arrayResults['DoorToDoorSellingResult08']['total'] = $arrayResults['DoorToDoorSellingResult08']['total'] + $resultProductZEIKOMI;
                                }
                            }
                            else // 消費税10％商品
                            {
                                if (strpos($strTantou_Mei,'（エリア）') !== false) // エリア
                                {
                                    Log::debug("arrayResults['DoorToDoorSellingResult10']['productArea']");
                                    $arrayResults['DoorToDoorSellingResult10']['productArea'] = $arrayResults['DoorToDoorSellingResult10']['productArea'] + $resultProductZEIKOMI;

                                    $arrayResults['DoorToDoorSellingResult10']['total'] = $arrayResults['DoorToDoorSellingResult10']['total'] + $resultProductZEIKOMI;
                                }
                                else // その他
                                {
                                    Log::debug("arrayResults['DoorToDoorSellingResult10']['product']");
                                    $arrayResults['DoorToDoorSellingResult10']['product'] = $arrayResults['DoorToDoorSellingResult10']['product'] + $resultProductZEIKOMI;

                                    $arrayResults['DoorToDoorSellingResult10']['total'] = $arrayResults['DoorToDoorSellingResult10']['total'] + $resultProductZEIKOMI;
                                }
                            }                            
                        }
                        else //返品
                        {
                            if (empty($resultProductHENPINBI) or is_null($resultProductHENPINBI) or $resultProductHENPINBI >= '20191001')
                            {
                                if(strpos($resultProductSYOHIN_RMEI,'※') !== false) // 消費税8％商品
                                {
                                    Log::debug("arrayResults['DoorToDoorSellingResult08']['returnedProduct']");
                                    $arrayResults['DoorToDoorSellingResult08']['returnedProduct'] = $arrayResults['DoorToDoorSellingResult08']['returnedProduct'] + $resultProductZEINUKI * (-1);
                                }
                                else // 消費税10％商品
                                {
                                    Log::debug("arrayResults['DoorToDoorSellingResult10']['returnedProduct']");
                                    $arrayResults['DoorToDoorSellingResult10']['returnedProduct'] = $arrayResults['DoorToDoorSellingResult10']['returnedProduct'] + $resultProductZEINUKI * (-1);
                                }
                            }
                            elseif ($resultProductHENPINBI <= '20190931')
                            {
                                Log::debug("arrayResults['DoorToDoorSellingResult08']['returnedProduct']");
                                $arrayResults['DoorToDoorSellingResult08']['returnedProduct'] = $arrayResults['DoorToDoorSellingResult08']['returnedProduct'] + $resultProductZEINUKI * (-1);             

                                Log::debug("arrayResults['DoorToDoorSellingResult08']['returnedTax']");
                                $arrayResults['DoorToDoorSellingResult08']['returnedTax'] = $arrayResults['DoorToDoorSellingResult08']['returnedTax'] + $resultProductSYOHIZEI * (-1);
                            }

                        }
                    }
                }
                

                // 雑品
                if ($strZappin_cd == "02")
                {
                    if($strKbn != '返品')
                    {
                        if($resultProductSYOHIN_RMEI == '消費税(8%)')
                        {
                            Log::debug("arrayResults['DoorToDoorSellingResult08']['tax']");
                            $arrayResults['DoorToDoorSellingResult08']['tax'] = $arrayResults['DoorToDoorSellingResult08']['tax'] + $resultProductZEIKOMI;

                            $arrayResults['DoorToDoorSellingResult08']['total'] = $arrayResults['DoorToDoorSellingResult08']['total'] + $resultProductZEIKOMI;
                        }
                        elseif($resultProductSYOHIN_RMEI == '消費税(10%)')
                        {
                            Log::debug("arrayResults['DoorToDoorSellingResult10']['tax']");
                            $arrayResults['DoorToDoorSellingResult10']['tax'] = $arrayResults['DoorToDoorSellingResult10']['tax'] + $resultProductZEIKOMI;

                            $arrayResults['DoorToDoorSellingResult10']['total'] = $arrayResults['DoorToDoorSellingResult10']['total'] + $resultProductZEIKOMI;
                        }
                        elseif($resultProductSYOHIN_RMEI == 'その他（雑品）')
                        {
                            if($miscGoodsCode == "01") // 消費税8％雑品
                            {
                                if (strpos($strTantou_Mei,'（エリア）') !== false) // エリア
                                {
                                    Log::debug("arrayResults['DoorToDoorSellingResult08']['miscGoodsArea']");
                                    $arrayResults['DoorToDoorSellingResult08']['miscGoodsArea'] = $arrayResults['DoorToDoorSellingResult08']['miscGoodsArea'] + $resultProductZEIKOMI;

                                    $arrayResults['DoorToDoorSellingResult08']['total'] = $arrayResults['DoorToDoorSellingResult08']['total'] + $resultProductZEIKOMI;
                                }
                                else // その他
                                {
                                    Log::debug("arrayResults['DoorToDoorSellingResult08']['miscGoods']");
                                    $arrayResults['DoorToDoorSellingResult08']['miscGoods'] = $arrayResults['DoorToDoorSellingResult08']['miscGoods'] + $resultProductZEIKOMI;

                                    $arrayResults['DoorToDoorSellingResult08']['total'] = $arrayResults['DoorToDoorSellingResult08']['total'] + $resultProductZEIKOMI;
                                }
                            }
                            else //消費税10％雑品
                            {
                                if (strpos($strTantou_Mei,'（エリア）') !== false) // エリア
                                {
                                    Log::debug("arrayResults['DoorToDoorSellingResult10']['miscGoodsArea']");
                                    $arrayResults['DoorToDoorSellingResult10']['miscGoodsArea'] = $arrayResults['DoorToDoorSellingResult10']['miscGoodsArea'] + $resultProductZEIKOMI;

                                    $arrayResults['DoorToDoorSellingResult10']['total'] = $arrayResults['DoorToDoorSellingResult10']['total'] + $resultProductZEIKOMI;
                                }
                                else // その他
                                {
                                    Log::debug("arrayResults['DoorToDoorSellingResult10']['miscGoods']");
                                    $arrayResults['DoorToDoorSellingResult10']['miscGoods'] = $arrayResults['DoorToDoorSellingResult10']['miscGoods'] + $resultProductZEIKOMI;

                                    $arrayResults['DoorToDoorSellingResult10']['total'] = $arrayResults['DoorToDoorSellingResult10']['total'] + $resultProductZEIKOMI;
                                }
                            }

                        }
                        else //その他
                        {
                            if($resultProductZEIKOMI != 0)
                            {
                                if(strpos($resultProductSYOHIN_RMEI,'※') !== false) // 消費税8％雑品
                                {
                                    if (strpos($strTantou_Mei,'（エリア）') !== false) // エリア
                                    {
                                        Log::debug("arrayResults['DoorToDoorSellingResult08']['miscGoodsArea']");
                                        $arrayResults['DoorToDoorSellingResult08']['miscGoodsArea'] = $arrayResults['DoorToDoorSellingResult08']['miscGoodsArea'] + $resultProductZEIKOMI;

                                        $arrayResults['DoorToDoorSellingResult08']['total'] = $arrayResults['DoorToDoorSellingResult08']['total'] + $resultProductZEIKOMI;
                                    }
                                    else // その他
                                    {
                                        Log::debug("arrayResults['DoorToDoorSellingResult08']['miscGoods']");
                                        $arrayResults['DoorToDoorSellingResult08']['miscGoods'] = $arrayResults['DoorToDoorSellingResult08']['miscGoods'] + $resultProductZEIKOMI;

                                        $arrayResults['DoorToDoorSellingResult08']['total'] = $arrayResults['DoorToDoorSellingResult08']['total'] + $resultProductZEIKOMI;
                                    }
                                }
                                else // 消費税10％雑品
                                {
                                    if (strpos($strTantou_Mei,'（エリア）') !== false) // エリア
                                    {
                                        Log::debug("arrayResults['DoorToDoorSellingResult10']['miscGoodsArea']");
                                        $arrayResults['DoorToDoorSellingResult10']['miscGoodsArea'] = $arrayResults['DoorToDoorSellingResult10']['miscGoodsArea'] + $resultProductZEIKOMI;
    
                                        $arrayResults['DoorToDoorSellingResult10']['total'] = $arrayResults['DoorToDoorSellingResult10']['total'] + $resultProductZEIKOMI;
                                    }
                                    else // その他
                                    {
                                        Log::debug("arrayResults['DoorToDoorSellingResult10']['miscGoods']");
                                        $arrayResults['DoorToDoorSellingResult10']['miscGoods'] = $arrayResults['DoorToDoorSellingResult10']['miscGoods'] + $resultProductZEIKOMI;

                                        $arrayResults['DoorToDoorSellingResult10']['total'] = $arrayResults['DoorToDoorSellingResult10']['total'] + $resultProductZEIKOMI;
                                    }
                                }
                            }
                        }                        
                    }
                    else //返品
                    {
                        if($resultProductSYOHIN_RMEI == '消費税(8%)') // 消費税8％商品
                        {
                            Log::debug("arrayResults['DoorToDoorSellingResult08']['returnedTax']");
                            $arrayResults['DoorToDoorSellingResult08']['returnedTax'] = $resultProductZEIKOMI * (-1);
                        }
                        elseif($resultProductSYOHIN_RMEI == '消費税(10%)') // 消費税10％商品
                        {
                            Log::debug("arrayResults['DoorToDoorSellingResult10']['returnedTax']");
                            $arrayResults['DoorToDoorSellingResult10']['returnedTax'] = $resultProductZEIKOMI * (-1);
                        }

                    }

                }

                Log::debug(print_r($arrayResults,true));
            }
            
        }

        Log::debug("[END] OfficeSalesService::calculateDoorToDoorSellingSalesValueByProduct()");
        return $arrayResults;
    }










    public function exportOnlineShoppingCsv($arrayResults, $startMonth, $endMonth, $strStartDateYYYYMMDD, $strEndDateYYYYMMDD)
    {

        Log::debug("[START] OfficeSalesService::exportDoorToDoorSellingCsv()");
        Log::debug("[INPUT] startMonth = " . $startMonth);
        Log::debug("[INPUT] endMonth = " . $endMonth);
        Log::debug("[INPUT] strStartDateYYYYMMDD = " . $strStartDateYYYYMMDD);
        Log::debug("[INPUT] strEndDateYYYYMMDD = " . $strEndDateYYYYMMDD);
        Log::debug(print_r($arrayResults,true));

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

        $callback = function () use ($arrayResults, $startMonth, $endMonth, $strStartDateYYYYMMDD, $strEndDateYYYYMMDD) 
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
                "伝票摘要",//""
                "枝番",
                "借方部門",
                "借方部門名",//""
                "借方科目",
                "借方科目名",//""
                "借方補助",
                "借方補助科目名",//""
                "借方金額",
                "借方消費税コード",
                "借方消費税業種",
                "借方消費税税率",
                "借方資金区分",
                "借方任意項目１",//""
                "借方任意項目２",//""
                "貸方部門",
                "貸方部門名",//""
                "貸方科目",
                "貸方科目名",//""
                "貸方補助",
                "貸方補助科目名",//""
                "貸方金額",
                "貸方消費税コード",
                "貸方消費税業種",
                "貸方消費税税率",
                "貸方資金区分",
                "貸方任意項目１",//""
                "貸方任意項目２",//""
                "摘要",//""
                "期日",
                "証番号",//""
                "入力マシン",//""
                "入力ユーザ",//""
                "入力アプリ",//""
                "入力会社",//""
                "入力日付",
            ]; 

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $columns); //文字化け対策    
            fputcsv($createCsvFile, $columns); //1行目の情報を追記

            $slipNo1 = 151;
            $slipNo2 = 152;

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo1,
                '',
                1,
                '',
                '',
                1133,
                '売掛金６通販',
                0,
                '',
                intval($arrayResults['OnlineShoppingResult08']['total']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['OnlineShoppingResult08']['total']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n通販商品（軽税*8%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          


            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo1,
                '',
                1,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['OnlineShoppingResult08']['product']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                8115,
                '売上高６通販',
                0,
                '',
                intval($arrayResults['OnlineShoppingResult08']['product']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n通販商品（軽税*8%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          


            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo1,
                '',
                2,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['OnlineShoppingResult08']['miscGoods']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                8113,
                '売上高３雑品',//TODO
                0,
                '',
                intval($arrayResults['OnlineShoppingResult08']['miscGoods']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n通販雑品（軽税*8%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo1,
                '',
                3,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['OnlineShoppingResult08']['tax']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                5000,
                '仮受消費税',
                0,
                '',
                intval($arrayResults['OnlineShoppingResult08']['tax']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n通販消費税（軽税*8%） ",
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          


            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo1,
                '',
                4,
                '',
                '',
                8115,
                '売上高６通販',
                0,
                '',
                intval($arrayResults['OnlineShoppingResult08']['returnedProduct']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['OnlineShoppingResult08']['returnedProduct']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n通販商品　返品（軽税*8%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo1,
                '',
                5,
                '',
                '',
                5000,
                '仮受消費税',
                0,
                '',
                intval($arrayResults['OnlineShoppingResult08']['returnedTax']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['OnlineShoppingResult08']['returnedTax']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n通販消費税　返品（軽税*8%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する


            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo2,
                '',
                1,
                '',
                '',
                1133,
                '売掛金６通販',
                0,
                '',
                intval($arrayResults['OnlineShoppingResult10']['total']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['OnlineShoppingResult10']['total']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n通販商品（税*10%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          


            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo2,
                '',
                1,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['OnlineShoppingResult10']['product']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                8115,
                '売上高６通販',
                0,
                '',
                intval($arrayResults['OnlineShoppingResult10']['product']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n通販商品（税*10%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          


            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo2,
                '',
                2,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['OnlineShoppingResult10']['miscGoods']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                8113,
                '売上高３雑品',//TODO
                0,
                '',
                intval($arrayResults['OnlineShoppingResult10']['miscGoods']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n通販雑品（税*10%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo2,
                '',
                3,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['OnlineShoppingResult10']['tax']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                5000,
                '仮受消費税',
                0,
                '',
                intval($arrayResults['OnlineShoppingResult10']['tax']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n通販消費税（税*10%） ",
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          


            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo2,
                '',
                4,
                '',
                '',
                8115,
                '売上高６通販',
                0,
                '',
                intval($arrayResults['OnlineShoppingResult10']['returnedProduct']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['OnlineShoppingResult10']['returnedProduct']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n通販商品　返品（税*10%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo2,
                '',
                5,
                '',
                '',
                5000,
                '仮受消費税',
                0,
                '',
                intval($arrayResults['OnlineShoppingResult10']['returnedTax']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['OnlineShoppingResult10']['returnedTax']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n通販消費税　返品（税*10%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する


            fclose($createCsvFile); //ファイル閉じる
        };
        
        Log::debug("[END] OfficeSalesService::exportOnlineShoppingCsv()");
        return response()->stream($callback, 200, $headers); //ここで実行

    }


    public function getOnlineShoppingSalesValue($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $officeCode, $departmentCode, $miscGoodsCode)
    {
        Log::debug("[START] OfficeSalesService::getOnlineShoppingSalesValue()");

        $arrayResults = $this->calculateOnlineShoppingSalesValue($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $officeCode, $departmentCode, $miscGoodsCode);

        Log::debug("[END] OfficeSalesService::getOnlineShoppingSalesValue()");
        return $arrayResults;
    }


    private function calculateOnlineShoppingSalesValue($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $officeCode, $departmentCode,  $miscGoodsCode)
    {
        Log::debug("[START] OfficeSalesService::calculateOnlineShoppingSalesValue()");

        $strHenpin_Kbn = "00";
        $strSyahan_Cd = "00";
        $strTuhan_Kbn = "01";
        $strHeadTantou_cd = "";
        $strHeadKokyaku_cd ="";
        $strJigyo_Cd = $officeCode;
        $strBu_cd = $departmentCode;
        $strSyozokuJigyo_Cd = "";
        $strSyozokuBu_Cd = "00";


        //10%　通販結果
        $arrayOnlineShoppingResult10 = array('product'=> 0, 'miscGoods'=> 0, 'tax'=> 0, 'returnedProduct'=> 0, 'returnedTax'=> 0, 'total'=>0);

        //8%　通販結果
        $arrayOnlineShoppingResult08 = array('product'=> 0, 'miscGoods'=> 0, 'tax'=> 0, 'returnedProduct'=> 0, 'returnedTax'=> 0, 'total'=>0);

        $arrayResults = array('OnlineShoppingResult10'=> $arrayOnlineShoppingResult10 , 'OnlineShoppingResult08'=> $arrayOnlineShoppingResult08);


        //雑品
        $strZappin_cd = "02";

        $results = $this->getSalesHeaderRecords($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $strHenpin_Kbn, $strZappin_cd, $strSyahan_Cd, $strTuhan_Kbn, $strHeadTantou_cd, $strHeadKokyaku_cd, $strJigyo_Cd, $strBu_cd, $strSyozokuJigyo_Cd, $strSyozokuBu_Cd);

        $arrayResults = $this->calculateOnlineShoppingSalesValueByProduct($strZappin_cd, $miscGoodsCode, $results, $arrayResults);


        //雑品以外
        $strZappin_cd = "01";

        $results = $this->getSalesHeaderRecords($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $strHenpin_Kbn, $strZappin_cd, $strSyahan_Cd, $strTuhan_Kbn, $strHeadTantou_cd, $strHeadKokyaku_cd, $strJigyo_Cd, $strBu_cd, $strSyozokuJigyo_Cd, $strSyozokuBu_Cd);

        $arrayResults = $this->calculateOnlineShoppingSalesValueByProduct($strZappin_cd, $miscGoodsCode, $results, $arrayResults);


        Log::debug("[END] OfficeSalesService::calculateOnlineShoppingSalesValue()");
        return $arrayResults;
    }



    private function calculateOnlineShoppingSalesValueByProduct($strZappin_cd, $miscGoodsCode, $results, $arrayResults)
    {
        Log::debug("[START] OfficeSalesService::calculateOnlineShoppingSalesValueByProduct()");


        foreach ($results as $result) 
        {
            $resultURIAGEBI = $result->URIAGEBI;
            $resultTANTOU_CD = $result->TANTOU_CD;
            $resultKOKYAKU_CD = $result->KOKYAKU_CD;
            $resultBU_CD = $result->BU_CD;
            $resultHENPIN_KBN = $result->HENPIN_KBN;
            $resultKBN = $result->KBN;
            $resultJIGYO_CD = $result->JIGYO_CD;
            //'========================================================
            //'   売上データ取得処理
            //'========================================================
            /*
            '--------------------------------------------------------
            '   取得カラム：売上日(204)、担当者漢字姓(101)
            '              担当者漢字名(101)、顧客漢字姓(201)
            '              個顧客漢字名(201)、商品略称(103)
            '              上代(204)、商品個数(204)
            '              税抜き額(204)、 消費税額(204)
            '              税込み額(204)、販売担当者コード(204)
            '              顧客コード(204)、商品区分コード(204)
            '              商品コード(204)、売上コード(204)
            '              部略称(110)、返品区分コード(904)
            '              返品日(904)、備考(904)
            '              事業所略称(102)
            '              フォローNO(204)
            '   対象テーブル：商品売上テーブル
            '                社員マスターテーブル
            '                顧客マスターテーブル
            '                商品マスターテーブル
            '                部マスターテーブル
            '                事業所マスターテーブル
            '   条件：売上日が任意のもの
            '         販売担当者コードが任意のもの
            '         顧客コードが任意のもの
            '         事業所コードが任意のもの
            '         部コードが任意のもの
            '         返品区分コードが任意のもの
            '---------------------------------------------------------
            */
            $strSQL = "SELECT TB204.URIAGEBI, TB101.KANJI_SEI AS TANTOU_SEI, TB101.KANJI_MEI AS TANTOU_MEI, TB201.KANJI_SEI AS KOKYAKU_SEI, TB201.KANJI_MEI AS KOKAYKU_MEI, "
                    ."TB103.SYOHIN_RMEI, TB204.ZYODAI, TB204.SYOHINNUM, TB204.ZEINUKI, TB204.SYOHIZEI, TB204.ZEIKOMI, "
                    ."TB204.TANTOU_CD, TB204.KOKYAKU_CD, TB204.SYOHIN_KBN, TB204.SYOHIN_CD, TB204.URIAGE_CD, TB110.BU_RMEI, TB204.HENPIN_KBN, TB204.HENPINBI, TB204.TEKIYOU, TB102.JIGYO_RMEI, TB204.FOLLOW_NO "
                    ."FROM TW_HB204 TB204, TM_BS101 TB101, TM_HB201 TB201, TM_BS103 TB103, TM_BS110 TB110, TM_BS102 TB102 "
                    ."WHERE TB204.URIAGEBI ='" . $resultURIAGEBI . "' "
                    ."AND TB204.TANTOU_CD ='" . $resultTANTOU_CD . "' "
                    ."AND TB204.KOKYAKU_CD ='" . $resultKOKYAKU_CD . "' "
                    ."AND TB204.BU_CD ='" . $resultBU_CD . "' "
                    ."AND TB204.HENPIN_KBN ='" . $resultHENPIN_KBN . "' "
                    ."AND ";                                                                      
                
            if ($resultKBN == 1)
            {
                $strSQL = $strSQL . "TB204.JIGYO_CD ='" . $resultJIGYO_CD . "' ";   
            }
            else
            {
                $strSQL = $strSQL . "TB101.JIGYO_CD ='" . $resultJIGYO_CD . "' ";           
            }

            
            if ($strZappin_cd == "01")
            {
                //CO_CODE_ZAPPIN = "96"
                $strSQL = $strSQL . " AND TB204.BUNBETU_CD NOT IN ('96') ";
            }
                            
            if ($strZappin_cd == "02")
            {
                //CO_CODE_ZAPPIN = "96"
                $strSQL = $strSQL . " AND TB204.BUNBETU_CD = '96' ";
            }
                
            $strSQL = $strSQL . "AND TB101.SYAIN_CD = TB204.TANTOU_CD "
                    ."AND TB201.KOKYAKU_CD = TB204.KOKYAKU_CD "
                    ."AND TB103.SYOHIN_KBN = TB204.SYOHIN_KBN "
                    ."AND TB103.SYOHIN_CD = TB204.SYOHIN_CD "
                    ."AND TB110.BU_CD = TB204.BU_CD "
                    ."AND TB204.JIGYO_CD = TB102.JIGYO_CD "
                    ."ORDER BY TB204.HENPIN_KBN ";
            

            $resultsProduct = DB::select($strSQL);


            foreach ($resultsProduct as $resultProduct)
            {                                                        
                $resultProductJIGYO_RMEI = $resultProduct->JIGYO_RMEI;//売上事業所
                $resultProductBU_RMEI = $resultProduct->BU_RMEI;//売上部署
                Log::debug("resultProductJIGYO_RMEI = " . $resultProductJIGYO_RMEI);
                Log::debug("resultProductBU_RMEI = " . $resultProductBU_RMEI);

                $resultProductSYOHIN_RMEI = $resultProduct->SYOHIN_RMEI;//商品名
                $resultProductZEINUKI = $resultProduct->ZEINUKI;//金額
                $resultProductSYOHIZEI = $resultProduct->SYOHIZEI;//消費税額
                $resultProductZEIKOMI = $resultProduct->ZEIKOMI;//小計
                Log::debug("resultProductSYOHIN_RMEI = " . $resultProductSYOHIN_RMEI);
                Log::debug("resultProductZEIKOMI = " . $resultProductZEIKOMI);
                
                $resultProductTANTOU_SEI = $resultProduct->TANTOU_SEI;//販売担当者姓
                $resultProductTANTOU_MEI = $resultProduct->TANTOU_MEI;//販売担当者名
                $strTantou_Mei = $resultProductTANTOU_SEI . "　" . $resultProductTANTOU_MEI;
                Log::debug("strTantou_Mei = " . $strTantou_Mei);

                //$resultProductKOKYAKU_SEI = $resultProduct->KOKYAKU_SEI;//顧客姓
                //$resultProductKOKYAKU_MEI = $resultProduct->KOKYAKU_MEI;//顧客名
                //$strKokyaku_Mei = $resultProductKOKYAKU_SEI . "　" . $resultProductKOKYAKU_MEI;
                //Log::debug("strKokyaku_Mei = " . $strKokyaku_Mei);


                $resultProductHENPIN_KBN = $resultProduct->HENPIN_KBN;//返品区分
                $resultProductHENPINBI = $resultProduct->HENPINBI;//返品日
                if ($resultProductHENPIN_KBN == "01")//CO_CODE_URIAGE="01"
                {
                    $strKbn = "売上";
                }
                elseif ($resultProductHENPIN_KBN == "02")//CO_CODE_HENPIN="02"
                {
                    $strKbn = "返品";
                }
                elseif ($resultProductHENPIN_KBN == "03")//CO_CODE_MIHON="03"
                {
                    $strKbn = "見本";
                }
                Log::debug("strKbn = " . $strKbn);
                Log::debug("resultProductHENPINBI = " . $resultProductHENPINBI);


                // 雑品以外
                if ($strZappin_cd == "01")
                {
                    if($resultProductZEIKOMI != 0)
                    {
                        if($strKbn != '返品') // 返品以外
                        {
                            if(strpos($resultProductSYOHIN_RMEI,"※") !== false) // 消費税8％商品
                            {
                                Log::debug("arrayResults['OnlineShoppingResult08']['product']");
                                $arrayResults['OnlineShoppingResult08']['product'] = $arrayResults['OnlineShoppingResult08']['product'] + $resultProductZEIKOMI;

                                $arrayResults['OnlineShoppingResult08']['total'] = $arrayResults['OnlineShoppingResult08']['total'] + $resultProductZEIKOMI;
                            }
                            else // 消費税10％商品
                            {
                                Log::debug("arrayResults['OnlineShoppingResult10']['product']");
                                $arrayResults['OnlineShoppingResult10']['product'] = $arrayResults['OnlineShoppingResult10']['product'] + $resultProductZEIKOMI;

                                $arrayResults['OnlineShoppingResult10']['total'] = $arrayResults['OnlineShoppingResult10']['total'] + $resultProductZEIKOMI;
                            }                            
                        }
                        else //返品
                        {
                            if (empty($resultProductHENPINBI) or is_null($resultProductHENPINBI) or $resultProductHENPINBI >= '20191001')
                            {
                                if(strpos($resultProductSYOHIN_RMEI,'※') !== false) // 消費税8％商品
                                {
                                    Log::debug("arrayResults['OnlineShoppingResult08']['returnedProduct']");
                                    $arrayResults['OnlineShoppingResult08']['returnedProduct'] = $arrayResults['OnlineShoppingResult08']['returnedProduct'] + $resultProductZEINUKI * (-1);
                                }
                                else // 消費税10％商品
                                {
                                    Log::debug("arrayResults['OnlineShoppingResult10']['returnedProduct']");
                                    $arrayResults['OnlineShoppingResult10']['returnedProduct'] = $arrayResults['OnlineShoppingResult10']['returnedProduct'] + $resultProductZEINUKI * (-1);
                                }
                            }
                            elseif ($resultProductHENPINBI <= '20190931')
                            {
                                Log::debug("arrayResults['OnlineShoppingResult08']['returnedProduct']");
                                $arrayResults['OnlineShoppingResult08']['returnedProduct'] = $arrayResults['OnlineShoppingResult08']['returnedProduct'] + $resultProductZEINUKI * (-1);             

                                Log::debug("arrayResults['OnlineShoppingResult08']['returnedTax']");
                                $arrayResults['OnlineShoppingResult08']['returnedTax'] = $arrayResults['OnlineShoppingResult08']['returnedTax'] + $resultProductSYOHIZEI * (-1);
                            }

                        }

                    }
                }
                

                // 雑品
                if ($strZappin_cd == "02")
                {
                    if($strKbn != '返品')
                    {
                        if($resultProductSYOHIN_RMEI == '消費税(8%)')
                        {
                            Log::debug("arrayResults['OnlineShoppingResult08']['tax']");
                            $arrayResults['OnlineShoppingResult08']['tax'] = $arrayResults['OnlineShoppingResult08']['tax'] + $resultProductZEIKOMI;

                            $arrayResults['OnlineShoppingResult08']['total'] = $arrayResults['OnlineShoppingResult08']['total'] + $resultProductZEIKOMI;

                        }
                        elseif($resultProductSYOHIN_RMEI == '消費税(10%)')
                        {
                            Log::debug("arrayResults['OnlineShoppingResult10']['tax']");
                            $arrayResults['OnlineShoppingResult10']['tax'] = $arrayResults['OnlineShoppingResult10']['tax'] + $resultProductZEIKOMI;

                            $arrayResults['OnlineShoppingResult10']['total'] = $arrayResults['OnlineShoppingResult10']['total'] + $resultProductZEIKOMI;
                        }
                        elseif($resultProductSYOHIN_RMEI == 'その他（雑品）')
                        {
                            if($miscGoodsCode == "01") // 消費税8％雑品
                            {
                                Log::debug("arrayResults['OnlineShoppingResult08']['miscGoods']");
                                $arrayResults['OnlineShoppingResult08']['miscGoods'] = $arrayResults['OnlineShoppingResult08']['miscGoods'] + $resultProductZEIKOMI;

                                $arrayResults['OnlineShoppingResult08']['total'] = $arrayResults['OnlineShoppingResult08']['total'] + $resultProductZEIKOMI;
                            }
                            else //消費税10％雑品
                            {
                                Log::debug("arrayResults['OnlineShoppingResult10']['miscGoods']");
                                $arrayResults['OnlineShoppingResult10']['miscGoods'] = $arrayResults['OnlineShoppingResult10']['miscGoods'] + $resultProductZEIKOMI;

                                $arrayResults['OnlineShoppingResult10']['total'] = $arrayResults['OnlineShoppingResult10']['total'] + $resultProductZEIKOMI;
                            }

                        }
                        else //その他
                        {
                            if($resultProductZEIKOMI != 0)
                            {
                                if(strpos($resultProductSYOHIN_RMEI,"※") !== false) // 消費税8％雑品
                                {
                                    Log::debug("arrayResults['OnlineShoppingResult08']['miscGoods']");
                                    $arrayResults['OnlineShoppingResult08']['miscGoods'] = $arrayResults['OnlineShoppingResult08']['miscGoods'] + $resultProductZEIKOMI;

                                    $arrayResults['OnlineShoppingResult08']['total'] = $arrayResults['OnlineShoppingResult08']['total'] + $resultProductZEIKOMI;
                                }
                                else // 消費税10％雑品
                                {
                                    Log::debug("arrayResults['OnlineShoppingResult10']['miscGoods']");
                                    $arrayResults['OnlineShoppingResult10']['miscGoods'] = $arrayResults['OnlineShoppingResult10']['miscGoods'] + $resultProductZEIKOMI;

                                    $arrayResults['OnlineShoppingResult10']['total'] = $arrayResults['OnlineShoppingResult10']['total'] + $resultProductZEIKOMI;
                                }
                            }
                        }                        
                    }
                    else //返品
                    {
                        if($resultProductSYOHIN_RMEI == '消費税(8%)') // 消費税8％商品
                        {
                            Log::debug("arrayResults['OnlineShoppingResult08']['returnedTax']");
                            $arrayResults['OnlineShoppingResult08']['returnedTax'] = $resultProductZEIKOMI * (-1);
                        }
                        elseif($resultProductSYOHIN_RMEI == '消費税(10%)') // 消費税10％商品
                        {
                            Log::debug("arrayResults['OnlineShoppingResult10']['returnedTax']");
                            $arrayResults['OnlineShoppingResult10']['returnedTax'] = $resultProductZEIKOMI * (-1);
                        }

                    }

                }

                Log::debug(print_r($arrayResults,true));
            }
            
        }

        Log::debug("[END] OfficeSalesService::calculateOnlineShoppingSalesValueByProduct()");
        return $arrayResults;
    }














    public function exportBueatySalonCsv($arrayResults, $startMonth, $endMonth, $strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $officeCode)
    {

        Log::debug("[START] OfficeSalesService::exportBueatySalonCsv()");
        Log::debug("[INPUT] startMonth = " . $startMonth);
        Log::debug("[INPUT] endMonth = " . $endMonth);
        Log::debug("[INPUT] strStartDateYYYYMMDD = " . $strStartDateYYYYMMDD);
        Log::debug("[INPUT] strEndDateYYYYMMDD = " . $strEndDateYYYYMMDD);
        Log::debug("[INPUT] officeCode = " . $officeCode);
        Log::debug(print_r($arrayResults,true));

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

        $callback = function () use ($arrayResults, $startMonth, $endMonth, $strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $officeCode) 
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
                "伝票摘要",//""
                "枝番",
                "借方部門",
                "借方部門名",//""
                "借方科目",
                "借方科目名",//""
                "借方補助",
                "借方補助科目名",//""
                "借方金額",
                "借方消費税コード",
                "借方消費税業種",
                "借方消費税税率",
                "借方資金区分",
                "借方任意項目１",//""
                "借方任意項目２",//""
                "貸方部門",
                "貸方部門名",//""
                "貸方科目",
                "貸方科目名",//""
                "貸方補助",
                "貸方補助科目名",//""
                "貸方金額",
                "貸方消費税コード",
                "貸方消費税業種",
                "貸方消費税税率",
                "貸方資金区分",
                "貸方任意項目１",//""
                "貸方任意項目２",//""
                "摘要",//""
                "期日",
                "証番号",//""
                "入力マシン",//""
                "入力ユーザ",//""
                "入力アプリ",//""
                "入力会社",//""
                "入力日付",
            ]; 

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $columns); //文字化け対策    
            fputcsv($createCsvFile, $columns); //1行目の情報を追記

            if ($officeCode == "0001")//本社
            {
                $slipNo1 = 161;
                $slipNo2 = 162;
                $officeName = "本社";
            }
            elseif ($officeCode == "0011")//狭山
            {
                $slipNo1 = 163;
                $slipNo2 = 164;
                $slipNo3 = 165;
                $officeName = "狭山店";
            }
            elseif ($officeCode == "0017")//高松
            {
                $slipNo1 = 166;
                $slipNo2 = 167;
                $slipNo3 = 168;
                $officeName = "高松店";
            }
            elseif ($officeCode == "0023")//佐世保
            {
                $slipNo1 = 169;
                $slipNo2 = 170;
                $slipNo3 = 171;
                $officeName = "佐世保店";
            }
            elseif ($officeCode == "0027")//熊本
            {
                $slipNo1 = 172;
                $slipNo2 = 173;
                $slipNo3 = 174;
                $officeName = "熊本店";
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
                $slipNo1,
                '',
                1,
                '',
                '',
                1132,
                '売掛金４エステ',
                0,
                '',
                intval($arrayResults['BueatySalonResult08']['total']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['BueatySalonResult08']['total']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n$officeName 商品（軽税*8%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          


            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo1,
                '',
                1,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['BueatySalonResult08']['product']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                8114,
                '売上高４エステ',
                0,
                '',
                intval($arrayResults['BueatySalonResult08']['product']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n$officeName 商品（軽税*8%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          


            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo1,
                '',
                2,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['BueatySalonResult08']['productArea']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                8112,
                '売上高２代理店',
                0,
                '',
                intval($arrayResults['BueatySalonResult08']['productArea']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n$officeName エリア商品（軽税*8%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo1,
                '',
                3,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['BueatySalonResult08']['miscGoods']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                8113,
                '売上高３雑品',//TODO
                0,
                '',
                intval($arrayResults['BueatySalonResult08']['miscGoods']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n$officeName 雑品（軽税*8%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo1,
                '',
                4,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['BueatySalonResult08']['miscGoodsArea']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                8113,
                '売上高３雑品',//TODO
                0,
                '',
                intval($arrayResults['BueatySalonResult08']['miscGoodsArea']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n$officeName エリア雑品（軽税*8%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo1,
                '',
                5,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['BueatySalonResult08']['tax']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                5000,
                '仮受消費税',
                0,
                '',
                intval($arrayResults['BueatySalonResult08']['tax']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n$officeName 消費税（軽税*8%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          


            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo1,
                '',
                6,
                '',
                '',
                8114,
                '売上高４エステ',
                0,
                '',
                intval($arrayResults['BueatySalonResult08']['returnedProduct']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['BueatySalonResult08']['returnedProduct']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n$officeName 商品　返品（軽税*8%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo1,
                '',
                7,
                '',
                '',
                5000,
                '仮受消費税',
                0,
                '',
                intval($arrayResults['BueatySalonResult08']['returnedTax']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['BueatySalonResult08']['returnedTax']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n$officeName 消費税　返品（軽税*8%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する


            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo2,
                '',
                1,
                '',
                '',
                1132,
                '売掛金４エステ',
                0,
                '',
                intval($arrayResults['BueatySalonResult10']['total']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['BueatySalonResult10']['total']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n$officeName 商品（税*10%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          


            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo2,
                '',
                1,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['BueatySalonResult10']['product']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                8114,
                '売上高４エステ',
                0,
                '',
                intval($arrayResults['BueatySalonResult10']['product']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n$officeName 商品（税*10%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          


            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo2,
                '',
                2,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['BueatySalonResult10']['productArea']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                8112,
                '売上高２代理店',
                0,
                '',
                intval($arrayResults['BueatySalonResult10']['productArea']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n$officeName エリア商品（税*10%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo2,
                '',
                3,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['BueatySalonResult10']['miscGoods']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                8113,
                '売上高３雑品',//TODO
                0,
                '',
                intval($arrayResults['BueatySalonResult10']['miscGoods']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n$officeName 雑品（税*10%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo2,
                '',
                4,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['BueatySalonResult10']['miscGoodsArea']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                8113,
                '売上高３雑品',//TODO
                0,
                '',
                intval($arrayResults['BueatySalonResult10']['miscGoodsArea']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n$officeName エリア雑品（税*10%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo2,
                '',
                5,
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['BueatySalonResult10']['tax']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                5000,
                '仮受消費税',
                0,
                '',
                intval($arrayResults['BueatySalonResult10']['tax']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n$officeName 消費税（税*10%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          


            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo2,
                '',
                6,
                '',
                '',
                8114,
                '売上高４エステ',
                0,
                '',
                intval($arrayResults['BueatySalonResult10']['returnedProduct']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['BueatySalonResult10']['returnedProduct']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n$officeName 商品　返品（税*10%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

            $csv = [
                0,
                0,
                3,
                0,
                0,
                $strEndDateYYYYMMDD,
                $slipNo2,
                '',
                7,
                '',
                '',
                5000,
                '仮受消費税',
                0,
                '',
                intval($arrayResults['BueatySalonResult10']['returnedTax']),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                999,
                '諸口',
                0,
                '',
                intval($arrayResults['BueatySalonResult10']['returnedTax']),
                '',
                '',
                '',
                '',
                '',
                '',
                $endMonth."月度　売上計上\n$officeName 消費税　返品（税*10%） ",
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
            $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する



            if ($officeCode != "0001")
            {
                $csv = [
                    0,
                    0,
                    3,
                    0,
                    0,
                    $strEndDateYYYYMMDD,
                    $slipNo3,
                    '',
                    1,
                    '',
                    '',
                    1132,
                    '売掛金４エステ',
                    0,
                    '',
                    intval($arrayResults['EmployeeSalesResult10']['total']+$arrayResults['EmployeeSalesResult08']['total']),
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    999,
                    '諸口',
                    0,
                    '',
                    intval($arrayResults['EmployeeSalesResult10']['total']+$arrayResults['EmployeeSalesResult08']['total']),
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    $endMonth."月度　売上計上\n$officeName 社販商品（軽税*8%） ",
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                ];

                mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
                $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          


                $csv = [
                    0,
                    0,
                    3,
                    0,
                    0,
                    $strEndDateYYYYMMDD,
                    $slipNo3,
                    '',
                    1,
                    '',
                    '',
                    999,
                    '諸口',
                    0,
                    '',
                    intval($arrayResults['EmployeeSalesResult08']['product']),
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    8112,
                    '売上高２代理店',
                    0,
                    '',
                    intval($arrayResults['EmployeeSalesResult08']['product']),
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    $endMonth."月度　売上計上\n$officeName 社販商品（軽税*8%） ",
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                ];

                mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
                $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          


                $csv = [
                    0,
                    0,
                    3,
                    0,
                    0,
                    $strEndDateYYYYMMDD,
                    $slipNo3,
                    '',
                    2,
                    '',
                    '',
                    999,
                    '諸口',
                    0,
                    '',
                    intval($arrayResults['EmployeeSalesResult08']['miscGoods']),
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    8113,
                    '売上高３雑品',//TODO
                    0,
                    '',
                    intval($arrayResults['EmployeeSalesResult08']['miscGoods']),
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    $endMonth."月度　売上計上\n$officeName 社販雑品（軽税*8%） ",
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                ];

                mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
                $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

                $csv = [
                    0,
                    0,
                    3,
                    0,
                    0,
                    $strEndDateYYYYMMDD,
                    $slipNo3,
                    '',
                    3,
                    '',
                    '',
                    999,
                    '諸口',
                    0,
                    intval($arrayResults['EmployeeSalesResult08']['tax']),
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    5000,
                    '仮受消費税',
                    0,
                    '',
                    intval($arrayResults['EmployeeSalesResult08']['tax']),
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    $endMonth."月度　売上計上\n$officeName 消費税（軽税*8%） ",
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                ];

                mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
                $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          


                $csv = [
                    0,
                    0,
                    3,
                    0,
                    0,
                    $strEndDateYYYYMMDD,
                    $slipNo3,
                    '',
                    4,
                    '',
                    '',
                    999,
                    '諸口',
                    0,
                    '',
                    intval($arrayResults['EmployeeSalesResult10']['product']),
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    8112,
                    '売上高２代理店',
                    0,
                    '',
                    intval($arrayResults['EmployeeSalesResult10']['product']),
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    $endMonth."月度　売上計上\n$officeName 社販商品（税*10%） ",
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                ];

                mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
                $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          


                $csv = [
                    0,
                    0,
                    3,
                    0,
                    0,
                    $strEndDateYYYYMMDD,
                    $slipNo3,
                    '',
                    5,
                    '',
                    '',
                    999,
                    '諸口',
                    0,
                    '',
                    intval($arrayResults['EmployeeSalesResult10']['miscGoods']),
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    8113,
                    '売上高３雑品',//TODO
                    0,
                    '',
                    intval($arrayResults['EmployeeSalesResult10']['miscGoods']),
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    $endMonth."月度　売上計上\n$officeName 社販雑品（税*10%） ",
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                ];

                mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
                $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          

                $csv = [
                    0,
                    0,
                    3,
                    0,
                    0,
                    $strEndDateYYYYMMDD,
                    $slipNo3,
                    '',
                    6,
                    '',
                    '',
                    999,
                    '諸口',
                    0,
                    '',
                    intval($arrayResults['EmployeeSalesResult10']['tax']),
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    5000,
                    '仮受消費税',
                    0,
                    '',
                    intval($arrayResults['EmployeeSalesResult10']['tax']),
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    $endMonth."月度　売上計上\n$officeName 消費税（税*10%） ",
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                ];

                mb_convert_variables("UTF-8", "SJIS,ASCII,UTF-8,SJIS-win", $csv); //文字化け対策
                $this->fputcsvNew($createCsvFile, $csv); //ファイルに追記する          



            }


            fclose($createCsvFile); //ファイル閉じる
        };
        
        Log::debug("[END] OfficeSalesService::exportBueatySalonCsv()");
        return response()->stream($callback, 200, $headers); //ここで実行

    }


	public function getBueatySalonSalesValue($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $officeCode, $departmentCode, $miscGoodsCode)
    {
		Log::debug("[START] OfficeSalesService::getBueatySalonSalesValue()");

        $arrayResults = $this->calculateBueatySalonSalesValue($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $officeCode, $departmentCode, $miscGoodsCode);

		Log::debug("[END] OfficeSalesService::getBueatySalonSalesValue()");
	    return $arrayResults;
    }


    private function calculateBueatySalonSalesValue($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $officeCode, $departmentCode,  $miscGoodsCode)
    {
        Log::debug("[START] OfficeSalesService::calculateBueatySalonSalesValue()");

        $strHenpin_Kbn = "00"; 
        $strSyahan_Cd = "00";
        $strTuhan_Kbn = "01";
        $strHeadTantou_cd = "";
        $strHeadKokyaku_cd ="";
        $strJigyo_Cd = $officeCode;
        $strBu_cd = $departmentCode;
        $strSyozokuJigyo_Cd = "";
        $strSyozokuBu_Cd = "00";


        //10%　エステ結果
        $arrayBueatySalonResult10 = array('product'=> 0, 'productArea'=> 0, 'miscGoods'=> 0, 'miscGoodsArea'=> 0, 'tax'=> 0, 'returnedProduct'=> 0, 'returnedTax'=> 0, 'total'=>0);

        //8%　エステ結果
        $arrayBueatySalonResult08 = array('product'=> 0, 'productArea'=> 0, 'miscGoods'=> 0, 'miscGoodsArea'=> 0, 'tax'=> 0, 'returnedProduct'=> 0, 'returnedTax'=> 0, 'total'=>0);

        //10%　社販結果
        $arrayEmployeeSalesResult10 = array('product'=> 0, 'miscGoods'=> 0, 'tax'=> 0, 'total'=>0);

        //8%　社販結果
        $arrayEmployeeSalesResult08 = array('product'=> 0, 'miscGoods'=> 0, 'tax'=> 0, 'total'=>0);

        $arrayResults = array('BueatySalonResult10'=> $arrayBueatySalonResult10, 'BueatySalonResult08'=> $arrayBueatySalonResult08, 'EmployeeSalesResult10'=> $arrayEmployeeSalesResult10 , 'EmployeeSalesResult08'=> $arrayEmployeeSalesResult08);


        //雑品
        $strZappin_cd = "02";

        $results = $this->getSalesHeaderRecords($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $strHenpin_Kbn, $strZappin_cd, $strSyahan_Cd, $strTuhan_Kbn, $strHeadTantou_cd, $strHeadKokyaku_cd, $strJigyo_Cd, $strBu_cd, $strSyozokuJigyo_Cd, $strSyozokuBu_Cd);

        $arrayResults = $this->calculateBueatySalonSalesValueByProduct($strZappin_cd, $miscGoodsCode, $results, $arrayResults);


        //雑品以外
        $strZappin_cd = "01";

        $results = $this->getSalesHeaderRecords($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $strHenpin_Kbn, $strZappin_cd, $strSyahan_Cd, $strTuhan_Kbn, $strHeadTantou_cd, $strHeadKokyaku_cd, $strJigyo_Cd, $strBu_cd, $strSyozokuJigyo_Cd, $strSyozokuBu_Cd);

        $arrayResults = $this->calculateBueatySalonSalesValueByProduct($strZappin_cd, $miscGoodsCode, $results, $arrayResults);


        Log::debug("[END] OfficeSalesService::calculateBueatySalonSalesValue()");
        return $arrayResults;
    }



    private function calculateBueatySalonSalesValueByProduct($strZappin_cd, $miscGoodsCode, $results, $arrayResults)
    {
        Log::debug("[START] OfficeSalesService::calculateBueatySalonSalesValueByProduct()");


        foreach ($results as $result) 
        {
            $resultURIAGEBI = $result->URIAGEBI;
            $resultTANTOU_CD = $result->TANTOU_CD;
            $resultKOKYAKU_CD = $result->KOKYAKU_CD;
            $resultBU_CD = $result->BU_CD;
            $resultHENPIN_KBN = $result->HENPIN_KBN;
            $resultKBN = $result->KBN;
            $resultJIGYO_CD = $result->JIGYO_CD;
            //'========================================================
            //'   売上データ取得処理
            //'========================================================
            /*
            '--------------------------------------------------------
            '   取得カラム：売上日(204)、担当者漢字姓(101)
            '              担当者漢字名(101)、顧客漢字姓(201)
            '              個顧客漢字名(201)、商品略称(103)
            '              上代(204)、商品個数(204)
            '              税抜き額(204)、 消費税額(204)
            '              税込み額(204)、販売担当者コード(204)
            '              顧客コード(204)、商品区分コード(204)
            '              商品コード(204)、売上コード(204)
            '              部略称(110)、返品区分コード(904)
            '              返品日(904)、備考(904)
            '              事業所略称(102)
            '              フォローNO(204)
            '   対象テーブル：商品売上テーブル
            '                社員マスターテーブル
            '                顧客マスターテーブル
            '                商品マスターテーブル
            '                部マスターテーブル
            '                事業所マスターテーブル
            '   条件：売上日が任意のもの
            '         販売担当者コードが任意のもの
            '         顧客コードが任意のもの
            '         事業所コードが任意のもの
            '         部コードが任意のもの
            '         返品区分コードが任意のもの
            '---------------------------------------------------------
            */
            $strSQL = "SELECT TB204.URIAGEBI, TB101.KANJI_SEI AS TANTOU_SEI, TB101.KANJI_MEI AS TANTOU_MEI, TB201.KANJI_SEI AS KOKYAKU_SEI, TB201.KANJI_MEI AS KOKAYKU_MEI, "
                    ."TB103.SYOHIN_RMEI, TB204.ZYODAI, TB204.SYOHINNUM, TB204.ZEINUKI, TB204.SYOHIZEI, TB204.ZEIKOMI, "
                    ."TB204.TANTOU_CD, TB204.KOKYAKU_CD, TB204.SYOHIN_KBN, TB204.SYOHIN_CD, TB204.URIAGE_CD, TB110.BU_RMEI, TB204.HENPIN_KBN, TB204.HENPINBI, TB204.TEKIYOU, TB102.JIGYO_RMEI, TB204.FOLLOW_NO "
                    ."FROM TW_HB204 TB204, TM_BS101 TB101, TM_HB201 TB201, TM_BS103 TB103, TM_BS110 TB110, TM_BS102 TB102 "
                    ."WHERE TB204.URIAGEBI ='" . $resultURIAGEBI . "' "
                    ."AND TB204.TANTOU_CD ='" . $resultTANTOU_CD . "' "
                    ."AND TB204.KOKYAKU_CD ='" . $resultKOKYAKU_CD . "' "
                    ."AND TB204.BU_CD ='" . $resultBU_CD . "' "
                    ."AND TB204.HENPIN_KBN ='" . $resultHENPIN_KBN . "' "
                    ."AND ";                                                                      
                
            if ($resultKBN == 1)
            {
                $strSQL = $strSQL . "TB204.JIGYO_CD ='" . $resultJIGYO_CD . "' ";   
            }
            else
            {
                $strSQL = $strSQL . "TB101.JIGYO_CD ='" . $resultJIGYO_CD . "' ";           
            }

            
            if ($strZappin_cd == "01")
            {
                //CO_CODE_ZAPPIN = "96"
                $strSQL = $strSQL . " AND TB204.BUNBETU_CD NOT IN ('96') ";
            }
                            
            if ($strZappin_cd == "02")
            {
                //CO_CODE_ZAPPIN = "96"
                $strSQL = $strSQL . " AND TB204.BUNBETU_CD = '96' ";
            }
                
            $strSQL = $strSQL . "AND TB101.SYAIN_CD = TB204.TANTOU_CD "
                    ."AND TB201.KOKYAKU_CD = TB204.KOKYAKU_CD "
                    ."AND TB103.SYOHIN_KBN = TB204.SYOHIN_KBN "
                    ."AND TB103.SYOHIN_CD = TB204.SYOHIN_CD "
                    ."AND TB110.BU_CD = TB204.BU_CD "
                    ."AND TB204.JIGYO_CD = TB102.JIGYO_CD "
                    ."ORDER BY TB204.HENPIN_KBN ";
            

            $resultsProduct = DB::select($strSQL);


            foreach ($resultsProduct as $resultProduct)
            {                                                        
                $resultProductJIGYO_RMEI = $resultProduct->JIGYO_RMEI;//売上事業所
                $resultProductBU_RMEI = $resultProduct->BU_RMEI;//売上部署
                Log::debug("resultProductJIGYO_RMEI = " . $resultProductJIGYO_RMEI);
                Log::debug("resultProductBU_RMEI = " . $resultProductBU_RMEI);

                $resultProductSYOHIN_RMEI = $resultProduct->SYOHIN_RMEI;//商品名
                $resultProductZEINUKI = $resultProduct->ZEINUKI;//金額
                $resultProductSYOHIZEI = $resultProduct->SYOHIZEI;//消費税額
                $resultProductZEIKOMI = $resultProduct->ZEIKOMI;//小計
                Log::debug("resultProductSYOHIN_RMEI = " . $resultProductSYOHIN_RMEI);
                Log::debug("resultProductZEIKOMI = " . $resultProductZEIKOMI);
                
                $resultProductTANTOU_SEI = $resultProduct->TANTOU_SEI;//販売担当者姓
                $resultProductTANTOU_MEI = $resultProduct->TANTOU_MEI;//販売担当者名
                $strTantou_Mei = $resultProductTANTOU_SEI . "　" . $resultProductTANTOU_MEI;
                Log::debug("strTantou_Mei = " . $strTantou_Mei);

                //$resultProductKOKYAKU_SEI = $resultProduct->KOKYAKU_SEI;//顧客姓
                //$resultProductKOKYAKU_MEI = $resultProduct->KOKYAKU_MEI;//顧客名
                //$strKokyaku_Mei = $resultProductKOKYAKU_SEI . "　" . $resultProductKOKYAKU_MEI;
                //Log::debug("strKokyaku_Mei = " . $strKokyaku_Mei);


                $resultProductHENPIN_KBN = $resultProduct->HENPIN_KBN;//返品区分
                $resultProductHENPINBI = $resultProduct->HENPINBI;//返品日
                if ($resultProductHENPIN_KBN == "01")//CO_CODE_URIAGE="01"
                {
                    $strKbn = "売上";
                }
                elseif ($resultProductHENPIN_KBN == "02")//CO_CODE_HENPIN="02"
                {
                    $strKbn = "返品";
                }
                elseif ($resultProductHENPIN_KBN == "03")//CO_CODE_MIHON="03"
                {
                    $strKbn = "見本";
                }
                Log::debug("strKbn = " . $strKbn);
                Log::debug("resultProductHENPINBI = " . $resultProductHENPINBI);


                // 雑品以外
                if ($strZappin_cd == "01")
                {
                    if($resultProductZEIKOMI != 0)
                    {
                        if($strKbn != '返品') // 返品以外
                        {
                            if(strpos($resultProductSYOHIN_RMEI,'※') !== false) // 消費税8％商品
                            {
                                if (strpos($strTantou_Mei,'社販') !== false) // 社販
                                {
                                    Log::debug("arrayResults['EmployeeSalesResult08']['product']");
                                    $arrayResults['EmployeeSalesResult08']['product'] = $arrayResults['EmployeeSalesResult08']['product'] + $resultProductZEIKOMI;

                                    $arrayResults['EmployeeSalesResult08']['total'] = $arrayResults['EmployeeSalesResult08']['total'] + $resultProductZEIKOMI;
                                }
                                elseif (strpos($strTantou_Mei,'（エリア）') !== false) // エリア
                                {
                                    Log::debug("arrayResults['BueatySalonResult08']['productArea']");
                                    $arrayResults['BueatySalonResult08']['productArea'] = $arrayResults['BueatySalonResult08']['productArea'] + $resultProductZEIKOMI;

                                    $arrayResults['BueatySalonResult08']['total'] = $arrayResults['BueatySalonResult08']['total'] + $resultProductZEIKOMI;
                                }
                                else //その他
                                {                                    
                                    Log::debug("arrayResults['BueatySalonResult08']['product']");
                                    $arrayResults['BueatySalonResult08']['product'] = $arrayResults['BueatySalonResult08']['product'] + $resultProductZEIKOMI;

                                    $arrayResults['BueatySalonResult08']['total'] = $arrayResults['BueatySalonResult08']['total'] + $resultProductZEIKOMI;
                                }
                            }
                            else // 消費税10％商品
                            {
                                if (strpos($strTantou_Mei,'社販') !== false) // 社販
                                {
                                    Log::debug("arrayResults['EmployeeSalesResult10']['product']");
                                    $arrayResults['EmployeeSalesResult10']['product'] = $arrayResults['EmployeeSalesResult10']['product'] + $resultProductZEIKOMI;

                                    $arrayResults['EmployeeSalesResult10']['total'] = $arrayResults['EmployeeSalesResult10']['total'] + $resultProductZEIKOMI;
                                }
                                elseif (strpos($strTantou_Mei,'（エリア）') !== false) // エリア
                                {
                                    Log::debug("arrayResults['BueatySalonResult10']['productArea']");
                                    $arrayResults['BueatySalonResult10']['productArea'] = $arrayResults['BueatySalonResult10']['productArea'] + $resultProductZEIKOMI;

                                    $arrayResults['BueatySalonResult10']['total'] = $arrayResults['BueatySalonResult10']['total'] + $resultProductZEIKOMI;
                                }
                                else // その他
                                {
                                    Log::debug("arrayResults['BueatySalonResult10']['product']");
                                    $arrayResults['BueatySalonResult10']['product'] = $arrayResults['BueatySalonResult10']['product'] + $resultProductZEIKOMI;

                                    $arrayResults['BueatySalonResult10']['total'] = $arrayResults['BueatySalonResult10']['total'] + $resultProductZEIKOMI;
                                }
                            }                            
                        }
                        else //返品
                        {
                            if (empty($resultProductHENPINBI) or is_null($resultProductHENPINBI) or $resultProductHENPINBI >= '20191001')
                            {
                                if(strpos($resultProductSYOHIN_RMEI,'※') !== false) // 消費税8％商品
                                {
                                    Log::debug("arrayResults['BueatySalonResult08']['returnedProduct']");
                                    $arrayResults['BueatySalonResult08']['returnedProduct'] = $arrayResults['BueatySalonResult08']['returnedProduct'] + $resultProductZEINUKI * (-1);
                                }
                                else // 消費税10％商品
                                {
                                    Log::debug("arrayResults['BueatySalonResult10']['returnedProduct']");
                                    $arrayResults['BueatySalonResult10']['returnedProduct'] = $arrayResults['BueatySalonResult10']['returnedProduct'] + $resultProductZEINUKI * (-1);
                                }
                            }
                            elseif ($resultProductHENPINBI <= '20190931')
                            {
                                Log::debug("arrayResults['BueatySalonResult08']['returnedProduct']");
                                $arrayResults['BueatySalonResult08']['returnedProduct'] = $arrayResults['BueatySalonResult08']['returnedProduct'] + $resultProductZEINUKI * (-1);             

                                Log::debug("arrayResults['BueatySalonResult08']['returnedTax']");
                                $arrayResults['BueatySalonResult08']['returnedTax'] = $arrayResults['BueatySalonResult08']['returnedTax'] + $resultProductSYOHIZEI * (-1);
                            }

                        }
                    }
                }
                

                // 雑品
                if ($strZappin_cd == "02")
                {
                    if($strKbn != '返品')
                    {
                        if($resultProductSYOHIN_RMEI == '消費税(8%)')
                        {
                            if (strpos($strTantou_Mei,'社販') !== false) // 社販
                            {
                                Log::debug("arrayResults['EmployeeSalesResult08']['tax']");
                                $arrayResults['EmployeeSalesResult08']['tax'] = $arrayResults['EmployeeSalesResult08']['tax'] + $resultProductZEIKOMI;

                                $arrayResults['EmployeeSalesResult08']['total'] = $arrayResults['EmployeeSalesResult08']['total'] + $resultProductZEIKOMI;
                            }
                            else // エステ
                            {
                                Log::debug("arrayResults['BueatySalonResult08']['tax']");
                                $arrayResults['BueatySalonResult08']['tax'] = $arrayResults['BueatySalonResult08']['tax'] + $resultProductZEIKOMI;

                                $arrayResults['BueatySalonResult08']['total'] = $arrayResults['BueatySalonResult08']['total'] + $resultProductZEIKOMI;
                            }
                        }
                        elseif($resultProductSYOHIN_RMEI == '消費税(10%)')
                        {
                            if (strpos($strTantou_Mei,'社販') !== false) // 社販
                            {
                                Log::debug("arrayResults['EmployeeSalesResult10']['tax']");
                                $arrayResults['EmployeeSalesResult10']['tax'] = $arrayResults['EmployeeSalesResult10']['tax'] + $resultProductZEIKOMI;

                                $arrayResults['EmployeeSalesResult10']['total'] = $arrayResults['EmployeeSalesResult10']['total'] + $resultProductZEIKOMI;
                            }
                            else // エステ
                            {
                                Log::debug("arrayResults['BueatySalonResult10']['tax']");
                                $arrayResults['BueatySalonResult10']['tax'] = $arrayResults['BueatySalonResult10']['tax'] + $resultProductZEIKOMI;

                                $arrayResults['BueatySalonResult10']['total'] = $arrayResults['BueatySalonResult10']['total'] + $resultProductZEIKOMI;
                            }
                        }
                        elseif($resultProductSYOHIN_RMEI == 'その他（雑品）')
                        {
                            if($miscGoodsCode == "01") // 消費税8％雑品
                            {
                                if (strpos($strTantou_Mei,'社販') !== false) // 社販
                                {
                                    Log::debug("arrayResults['EmployeeSalesResult08']['miscGoods']");
                                    $arrayResults['EmployeeSalesResult08']['miscGoods'] = $arrayResults['EmployeeSalesResult08']['miscGoods'] + $resultProductZEIKOMI;

                                    $arrayResults['EmployeeSalesResult08']['total'] = $arrayResults['EmployeeSalesResult08']['total'] + $resultProductZEIKOMI;
                                }
                                elseif (strpos($strTantou_Mei,'（エリア）') !== false) // エリア
                                {
                                    Log::debug("arrayResults['BueatySalonResult08']['miscGoodsArea']");
                                    $arrayResults['BueatySalonResult08']['miscGoodsArea'] = $arrayResults['BueatySalonResult08']['miscGoodsArea'] + $resultProductZEIKOMI;

                                    $arrayResults['BueatySalonResult08']['total'] = $arrayResults['BueatySalonResult08']['total'] + $resultProductZEIKOMI;
                                }
                                else // その他
                                {
                                    Log::debug("arrayResults['BueatySalonResult08']['miscGoods']");
                                    $arrayResults['BueatySalonResult08']['miscGoods'] = $arrayResults['BueatySalonResult08']['miscGoods'] + $resultProductZEIKOMI;

                                    $arrayResults['BueatySalonResult08']['total'] = $arrayResults['BueatySalonResult08']['total'] + $resultProductZEIKOMI;
                                }
                            }
                            else //消費税10％雑品
                            {
                                if (strpos($strTantou_Mei,'社販') !== false) // 社販
                                {
                                    Log::debug("arrayResults['EmployeeSalesResult10']['miscGoods']");
                                    $arrayResults['EmployeeSalesResult10']['miscGoods'] = $arrayResults['EmployeeSalesResult10']['miscGoods'] + $resultProductZEIKOMI;

                                    $arrayResults['EmployeeSalesResult10']['total'] = $arrayResults['EmployeeSalesResult10']['total'] + $resultProductZEIKOMI;
                                }
                                elseif (strpos($strTantou_Mei,'（エリア）') !== false) // エリア
                                {
                                    Log::debug("arrayResults['BueatySalonResult10']['miscGoodsArea']");
                                    $arrayResults['BueatySalonResult10']['miscGoodsArea'] = $arrayResults['BueatySalonResult10']['miscGoodsArea'] + $resultProductZEIKOMI;

                                    $arrayResults['BueatySalonResult10']['total'] = $arrayResults['BueatySalonResult10']['total'] + $resultProductZEIKOMI;
                                }
                                else // その他
                                {
                                    Log::debug("arrayResults['BueatySalonResult10']['miscGoods']");
                                    $arrayResults['BueatySalonResult10']['miscGoods'] = $arrayResults['BueatySalonResult10']['miscGoods'] + $resultProductZEIKOMI;

                                    $arrayResults['BueatySalonResult10']['total'] = $arrayResults['BueatySalonResult10']['total'] + $resultProductZEIKOMI;
                                }
                            }

                        }
                        else //その他
                        {
                            if($resultProductZEIKOMI != 0)
                            {
                                if(strpos($resultProductSYOHIN_RMEI,'※') !== false) // 消費税8％雑品
                                {
                                    if (strpos($strTantou_Mei,'社販') !== false) // 社販
                                    {
                                        Log::debug("arrayResults['EmployeeSalesResult08']['miscGoods']");
                                        $arrayResults['EmployeeSalesResult08']['miscGoods'] = $arrayResults['EmployeeSalesResult08']['miscGoods'] + $resultProductZEIKOMI;

                                        $arrayResults['EmployeeSalesResult08']['total'] = $arrayResults['EmployeeSalesResult08']['total'] + $resultProductZEIKOMI;
                                    }
                                    elseif (strpos($strTantou_Mei,'（エリア）') !== false) // エリア
                                    {
                                        Log::debug("arrayResults['BueatySalonResult08']['miscGoodsArea']");
                                        $arrayResults['BueatySalonResult08']['miscGoodsArea'] = $arrayResults['BueatySalonResult08']['miscGoodsArea'] + $resultProductZEIKOMI;

                                        $arrayResults['BueatySalonResult08']['total'] = $arrayResults['BueatySalonResult08']['total'] + $resultProductZEIKOMI;
                                    }
                                    else // その他
                                    {
                                        Log::debug("arrayResults['BueatySalonResult08']['miscGoods']");
                                        $arrayResults['BueatySalonResult08']['miscGoods'] = $arrayResults['BueatySalonResult08']['miscGoods'] + $resultProductZEIKOMI;

                                        $arrayResults['BueatySalonResult08']['total'] = $arrayResults['BueatySalonResult08']['total'] + $resultProductZEIKOMI;
                                    }
                                }
                                else // 消費税10％雑品
                                {
                                    if (strpos($strTantou_Mei,'社販') !== false) // 社販
                                    {
                                        Log::debug("arrayResults['EmployeeSalesResult10']['miscGoods']");
                                        $arrayResults['EmployeeSalesResult10']['miscGoods'] = $arrayResults['EmployeeSalesResult10']['miscGoods'] + $resultProductZEIKOMI;

                                        $arrayResults['EmployeeSalesResult10']['total'] = $arrayResults['EmployeeSalesResult10']['total'] + $resultProductZEIKOMI;
                                    }
                                    elseif (strpos($strTantou_Mei,'（エリア）') !== false) // エリア
                                    {
                                        Log::debug("arrayResults['BueatySalonResult10']['miscGoodsArea']");
                                        $arrayResults['BueatySalonResult10']['miscGoodsArea'] = $arrayResults['BueatySalonResult10']['miscGoodsArea'] + $resultProductZEIKOMI;

                                        $arrayResults['BueatySalonResult10']['total'] = $arrayResults['BueatySalonResult10']['total'] + $resultProductZEIKOMI;
                                    }
                                    else // その他
                                    {
                                        Log::debug("arrayResults['BueatySalonResult10']['miscGoods']");
                                        $arrayResults['BueatySalonResult10']['miscGoods'] = $arrayResults['BueatySalonResult10']['miscGoods'] + $resultProductZEIKOMI;

                                        $arrayResults['BueatySalonResult10']['total'] = $arrayResults['BueatySalonResult10']['total'] + $resultProductZEIKOMI;
                                    }
                                }
                            }
                        }                        
                    }
                    else //返品
                    {
                        if($resultProductSYOHIN_RMEI == '消費税(8%)') // 消費税8％商品
                        {
                            Log::debug("arrayResults['BueatySalonResult08']['returnedTax']");
                            $arrayResults['BueatySalonResult08']['returnedTax'] = $resultProductZEIKOMI * (-1);
                        }
                        elseif($resultProductSYOHIN_RMEI == '消費税(10%)') // 消費税10％商品
                        {
                            Log::debug("arrayResults['BueatySalonResult10']['returnedTax']");
                            $arrayResults['BueatySalonResult10']['returnedTax'] = $resultProductZEIKOMI * (-1);
                        }

                    }

                }

                Log::debug(print_r($arrayResults,true));
            }
            
        }

        Log::debug("[END] OfficeSalesService::calculateBueatySalonSalesValueByProduct()");
        return $arrayResults;
    }



    private function fputcsvNew($fp, $data) {

        $csv = '';
        $count = 0;
        foreach ($data as $col) {
            $count++;

            if (is_numeric($col)) {
                $csv .= $col;
            } else {
                if ($count == 13 or $count == 26 or $count == 36)
                {
                    $csv .= '"' . $col . '"';
                }
                else
                {
                    $csv .= $col;
                }
            }
            $csv .= ',';
        }

        fwrite($fp, $csv);
        fwrite($fp, "\r\n");
    }




    private function getSalesHeaderRecords($strStartDateYYYYMMDD, $strEndDateYYYYMMDD, $strHenpin_Kbn, $strZappin_cd, $strSyahan_Cd, $strTuhan_Kbn, $strHeadTantou_cd, $strHeadKokyaku_cd, $strJigyo_Cd, $strBu_cd, $strSyozokuJigyo_Cd, $strSyozokuBu_Cd)
    {
		Log::debug("[START] OfficeSalesService::getSalesHeaderRecords()");
        Log::debug('[INPUT] strStartDateYYYYMMDD = ' . $strStartDateYYYYMMDD);
        Log::debug('[INPUT] strEndDateYYYYMMDD = ' . $strEndDateYYYYMMDD);
        Log::debug('[INPUT] strHenpin_Kbn = ' . $strHenpin_Kbn);
        Log::debug('[INPUT] strZappin_cd = ' . $strZappin_cd);
        Log::debug('[INPUT] strSyahan_Cd = ' . $strSyahan_Cd);
        Log::debug('[INPUT] strTuhan_Kbn = ' . $strTuhan_Kbn);
        Log::debug('[INPUT] strHeadTantou_cd = ' . $strHeadTantou_cd);
        Log::debug('[INPUT] strHeadKokyaku_cd = ' . $strHeadKokyaku_cd);
        Log::debug('[INPUT] strJigyo_Cd = ' . $strJigyo_Cd);
        Log::debug('[INPUT] strBu_cd = ' . $strBu_cd);
        Log::debug('[INPUT] strSyozokuJigyo_Cd = ' . $strSyozokuJigyo_Cd);
        Log::debug('[INPUT] strSyozokuJigyo_Cd = ' . $strSyozokuJigyo_Cd);

        /*
        '=====================================================
        '   通常売上/折半売上の絞込み
        '=====================================================
        '-----------------------------------------------------
        '   返品区分が全部以外の場合、絞込みを行う
        '   部が全部以外の場合、絞込みを行う(通常売上のみ)
        '   販売担当者に入力がある場合、絞込みを行う
        '   顧客に入力がある場合、絞込みを行う
        '-----------------------------------------------------
        */
        $strKbnSQL = "";
        $strKbnSQL2 = "";

        //$strHenpin_Kbn = "00"(全部)
        if ($strHenpin_Kbn <> "00")
        {
            $strKbnSQL = "AND TB204.HENPIN_KBN = '" . $strHenpin_Kbn . "' ";
            $strKbnSQL2 = "AND TB204.HENPIN_KBN = '" . $strHenpin_Kbn  . "' ";
        }
        
        //売上部：$strBu_cd = "00"(全部)      
        if ($strBu_cd <> "00")
        {
            $strKbnSQL = $strKbnSQL . "AND TB204.BU_CD = '". $strBu_cd . "' ";
        }

        //所属部：$strSyozokuBu_Cd = "00"(全部) 
        if ($strSyozokuBu_Cd <> "00")
        {
            $strKbnSQL = $strKbnSQL . "AND TB101.SYOZOKU1_CD = '" . $strSyozokuBu_Cd  . "' ";
            $strKbnSQL2 = $strKbnSQL2 . "AND TB101.SYOZOKU1_CD = '" . $strSyozokuBu_Cd  ."' ";
        }

        //雑品表示：$strZappin_cd = "01"(雑品以外)
        if ($strZappin_cd == "01")
        {
            //CO_CODE_ZAPPIN = "96"
            $strKbnSQL = $strKbnSQL . "AND TB204.BUNBETU_CD NOT IN ('96') ";
            $strKbnSQL2 = $strKbnSQL2 . "AND TB204.BUNBETU_CD NOT IN ('96') ";
        }
                    
        //雑品表示：$strZappin_cd = "02"(雑品)
        if ($strZappin_cd == "02")
        {
            //CO_CODE_ZAPPIN = "96"
            $strKbnSQL = $strKbnSQL . "AND TB204.BUNBETU_CD = '96' ";
            $strKbnSQL2 = $strKbnSQL2 . "AND TB204.BUNBETU_CD = '96' ";
        }
        
        //社販表示：$strSyahan_Cd = "01"(社販以外)
        if ($strSyahan_Cd == "01")
        {
            //CO_CODE_SYAHAN_NOTHING = "01" 
            $strKbnSQL = $strKbnSQL . "AND TB101.SYAHAN_KBN = '01' ";
            $strKbnSQL2 = $strKbnSQL2 . "AND TB101.SYAHAN_KBN = '01' ";            
        }

        //販売担当者コード
        if ($strHeadTantou_cd <> "")
        {
            $strKbnSQL = $strKbnSQL . "AND TB204.TANTOU_CD = '" . $strHeadTantou_cd . "' ";
            $strKbnSQL2 = $strKbnSQL2 . "AND TB204.TANTOU_CD = '" . $strHeadTantou_cd . "' ";            
        }
        
        //顧客コード
        if ($strHeadKokyaku_cd <> "")
        {
            $strKbnSQL = $strKbnSQL . "AND TB204.KOKYAKU_CD = '" . $strHeadKokyaku_cd . "' ";
            $strKbnSQL2 = $strKbnSQL2 . "AND TB204.KOKYAKU_CD = '" . $strHeadKokyaku_cd . "' ";            
        }
        
        //売上事業部コード
        if ($strJigyo_Cd <> "")
        {
            $strKbnSQL = $strKbnSQL . "AND TB204.JIGYO_CD = '" . $strJigyo_Cd . "' ";
            $strKbnSQL2 = $strKbnSQL2 . "AND TB101.JIGYO_CD = '" . $strJigyo_Cd . "' ";            
        }
        
        //所属事業部コード
        if ($strSyozokuJigyo_Cd <> "")
        {
            $strKbnSQL = $strKbnSQL . "AND TB101.JIGYO_CD = '" . $strSyozokuJigyo_Cd . "' ";
            $strKbnSQL2 = $strKbnSQL2 . "AND TB101.JIGYO_CD = '" . $strSyozokuJigyo_Cd . "' ";            
        }
        


        //'==========================================
        //'   1売上に応じたレコードカウントを取得
        //'==========================================
        if ($strTuhan_Kbn == "00")
        {

            //'---------------------------------------------------
            //'   通販部表示の場合
            //'---------------------------------------------------
            /*
            '-------------------------------------------------------------------------------
            '   取得カラム：売上日、販売担当者コード
            '              顧客コード、部コード
            '              返品区分コード、区分1→事業所コード
            '              区分2→販売担当者所属事業所コード
            '              区分(1→通常売上、2→折半売上)
            '   対象テーブル：商品売上テーブル
            '                社員マスターテーブル
            '   条件：事業所が任意のもの
            '         顧客売上のもの
            '         売上日に範囲が任意のもの
            '         上記の条件
            '         折半データは販売担当者の所属事業所が任意のもの
            '         売上日、販売担当者コード、顧客コード、部コード、返品区分コードが
            '         同じものは1レコードとする
            '         売上日、販売担当者コード、顧客コード、部コード順
            '-------------------------------------------------------------------------------
            */
            //CO_CODE_KOKYAKU_URIAGE = "01"
            $strSQL = "SELECT TB204.URIAGEBI, TB204.TANTOU_CD, TB204.KOKYAKU_CD, TB204.BU_CD, TB204.HENPIN_KBN, TB204.JIGYO_CD, 1 AS KBN "
                    ."FROM TW_HB204 TB204, TM_BS101 TB101 "
                    ."WHERE TB204.URIAGEBI BETWEEN '" . $strStartDateYYYYMMDD . "' AND '" . $strEndDateYYYYMMDD  . "' "
                    ."AND TB204.FOLLOW_NO IS NULL "
                    ."AND TB204.URIAGE_KBN ='01' "
                    .$strKbnSQL
                    ."AND TB101.SYAIN_CD = TB204.TANTOU_CD "
                    ."GROUP BY TB204.URIAGEBI, TB204.TANTOU_CD, TB204.KOKYAKU_CD, TB204.BU_CD, TB204.HENPIN_KBN, TB204.JIGYO_CD "
                    ."UNION "
                    ."SELECT TB204.URIAGEBI, TB204.TANTOU_CD, TB204.KOKYAKU_CD, TB204.BU_CD, TB204.HENPIN_KBN, TB101.JIGYO_CD, 2 AS KBN "
                    ."FROM TW_HB204 TB204, TM_BS101 TB101 "
                    ."WHERE TB204.URIAGEBI BETWEEN '" . $strStartDateYYYYMMDD . "' AND '" . $strEndDateYYYYMMDD  . "' "
                    ."AND TB204.FOLLOW_NO IS NOT NULL "
                    ."AND TB204.URIAGE_KBN ='01' "
                    .$strKbnSQL2
                    ."AND TB101.SYAIN_CD = TB204.TANTOU_CD "
                    ."GROUP BY TB204.URIAGEBI, TB204.TANTOU_CD, TB204.KOKYAKU_CD, TB204.BU_CD, TB204.HENPIN_KBN, TB101.JIGYO_CD "
                    ."ORDER BY TB204.URIAGEBI, TB204.TANTOU_CD, TB204.KOKYAKU_CD, TB204.BU_CD, TB204.HENPIN_KBN ";

        }
        elseif ($strTuhan_Kbn == "01") 
        {
            //'---------------------------------------------------
            //'   通販部非表示の場合
            //'---------------------------------------------------
            /*
            '-------------------------------------------------------------------------------
            '   取得カラム：売上日、販売担当者コード
            '              顧客コード、部コード
            '              返品区分コード、区分1→事業所コード
            '              区分(1→通常売上)
            '   対象テーブル：商品売上テーブル
            '                社員マスターテーブル
            '   条件：事業所が任意のもの
            '         顧客売上のもの
            '         売上日に範囲が任意のもの
            '         売上日、販売担当者コード、顧客コード、部コード、返品区分コードが
            '         同じものは1レコードとする
            '         売上日、販売担当者コード、顧客コード、部コード順
            '-------------------------------------------------------------------------------
            */
            //CO_CODE_KOKYAKU_URIAGE = "01"
            $strSQL = "SELECT TB204.URIAGEBI, TB204.TANTOU_CD, TB204.KOKYAKU_CD, TB204.BU_CD, TB204.HENPIN_KBN, TB204.JIGYO_CD, 1 AS KBN "
                    ."FROM TW_HB204 TB204, TM_BS101 TB101 "
                    ."WHERE TB204.URIAGEBI BETWEEN '" . $strStartDateYYYYMMDD . "' AND '" . $strEndDateYYYYMMDD  . "' "
                    ."AND TB204.FOLLOW_NO IS NULL "
                    ."AND TB204.URIAGE_KBN ='01' "
                    .$strKbnSQL
                    ."AND TB101.SYAIN_CD = TB204.TANTOU_CD "
                    ."GROUP BY TB204.URIAGEBI, TB204.TANTOU_CD, TB204.KOKYAKU_CD, TB204.BU_CD, TB204.HENPIN_KBN, TB204.JIGYO_CD "
                    ."ORDER BY TB204.URIAGEBI, TB204.TANTOU_CD, TB204.KOKYAKU_CD, TB204.BU_CD, TB204.HENPIN_KBN ";

        }
        else
        {
            //'---------------------------------------------------
            //'   通販のみの場合
            //'---------------------------------------------------
            /*
            '-------------------------------------------------------------------------------
            '   取得カラム：売上日、販売担当者コード
            '              顧客コード、部コード
            '              返品区分コード、区分1→事業所コード
            '              区分2→販売担当者所属事業所コード
            '              区分(1→通常売上、2→折半売上)
            '   対象テーブル：商品売上テーブル
            '                社員マスターテーブル
            '   条件：事業所が任意のもの
            '         売上部署が通販のもの
            '         顧客売上のもの
            '         売上日に範囲が任意のもの
            '         上記の条件
            '         折半データは販売担当者の所属事業所が任意のもの
            '         売上日、販売担当者コード、顧客コード、部コード、返品区分コードが
            '         同じものは1レコードとする
            '         売上日、販売担当者コード、顧客コード、部コード順
            '-------------------------------------------------------------------------------
            */
            //CO_TUHAN_CD = "06"
            //CO_CODE_KOKYAKU_URIAGE = "01"
            $strSQL = "SELECT TB204.URIAGEBI, TB204.TANTOU_CD, TB204.KOKYAKU_CD, TB204.BU_CD, TB204.HENPIN_KBN, TB204.JIGYO_CD, 1 AS KBN "
                    ."FROM TW_HB204 TB204, TM_BS101 TB101 "
                    ."WHERE TB204.URIAGEBI BETWEEN '" . $strStartDateYYYYMMDD . "' AND '" . $strEndDateYYYYMMDD  . "' "
                    ."AND TB204.BU_CD = '06' "
                    ."AND TB204.FOLLOW_NO IS NULL "
                    ."AND TB204.URIAGE_KBN ='01' "
                    .$strKbnSQL
                    ."AND TB101.SYAIN_CD = TB204.TANTOU_CD "
                    ."GROUP BY TB204.URIAGEBI, TB204.TANTOU_CD, TB204.KOKYAKU_CD, TB204.BU_CD, TB204.HENPIN_KBN, TB204.JIGYO_CD "
                    ."UNION "
                    ."SELECT TB204.URIAGEBI, TB204.TANTOU_CD, TB204.KOKYAKU_CD, TB204.BU_CD, TB204.HENPIN_KBN, TB101.JIGYO_CD, 2 AS KBN "
                    ."FROM TW_HB204 TB204, TM_BS101 TB101 "
                    ."WHERE TB204.URIAGEBI BETWEEN '" . $strStartDateYYYYMMDD . "' AND '" . $strEndDateYYYYMMDD  . "' "
                    ."AND TB204.BU_CD = '06' "
                    ."AND TB204.FOLLOW_NO IS NOT NULL "
                    ."AND TB204.URIAGE_KBN ='01' "
                    .$strKbnSQL2
                    ."AND TB101.SYAIN_CD = TB204.TANTOU_CD "
                    ."GROUP BY TB204.URIAGEBI, TB204.TANTOU_CD, TB204.KOKYAKU_CD, TB204.BU_CD, TB204.HENPIN_KBN, TB101.JIGYO_CD "
                    ."ORDER BY TB204.URIAGEBI, TB204.TANTOU_CD, TB204.KOKYAKU_CD, TB204.BU_CD, TB204.HENPIN_KBN ";                     
        }                                                                                                                                                                                                      
        $results = DB::select($strSQL);

		Log::debug("[END] OfficeSalesService::getSalesHeaderRecords()");
		return $results;
    }





}