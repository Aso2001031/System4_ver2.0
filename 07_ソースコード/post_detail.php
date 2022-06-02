<?php session_start(); ?>
<?php require 'header.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>post_detail</title>
    <link rel="stylesheet" href="./css/post_detail.css">
    <script src="./script/script.js"></script>
</head>
<body>
<button type="submit" id="return" href="map.php">←</button>
<?php
$pdo =new PDO(//DB接続
    'mysql:dbname=LAA1290633-system4ver2; host=mysql203.phy.lolipop.lan',
    'LAA1290633','System4');

$sql=$pdo->prepare('select * from post where post_id = ?');
$sql->execute();
$flg=true;

foreach ($sql as $row){
    if($_SESSION['member_id']=$row['member_id']){
        echo '<button type="submit" id="delete" onclick="" href="map.php">削除</button>';
        //できてない[ここを押したら↓が実行されて、メイン画面に戻る]
        $sql=$pdo->prepare('delete * from post where post_id = ?');
        $sql->execute();
    }

    $image_file=$row['image_file'];
    echo '<div id="post" class="post">';
    echo  $row['date'];
    echo '<h1 id="title" class="title">',$row['post_title'],'</h1>';
    echo  '<div id="image" class="image"><image src="./img/',$image_file,'.png"></div>';
    echo '<h2 id="place" class="place">',$row['place'],'</h2>';
    echo '<div id="comment" class="comment">',$row['comment'],'</div>';
    echo '</div>';
    $flg=false;
}
?>
</body>
</html>