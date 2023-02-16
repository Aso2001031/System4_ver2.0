<?php session_start();?>
<?php
$id=$name=$mail=$password=$icon=$gid='';
if (!empty($_SESSION['member'])) {
    $id=$_SESSION['member']['id'];
    $name=$_SESSION['member']['name'];
    $mail=$_SESSION['member']['mail'];
    $password=$_SESSION['member']['pass'];
    $icon=$_SESSION['member']['icon'];
}else{
    header('location:https://aso2001031.perma.jp/System4_ver2.0/login.php');//リダイレクト処理
    exit();
}
?>
<?php
$pdo=new PDO('mysql:host=mysql205.phy.lolipop.lan;
          dbname=LAA1290579-system4ver2;charset=utf8',
    'LAA1290579',
    'IZUken0626');
error_reporting(0);
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

    $_SESSION['member']['group_name'] = $_POST['groupname'];
    $_SESSION['member']['group_pass'] = $_POST['password'];
    header('location:https://aso2001031.perma.jp/System4_ver2.0/group-profile.php',true,301);
    exit();
}
//一般メンバー追放処理
if (isset($_POST['kick'])) {
    $sql = $pdo -> prepare('UPDATE post SET group_id = 1 where member_id = ? ');
    $sql->bindValue(1, $_POST['member_id'], PDO::PARAM_INT);
    $sql->execute();
    $kick = $pdo->prepare('update member set group_id = 1 where member_id = ?');
    $kick->bindValue(1, $_POST['member_id'], PDO::PARAM_INT);
    $kick->execute();
}
if ($_SESSION['member']['leader']){
    //グループ削除
    if (isset($_POST['delete'])){
        $sql = $pdo ->prepare('UPDATE post SET group_id = 1 where group_id = ? ');
        $sql->bindValue(1, $_SESSION['member']['group_id'], PDO::PARAM_INT);
        $sql->execute();
        $sql=$pdo->prepare('update member set group_id = 1 where group_id = ?');
        $sql->bindValue(1,$_SESSION['member']['group_id'],PDO::PARAM_INT);
        $sql->execute();
        $group_delete = $pdo->prepare('delete from `group` where group_id = ?');
        $group_delete->bindValue(1,$_SESSION['member']['group_id'],PDO::PARAM_INT);
        $group_delete->execute();
        $_SESSION['member']['group_id'] = 1;
        http_response_code(301);
        header("location:https://aso2001031.perma.jp/System4_ver2.0/group-create.php");
        exit();
    }
}
if ($_SESSION['member']['leader'] == false){
    //退会
    if (isset($_POST['withdrawal'])) {
        $sql = $pdo -> prepare('UPDATE post SET group_id = 1 where member_id = ? ');
        $sql->bindValue(1, $_SESSION['member']['id'], PDO::PARAM_INT);
        $sql->execute();
        $sql = $pdo->prepare('update member set group_id = 1 where member_id = ?');
        $sql->bindValue(1, $_SESSION['member']['id'], PDO::PARAM_INT);
        $sql->execute();
        $_SESSION['member']['group_id'] = 1;
        http_response_code(301);
        header("location:https://aso2001031.perma.jp/System4_ver2.0/group-in.php");
        exit();
    }
}
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/group_profile.css">
    <title>Walk Record - GROUP PROFILE</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body style="max-width: 550px; margin:auto; height: 100%; box-shadow: 0 0 8px gray;">
<div class="header">
    <a class="banner_title">Walk Record</a>
</div>
<form action="group_post_list.php">
    <a class="title">グループ情報</a>
    <button type="submit"　class="return">←</button>
</form>
<?php
if ($_SESSION['member']['leader']){
    echo '<form action="group-profile.php" method="post">';
    echo '<button type="submit"onclick="reset()">戻す</button>';
    echo '<button type="submit" class="edit" name="edit" id="edit">変更する</button>';
}
echo '<input type="text" name="groupid" value="',$group_id,'" hidden>';
?>
<div class="group_profile_area">
    <!--<button type="submit" class="backright" onclick="history.back()">↩</button>-->
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
    <a class="area_text">グループ名</a><br>
    <?php
    if ($_SESSION['member']['leader']){
        echo '<input type="checkbox" id="gname" name="check_name" onclick="showEdit()">';
        echo '<label for="gname" onclick="groupnameChange()">';
    }
    ?>
    <input type="text" id="groupname" name="groupname" value="<?= $group_name ?>" readonly><br>
    <?php
    if ($_SESSION['member']['leader']){
        echo '</label>';
    }
    ?>
    <a class="area_text">パスワード</a><br>
    <?php
    if ($_SESSION['member']['leader']){
        echo '<input type="checkbox" id="pass" name="check_pass" onclick="showEdit()">';
        echo '<label for="pass" onclick="passChange()">';
    }
    ?>
    <input type="text" id="password" name="password" value="<?= $group_pass ?>" readonly><br>
    <?php
    if ($_SESSION['member']['leader']){
        echo '</label><br>';
    }
    ?>
    <a class="area_text">グループコード</a><br>
    <a class="code"><?= $group_room ?></a><br>
    <?php
    if ($_SESSION['member']['leader']){
        echo '</form>';
    }
    ?>
    <a class="area_text">メンバー一覧</a><br>
    <?php
    $imgurl ='location:https://aso2001031.perma.jp/System4_ver2.0/';
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
    echo '</li>';
    //一般メンバー情報出力
    $num = 1;
    foreach ($show as $row) {
        if ($leaderid != $row['member_id']){
            echo '<li>';
            if ($_SESSION['member']['leader']){
                echo '<form action="group-profile.php" name="kickForm" method="post">';
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
            echo '</li>';
        }
    }
    ?>
    <?php
    if ($_SESSION['member']['leader']){
        echo '<form action="group-profile.php" name="deleteForm" method="post">';
        echo '<button type="submit" class="withdrawal" name="delete" onclick="groupDelete()">グループを削除する</button></form>';
    }
    if ($_SESSION['member']['leader'] == false){
        echo '<form action="group-profile.php" name="withdrawalForm" method="post">';
        echo '<button type="submit" class="withdrawal" name="withdrawal" onclick="groupWithdrawal()">退会する</button></form>';
    }
    ?>
</div>
<div class="footer"></div>
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
        }else{
            let kickfalse = document.forms.kickForm;
            kickfalse.kick.disabled = true;
            location.reload();
        }
    }
    function groupWithdrawal(){
        if(window.confirm('本当に退会しますか？')){
            location.href="./group-profile.php";
        }else{
            var withdrawalfalse = document.forms.withdrawalForm;
            withdrawalfalse.withdrawal.disabled = true;
            location.reload();
        }
    }
    function groupDelete(){
        if(window.confirm('本当に削除しますか？')){
            location.href="./group-profile.php";
        }else{
            var deletefalse = document.forms.deleteForm;
            deletefalse.delete.disabled = true;
            location.reload();
        }
    }
</script>
</html>
