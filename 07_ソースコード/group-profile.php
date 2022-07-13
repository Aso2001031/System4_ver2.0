<?php
session_start();

$pdo=new PDO('mysql:host=mysql203.phy.lolipop.lan;
      dbname=LAA1290590-system4ver2;charset=utf8',
    'LAA1290590',
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
    header('location:http://aso2001027.angry.jp/system4_ver2/group-profile.php',true,301);
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
if ($_SESSION['member']['leader'] == false){
    //退会
    if (isset($_POST['withdrawal'])) {
        $sql = $pdo->query('update `group` set group_id = 1 where member_id = ?');
        $sql->bindValue(1, $_SESSION['member']['id'], PDO::PARAM_INT);
        $sql->execute();
        $_SESSION['member'] = [
            'group_id' => 1];
    }
}
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/style.css">
    <title>group-profile</title>
</head>
<body>
<h1>グループ情報</h1>
<?php
if ($_SESSION['member']['leader']){
    echo '<form action="group-profile.php" method="post">';
}
echo '<input type="text" name="groupid" value="',$group_id,'" hidden>';
?>
<button type="submit" class="backright"><img src="./img/back.png"></button>
<?php
$sql=$pdo->prepare('SELECT * FROM `group` where group_id = ?');
$sql->bindValue(1,$_SESSION['member']['group_id'],PDO::PARAM_INT);
$sql->execute();
/*if ($_SESSION['member']['leader']){
    echo '<input type="checkbox" id="icon">';
    echo '<label for="icon" name="check_icon">';
}
?>
<!-- 画像の表示処理 -->
<?php
if ($_SESSION['member']['leader']){
    echo '</label>';
}
*/
?>
<h2>グループ名</h2>
<?php
if ($_SESSION['member']['leader']){
    echo '<input type="checkbox" id="gname" name="check_name" onclick="showEdit()">';
    echo '<label for="gname" onclick="groupnameChange()">';
}
?>
<input type="text" id="groupname" name="groupname" value="<?= $group_name ?>" readonly>
<?php
if ($_SESSION['member']['leader']){
    echo '</label>';
}
?>
<h2>パスワード</h2>
<?php
if ($_SESSION['member']['leader']){
    echo '<input type="checkbox" id="pass" name="check_pass" onclick="showEdit()">';
    echo '<label for="pass" onclick="passChange()">';
}
?>
<input type="text" id="password" name="password" value="<?= $group_pass ?>" readonly>
<?php
if ($_SESSION['member']['leader']){
    echo '</label><br>';
}
?>
<h2>ルーム名</h2>
<?= $group_room ?><br>
<?php
if ($_SESSION['member']['leader']){
    echo '<button type="submit"onclick="reset()">戻す</button>';
    echo '<button type="submit" class="edit" name="edit" id="edit">変更する</button>';
    echo '</form>';
}
?>
<h2>メンバー一覧</h2>
<?php
$imgurl ='http://aso2001007.versus.jp/System4_Ver2.0/';
//グループ管理者情報取得
$sql = $pdo->prepare('SELECT * FROM member join `group` on member.member_id = `group`.group_leader where member.group_id = ? and `group`.group_leader = ? order by member.member_id asc');
$sql->bindValue(1, $_SESSION['member']['group_id'], PDO::PARAM_INT);
$sql->bindValue(2,$_SESSION['member']['id'],PDO::PARAM_INT);
$sql->execute();
$leadershow = $sql;
//一般メンバー情報取得
$sql = $pdo->prepare('SELECT * FROM member where group_id = ? order by member_id asc');
$sql->bindValue(1, $_SESSION['member']['group_id'], PDO::PARAM_INT);
$sql->execute();
$show = $sql;
$leaderid = '';
echo '<ul>';
echo '<li>';
//グループ管理者情報出力
foreach ($leadershow as $lrow){
    $leaderid = $lrow['member_id'];
    echo '<img src="',$imgurl,$lrow['member_icon'],'">';
    if ($_SESSION['member']['leader']){
        echo $lrow['member_id'];
    }
    echo $lrow['member_name'];
}
echo '</li><br>';
//一般メンバー情報出力
$num = 1;
foreach ($show as $row) {
    if ($leaderid != $row['member_id']){
        echo '<li>';
        if ($_SESSION['member']['leader']){
            echo '<form action="group-profile.php" method="post">';
        }
        echo '<img src="',$imgurl,$row['member_icon'],'">';
        if ($_SESSION['member']['leader']){
            echo $row['member_id'];
        }
        echo '<input type="text" name="member_id" value="',$row['member_id'],'"hidden>';
        echo $row['member_name'];
        if ($_SESSION['member']['leader']){
            echo '<button type="submit" class="" onclick="memberKick()" name="kick">追放</button></form>';
            $num++;
        }
        echo '</li><br>';
    }
}
echo '</ul>';
?>
<?php
if ($_SESSION['member']['leader']){
    echo '<form action="group-profile.php" method="post">';
    echo '<button type="submit" class="withdrawal" name="delete" onclick="groupDelete()">グループを削除する</button></form>';
}
if ($_SESSION['member']['leader'] == false){
    echo '<form action="group-profile.php" method="post">';
    echo '<button type="submit" class="withdrawal" name="withdrawal" onclick="groupWithdrawal()">退会する</button></form>';
}
?>
</body>
<script>
    function showEdit(){
        var check = document.getElementById('gname');
        var check2 = document.getElementById('pass');
        if (check.checked == true || check2.checked == true){
            document.getElementById('edit').readOnly = false;
        }
    }
    function groupnameChange(){
        var check = document.getElementById('gname');
        if (check.checked == true) {
            document.getElementById('groupname').readOnly = false;
            document.getElementById('gname').disabled = true;
        }
    }
    //パスワード変更
    function passChange(){
        var check = document.getElementById('pass');
        if (check.checked == true)
            var newpass = prompt('新しいパスワードを入力してください','');
            if (newpass.length >= 1 && newpass.length <= 15){
                var newpass2 = prompt('もう一度入力してください','');
                if (newpass == newpass2){
                    document.getElementById("password").value = newpass;
                    document.getElementById('pass').disabled = true;
                }else{
                    alert("新しいパスワードが一致しませんでした");
                }
            }else{
                alert("文字数は1文字以上15文字以下にしてください");
        }
    }
    //
    function reset(){
        var beforegroup_name =JSON.parse('<?php echo $jgroup_name;?>');
        var beforepassword =JSON.parse('<?php echo $jgroup_pass;?>');
        document.getElementById('groupname').value = beforegroup_name;
        document.getElementById('password').value = beforepassword;
        location.href="./group-profile.php";
    }
    function memberKick(){
        if (window.confirm('本当に追放しますか？')){
            location.href="./group-profile.php";
        }
    }
    function groupWithdrawal(){
        if(window.confirm('本当に退会しますか？')){
            location.href="./group-profile.php";
        }
    }
    function groupDelete(){
        if(window.confirm('本当に削除しますか？')){
            location.href="./group-profile.php";
        }
    }

</script>
</html>
