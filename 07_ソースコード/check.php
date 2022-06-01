<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>

<?php
try {
    $pdo = new PDO('mysql:host=mysql203.phy.lolipop.lan;dbname=LAA1290633-system4ver2; charset=utf8', 'LAA1290633', 'System4');
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    if (isset($_SESSION['member'])) {
        $id = $_SESSION['member']['id'];
        $sql = $pdo->prepare('select * from member where member_id!=? and member_mail=?');
        $sql->execute([$id, $_REQUEST['mail']]);
    } else {
        $sql = $pdo->prepare('select * from member where member_mail=?');
        $sql->execute([$_REQUEST['mail']]);
    }

    if (empty($sql->fetchAll())) {
        if (isset($_SESSION['member'])) {
            $sql = $pdo->prepare('update member set member_name=?, member_mail=?, member_pass=?, where member_id=?');
            $sql->execute([$_REQUEST['name'], $_REQUEST['mail'], $_REQUEST['pass'], $id]);
            $_SESSION['member'] = ['id' => $id, 'name' => $_REQUEST['name'], 'mail' => $_REQUEST['mail'], 'pass' => $_REQUEST['pass']];
            echo 'お客様情報を更新しました。';
        } else {
            //        insert into member values(null,'System4','2001007@s.asojuku.ac.jp',08085579573,'pass','hukuoka');
            $sql=$pdo->prepare('insert into member values(null,?,?,?,0,1)');
            $sql->execute([$_REQUEST['name'],$_REQUEST['pass'],$_REQUEST['mail']]);
            echo '<div class="register-info">';
            echo '<p>お客様情報を登録しました。</p>';
            echo '<a href="login.php">ログイン画面に進む</a>';
            echo '</div>';

        }

    } else {
        echo '<div class="register-info">';
        echo '<p>メールアドレスがすでに使用されていますので、変更してください！</p>';
        echo '<a href="entry.php">戻る</a>';
        echo '</div>';
    }

    $pdo = null;
}catch(PDOException $e){
    echo $e->getMessage();
}
?>

</body>
</html>
