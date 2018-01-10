<?php require_once '../../../private/initialize.php'; ?>

<?php
    require_login();
    check_if_timeout();

if (is_post_request()) {
    $user['fname'] = $_POST['fname'] ?? "";
    $user['sname'] = $_POST['sname'] ?? "";
    $user['email'] = $_POST['email']?? "";
    $user['password'] = $_POST['password'] ?? "";
    $user['confirm_password'] = $_POST['confirm_password'] ?? "";
    $user['priv'] = $_POST['priv'] ?? "";
    $user['active'] = $_POST['active'] ?? "";

    $email_is_already_taken =  find_user_by_email($_POST['email'])['email'] === $_POST['email'];

    $result = create_new_user($user, ["email_is_already_taken" => $email_is_already_taken]);
    if ($result === true) {
        $newId = mysqli_insert_id($db);
        redirect_to(url('/admin/users/view_user.php?id='. $newId));
    } else {
        $errors = $result;
        // var_dump($errors);
    }
} else {
  // display the blank form
    $user = [];
    $user[$fname] = "";
    $user[$sname] = "";
    $user[$email] = "";
    $user[$password] = "";
    $user[$active] = "";
}
    ?>
<?php $page_title = "New User"; ?>
<?php include SHARED_PATH . '/admin_header.php'; ?>
         <section class="content">
                    <?php echo display_errors($errors); ?>
                 <form class="form" action="<?php echo url('/admin/users/new_user.php'); ?>" method="post" autocomplete="off">
                     <h1 class="formTitle">Add New User</h2>
                         <p type="First Name:"><input type="text" name="fname" placeholder="First name goes here"/></p>
                         <p type="Last name:"><input type="text" name="sname" placeholder="Surname goes here"/></p>
                         <p type="Email:"><input type="text" name="email" placeholder="Type your email here"/></p>
                         <p type="Choose a Password:">
                             <br>
                             <span class="small info_text">Passwords should be at least 12 characters and include at least one uppercase letter, lowercase letter, number, and symbol.</span>
                             <input  type="password" name="password" placeholder="Type in your password"/>
                         </p>
                         <p type="Confirm Password:"><input type="password" name="confirm_password" placeholder="Confirm password"/></p>
                         <p type="Privilege Level: ">
                             <select name="priv">
                                 <option value="1">User</option>
                                 <option value="2">Admin</option>
                                 <option value="3">Super Admin</option>
                             </select>
                         </p>
                     <label for="active">
                         Visible User?
                         <input type="hidden" name="active" value="n"/>
                         <input type="checkbox" name="active" value="y"/>
                     </label>
                         <button type="submit" name="submit">Submit</button>
                 </form>

         </section>


<?php include SHARED_PATH . '/admin_footer.php'; ?>
