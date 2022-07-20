<?php
session_start();
?>
<?php
$pdo=new PDO('mysql:host=mysql203.phy.lolipop.lan;
      dbname=LAA1290590-system4ver2;charset=utf8',
    'LAA1290590',
    'System4');
$pageflg = true;
//リーダーは入室できない
$check=$pdo->prepare('select group_id,group_leader from `group`');
$check->execute();
foreach ($check as $leadercheck){
    if ($leadercheck['group_leader'] != 1 and $leadercheck['group_leader'] == $_SESSION['member']['id']){
        $pageflg = false;
        $_SESSION['member']['leader'] = true;
        $_SESSION['member']['group_id'] = $leadercheck['group_id'];
        http_response_code(301);
        header("location:http://aso2001027.angry.jp/system4_ver2/group_post.php");
        exit();
    }
}
if ($pageflg){
    //入室ボタンが押されたら実行
    if (isset($_POST['groupsubmit'])) {
        //グループコードとパスワードが一致したらfalse
        $passflg = true;
        //入力したグループコードとDBから取得したグループコードが一致したらfalse
        $roomflg = true;
        $group_id = '';
        $leader = '';
        $sql = $pdo->prepare('select group_id,group_room,group_pass,group_leader from `group` where group_room = ?');
        $sql->bindValue(1,$_POST['grouproom'],PDO::PARAM_STR);
        if ($sql->execute()) {
            $roomflg = false;
        }
        foreach ($sql as $row) {
            if ($row['group_pass'] == $_POST['grouppassword']) {
                if ($row['group_leader'] == $_SESSION['member']['id']){
                        $group_id = $row['group_id'];
                        $leader = true;
                }else{
                        $group_id = $row['group_id'];
                        $leader = false;
                }
                $passflg = false;
                //break;
            }
        }
        $_SESSION['member']['group_id'] = $group_id;
        $_SESSION['member']['leader'] = $leader;
        //グループコードかパスワードのどちらか、または両方が一致しなかった場合
        if ($roomflg or $passflg) {
            echo '部屋名かパスワード、またはその両方が一致しません';
            echo '<a href="group-in.php">戻る</a>';
        }
        //グループコードとパスワードが一致した場合
        if ($roomflg == false and $passflg == false) {
            $changegroup_id = $pdo->prepare('update member set group_id = ? where member_id = ?');
            $changegroup_id->bindValue(1,$_SESSION['member']['group_id'],PDO::PARAM_INT);
            $changegroup_id->bindValue(2,$_SESSION['member']['id'],PDO::PARAM_INT);
            $changegroup_id->execute();
            http_response_code(301);
            header("location:http://aso2001027.angry.jp/system4_ver2/group_post.php");
            exit();
        }
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
<div class="header">
    <a class="banner_title">お散歩</a>
    <a class="member_icon" type="image" src="./<?php $icon?>" name="member_icon"></a>
</div>
<form action="group-in.php" method="post">
<h1>入室</h1>
<div><button type="submit" class="back" onclick="history.back()">↩</button>
    <?php
    //echo '<a href="' . $_SERVER['HTTP_REFERER'] . '">↩</a>';
    ?>
</div>
<h2>グループコード</h2>
<input type="text" name="grouproom" maxlength="4" id="grouproom" placeholder="グループコードを入力"><br>
<h2>パスワード</h2>
<input type="password" name="grouppassword" id="grouppassword" placeholder="パスワードを入力"><br>
<button type="submit" name="groupsubmit">入室</button>
</form>
<a href="group-create.php">グループを作成する</a>
</body>
</html>
