<?php session_start();?>
<?php
//会員情報セッション
$id=$name=$mail=$password=$icon=$gid='';
if (!empty($_SESSION['member'])) {
    $id=$_SESSION['member']['id'];
    $name=$_SESSION['member']['name'];
    $mail=$_SESSION['member']['mail'];
    $password=$_SESSION['member']['pass'];
    $icon=$_SESSION['member']['icon'];
    $group_id = $_SESSION['member']['group_id'];
}else{
    header('location:https://aso2001031.perma.jp/System4_ver2.0/login.php');//リダイレクト処理
    exit();
}
?>
<?php //カレンダー処理
if(isset($_GET['t']) && preg_match('/\A\d{4}-\d{2}\z/', $_GET['t'])) {
//クエリ情報を基にしてDateTimeインスタンスを作成
    $start_day = new DateTime($_GET['t'] . '-01');
} else {
//当月初日のDateTimeインスタンスを作成
    $start_day = new DateTime('first day of this month');
}
//カレンダー表示月の前月の年月を取得
$dt = clone($start_day);
$prev_month =  $dt->modify('-1 month')->format('Y-m');
//カレンダー表示月の翌月の年月を取得
$dt = clone($start_day);
$next_month =  $dt->modify('+1 month')->format('Y-m');
$month = $start_day->format('m');/*カレンダーの月の変数*/
$year = $start_day->format('Y');/*カレンダーの年の変数*/
?><!--カレンダー処理ここまで-->
<?php     //天気API
function get_json( $type = null ){
$city = "Fukuoka-shi,jp";
$appid = "1d578476daafb5133d22f27eff869910";
$url = "http://api.openweathermap.org/data/2.5/weather?q=" . $city . "&units=metric&APPID=" . $appid;
$json = file_get_contents( $url );
$json = mb_convert_encoding( $json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN' );
$json_decode = json_decode( $json );
//現在の天気
if( $type  === "weather" ):
    $out = $json_decode->weather[0]->main;

//現在の天気アイコン
elseif( $type === "icon" ):?>
    <a><?php $out = "<img src='https://openweathermap.org/img/wn/" . $json_decode->weather[0]->icon . "@2x.png' width='30%' height='50px' >"; ?></a>
<?php

//現在の気温
elseif( $type  === "temp" ):?>
<a><?php $out = $json_decode->main->temp;?><a/>
    <?php

    //パラメータがないときは配列を出力
    else:
        $out = $json_decode;

    endif;

    return $out;
    }
    ?><!--天気APIここまで-->

    <?php //DB記述

    $member_id=$_SESSION['member']['id'];//セッション,member_id
    $text='';

    //DB接続

    $pdo=new PDO('mysql:host=mysql205.phy.lolipop.lan;
          dbname=LAA1290579-system4ver2;charset=utf8',
        'LAA1290579',
        'IZUken0626');
    //カレンダー表示
    $last_day = date("j", mktime(0, 0, 0, $month + 1, 0, $year));
    ?><!--DB記述ここまで-->
    <!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>Walk Record - HOME</title>
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="css/menu.css">
    </head>
    <body style="max-width: 550px; margin: auto; box-shadow: 0 0 8px gray;">
    <div class="header">
        <a class="banner_title">Walk Record</a>
    </div>
    <div class="calender_banner">
        <div class="calender_banner_1">
            <a class="calender_1" href="?t=<?php echo $prev_month ?>">&laquo;</a>
            <colspan="5"><a class="calender_2" href=""><?php echo $year ?></a>
            <a class="calender_3" href="?t=<?php echo $next_month ?>">&raquo;</a>
        </div>
        <div class="calender_banner_2">
            <a> <?php echo get_json("icon"); ?></a>
            <a><?php echo get_json("temp"); ?>℃</a>
        </div>
    </div>
    <div class="calender_area">
        <table class="calender">
            <?php
            for ($i = 1; $i < $last_day + 1; $i++){
                $date=$year.$month.$i;//その日を取得
                $week = date("w", mktime(0, 0, 0, $month, $i, $year));
                $weekjp_array = array('日', '月', '火', '水', '木', '金', '土');
                $weekjp = $weekjp_array[$week];


                //変数textにDBから予定を取得

                $stmt =$pdo->prepare('select comment from calender WHERE member_id=:member_id and date=:date ');
                $stmt->bindValue(':member_id',$member_id);
                $stmt->bindValue(':date',$date);
                $res=$stmt->execute();
                if($res=true) {
                    foreach ($stmt as $row) {
                        $text = $row['comment'];
                    }
                }
                ?>

                <tr class="calender_table_tr">
                    <td class="calender_day">
                        <?php
                        echo "<tr>\n";
                        echo "<th align='center'><a class='calender_text'>".$month."/".$i."</a><br><a class='calender_text'>".$weekjp."</a>"
                        ;?>
                    </td>
                    <td class="calender_schedule">
                        <a class="calender_text">
                            <form method="post" name="contactform" action="calendar_edit.php">
                                <input  hidden type="int"  value="<?php echo $member_id; ?>" name="member_id">
                                <input  hidden type="text" value="<?php echo $i; ?>" name="da">
                                <input  hidden type="text" value="<?php echo $date; ?>" name="date">
                                <input  hidden type="text" value="<?php echo $month; ?>" name="month">
                                <input  hidden type="text" value="<?php echo $weekjp; ?>" name="weekjp">
                                <input type="text" value="<?php echo $text; ?>" name="text" class="calender_box" readonly>
                                <button type="submit" class="calender_edit_button_float">追加</button>

                            </form>
                        </a>
                    </td>
                </tr>
                <?php
                $text='';
            }
            echo "</table>\n";
            $pdo=null;
            ?>
        </table>
    </div>
    <!-- メニューボタンエリア -->
    <div class="button_area">
        <table class="button_table">
            <tr class="button_area_tr">
                <!--ホーム画面へ-->
                <td class="button_area_td"><form action="map.php">
                        <input type="image" src="./img/map.png" name="map_home" ><br>
                        <a class="button_sub_text">マップ</a>
                    </form></td>
                <!--グループ画面へ-->
                <td class="button_area_td"><form action="profile.php">
                        <input type="image" src="./img/profile.png" name="map_group" ><br>
                        <a class="button_sub_text">プロフィール</a>
                    </form></td>
                <!--投稿画面へ画面へ-->
                <td class="button_area_td"><form method="post" action="post_list.php">
                        <input type="image" src="./img/post_list.png" name="map_post"><br>
                        <a class="button_sub_text">投稿一覧</a>
                    </form></td>
            </tr>
        </table>
    </div>
    <!-- メニューココまで -->
    <div class="footer"></div>
    </body>
    </html>

