<nav class="menu">
	<?php
    $menu_items = sort_menu_items();
    $login = find_content_by_link('login');
    $logout = find_content_by_link('logout');
    // var_dump($_SESSION['user_id']);
    ?>

    <ul>
        <?php while ($menu_item = mysqli_fetch_assoc($menu_items)) {
            ?>
            <?php if ($menu_item['navBarDisplay'] === 'y') {
                ?>

                <li>
                    <a href="#" title="<?php echo $menu_item['title']; ?>">
                        <?php
                        echo h($menu_item['navBarText']);
                        ?>
                    </a>
                </li>
                <?php
} // end of while menu_item?>
            <?php
} // end of if navbardisplay?>
        <li class="">
            <?php if (isset($_SESSION['user_id'])) { ?>

                <a href="<?php echo url('admin/index.php'); ?>">Admin Area </a>

                <?php } else { ?>

                <a href="<?php echo url('login.php'); ?>">Admin Area</a>

            <?php } ?>
        </li>
    </ul>
    <?php mysqli_free_result($menu_items); ?>
</nav>
