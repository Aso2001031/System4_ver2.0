<?php session_start();
unset($_SESSION['member']);
if(isset($_POST["login"])){
    unset($_SESSION['member']);
    $pdo=new PDO('mysql:host=mysql205.phy.lolipop.lan;
          dbname=LAA1290579-system4ver2;charset=utf8',
        'LAA1290579',
        'IZUken0626');
    $mail=$_POST['mail'];
    if (preg_match('|^[0-9a-z_./?-]+@([0-9a-z]+\.)+[0-9a-z-]+$|',$mail)){
        $sql=$pdo->prepare('select * from member where member_mail=? and member_pass=?');
        $sql->execute([$_REQUEST['mail'],$_REQUEST['pass']]);
        foreach ($sql as $row){
            $_SESSION['member']=[
                'id'=>$row['member_id'],'mail'=>$row['member_mail'],
                'name'=>$row['member_name'],'pass'=>$row['member_pass'],
                'icon'=>$row['member_icon'],'group_id'=>$row['group_id']];
        }
        if (isset($_SESSION['member'])){
            http_response_code(301);
            header("location:https://aso2001031.perma.jp/System4_ver2.0/map.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Walk Record - LOGIN</title>
    <link rel="stylesheet" href="./css/login.css">
</head>
<body style="max-width: 550px; margin:auto; height: 770px; box-shadow: 0 0 8px gray;">
<!-- バナー表示 -->
<div class="header">
    <a class="banner_title">Walk Record</a>
</div>
<!-- バナーココまで -->
<div class="login">
    <a class="login_title">ログイン</a><br>
    <?php
    if(isset($_POST["login"])){
        echo '<div class="error_text">';
        $alert = "<script type='text/javascript'>alert('メールアドレス又はパスワードが違います。');</script>";
        echo $alert;
        echo '</div>';
    }else{
        echo "<br>";
    }
    ?>
    <div class="login_area">
        <form action="login.php" method="post">
            <a class="log_text">メールアドレス</a><br>
            <input type="text" name="mail"class="log_box"><br>
            <a class="log_text">パスワード</a><br>
            <input type="password" name="pass" class="log_box"><br>
            <button type="submit" name="login" class="login_button">ログイン</button><br>
        </form>
        <a class="register-text" href="register.php">会員登録の方はこちら</a><br>
        <a class="use">※携帯のブラウザからの使用を推奨します。</a>
    </div>
</div>
<div class="footer"></div>
</body>
</html>
