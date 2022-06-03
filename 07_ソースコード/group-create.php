<?php
session_start();
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="script" href="./script/group-create.js">
    <title>group-create</title>
</head>
<body>
<div class="background">
<?php
$pdo = new PDO('mysql:dbname=LAA1290633-system4ver2; host=mysql203.phy.lolipop.lan',
    'LAA1290633','System4')
?>
<form action="" method="post">
<h1>グループ作成</h1>
<button type="submit" name="back"><img src="./img/back.png"></button>
<h2>グループ名</h2>
<input type="text" name="groupname" placeholder="グループ名を入力"><br>
<h2>パスワード</h2>
<input type="password" name="grouppassword" placeholder="パスワードを入力"><br>
<h2>パスワード(確認)</h2>
<input type="password" name="grouppassword2" placeholder="再度パスワードを入力"><br>
<button type="submit" id="groupcreatesubmit" onclick="groupRoomCreate()">作成</button>
    <?php
    $sql=$pdo->prepare('select group_room from group');
    $sql->execute();
    foreach ($sql as $row){
        $row['group_room'] ==
    }
    ?>
</form>
</div>
</body>
</html>
