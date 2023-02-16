<?php session_start();?>
<!--sessionで会員情報を取得-->
<?php
$id=$name=$mail=$password=$icon='';
if (!empty($_SESSION['member'])) {
    $id = $_SESSION['member']['id'];
    $name=$_SESSION['member']['name'];
    $mail=$_SESSION['member']['mail'];
    $password=$_SESSION['member']['pass'];
    $icon=$_SESSION['member']['icon'];
}else{
    header('location:https://aso2001031.perma.jp/System4_ver2.0/login.php');//リダイレクト処理
    exit();
}
?>
<!--会員情報ページ-->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Walk Record - PROFILE</title>
    <link rel="stylesheet" href="css/profile-edit.css">
    <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body style="max-width: 550px; margin:auto; height: 100%; box-shadow: 0 0 8px gray;">
<div class="header">
    <a class="banner_title">Walk Record</a>
</div>
<!-- 会員情報 -->
<form action="home.php">
    <a class="profile_title">会員情報</a>
    <button type="submit" class="return">←</button>
</form>
<div class="profile-edit_area">
    <a class="profile-edit_text">ユーザー名</a><br>
    <?php
    echo '<input type="text" class="profile_main_box" value="',$name,'" maxlength="20" readonly>';
    ?>
    <br>
    <a class="profile-edit_text">メールアドレス</a><br>
    <?php
    echo '<input type="text" class="profile_main_box" value="',$mail,'" readonly maxlength="20">';
    ?>
    <br>
    <a class="profile-edit_text">パスワード</a><br>
    <?php
    echo '<input type="text" class="profile_main_box" value="',$password,'" readonly maxlength="15">';
    ?>
    <br>
    <a class="profile-edit_text">アイコン</a><br>
    <?php
    echo '<img src="',$icon,'" alt="">';
    echo '<br>';
    ?>
    <a href="profile_edit.php"><button type="submit" name="edit" class="profile-edit_button">変更</button></a>
</div>
</body>
</html>