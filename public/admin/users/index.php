<?php require_once '../../../private/initialize.php'; ?>

<?php include SHARED_PATH . '/admin_header.php'; ?>

<?php
require_login();
check_if_timeout();
?>

<?php // $result = find_all_users();?>


<section class="content">
    <?php
    $all_users = find_all_users();
    $counted_users = mysqli_num_rows($all_users);
    $total_super_admins = count_users_by_privilege(3);
    $total_admin = count_users_by_privilege(2);
    $normal_users = count_users_by_privilege(1);
    ?>

    <!-- Card section -->

    <div class="card orange_card">
        Total Users: <?php echo $counted_users; ?>
        <hr>
        <span class="small">Tracked from database</span>
    </div>

    <div class="card green_card">
        Total Admins: <?php echo $total_admin; ?>
        <hr>
        <span class="small">Tracked from database</span>
    </div>

    <div class="card teal_card">
        Total Super Admins: <?php echo $total_super_admins; ?>
        <hr>
        <span class="small">Tracked from database</span>
    </div>

    <div class="card red_card">
        Total Normal Users: <?php echo $normal_users; ?>
        <hr>
        <span class="small">Tracked from database</span>
    </div>

    <!-- End of card section -->

    <?php if (has_priv_level(2)) { ?>
        <span class="add_link menuItem bigbold text_shadow"><a href="<?php echo url('admin/users/new_user.php'); ?>"
                                                               title="Add new user">Add New User</a></span>
        <form class="search" action="<?php echo url('admin/users/index.php'); ?>" method="post">
            <!-- <h1 class="formTitle">Search</h1> -->
            <input type="text" name="search" value="" placeholder="Search users">
        </form>
    <?php } elseif (has_priv_level(1)) {
        $user_result = find_user_by_id($_SESSION[user_id], false);
        display_table($user_result);
} ?>
    <?php
    if (is_post_request()) {
        $search = $_POST['search'];
        $result = search_user($search);
        display_table($result);
    }
    ?>
    <!-- </article> -->

</section>


<?php include SHARED_PATH . '/admin_footer.php'; ?>
