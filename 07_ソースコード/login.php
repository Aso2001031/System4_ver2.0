<?php session_start();

unset($_SESSION['member']);

if(isset($_POST["login"])){

    unset($_SESSION['member']);

    $pdo=new PDO('mysql:host=mysql203.phy.lolipop.lan;
          dbname=LAA1290633-system4ver2;charset=utf8',
        'LAA1290633',
        'System4');

    $mail=$_POST['mail'];
    if (preg_match('|^[0-9a-z_./?-]+@([0-9a-z]+\.)+[0-9a-z-]+$|',$mail)){

        $sql=$pdo->prepare('select * from member where member_mail=? and member_pass=?');
        $sql->execute([$_REQUEST['mail'],$_REQUEST['pass']]);

        foreach ($sql as $row){
            $_SESSION['member']=[
                'id'=>$row['member_id'],'mail'=>$row['member_mail'],
                'name'=>$row['member_name'],'pass'=>$row['member_pass']];
        }
        if (isset($_SESSION['member'])){
            http_response_code(301);

            header("location:http://aso2001007.versus.jp/System4_Ver2.0/thank.php");
            exit();
        }

    } 

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>お散歩</title>
</head>
<body>

<div class="login">


    <a class="login_title">ログイン</a><br>

    <?php
    if(isset($_POST["login"])){
        echo '<div class="error_text">';
        echo 'メールアドレス又はパスワードが違います。';
        echo '</div>';
    }else{
        echo "<br>";
    }
    ?>

    <form action="login.php" method="post">
        <a class="log_text">メールアドレス</a><br>
        <input type="text" name="mail"class="log_box"><br>
        <a class="log_text">パスワード</a><br>
        <input type="password" name="pass" class="log_box"><br>


        <button type="submit" name="login" class="login_button">ログイン</button><br>
    </form>
    
    <a class="login-url" href="http://aso2001007.versus.jp/System4_Ver2.0/entry.php">会員登録の方はこちら</a>
</div>
</body>
</html>