<?php require_once '../../../private/initialize.php'; ?>

<?php $page_title = "Delete User"; ?>
<?php include SHARED_PATH . '/admin_header.php'; ?>

<?php
    require_login();
    check_if_timeout();
?>

<?php
if (!isset($_GET['id'])) {
    redirect_to(url('admin/participants/index.php'));
}

    $id = $_GET['id'];
    $delete = find_participant_by_id($id);

if (is_post_request()) {
    delete_participant($id);
    redirect_to(url('admin/participants/index.php'));
} else {
    echo "not good";
}

    ?>
    <section class="content">
        <h2>Delete User</h2>
        <article>


        <form class="" action="<?php echo url('admin/participants/delete_participant.php?id='. h(u($delete['part_id']))); ?>" method="post">
            <p type="Are you sure you want to delete this participant?"><strong><?php echo h($delete['email']); ?></strong></p>
            <button type="submit" name="delete_participant" value="Delete User">Delete User</button>
        </form>
        </article>
    </section>

<?php include SHARED_PATH . '/admin_footer.php'; ?>
