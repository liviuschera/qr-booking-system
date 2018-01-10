<?php require_once '../../../private/initialize.php'; ?>

<?php //include SHARED_PATH . '/admin_header.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ticket Generator</title>
    <link rel="stylesheet" href="<?php echo url('/css/styles.css'); ?>" >
</head>
<body>


<?php
// require_login();
// check_if_timeout();
?>

<?php
$id = $_GET['id'] ?? '';
$result = find_participant_by_id($id);

$class_time = convert_time($result['class_time']);
?>
<script src="<?php echo url('js/qrcode.min.js'); ?>"></script>

<!--<section class="content">-->
<div class="container">
    <a href="<?php echo url('admin/index.php'); ?>">Go Back</a>
    <div id="ticket">
        <div class="info">
            <p><?php echo "{$result['fname']} {$result['sname']}"; ?></p><br>
            <p><?php echo "{$result['class_name']} | {$result['class_duration']} min"; ?></p>
            <p><?php echo "{$result['class_date']} | {$class_time}"; ?></p>
        </div>
        <div id="qrcode"></div>
    </div>
</div>

<script type="text/javascript">
    new QRCode(document.getElementById("qrcode"), "<?php echo "{$result['fname']}|{$result['sname']}|{$result['class_name']}|{$result['class_duration']}|{$result['class_date']}|{$class_time}"; ?>");
</script>
<!--</section>-->

<?php include SHARED_PATH . '/admin_footer.php'; ?>
