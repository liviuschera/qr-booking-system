<?php require_once '../../../private/initialize.php'; ?>

<?php include SHARED_PATH . '/admin_header.php'; ?>

<?php
    require_login();
    check_if_timeout();
?>

<?php
$id = $_GET['id'] ?? '';
$participant = find_participant_by_id($id);
// var_dump($_SESSION['email_taken']);
// unset($_SESSION['email_taken']);
?>

<section class="content">
    <h2 class="spacing">Details for participant: <?php echo $participant['sname']; ?></h2>
    <article class="view">
        <p><span>First Name: </span><?php echo h($participant['fname']); ?></p>
        <p><span>Last Name: </span><?php echo h($participant['sname']); ?></p>
        <p><span>Email: </span><?php echo h($participant['email']); ?></p>
        <p><span>Class Name: </span><?php echo h($participant['class_name']); ?></p>
        <p><span>Class Duration: </span><?php echo h($participant['class_duration']); ?></p>
        <p><span>Class Date: </span><?php echo h($participant['class_date']); ?></p>
        <p><span>Class Time: </span><?php echo h(convert_time($participant['class_time'])); ?></p>
    </article>
</section>

<?php include SHARED_PATH . '/admin_footer.php'; ?>
