<?php session_start();?>
<?php
$date=1;
$pdo=new PDO('mysql:host=mysql205.phy.lolipop.lan;
          dbname=LAA1290579-system4ver2;charset=utf8',
    'LAA1290579',
    'IZUken0626');
$da=$_REQUEST['date'];
$member_id=$_REQUEST['member_id'];
$comment=$_REQUEST['comment'];
$stmt =$pdo->prepare('select "date" from calender WHERE member_id=:member_id and date=:date ');
$stmt->bindValue(':member_id',$member_id);
$stmt->bindValue(':date',$da);
$res=$stmt->execute();
if ($res=true) {
    foreach ($stmt as $row) {
        $date = $row['date'];
    }
}
if($da==$date){
    $sql=$pdo->prepare('UPDATE `calender` SET comment=:comment WHERE member_id=:member_id AND date=:date');
    $sql->execute(array(':comment'=>$_REQUEST['comment'],':member_id'=>$_REQUEST['member_id'],':da'=>$_REQUEST['date']));
}else{
    $sql=$pdo->prepare('INSERT INTO calender VALUES (null,?,?,?)');
    $sql->execute([$_REQUEST['member_id'],$_REQUEST['date'],$_REQUEST['comment']]);
}
$pdo= null;
header('location:https://aso2001031.perma.jp/System4_ver2.0/home.php');//リダイレクト処理
exit();
?>
