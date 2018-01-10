<?php require_once '../../../private/initialize.php'; ?>

<?php $page_title = "Edit User"; ?>
<?php include SHARED_PATH . '/admin_header.php'; ?>

<?php
require_login();
check_if_timeout();
?>

<?php
if (!isset($_GET['id'])) {
	redirect_to(url('/admin/users/index.php'));
}

$id = $_GET['id'];
?>
<?php
if (is_post_request()) {
	$user['id'] = $id;
	$user['fname'] = $_POST['fname'] ?? "";
	$user['sname'] = $_POST['sname'] ?? "";
	$user['email'] = $_POST['email'] ?? "";
	$user['password'] = $_POST['password'] ?? "";
	$user['confirm_password'] = $_POST['confirm_password'] ?? "";
	$user['priv'] = $_POST['priv'] ?? "";


	$email_is_already_taken =  find_user_by_id($id)['email'] !== find_user_by_email($_POST['email'])['email'] && find_user_by_email($_POST['email'])['email'];

//	$_SESSION['email_taken'] = $email_is_already_taken;
//	var_dump($_SESSION['email_taken']);
//	var_dump(has_unique_email($_POST['email'])['email']);
//	unset($_SESSION['email_taken']);

	$new_password = !is_empty($_POST['password']);
	$result = edit_user($user, ["password_required" => $new_password, "email_is_already_taken" => $email_is_already_taken]);

	if ($result === true) {
		redirect_to(url('admin/users/view_user.php?id=' . $id));
	} else {
		$errors = $result;
	}
} else {
	$user = find_user_by_id($id);
}
?>

<section class="content">
	<?php echo display_errors($errors); ?>
    <form class="form" action="<?php echo url('/admin/users/edit_user.php?id=' . h(u($id))); ?>" method="post"
          autocomplete="off">
        <h1 class="formTitle">Edit User</h2>
            <p type="First Name:"><input type="text" name="fname" placeholder="Type your frist name here"
                                         value="<?php echo $user['fname'] ?? ''; ?>"/></p>
            <p type="Last name:"><input type="text" name="sname" placeholder="Type your surname here"
                                        value="<?php echo $user['sname'] ?? ''; ?>"/></p>
            <p type="Email:"><input type="text" name="email" placeholder="Type your email here"
                                    value="<?php echo $user['email'] ?? ''; ?>"/></p>
            <p type="Choose a Password:"><input type="password" name="password"/></p>
            <p type="Confirm Password:"><input type="password" name="confirm_password"/></p>
            <p type="Privilege Level:">
                <select name="priv">
					<?php
					for ($i = 0; $i <= $_SESSION['priv_level']; $i++) {
						echo "<option value='{$i}'";
						if ($i === (int)$user['priv']) {
							echo " selected";
						}
						echo ">{$i}</option>";
					}
					?>
                </select>
            </p>
            <p type="Visible:">
                <input type="hidden" name="active" value="n"/>
                <input type="checkbox" name="active" value="y"/>
            </p>
            <button type="submit" name="submit">Submit</button>
    </form>

</section>


<?php include SHARED_PATH . '/admin_footer.php'; ?>
