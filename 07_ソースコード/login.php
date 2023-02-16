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
                'name'=>$row['member_name'],'pass'=>$row['member_pass'],'icon'=>$row['member_icon']];
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
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <!-- バナー表示 -->
    <div class="header">
        <a class="banner_title">お散歩</a>
        <a class="member_icon" type="image" src="./<?php $icon?>" name="member_icon"></a>
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
    <div class="login-form">
        <form action="login.php" method="post">
            <a class="log_text">メールアドレス</a><br>
            <input type="text" name="mail"class="log_box" value="sample@ac.asojuku.ac"><br>
            <a class="log_text">パスワード</a><br>
            <input type="password" name="pass" class="log_box" value="SAMple2022"><br>
            <button type="submit" name="login" class="login_button">ログイン</button><br>
        </form>
        
        <a class="login-url" href="http://aso2001007.versus.jp/System4_Ver2.0/entry.php">会員登録の方はこちら</a>
    </div>
</div>
</body>
</html>
