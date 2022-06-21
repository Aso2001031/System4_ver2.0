<?php session_start();
if (isset($_POST["edit"])){
    /*DB接続*/
    $pdo = new PDO('mysql:host=mysql203.phy.lolipop.lan;
                  dbname=LAA1290633-system4ver2; charset=utf8',
        'LAA1290633',
        'System4');


    $mail=$_POST['mail'];
    $password=$_POST['pass'];


    /*メールアドレスの正規表現*/
        if (preg_match('|^[0-9a-z_./?-]+@([0-9a-z]+\.)+[0-9a-z-]+$|',$mail)){
            /*パスワードの正規表現*/
            if (preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[a-zA-Z0-9]{8,24}/',$password)){

                if (isset($_SESSION['member'])) {
                    $id=$_SESSION['member']['member_id'];
                    $sql=$pdo->prepare('SELECT * FROM member WHERE member_id=?');
                    $sql->execute([$id]);
                }

                if (isset($_SESSION['member'])) {
                    $sql=$pdo->prepare('UPDATE member SET member_name=?,member_mail=?,member_pass=?, member_icon=? WHERE member_id=?');
                    $sql->execute([
                        $_REQUEST['name'],$_REQUEST['mail'],$_REQUEST['password'],$_REQUEST['icon'],$id]);

                    /*sessionに再登録*/
                    $_SESSION['member']=[
                        'member_id'=>$id,'member_name'=>$_REQUEST['name'],
                        'member_mail'=>$_REQUEST['mail'],'member_pass'=>$_REQUEST['password'],
                        'member_icon'=>$_REQUEST['icon']];

                    if (isset($_SESSION['member'])){
                        http_response_code(301);

                        header("location:http://aso2001007.versus.jp/System4_Ver2.0/edit.php");
                        exit;
                    }
                }
            }
        }
    }

?>
<!--会員情報編集ページ-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>お散歩</title>
</head>
<body>
<!--sessionで会員情報を取得-->
<?php
$id=$name=$mail=$password=$icon='';
if (isset($_SESSION['member'])) {
    $id=$_SESSION['member']['id'];
    $name=$_SESSION['member']['name'];
    $mail=$_SESSION['member']['mail'];
    $password=$_SESSION['member']['pass'];
    $icon=$_SESSION['member']['icon'];
}
?>

<!-- 会員情報 -->
<a class="edit_title">会員情報</a>

<?php
if (isset($_POST["edit"])){
    echo '<a class="cant_edit">会員情報を更新出来ませんでした</a>';
}
?>
<div class="edit">
    <form action="edit.php" method="post">
        
        <a class="edit-text">お名前：</a>
        <?php
        echo '<input type="text" name="name" class="edit-box" value="',$name,'">';
        ?>
        <a class="edit-text">メールアドレス：</a>
        <?php
        echo '<input type="text" name="mail" class="edit-box" value="',$mail,'">';
        ?>
        <a class="edit-text">パスワード：</a>
        <?php
        echo '<input type="password" name="pass" class="edit-box" value="',$password,'">';
        ?>
        <a class="edit-text">ユーザーアイコン：</a>
        <!-- 画像を表示している箇所 -->
        <?php if(!empty($icon)){;?>
        <img src="<?php echo $icon;?>" alt="">
        <?php } ;?>
        <input type="file" name="icon" class="edit-box">

        <button type="submit" name="edit" class="edit-button">完了</button>
    </form>
    <a href="http://aso2001007.versus.jp/System4_Ver2.0/menu.php">メニューへ</a>
</div>

</body>
</html>