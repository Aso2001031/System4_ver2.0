<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="./css/style.css">
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

<div class="head">
    <a id="vanner">お散歩</a>
    <?php
    echo '<a href="http://aso2001007.versus.jp/System4_Ver2.0/edit.php"><img src="',$icon,'" alt=""></a>';
    ?>
</div>
