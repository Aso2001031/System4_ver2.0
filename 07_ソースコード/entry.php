<?php session_start();?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
    <h1>会員登録</h1>
<?php
$name=$pass=$mail=$icon='';
if (isset($_SESSION['member'])) {
    $name=$_SESSION['member']['name'];
    $pass=$_SESSION['member']['pass'];
    $mail=$_SESSION['member']['mail'];
    $icon=$_SESSION['member']['icon'];
}

echo '<form action="check.php" method="post">';
echo '<p>お名前</p>';
echo '<input type="text" name="name" placeholder="名前を入力してください" value="',$name,'">','<br>';
echo '<p>メールアドレス</p>';
echo '<input type="text" name="mail" placeholder="メールアドレスを入力してください" value="',$mail,'">','<br>';
echo '<p>パスワード</p>';
echo '<input type="password" name="pass" placeholder="英大文字,小文字,数字が1文字以上含まれてる8文字以上24文字以下" value="',$pass,'">','<br>';
echo '<input type="submit" class="register-submit" value="確定">';
echo '</form>';
echo '</div>';
?>
</body>
</html>