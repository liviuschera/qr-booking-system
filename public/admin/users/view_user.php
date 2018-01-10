<?php require_once '../../../private/initialize.php'; ?>

<?php include SHARED_PATH . '/admin_header.php'; ?>

<?php
    require_login();
    check_if_timeout();
?>

<?php
$id = $_GET['id'] ?? '';
$user = find_user_by_id($id);
var_dump($_SESSION['email_taken']);
unset($_SESSION['email_taken']);
?>

<section class="content">
    <h2 class="spacing">Details for user: <?php echo $user['sname']; ?></h2>
    <article class="view">
        <p><span>First Name: </span><?php echo h($user['fname']); ?></p>
        <p><span>Last Name: </span><?php echo h($user['sname']); ?></p>
        <p><span>Email: </span><?php echo h($user['email']); ?></p>
        <p><span>Privilege Level: </span><?php echo h($user['priv']); ?></p>
        <p><span>Visible User: </span><?php echo h($user['active']); ?></p>
    </article>
</section>

<?php include SHARED_PATH . '/admin_footer.php'; ?>
