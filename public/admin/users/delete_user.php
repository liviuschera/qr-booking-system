<?php require_once '../../../private/initialize.php'; ?>

<?php $page_title = "Delete User"; ?>
<?php include SHARED_PATH . '/admin_header.php'; ?>

<?php
    require_login();
    check_if_timeout();
?>

<?php
if (!isset($_GET['id'])) {
    redirect_to(url('admin/users/index.php'));
}

    $id = $_GET['id'];
    $delete = find_user_by_id($id);

if (is_post_request()) {
    delete_page($id);
    redirect_to(url('admin/users/index.php'));
} else {
    echo "not good";
}

    ?>
    <section class="content">
        <h2>Delete User</h2>
        <article>


        <form class="" action="<?php echo url('admin/users/delete_user.php?id='. h(u($delete['id']))); ?>" method="post">
            <p type="Are you sure you want to delete this user?"><strong><?php echo h($delete['email']); ?></strong></p>
            <button type="submit" name="delete_user" value="Delete User">Delete User</button>
        </form>
        </article>
    </section>

<?php include SHARED_PATH . '/admin_footer.php'; ?>
