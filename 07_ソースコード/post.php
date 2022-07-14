<?php session_start(); ?>
<?php ini_set('display_errors',"On");?>
<?php
//会員情報セッション
$member_id = $_SESSION['member']['id'];
$name = $_SESSION['member']['name'];
$icon = $_SESSION['member']['icon'];
$group_id = $_SESSION['member']['group_id']
?>
<?php
    //$_FILEに情報があるか確認
    if(isset($_POST['post'])) {
        if (!empty($_FILES)) {
            //$_FILESからファイル名を取得する
            $filename = $_FILES['image_file']['name'];
            //（4）$_FILESから保存先を取得して、images_after（ローカルフォルダ）に移す
            //move_uploaded_file（第1引数：ファイル名,第2引数：格納後のディレクトリ/ファイル名）
            $uploaded_path = 'image/' . $filename;
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
                location.href='map.php';
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
    <title>post</title>
    <link rel="stylesheet" href="./css/post.css">
    <script src="./script/script.js"></script>
    </head>
    <body class="body">
    <button type="submit" id="return"　class="return" onclick="history.back()">←</button>
    <div id="post_area" class="post_area">
    <form action="post.php" method="post" enctype="multipart/form-data">
        <input required type="text" id="post_name" class="post_name" name="post_name" placeholder="タイトル"><br>
        <div>
            <label>
                <span id="file_label" class="file_label">
                    写真を選択
                </span>
                <input required type="file" id="image_file" class="image_file" name="image_file" onchange="previewImage(this);">
            </label>
        </div>
        <img id="preview" class="preview" src="data:image/gif;base64,R01GODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" style="max-width:300px;max-height:250px;"><br>
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
        <input type="text" name="address" id="address" class="address" value="" > <!--場所-->
        <input type="hidden" name="lat" id="lat" class="lat" value=""><!-- 緯度 -->
        <input type="hidden" name="lon" id="lon" class="lon" value=""><br><!-- 経度 -->

        <input type="text" id="comment" class="comment" name="comment" placeholder="コメント" style="width: 300px;height: 200px;"><br>
        <button type="submit" id="post" class="post" name="post">投稿</button>
    </form>
    </div>
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
