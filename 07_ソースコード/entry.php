<?php session_start();

if(isset($_POST['name'])){
    //入力欄に情報が入っているかどうか
    if(empty($_POST['name']) === true or empty($_POST['mail']) === true or empty($_POST['pass'])){
        $alert = "<script type='text/javascript'>alert('正しく入力してください');</script>";
        echo $alert;
    }else{
        //入っていれば登録に進む
        $name=$_POST['name'];
        $mail=$_POST['mail'];
        $pass=$_POST['pass'];
        
        //（2）$_FILEに情報があれば（formタグからpost送信されていれば）以下の処理を実行する
        if(!empty($_FILES)){
 
            //（3）$_FILESからファイル名を取得する
            $filename = $_FILES['img']['name'];
            
            //（4）$_FILESから保存先を取得して、images_after（ローカルフォルダ）に移す
            //move_uploaded_file（第1引数：ファイル名,第2引数：格納後のディレクトリ/ファイル名）
            $uploaded_path = 'user_img/'.$filename;
            //echo $uploaded_path.'<br>';
            
            $result = move_uploaded_file($_FILES['img']['tmp_name'],$uploaded_path);
            if($result){
                $img_path = $uploaded_path;

                $dsn = "mysql:host=mysql203.phy.lolipop.lan;dbname=LAA1290633-system4ver2;charset=utf8";
                $user = "LAA1290633";
                $password = "System4";
                $dbh = new PDO($dsn, $user, $password);
                $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                //既に使われているメールアドレスかどうかを判断
                $sql = "SELECT * FROM member WHERE member_mail=?";
                $stmt = $dbh -> prepare($sql);
                $data[] = $mail;
                $stmt -> execute($data);
                $data = array();
                
                $rec = $stmt -> fetch(PDO::FETCH_ASSOC);
                
                
                
                if(!empty($rec) === true) {
                    $alert = "<script type='text/javascript'>alert('すでに使われているメールです');</script>";
                    echo $alert;
                } else {

                    //使われていなければ登録
                    $sql = "INSERT INTO member(member_id, member_name, member_pass, member_mail, member_icon, group_id) VALUES(null,?,?,?,?,1)";
                    $stmt = $dbh -> prepare($sql);
                    //$pass = md5($pass);
                    $data[] = $name;
                    $data[] = $pass;
                    $data[] = $mail;
                    $data[] = $uploaded_path;
                    $stmt -> execute($data);

                    $dbh = null;

                    header("location:http://aso2001007.versus.jp/System4_Ver2.0/login.php");
                    exit();
                
                }
                
                $dsn = "mysql:host=mysql203.phy.lolipop.lan;dbname=LAA1290633-system4ver2;charset=utf8";
                $user = "LAA1290633";
                $password = "System4";
                $dbh = new PDO($dsn, $user, $password);
                $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                //既に使われているメールアドレスかどうかを判断
                $sql = "SELECT * FROM member WHERE member_mail=?";
                $stmt = $dbh -> prepare($sql);
                $data[] = $mail;
                $stmt -> execute($data);
                $data = array();
            
                $rec = $stmt -> fetch(PDO::FETCH_ASSOC);
                
                
                
                if(!empty($rec) === true) {
                    $alert = "<script type='text/javascript'>alert('すでに使われているメールです');</script>";
                    echo $alert;
                } else {
    
                    $img= 'user_img/no-image.png';
                    //使われていなければ登録
                    $sql = "INSERT INTO member(member_id, member_name, member_pass, member_mail, member_icon, group_id) VALUES(null,?,?,?,?,1)";
                    $stmt = $dbh -> prepare($sql);
                    //$pass = md5($pass);
                    $data[] = $name;
                    $data[] = $pass;
                    $data[] = $mail;
                    $data[] = $img;
                    $stmt -> execute($data);
    
                    $dbh = null;
    
                    header("location:http://aso2001007.versus.jp/System4_Ver2.0/login.php");
                    exit();
                }
            }           
        }
    //$_FILESに何も入っていなかったら下の処理を行う  
    }
    $dsn = "mysql:host=mysql203.phy.lolipop.lan;dbname=LAA1290633-system4ver2;charset=utf8";
    $user = "LAA1290633";
    $password = "System4";
    $dbh = new PDO($dsn, $user, $password);
    $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
    //既に使われているメールアドレスかどうかを判断
    $sql = "SELECT * FROM member WHERE member_mail=?";
    $stmt = $dbh -> prepare($sql);
    $data[] = $mail;
    $stmt -> execute($data);
    $data = array();
    
    $rec = $stmt -> fetch(PDO::FETCH_ASSOC);
            
            
            
    if(!empty($rec) === true) {
        print "<br>";
        print "すでに使われているメールです。<br><br>";
    } else {

        $img= 'user_img/no-image.png';
        //使われていなければ登録
        $sql = "INSERT INTO member(member_id, member_name, member_pass, member_mail, member_icon, group_id) VALUES(null,?,?,?,?,1)";
        $stmt = $dbh -> prepare($sql);
        //$pass = md5($pass);
        $data[] = $name;
        $data[] = $pass;
        $data[] = $mail;
        $data[] = $img;
        $stmt -> execute($data);

        $dbh = null;

        header("location:http://aso2001007.versus.jp/System4_Ver2.0/login.php");
        exit();
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
    <a class="entry-title">会員登録</a>
    <div class="entry-form">
        <form action="entry.php" method="post" enctype="multipart/form-data">

            <a class="entry-text">お名前</a><br>
            <input type="text" name="name" class="entry-box" placeholder="名前を入力してください" ><br>

            <a class="entry-text">メールアドレス</a><br>
            <input type="text" name="mail" class="entry-box" placeholder="メールアドレスを入力してください" ><br>

            <a class="entry-text">パスワード</a><br>
            <input type="password" name="pass" class="entry-box" placeholder="英大文字,小文字,数字が1文字以上含まれてる8文字以上24文字以下"><br>

            <a class="entry-text">ユーザーアイコン(任意)</a><br>
            <input type="file" name="img" class="entry-file"><br>

            <button type="submit" class="entry-button">確定</button>
        </form>
    </div>

</body>
</html>
