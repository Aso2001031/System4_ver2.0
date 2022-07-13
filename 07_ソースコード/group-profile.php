<?php
session_start();
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="script" href="./script/group-profile-script.js">
    <title>group-profile</title>
</head>
<body>
 <!-- バナー表示 -->
 <div class="header">
    <a class="banner_title">お散歩</a>
    <a class="member_icon" type="image" src="./<?php $icon?>" name="member_icon"></a>
</div>
<!-- バナーココまで -->

    
<?php
$pdo=new PDO('mysql:host=mysql203.phy.lolipop.lan;
      dbname=LAA1290633-system4ver2;charset=utf8',
    'LAA1290633',
    'System4');
$sql=$pdo->prepare('select * from group where group_id = 1');
$sql->execute();
?>
<?php
echo $_POST['group_id'];
?>
<h1>グループ情報</h1>
<div class="form">

<button type="submit" class="back"><img src="./img/back.png"></button>
<input type="checkbox">
<label for="icon"><a href=""><img src=""></a></label>
<h2>グループ名</h2>
<label for="groupname"><?= $_POST['group_name'] ?></label>
<h2>パスワード</h2>
<label for="password" name="password" hidden onclick="passChange()" id="password"><?= $_POST['group_pass'] ?></label>
<h2>グループID</h2>
<?= $_POST['group_id']?>
<h2>メンバー一覧</h2>
<button type="submit" class="kick">追放</button><br>
<button type="submit" class="edit">編集</button><br>
<button type="submit" class="withdrawal">退会する</button>
</div>
</body>
<script>
    //パスワード変更
    function passChange(){
        var pass = prompt('現在のパスワードを入力してください','');
        if(pass == document.getElementById('password')) {
            var newpass = prompt('新しいパスワードを入力してください','');
            if (newpass.length >= 1 && newpass.length <= 15){
                var newpass2 = prompt('もう一度入力してください','');
                if (newpass == newpass2){
                    document.getElementById("password").value == newpass;
                }else{
                    console.log("新しいパスワードが一致しませんでした");
                }
            }else{
                console.log("文字数は1文字以上15文字以下にしてください");
            }
        }else{
            console.log("現在のパスワードが間違っています");
        }
    }
</script>
</html>
