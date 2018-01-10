<?php require_once '../private/initialize.php'; ?>


<?php

if (is_post_request()) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (is_empty($email)) {
        $errors[] = "Username cannot be blank.";
    }
    if (is_empty($password)) {
        $errors[] = "Password cannot be blank.";
    }

    $user = find_user_by_email($email);

    if ($user) {
        if (password_verify($password, $user['pw'])) {
            log_in($user);
            if (has_priv_level(1)) {
                redirect_to(url('admin/index.php'));
            } else {
                $errors[] = "Not enough privileges";
            }
        } else {
            // echo "wrong pass";
        }
    } else {
        // echo $loggin_unsuccessful_msg;
        $errors[] = "Log in was unsuccessful.";
    }
}

    ?>


<!DOCTYPE HTML>

<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="<?php echo url('/css/admin.css'); ?>" >
    <link rel="stylesheet" href="<?php echo url('/css/forms.css'); ?>" >
    <title>Login</title>
</head>

<body>
<div class="login">

<?php echo display_errors($errors); ?>
</div>
<form class="form login" action="login.php" method="post" autocomplete="off">
    <h1 class="formTitle">Log In</h2>
        <p type="Email:"><input name="email" placeholder="Enter your email"/></p>
        <p type="Password:"><input type="password" name="password" placeholder="Enter your password"/></p>
        <button type="submit" name="submit">Submit</button>
</form>
</body>
</html>
