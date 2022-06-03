<?php
session_start();
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="script" href="./script/group-profile-script.js">
    <title>group-profile</title>
</head>
<body>
<?php
$pdo = new PDO('mysql:dbname=LAA1290633-system4ver2; host=mysql203.phy.lolipop.lan',
'LAA1290633','System4')
?>

<h1>グループ情報</h1>

<button type="submit" class="back"><img src="./img/back.png"></button>
<input type="checkbox">
<label for="icon"><a href=""><img src=""></a></label>
<h2>グループ名</h2>
<label for="groupname"><?= $_POST['group_name'] ?></label>
<h2>パスワード</h2>
<label for="password" name="password" hidden onclick="passChange()" id="password"><?= $_POST['group_pass'] ?></label>
<h2>グループID</h2>
<?= $_POST['group_id']?>
<h2>メンバー一覧</h2>
<button type="submit" name="kick">追放</button>
<button type="submit" name="edit">編集</button>
<button type="submit" name="withdrawal">退会する</button>
</body>
</html>