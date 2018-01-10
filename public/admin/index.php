<?php require_once '../../private/initialize.php'; ?>

<?php include SHARED_PATH . '/admin_header.php'; ?>

<?php
    require_login();
    check_if_timeout();
?>


        <section class="content">
                <?php $result = find_all_users(); ?>
             <h2 class="">Placeholder</h2>


        </section>



<?php include SHARED_PATH . '/admin_footer.php'; ?>
