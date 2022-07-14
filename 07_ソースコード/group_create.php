<?php
session_start();

$id=$name=$mail=$password=$icon='';

if(!empty($_SESSION['member'])){
    $id=$_SESSION['member']['id'];
    $name=$_SESSION['member']['name'];
    $mail=$_SESSION['member']['mail'];
    $password=$_SESSION['member']['pass'];
    $icon=$_SESSION['member']['icon'];
}



$pdo = new PDO('mysql:dbname=LAA1290633-system4ver2; host=mysql203.phy.lolipop.lan',
        'LAA1290633','System4');

//作成ボタンが押された場合実行
if (isset($_POST['groupcreatesubmit'])){
    $sql=$pdo->prepare('select group_room from `group` order by group_id asc');
    $sql->execute();
    $flg = false;
    $roomflg = true;
    //自動生成した部屋名とDBにある部屋名が一致しなかったら抜ける
    while ($roomflg) {
        $flg = false;
        //自動生成した部屋名とDBにある部屋名が一致したら新しく部屋名を生成して比較しなおす
        foreach ($sql as $row) {
            if ($row['group_room'] == $_POST['grouproom']) {
                echo '<script>', 'groupRoomCreate();', '</script>';
                $flg = true;
            }
        }
        if ($flg == false) {
            $roomflg = false;
        }
    }



    //作成したグループをDBに追加する
    $sql=$pdo->prepare('insert into `group`(group_id,group_name,group_pass,group_image,group_room,group_leader) values(null,?,?,null,?,?)');
    $sql->bindValue(1,$_POST['groupname'],PDO::PARAM_STR);
    $sql->bindValue(2,$_POST['grouppassword'],PDO::PARAM_STR);
    $sql->bindValue(3,$_POST['grouproom'],PDO::PARAM_STR);
    $sql->bindValue(4,$id,PDO::PARAM_INT);
    $sql->execute();
    $_SESSION['group'] = [
        'group_name' => $_POST['groupname'],
        'group_pass' => $_POST['grouppassword'],
        'room' => $_POST['grouproom'],
        'leader' => true];
    //作成したグループのIDをセッションに保存する
    $sql2=$pdo->prepare('select group_id from `group` where group_room = ?');
    $sql2->bindValue(1,$_POST['grouproom'],PDO::PARAM_STR);
    $sql2->execute();
    foreach ($sql2 as $row2){
        $_SESSION['member']=[
        'id'=>$id,'mail'=>$mail,
        'name'=>$name,'pass'=>$password,'icon'=>$icon,'group_id'=>$row2['group_id']];
    }
    header('location:http://aso2001007.versus.jp/System4_Ver2.0/group_post.php');
    exit();
}
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/style.css">
    <title>group-create</title>
</head>
<body>
<div class="background">
    <div class="head">appname</div>
    <div class="box"></div>
<form action="group_create.php" method="post">
<h1 class="pagename">グループ作成</h1>
    <div><button type="submit" class="backright"><img src="./img/back.png"></button></div>
<h2>グループ名</h2>
<input type="text" class="input" maxlength="15" name="groupname" placeholder="グループ名を入力"><br>
<h2>パスワード</h2>
<input type="password" class="input" maxlength="10" name="grouppassword" id="grouppassword" placeholder="パスワードを入力"><br>
<input type="text" name="grouproom" id="grouproom" value="" hidden>
<button type="submit" class="groupcreatesubmit" name="groupcreatesubmit" id="groupcreatesubmit" onclick="passCheck();groupRoomCreate();">作成</button>
</form>
</div>
<script>
    function groupRoomCreate(){
        //文字列の種類
        var String="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"
        //桁数
        var digits=4
        var result = Array.from(Array(digits)).map(()=>String[Math.floor(Math.random()*String.length)]).join('')
        document.getElementById('grouproom').value = result;
    }
    //パスワード確認
    function passCheck(){
        var newpass = prompt('もう一度パスワードを入力してください','');
        if (newpass.length >= 1){
            if (newpass !== ' ') {
                if (newpass == document.getElementById("grouppassword").value) {
                    location.href = "./group_create.php";
                } else {
                    alert("パスワードが一致しませんでした");
                    document.getElementById("groupcreatesubmit").disabled = true;
                    location.href = "./group_create.php";
                }
            }else{
                alert("パスワードは空白以外にしてください");
                document.getElementById("groupcreatesubmit").disabled = true;
                location.href="./group_create.php";
            }
        }else{
            alert("文字数は1文字以上にしてください");
            document.getElementById("groupcreatesubmit").disabled = true;
            location.href="./group_create.php";
        }
    }
</script>
</body>
</html>