<?php session_start();?>
<?php
$pdo=new PDO('mysql:host=mysql205.phy.lolipop.lan;
          dbname=LAA1290579-system4ver2;charset=utf8',
    'LAA1290579',
    'IZUken0626');
$pid = ($_GET['id']);

$sql=$pdo->prepare('delete from post where post_id=?');
$sql->execute([$pid]);
$pdo= null;
header('location:https://aso2001031.perma.jp/System4_ver2.0/post_list');//リダイレクト処理
exit();
?>
