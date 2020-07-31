@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">振替伝票CSVファイル出力 - 月次レポート</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                  
                  
                    <script>
                        function formSwitch() {
                            type = document.getElementsByName('type')
                            if (type[0].checked) {
                                // 事業所別在庫が選択されたら下記を実行
                                document.getElementById('office').style.display = "";
                                document.getElementById('department').style.display = "";
                                document.getElementById('officeExpenses').style.display = "none";
                                document.getElementById('departmentExpenses').style.display = "none";
                                document.getElementById('officeSales').style.display = "none";
                                document.getElementById('departmentSales').style.display = "none";
                                document.getElementById('miscGoodsSales').style.display = "none";
                            } else if (type[1].checked) {
                                // 売掛残高一覧表＆売上一覧表が選択されたら下記を実行
                                document.getElementById('office').style.display = "none";
                                document.getElementById('department').style.display = "none";
                                document.getElementById('officeExpenses').style.display = "none";
                                document.getElementById('departmentExpenses').style.display = "none";
                                document.getElementById('officeSales').style.display = "";
                                document.getElementById('departmentSales').style.display = "";
                                document.getElementById('miscGoodsSales').style.display = "";
                            } else if (type[2].checked) {
                                // 商品管理部売掛残高一覧表が選択されたら下記を実行
                                document.getElementById('office').style.display = "none";
                                document.getElementById('department').style.display = "none";
                                document.getElementById('officeExpenses').style.display = "none";
                                document.getElementById('departmentExpenses').style.display = "none";
                                document.getElementById('officeSales').style.display = "none";
                                document.getElementById('departmentSales').style.display = "none";
                                document.getElementById('miscGoodsSales').style.display = "none";
                            } else if (type[3].checked) {
                                // 販売担当者別売上実績表＆買掛台帳が選択されたら下記を実行
                                document.getElementById('office').style.display = "none";
                                document.getElementById('department').style.display = "none";
                                document.getElementById('officeExpenses').style.display = "";
                                document.getElementById('departmentExpenses').style.display = "";
                                document.getElementById('officeSales').style.display = "none";
                                document.getElementById('departmentSales').style.display = "none";
                                document.getElementById('miscGoodsSales').style.display = "none";
                            } else {
                                document.getElementById('office').style.display = "none";
                                document.getElementById('department').style.display = "none";
                                document.getElementById('officeExpenses').style.display = "none";
                                document.getElementById('departmentExpenses').style.display = "none";
                                document.getElementById('officeSales').style.display = "none";
                                document.getElementById('departmentSales').style.display = "none";
                                document.getElementById('miscGoodsSales').style.display = "none";
                            }
                        }
                        window.addEventListener('load', formSwitch());
                    </script>
                    

                    <form id="send-form">
                        @csrf

                        <div style="margin-bottom: 15px;">
                            <p>出力対象：</p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" value="stock" onclick="formSwitch()" checked>
                                <label class="form-check-label">事業所別在庫一覧表</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" value="sales" onclick="formSwitch()">
                                <label class="form-check-label">売掛残高一覧表＆売上一覧表</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" value="purchases" onclick="formSwitch()">
                                <label class="form-check-label">商品管理部売掛残高一覧表</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" value="expenses" onclick="formSwitch()">
                                <label class="form-check-label">販売担当者別売上実績表＆買掛台帳</label>
                            </div>                            
                        </div>

                        <div>
                            <label>出力条件：</label>
                            <table style="width:80%">
                                <tr>
                                    <td>月指定</td>
                                    <td><input type="month" name="month" min="2019-01"></td>
                                </tr>
                                <tr id="office">
                                    <td>事業所名</td>
                                    <td>
                                        <select name="office">
                                            <option value="0001">本社</option>
                                            <option value="0011">狭山</option>
                                            <option value="0017">高松</option>
                                            <option value="0023">佐世保</option>
                                            <option value="0027">熊本</option>
                                        </select>                                        
                                    </td>
                                </tr>
                                <tr id="department">
                                    <td>部署名</td>
                                    <td>
                                        <select name="department">
                                            <option value="05">エステ</option>
                                        </select>
                                    </td>
                                </tr>

                                <tr id="officeExpenses" style="display: none;">
                                    <td>事業所名</td>
                                    <td>
                                        <select name="officeExpenses">
                                            <option value="0011">狭山</option>
                                            <option value="0017">高松</option>
                                            <option value="0023">佐世保</option>
                                            <option value="0027">熊本</option>
                                            <option value="0050">福岡</option>
                                        </select>                                        
                                    </td>
                                </tr>
                                <tr id="departmentExpenses" style="display: none;">
                                    <td>部署名</td>
                                    <td>
                                        <select name="departmentExpenses">
                                            <option value="05">エステ</option>
                                            <option value="04">訪販</option>
                                        </select>
                                    </td>
                                </tr>


                                <tr id="officeSales" style="display: none;">
                                    <td>事業所名</td>
                                    <td>
                                        <select name="officeSales">
                                            <option value="0001">本社</option>
                                            <option value="0011">狭山</option>
                                            <option value="0017">高松</option>
                                            <option value="0023">佐世保</option>
                                            <option value="0027">熊本</option>
                                            <option value="0050">福岡</option>
                                        </select>                                        
                                    </td>
                                </tr>
                                <tr id="departmentSales" style="display: none;">
                                    <td>部署名</td>
                                    <td>
                                        <select name="departmentSales">
                                            <option value="02">商品管理</option>
                                            <option value="04">訪販</option>
                                            <option value="05">エステ</option>
                                            <option value="06">通販</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr id="miscGoodsSales" style="display: none;">
                                    <td>その他（雑品）</td>
                                    <td>
                                        <select name="miscGoodsSales">
                                            <option value="01">8％</option>
                                            <option value="02">10％</option>
                                        </select>
                                    </td>
                                </tr>

                            </table>
                        </div>

                    </form>
                    <button type="button" onclick="sendData()">出力</button>


                    <script>
                        function sendData() {
                            var XHR = new XMLHttpRequest();
                            var form = document.getElementById("send-form");
                            var FD = new FormData(form);

                            var month = FD.get("month");
                            if (month == null || month.length === 0){
                                alert("月指定を入力してください");
                                return;
                            }

                            var officeExpenses = FD.get("officeExpenses");
                            var departmentExpenses = FD.get("departmentExpenses");
                            var officeSales = FD.get("officeSales");
                            var departmentSales = FD.get("departmentSales");

                            if (officeExpenses == "0011" || officeExpenses == "0017" || officeExpenses == "0023" || officeExpenses == "0027") {
                                if(departmentExpenses != "05"){
                                    alert("部署名：エステを入力してください");
                                    return;                                    
                                }
                            }

                            if (officeSales == "0011" || officeSales == "0017" || officeSales == "0023" || officeSales == "0027") {
                                if(departmentSales != "05"){
                                    alert("部署名：エステを入力してください");
                                    return;                                    
                                }
                            }


                            //福岡
                            if (officeExpenses == "0050" && departmentExpenses != "04"){
                                alert("部署名：訪販を入力してください");
                                return;                                    
                            }

                            if (officeSales == "0050" && departmentSales != "04"){
                                alert("部署名：訪販を入力してください");
                                return;                                    
                            }


                            var url = '/export-csv/monthly-report/transfer-slip?';

                            //　URLパラメータを付加
                            var count = 0;
                            for( var [name, value] of FD ) {
                                if ( count == 0) {
                                    url = url + `${name}=${value}`;
                                    count++;
                                } else {
                                    url = url + `&${name}=${value}`;                                    
                                }
                            }

                            // リクエストをセットアップ
                            XHR.open("GET", url);

                            const token = document.getElementsByName('csrf-token')[0].content;
                            XHR.setRequestHeader('Csrf-Token', token);                            


                            // イベントハンドラーとして登録
                            XHR.onload = function(e) {
                                // onload 時の処理が動いたら成功かチェックさせる
                                if (this.status == 200) {
                                    var fileName = FD.get('month') + '_' + FD.get('type') + '.csv';
                                    

                                    var contentDisposition = this.getResponseHeader('Content-Disposition');
                                    var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/; // 正規表現でfilenameを抜き出す
                                    var matches = filenameRegex.exec(contentDisposition);
                                    if (matches != null && matches[1]) {
                                        fileName = matches[1].replace(/['"]/g, '');
                                    }


                                    //文字コード変換 UTF-8 > SJIS
                                    var strResponse = Encoding.stringToCode(this.response);
                                    var arrayResponse = Encoding.convert(strResponse, "SJIS", "UNICODE");
                                    var blob = new Uint8Array(arrayResponse);

                                    // IEとその他で処理の切り分け
                                    if (navigator.appVersion.toString().indexOf('.NET') > 0) {
                                        // IE 10+
                                        // IEだけはこれじゃないとダウンロードできない
                                        window.navigator.msSaveBlob(new Blob([blob], {type: 'text/csv'}), fileName);
                                    
                                    } else {
                                        // aタグの生成
                                        var a = document.createElement('a');
                                        // レスポンスからBlobオブジェクト＆URLの生成
                                        
                                        var blobUrl = window.URL.createObjectURL(new Blob([blob], {type: 'text/csv'}));

                                        document.body.appendChild(a);
                                        a.style = 'display: none';
                                        // 生成したURLをセット
                                        a.href = blobUrl;
                                        // ダウンロードの時にファイル名として表示される
                                        a.download = fileName;
                                        // クリックイベント発火
                                        a.click();

                                        a.parentNode.removeChild(a);
                                    }
                                    return;
                                } else {
                                    // エラーごとに分けたい場合は、status で切り分ける
                                    alert("CSVファイル出力に失敗しました。");
                                    return;
                                }
                            };

                            XHR.send();
                        }
                  </script>
                  
                </div>
            </div>
        </div>
    </div>
</div>
@endsection