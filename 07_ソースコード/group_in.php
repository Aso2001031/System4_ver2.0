<?php
session_start();


$pdo=new PDO('mysql:host=mysql203.phy.lolipop.lan;
      dbname=LAA1290633-system4ver2;charset=utf8',
    'LAA1290633',
    'System4');
//入室ボタンが押されたら実行
if (isset($_POST['groupsubmit'])) {
    //部屋名とパスワードが一致したらfalse
    $passflg = true;
    //入力した部屋名とDBから取得した部屋名が一致したらfalse
    $roomflg = true;
    $sql = $pdo->prepare('select group_id,group_room,group_pass,group_leader from `group` where group_room = ?');
    $sql->bindValue(1,$_POST['grouproom'],PDO::PARAM_STR);
    //

   

    if ($sql->execute()) {
        $roomflg = false;
    }
    foreach ($sql as $row) {
        if ($row['group_pass'] == $_POST['grouppassword']) {
            
            if ($row['group_leader'] == $_SESSION['member']['id']){
                //新しくgroupセッションを作成する
                $_SESSION['group'] = [
                        'group_id' => $row['group_id'],
                        'leader' => true];
                        
            }else{
                //新しくgroupセッションを作成する
                $_SESSION['group'] = [
                    'group_id' => $row['group_id'],
                    'leader' => false];
                    
            }
            $passflg = false;
            
        }
    }

    

    //部屋名かパスワードのどちらか、または両方が一致しなかった場合
    if ($roomflg or $passflg) {
        echo '部屋名かパスワード、またはその両方が一致しません';
        echo '<a href="group_in.php">戻る</a>';
    }
    //部屋名とパスワードが一致した場合
    if ($roomflg == false and $passflg == false) {
       
        header("location:http://aso2001007.versus.jp/System4_Ver2.0/group_post.php");
        exit();
    }
}
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/style.css">
    <title>group-in</title>
</head>
<body>
<form action="group_in.php" method="post">
<h1>入室</h1>
<div><button type="submit" class="back"><img src="./img/back.png"></button></div>
<h2>部屋名</h2>
<input type="text" name="grouproom" maxlength="4" id="grouproom" placeholder="部屋名を入力"><br>
<h2>パスワード</h2>
<input type="password" name="grouppassword" id="grouppassword" placeholder="パスワードを入力"><br>
<button type="submit" name="groupsubmit">入室</button>
</form>
<a href="group_create.php">グループを作成する</a>
</body>
</html>