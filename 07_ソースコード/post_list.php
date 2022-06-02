<?php session_start(); ?>
<?php require 'header.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>post_list</title>
    <link rel="stylesheet" href="./css/post_list.css">
    <script src="./script/script.js"></script>
</head>
<body>
<button type="submit" id="return" href="home.php">←</button>
<?php
$pdo =new PDO(//DB接続
    'mysql:dbname=LAA1290633-system4ver2; host=mysql203.phy.lolipop.lan',
    'LAA1290633','System4');
$stmt=$pdo->prepare('select p.member_icon,p.date,m.member_name,p.post_title,p.place,p.image_file 
                          from post as p,member as m where p.place = :place && p.member_id == m.member_id');
$stmt->bindParam(':place',$_POST['place'], PDO::PARAM_STR);
$stmt->execute();
foreach ($stmt as $row){
    $post_id = $row['post_id'];
    echo '<div id="post" class="post">';
    echo '<p id="icon" class="icon">',$row['member_icon'],'</p>';
    echo '<p id="time" class="time">',$row['date'],'</p>';
    echo '<p id="user" class="user">',$row['member_name'],'</p>';
    echo '<p id="title" class="title">',$row['post_title'],'</p>';
    echo '<p id="place" class="place">',$row['place'],'</p>';//<-
    echo '<div id="picture" class="picture"><img src = "./img/',$row['image_file'],'.png"></div>';
    echo '</div>';
}
?>
</body>
</html>
場所のがまだかな