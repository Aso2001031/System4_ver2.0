<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/style.css">
    <title>group-in</title>
</head>
<body>
<?php
$pdo = new PDO('mysql:dbname=LAA1290633-system4ver2; host=mysql203.phy.lolipop.lan',
    'LAA1290633','System4')
?>
<h1>入室</h1>
<div><button type="submit" class="back"><img src="./img/back.png"></button></div>
<h2>グループ名</h2>
<input type="text" id="groupinname" placeholder="グループ名を入力"><br>
<?php
$flg = true;
$sql=$pdo->prepare('select group_id,group_pass from group where group_name == ?');
$sql->bindValue(1,$_GET['groupname'],PDO::PARAM_STR);
if ($sql->execute()){
    $flg = false;
}
if ($flg){
    echo '存在しないグループです';
}
?>
<h2>パスワード</h2>
<input type="password" id="groupinpassword" placeholder="パスワードを入力"><br>
<?php

?>
<button type="submit" name="groupinsubmit">入室</button>
<a href="group-create.php">グループを作成する</a>

</body>
</html>