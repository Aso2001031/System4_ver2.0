<?php session_start(); ?>
<?php ini_set('display_errors',"On");?>
<?php
//会員情報セッション
$member_id=$name=$mail=$password=$icon=$gid='';
if (!empty($_SESSION['member'])) {
    $member_id=$_SESSION['member']['id'];
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
<?php
//$_FILEに情報があるか確認
if(isset($_POST['post'])) {
    if (!empty($_FILES)) {
        //$_FILESからファイル名を取得する
        $filename = $_FILES['image_file']['name'];
        //（4）$_FILESから保存先を取得して、images_after（ローカルフォルダ）に移す
        //move_uploaded_file（第1引数：ファイル名,第2引数：格納後のディレクトリ/ファイル名）
        $uploaded_path = 'post_img/' . $filename;
        $result = move_uploaded_file($_FILES['image_file']['tmp_name'],$uploaded_path);

        if ($result) {
            $img_path = $uploaded_path;
            $dsn = "mysql:host=mysql205.phy.lolipop.lan;dbname=LAA1290579-system4ver2;charset=utf8";
            $user = "LAA1290579";
            $password = "IZUken0626";
            $dbh = new PDO($dsn, $user, $password);
            $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if (!empty($_POST['post_name'] && $_POST['comment'])) {

                $sql = 'INSERT INTO post(post_id,post_name,member_id,group_id,image_file,comment,date,coordinate_X,coordinate_Y,post_address) 
                                      VALUES(null,?,?,?,?,?,?,?,?,?)';
                $stmt = $dbh->prepare($sql);

                $data[] = $_POST['post_name'];
                $data[] = $member_id;
                $data[] = $group_id;
                $data[] = $img_path;
                $data[] = $_POST['comment'];
                $date = date("Y/m/d H:i:s");
                $data[] = $date;
                $data[] = $_POST['lat'];
                $data[] = $_POST['lon'];
                $data[] = $_POST['address'];
                $stmt ->execute($data);
                $dbh = null;

                echo <<<EOF
                <script>//リダイレクト
                location.href='post_list.php';
                </script>
EOF;

            }
        } else {
            $alert = "<script type='text/javascript'>alert('写真を選択してください');</script>";
            echo $alert;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Walk Record - POST</title>
    <link rel="stylesheet" href="./css/post.css">
    <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body style="max-width: 550px; margin: auto; height: 100%; box-shadow: 0 0 8px gray;">
<div class="header">
    <a class="banner_title">Walk Record</a>
</div>
<form action="map.php">
    <a class="post_title">投稿</a>
    <button type="submit"　class="return">←</button>
</form>
<div class="post_area">
    <form action="post.php" method="post" enctype="multipart/form-data">
        <input required type="text" class="post_box" name="post_name" maxlength="10" placeholder="タイトル"><br>
        <input required type="file" id="img" accept='image/*' class="image_file" name="image_file" onchange="previewImage(this);"><br>
        <img id="preview" src="data:image/gif;base64,R01GODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="><br>
        <script>
            function previewImage(obj)
            {
                var fileReader = new FileReader();
                fileReader.onload = (function(){
                    document.getElementById('preview').src = fileReader.result;
                });
                fileReader.readAsDataURL(obj.files[0]);
            }
        </script>
        <button type="button" id="get-gps" class="get-gps">現在地名取得</button><br>
        <input type="text" class="post_box_address" name="address" id="address" value="" > <!--場所-->
        <input type="hidden" name="lat" id="lat"  value=""><!-- 緯度 -->
        <input type="hidden" name="lon" id="lon"  value=""><br><!-- 経度 -->
        <textarea class="comment_box" name="comment" placeholder="コメント(最大300文字)" maxlength="300"></textarea><br>
        <button type="submit" id="post_post_button" name="post">投稿</button>
    </form>
</div>
<div class="footer"></div>
</body>
<script>
    //*変換表を入れる場所
    var GSI = {};

    const latEle = document.querySelector('#lat');
    const lonEle = document.querySelector('#lon');
    const addressEle = document.querySelector('#address');
    const gpsButton  = document.querySelector('#get-gps');
    //緯度経度を画面
    const setGeoLoc  = (coords) => {
        document.getElementById('lat').value = coords.latitude;
        document.getElementById('lon').value = coords.longitude;
    }
    //緯度経度から住所を取得して表示
    const getAddress = async (coords) => {
        // 逆ジオコーディング API
        const url 　= new URL('https://mreversegeocoder.gsi.go.jp/reverse-geocoder/LonLatToAddress');
        url.searchParams.set('lat', coords.latitude);
        url.searchParams.set('lon', coords.longitude);
        const res  = await fetch(url.toString());
        const json = await res.json();
        const data = json.results;

        // 変換表から都道府県などを取得
        const muniData = GSI.MUNI_ARRAY[json.results.muniCd];
        // 都道府県コード,都道府県名,市区町村コード,市区町村名 に分割
        const [prefCode, pref, muniCode, city] = muniData.split(',');

        // 画面に反映
        address.textContent = `${pref} ${city} ${data.lv01Nm}`;
        document.getElementById('address').value = `${pref} ${city} ${data.lv01Nm}`;
    };
    //位置情報 API の実行(イベントリスナ)
    gpsButton.addEventListener('click', () => {
        navigator.geolocation.getCurrentPosition(
            geoLoc => {
                setGeoLoc(geoLoc.coords);
                getAddress(geoLoc.coords);
            },
            err => console.error({err}),
        );
    });
</script>
<script src="https://maps.gsi.go.jp/js/muni.js"></script>  <!-- 変換表の読込 -->
</html>
