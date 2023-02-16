<?php
session_start();
?>
<?php
$pdo=new PDO('mysql:host=mysql205.phy.lolipop.lan;
          dbname=LAA1290579-system4ver2;charset=utf8',
    'LAA1290579',
    'IZUken0626');
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
        header("location:https://aso2001031.perma.jp/System4_ver2.0/group_post_list.php");
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
            $changegroup_id = $pdo->prepare('update post set group_id = ? where member_id = ?');
            $changegroup_id->bindValue(1,$_SESSION['member']['group_id'],PDO::PARAM_INT);
            $changegroup_id->bindValue(2,$_SESSION['member']['id'],PDO::PARAM_INT);
            $changegroup_id->execute();
            $changegroup_id = $pdo->prepare('update member set group_id = ? where member_id = ?');
            $changegroup_id->bindValue(1,$_SESSION['member']['group_id'],PDO::PARAM_INT);
            $changegroup_id->bindValue(2,$_SESSION['member']['id'],PDO::PARAM_INT);
            $changegroup_id->execute();
            http_response_code(301);
            header("location:https://aso2001031.perma.jp/System4_ver2.0/group_post_list.php");
            exit();
        }
    }
}
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/group_in.css">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Walk Record - ENTER THE ROOM</title>
</head>
<body style="max-width: 550px; margin: auto; height: 770px; box-shadow: 0 0 8px gray;">
<div class="header">
    <a class="banner_title">Walk Record</a>
</div>
<form action="map.php">
    <a class="title">入室</a>
    <button type="submit"　class="return">←</button>
</form>
<div class="group_in_area">
    <form action="group-in.php" method="post">
        <a class="group_in_text">グループコード</a><br>
        <input type="text" name="grouproom" maxlength="4" id="grouproom" placeholder="グループコードを入力"><br>
        <a class="group_in_text">パスワード</a><br>
        <input type="password" name="grouppassword" id="grouppassword" placeholder="パスワードを入力"><br>
        <button type="submit" name="groupsubmit" class="enter_the_room">入室</button>
    </form>
    <a href="group-create.php" class="group_create">グループを作成する</a>
</div>
</body>
</html>