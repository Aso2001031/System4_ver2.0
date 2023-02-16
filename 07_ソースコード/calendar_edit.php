<?php session_start();?>
<?php
$date=$_REQUEST['date'];
$da=$_REQUEST['da'];
$month=$_REQUEST['month'];
$weekjp=$_REQUEST['weekjp'];
$member_id=$_REQUEST['member_id'];
$text=$_REQUEST['text'];
?>
<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <title>Walk Record - CALENDAR EDIT</title>
    <link rel="stylesheet" href="css/menu.css">
    <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body>

<div class="header">
    <a class="banner_title">Walk Record</a>
</div>
<div class="calender_edit_area_1">
    <a class="calender_edit_title">カレンダー情報</a>
    <div class="calender_edit_area_2">
        <a class="calender_month"><?php echo $month."/".$da?></a><br><a class="calender_week"><?php echo $weekjp?></a>
        <form method="post" name="contactform" action="calender_insert.php">
            <input hidden type="int"  value="<?php echo $member_id; ?>" name="member_id">
            <input hidden  type="text" value="<?php echo $date; ?>" name="date">
            <input type="text" class="calender_edit_box" value="<?php echo $text; ?>" name="comment" onclick="return confirm_test()" /><br>
            <button type="button" onclick="history.back();" class="calender_edit_button">戻る</button>
            <button type="submit" class="calender_edit_button" onclick="check()">変更</button>
        </form>
    </div>
</div>
</body>
</html>
<script type="text/javascript">
    function check() {
        if (<?php empty($text) ?>==true) { //tureの場合
            document.location = "insert.html"; //リンク先を呼び出す
        } else { //falseの場合
            alert("送信できません"); //アラートを表示する
        }
    }
</script>