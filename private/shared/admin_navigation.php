<nav class="menu">
    <ul>
        <li class="menu_item"><a href="<?php echo url('admin/index.php'); ?>">Admin Home</a></li>
        <li class="menu_item"><a href="<?php echo url('admin/users/index.php'); ?>">Users Area</a></li>
        <?php if (has_priv_level(2)) { ?>
        <li class="menu_item"><a href="<?php echo url('admin/participants/index.php'); ?>">Participants Area</a></li>
        <?php } ?>
        <li class="menu_item"><a href="<?php echo url('logout.php'); ?>">Log Out</a></li>
    </ul>
</nav>
