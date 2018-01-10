<?php require_once '../../../private/initialize.php'; ?>

<?php include SHARED_PATH . '/admin_header.php'; ?>

<?php
require_login();
check_if_timeout();
?>

<?php // $result = find_all_users();?>


<section class="content">
    <?php
    $all_participants = find_all_participants();
    $counted_participants = mysqli_num_rows($all_participants);
    $total_super_admins = count_users_by_privilege(3);
    $total_admin = count_users_by_privilege(2);
    $normal_users = count_users_by_privilege(1);
    ?>

    <!-- Card section -->

    <div class="card orange_card">
        Total Participants: <?php echo $counted_participants; ?>
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
        <span class="add_link menuItem bigbold text_shadow"><a href="<?php echo url('admin/participants/new_participant.php'); ?>"
                                                               title="Add new participant">Add New Participant</a></span>
        <form class="search" action="<?php echo url('admin/participants/index.php'); ?>" method="post">
            <!-- <h1 class="formTitle">Search</h1> -->
            <input type="text" name="search" value="" placeholder="Search participants">
        </form>
    <?php } ?>
    <?php
    if (is_post_request()) {
        $search = $_POST['search'];
        $result = search_participant($search);
        display_participants_table($result);
    }
    ?>
    

</section>


<?php include SHARED_PATH . '/admin_footer.php'; ?>
