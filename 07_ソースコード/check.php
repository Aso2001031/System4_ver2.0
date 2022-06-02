<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>

<?php
    
//$post = sanitize($_POST);
 
$name = $_POST["name"];
$pass = $_POST["pass"];
$mail = $_POST["mail"];
//$img = $_FILES["img"];
    
    
if(empty($name) === true or empty($pass) === true or empty($mail)) {
    print "正しい情報を入力して下さい。<br><br>";
    print "<form>";
    print "<input type='button' onclick='entry.back()' value='戻る'>";
    print "</form>";
} else {
    
    $dsn = "mysql:host=mysql203.phy.lolipop.lan;dbname=LAA1290633-system4ver2;charset=utf8";
    $user = "LAA1290633";
    $password = "System4";
    $dbh = new PDO($dsn, $user, $password);
    $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "SELECT * FROM member WHERE member_mail=?";
    $stmt = $dbh -> prepare($sql);
    $data[] = $mail;
    $stmt -> execute($data);
    $data = array();
    
    
        $rec = $stmt -> fetch(PDO::FETCH_ASSOC);
        
        
        
    
            if(!empty($rec) === true) {
                print "<br>";
                print "すでに使われているメールです。<br><br>";
                print "<form>";
                print "<input type='button' onclick='history.back()' value='戻る'>";
                print "</form>";
            } else {
                $sql = "INSERT INTO member(member_id, member_name, member_pass, member_mail, member_icon, group_id) VALUES(null,?,?,?,0,1)";
                $stmt = $dbh -> prepare($sql);
                $pass = md5($pass);
                $data[] = $name;
                $data[] = $pass;
                $data[] = $mail;
                //$data[] = $img["name"];
                $stmt -> execute($data);
 
            //move_uploaded_file($img["tmp_name"],"./img/".$img["name"]); 
            print "<br>";
            print "登録しました。<br><br>";
            print "<form>";
            print "<a href='./login.php'>ログイン画面へ</a>";
            print "</form>";
    }
    $dbh = null;
    }
    ?>
</body>
</html>
