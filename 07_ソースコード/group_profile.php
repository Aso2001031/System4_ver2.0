<?php
session_start();

$pdo=new PDO('mysql:host=mysql203.phy.lolipop.lan;
      dbname=LAA1290633-system4ver2;charset=utf8',
    'LAA1290633',
    'System4');
//グループ情報取得
if (!isset($_POST['edit'])){
$sql=$pdo->prepare('SELECT * FROM `group` where group_id = ?');
$sql->bindValue(1,$_SESSION['member']['group_id'],PDO::PARAM_INT);
$sql->execute();
$_SESSION['member']['group']=$sql;
    foreach ($_SESSION['member']['group'] as $row) {
        $group_id = $row['group_id'];
        $group_name = $row['group_name'];
        $group_pass = $row['group_pass'];
        $group_room = $row['group_room'];
        $jgroup_name = json_encode($group_name);
        $jgroup_pass = json_encode($group_pass);
    }
}
//更新処理
if (isset($_POST['edit'])) {
    $sql = $pdo->prepare('UPDATE `group` SET group_name=?, group_pass=? WHERE group_id=?');
    $sql->execute([
        $_POST['groupname'],$_POST['password'],$_POST['groupid']]);

    /*sessionに再登録*/
    $_SESSION['member'] = [
        'group_name' => $_POST['groupname'],
        'group_pass' => $_POST['password']];
    header('location:http://aso2001007.versus.jp/System4_Ver2.0/group_profile.php',true,301);
    exit();
}
//一般メンバー追放処理
if (isset($_POST['kick'])) {
    $kick = $pdo->prepare('update member set group_id = 1 where member_id = ?');
    $kick->bindValue(1, $_POST['member_id'], PDO::PARAM_INT);
    $kick->execute();
}
if ($_SESSION['member']['leader']){
    //グループ削除
    if (isset($_POST['delete'])){
        $sql=$pdo->query('update member set group_id = 1 where group_id = ?');
        $sql->bindValue(1,$_SESSION['member']['group_id'],PDO::PARAM_INT);
        $sql->execute();
        $_SESSION['member'] = [
            'group_id' => 1];
    }
}

echo '<a href="http://aso2001007.versus.jp/System4_Ver2.0/menu.php">メニューへ戻る</a>';