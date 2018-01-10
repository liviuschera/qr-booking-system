<?php if (!$page_title) {
    $page_title = "Admin Area";
} ?>

<!DOCTYPE HTML>

<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="<?php echo url('/css/admin.css'); ?>" >
    <link rel="stylesheet" href="<?php echo url('/css/forms.css'); ?>" >
    <title>CMS - <?php echo h($page_title); ?></title>
</head>

<body>
    <div class="wrapper">
        <div class="info">
            <span class="welcome">G'day <span class="lead pad_left_7px"><?php echo $_SESSION['username']; ?></span>!</span>
        </div>
        <header class="logonav">
            <h3 class="logo"><a href="<?php echo url('index.php'); ?>" title="home"> QR Scaner </a></h3>
<!--            <h1 class="pageHeader">Admin Area</h1>-->
            <?php include SHARED_PATH . '/admin_navigation.php'; ?>
        </header>
