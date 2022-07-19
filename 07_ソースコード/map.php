<?php session_start()?>
<?php
$id=$name=$icon='';
$id=$_SESSION['member']['id'];
$name=$_SESSION['member']['name'];
$icon=$_SESSION['member']['icon'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <title>Walk Record - MAP</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        .header {
            padding-top: 10px;
            background-color: #2D6497;
            text-align: left;
            width: 100%;
            height: 50px;
        }
        body{
            background-color: #F4F4F4;
        }
        .banner_title {
            margin-left: 10px;
            color: #FFFFFF;
            font-size: 32px;
            font-family: "BIZ UD明朝 Medium";
        }
        #map {
            margin: auto;
        }
        .button_area {
            background-color: #B9DEC6;
            width: 100%;
            height: 20%;
        }
        .button_table {
            width: 100%;
            border-collapse: collapse;
            border: solid 1px;
            margin: auto;
        }
        .button_area_tr {
            border: solid 1px;
        }
        .button_area_td {
            padding-left: 30px;
            padding-right: 30px;
            text-align: center;
            border: solid 1px;
        }
    </style>
    <script type="text/javascript">
        // googleMapsAPIを持ってくるときに,callback=initMapと記述しているため、initMap関数を作成
        function initMap() {
            // 位置情報の追跡を開始する
            var watchId = navigator.geolocation.watchPosition(successFunc, errorFunc, optionObj);
            // グローバル変数
            var syncerWatchPosition = {
                map: null,
                marker: null,
            };
            // 成功した時の関数
            function successFunc(position) {
                // 位置情報
                var opts = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                let lat = position.coords.latitude;//緯度
                let log = position.coords.longitude;//軽度
                // 住所
                document.getElementById('lat').value = lat;
                document.getElementById('log').value = log;
                // Google Mapsに書き出し
                if (syncerWatchPosition.map == null) {
                    // 地図の新規出力
                    syncerWatchPosition.map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 18,				// ズーム値
                        center: opts,		// 中心座標 [latlng]
                    });
                    // マーカーの新規出力
                    syncerWatchPosition.marker = new google.maps.Marker({
                        map: syncerWatchPosition.map,
                        position: opts,
                    });
                } else {
                    // マーカーの場所を変更
                    syncerWatchPosition.marker.setPosition(opts);
                }
            }
            // 失敗した時の関数
            function errorFunc(error) {
                // エラーコードのメッセージを定義
                var errorMessage = {
                    0: "原因不明のエラーが発生しました…。",
                    1: "位置情報の取得が許可されませんでした…。",
                    2: "電波状況などで位置情報が取得できませんでした…。",
                    3: "位置情報の取得に時間がかかり過ぎてタイムアウトしました…。",
                };
                // エラーコードに合わせたエラー内容を表示
                alert(errorMessage[error.code]);
            }
            // オプション・オブジェクト
            var optionObj = {
                "enableHighAccuracy": false,
                "timeout": 1000000,
                "maximumAge": 0,
            };
            // 現在位置を取得する
            navigator.geolocation.watchPosition( successFunc , errorFunc , optionObj ) ;

        }

    </script>
</head>
<body onload="initMap()">
<!-- バナー表示 -->
<div class="header">
    <a class="banner_title">Walk Record</a>
    <a class="member_icon" type="image" src="./<?php $icon?>" name="member_icon"></a>
</div>
<!-- バナーココまで -->
<!-- 地図表示エリア -->
<!-- APIキー = AIzaSyBdhr2imjSl32VAlcXGZ-08eyXqcYD_8sw -->
<div class="map_area">
    <div id="map" style="width:355px; height:620px"></div>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?sensor=true&key=AIzaSyBdhr2imjSl32VAlcXGZ-08eyXqcYD_8sw&callback=initMap">
    </script>
</div>
<!-- 地図表示ココまで -->
<!-- メニューボタンエリア -->
<div class="button_area">
    <table class="button_table">
        <tr class="button_area_tr">
            <!--ホーム画面へ-->
            <td class="button_area_td"><form action="home.php">
                    <input type="image" src="./img/home.png" name="map_home" ><br>
                    <input type="hidden" name="lat" id="lat" value=""><!--緯度-->
                    <input type="hidden" name="log" id="log" value=""><!--経度-->
                    <a class="button_sub_text">ホーム</a>
                </form></td>
            <!--グループ画面へ-->
            <td class="button_area_td"><form action="group-in.php">
                    <input type="image" src="./img/group.png" name="map_group" ><br>
                    <a class="button_sub_text">グループ</a>
                </form></td>
            <!--投稿画面へ画面へ-->
            <td class="button_area_td"><form method="post" action="post.php">
                    <input type="hidden" name="lat" id="lat" value=""><!--緯度-->
                    <input type="hidden" name="log" id="log" value=""><!--経度-->
                    <input type="image" src="./img/post.png" name="map_post"><br>
                    <a class="button_sub_text">投稿</a>
                </form></td>
        </tr>
    </table>
</div>
<!-- メニューココまで -->
</body>
</html>