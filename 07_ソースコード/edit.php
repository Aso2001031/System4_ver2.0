<?php session_start();
if(isset($_POST['edit'])){
    if (empty($_POST['simei']) === true or empty($_POST['mail']) === true or empty($_POST['pass'])){
        echo '正しい情報を入れてください！';
    }else{

        $id='';
        $id=$_SESSION['member']['id'];

        $name=$_POST['simei'];
        $mail=$_POST['mail'];
        $password=$_POST['pass'];


        /*メールアドレスの正規表現*/
        if (preg_match('|^[0-9a-z_./?-]+@([0-9a-z]+\.)+[0-9a-z-]+$|',$mail)){
            /*パスワードの正規表現*/
            if (preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[a-zA-Z0-9]{8,24}/',$password)){


                if(!empty($_FILES['icon'])){

                    $filename = $_FILES['icon']['name'];
    
                    $uploaded_path = 'user_img/'.$filename;

                    $result = move_uploaded_file($_FILES['icon']['tmp_name'],$uploaded_path);

                    if($result){
                        $img_path = $uploaded_path;

                        
                            /*DB接続*/
                            $pdo = new PDO('mysql:host=mysql203.phy.lolipop.lan;
                            dbname=LAA1290633-system4ver2; charset=utf8',
                            'LAA1290633',
                            'System4');

                            $stmt = 'UPDATE member SET member_name=:member_name,member_mail=:member_mail,member_pass=:member_pass,member_icon=:member_icon WHERE member_id=:member_id';
                            $stmt = $pdo -> prepare($stmt);
                            $stmt->bindParam(':member_name', $_POST['simei'], PDO::PARAM_STR);
                            $stmt->bindParam(':member_mail', $_POST['mail'], PDO::PARAM_STR);
                            $stmt->bindParam(':member_pass', $_POST['pass'], PDO::PARAM_STR);
                            $stmt->bindParam(':member_icon', $img_path, PDO::PARAM_STR);
                            $stmt->bindParam(':member_id', $id, PDO::PARAM_INT);
                            $stmt->execute();
                            
                            $_SESSION['member']=[
                                'id'=>$id,
                                'mail'=>$_POST['mail'],'name'=>$_POST['simei'],
                                'pass'=>$_POST['pass'],'icon'=>$img_path];
                            
                
                        header("location:http://aso2001007.versus.jp/System4_Ver2.0/edit.php");
                        exit();

                    }else{
                        /*DB接続*/
                        $pdo = new PDO('mysql:host=mysql203.phy.lolipop.lan;
                        dbname=LAA1290633-system4ver2; charset=utf8',
                        'LAA1290633',
                        'System4');
                                    
                        $sql=$pdo->prepare('UPDATE member SET member_name=?,member_mail=?,member_pass=?  WHERE member_id=?');
                        $sql->execute([
                            $_REQUEST['simei'],$_REQUEST['mail'],$_REQUEST['pass'],$id]);
        
                        $sql=$pdo->prepare('select * from member where member_mail=? and member_pass=?');
                            $sql->execute([$_REQUEST['mail'],$_REQUEST['pass']]);    
        
                        /*sessionに再登録*/
                        foreach ($sql as $row){
                            $_SESSION['member']=[
                                'id'=>$row['member_id'],'mail'=>$row['member_mail'],
                                'name'=>$row['member_name'],'pass'=>$row['member_pass'],'icon'=>$row['member_icon']];
                            }
                        header("location:http://aso2001007.versus.jp/System4_Ver2.0/edit.php");
                        exit;
                    
                    }
    
                               
                }
            }else{
                echo 'パスワードは八文字以上二十四文字以内でお願いします！';   
            }
        }else{
            echo 'メールアドレスが不正です';
        }
    }
}


?>
<!--会員情報編集ページ-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/style.css">
    <title>お散歩</title>
</head>
<body>
<!-- バナー表示 -->
<div class="header">
    <a class="banner_title">お散歩</a>
    <a class="member_icon" type="image" src="./<?php $icon?>" name="member_icon"></a>
</div>
<!-- バナーココまで -->



<!--sessionで会員情報を取得-->
<?php
$id=$name=$mail=$password=$icon='';
if (!empty($_SESSION['member'])) {
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
    <form action="edit.php" method="post" enctype="multipart/form-data">
        
        <a class="edit-text">お名前：</a><br>
        <?php
        echo '<input type="text" name="simei" class="edit-box" value="',$name,'">';
        ?>
        <a class="edit-text">メールアドレス：</a><br>
        <?php
        echo '<input type="text" name="mail" class="edit-box" value="',$mail,'">';
        ?>
        <a class="edit-text">パスワード：</a><br>
        <?php
        echo '<input type="password" name="pass" class="edit-box" value="',$password,'">';
        ?>
        <a class="edit-text">ユーザーアイコン：</a><br>
        <div class=edit-img>
        <?php
        echo '<img src="',$icon,'" alt="">';
        ?>
        </div>
        <input type="file" name="icon" class="edit-box">

        <button type="submit" name="edit" class="edit-button">完了</button>
    </form>
    <a href="http://aso2001007.versus.jp/System4_Ver2.0/menu.php">メニューへ</a>
    <?php
    echo '<a href="' . $_SERVER['HTTP_REFERER'] . '">前に戻る</a>';
    ?>
</div>

</body>
</html>