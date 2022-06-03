<?php session_start();

if(isset($_POST['name'])){
    //入力欄に情報が入っているかどうか
    if(empty($_POST['name']) === true or empty($_POST['mail']) === true or empty($_POST['pass'])){
        print "正しい情報を入力して下さい。<br><br>";
    }else{
        //入っていれば登録に進む
        $name=$_POST['name'];
        $mail=$_POST['mail'];
        $pass=$_POST['pass'];
        //$img=$_FILES['img'];

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
            //使われていなければ登録
            $sql = "INSERT INTO member(member_id, member_name, member_pass, member_mail, member_icon, group_id) VALUES(null,?,?,?,0,1)";
            $stmt = $dbh -> prepare($sql);
            //$pass = md5($pass);
            $data[] = $name;
            $data[] = $pass;
            $data[] = $mail;
            //$data[] = $img["name"];
            $stmt -> execute($data);

            //move_uploaded_file($img["tmp_name"],"./img/".$img["name"]); 

            $dbh = null;

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
    <title></title>
</head>
<body>
    <h1>会員登録</h1>

    <form action="entry.php" method="post" enctype="multipart/from-data">

    <p>お名前</p>
    <input type="text" name="name" placeholder="名前を入力してください" ><br>

    <p>メールアドレス</p>
    <input type="text" name="mail" placeholder="メールアドレスを入力してください" ><br>

    <p>パスワード</p>
    <input type="password" name="pass" placeholder="英大文字,小文字,数字が1文字以上含まれてる8文字以上24文字以下"><br>

    <p>ユーザーアイコン(任意)</p>
    <input type="file" name="img">

    <button type="submit">確定</button>
    </form>
    </div>

</body>
</html>