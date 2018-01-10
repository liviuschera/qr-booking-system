<?php
// USERS queries
function find_all_users()
{
    global $db;
    $query = "SELECT * FROM users";
    $result = mysqli_query($db, $query);
    confirm_result($result);
    return $result;
}

function count_all_users()
{
    global $db;
    $query = "SELECT count(*) FROM users";
    $result = mysqli_query($db, $query);
    confirm_result($result);
    return $result;
}

function search_user($user)
{
    global $db;
    if (!empty($user)) {
        $priv = $_SESSION['priv_level'];
        $query = "SELECT * FROM users WHERE ";
        $query .= "(lower(fname) LIKE '%" . db_escape($db, $user) . "%' OR ";
        $query .= "lower(sname) LIKE '%" . db_escape($db, $user) . "%' OR ";
        $query .= "lower(email) LIKE '%" . db_escape($db, $user) . "%') ";
        $query .= "AND priv <= '". db_escape($db, $priv) ."'";
        $result = mysqli_query($db, $query);
        confirm_result($result);
        // $search_result = mysqli_fetch_assoc($result);
        return $result;
    }
}

function find_user_by_id($id, $return_array = true)
{
    global $db;
    $query = "SELECT * FROM users WHERE id= '" . db_escape($db, $id) . "'";
    $result = mysqli_query($db, $query);
    confirm_result($result);
    if ($return_array) {
        $user = mysqli_fetch_assoc($result);
        return $user;
    } else {
        return $result;
    }
}

function find_user_by_email($email)
{
    global $db;
    $query = "SELECT * FROM users WHERE email = '" . db_escape($db, $email) . "'";
    $result = mysqli_query($db, $query);
    confirm_result($result);
    $user = mysqli_fetch_assoc($result);
    return $user;
}

function count_users_by_privilege($privilege_level)
{
    global $db;

    $query = "SELECT count(priv) FROM users ";
    $query .= "GROUP BY priv HAVING priv = '" . db_escape($db, $privilege_level) . "'";
    $result = mysqli_query($db, $query);
    confirm_result($result);
    $total = mysqli_fetch_row($result)[0];
    return $total;
}


function validate_user($user, $options = [])
{
    $password_required = $options['password_required'] ?? true;
    $email_is_already_taken = $options['email_is_already_taken'];

    // FIRST NAME
    if (is_empty($user['fname'])) {
        $errors[] = "First Name cannot be blank.";
    } elseif (strlen($user['fname']) < 2 || strlen($user['fname']) > 255) {
        $errors[] = "First Name must be between 2 and 255 characters.";
    }

    // LAST NAME
    if (is_empty($user['sname'])) {
        $errors[] = "Last Name cannot be blank.";
    } elseif (strlen($user['sname']) < 2 || strlen($user['sname']) > 255) {
        $errors[] = "Last Name must be between 2 and 255 characters.";
    }

    // EMAIL
    if (is_empty($user['email'])) {
        $errors[] = "Email cannot be blank.";
    } elseif (!has_valid_email($user['email'])) {
        $errors[] = "Email must be a valid format.";
    } elseif ($email_is_already_taken) {
        $errors[] = "Email is already taken.";
    }

    // PASSWORD
    if ($password_required) {
        if (is_empty($user['password'])) {
            $errors[] = "Password cannot be blank.";
        } elseif (strlen($user['password']) <= 4 || strlen($user['password']) >= 255) {
            $errors[] = "Password must be between 4 and 255 characters.";
        } elseif (!preg_match('/[A-Z]/', $user['password'])) {
            $errors[] = "Password must contain at least 1 uppercase letter";
        } elseif (!preg_match('/[a-z]/', $user['password'])) {
            $errors[] = "Password must contain at least 1 lowercase letter";
        } elseif (!preg_match('/[0-9]/', $user['password'])) {
            $errors[] = "Password must contain at least 1 number";
        } elseif (!preg_match('/[^A-Za-z0-9\s]/', $user['password'])) {
            $errors[] = "Password must contain at least 1 symbol";
        }
        // CONFIRM PASSWORD
        if (is_empty($user['confirm_password'])) {
            $errors[] = "Confirm password cannot be blank.";
        } elseif ($user['password'] !== $user['confirm_password']) {
            $errors[] = "Password and confirm password must match.";
        }
    }

    return $errors;
}

function create_new_user($user, $options = [])
{
    global $db;
    $email_is_already_taken = $options['email_is_already_taken'];
    $errors = validate_user($user, ["email_is_already_taken" => $email_is_already_taken]);
    if (!empty($errors)) {
        return $errors;
    }

    $hashed_password = password_hash($user['password'], PASSWORD_BCRYPT);
    $insert = "INSERT INTO users ";
    $insert .= "(fname, sname, email, pw, priv, active) ";
    $insert .= "VALUES (";
    $insert .= "'" . db_escape($db, $user['fname']) . "',";
    $insert .= "'" . db_escape($db, $user['sname']) . "',";
    $insert .= "'" . db_escape($db, $user['email']) . "',";
    $insert .= "'" . db_escape($db, $hashed_password) . "',";
    $insert .= "'" . db_escape($db, $user['priv']) . "',";
    $insert .= "'" . db_escape($db, $user['active']) . "'";
    $insert .= ")";
    $result = mysqli_query($db, $insert);
    if ($result) {
        return true;
    } else {
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

function edit_user($user, $options = [])
{
    global $db;

    $password_required = !is_empty($user['password']);
    $email_is_already_taken = $options['email_is_already_taken'];
//    $email_is_already_taken = has_unique_email($_POST['email'])['email'] === $user['email'];

    $errors = validate_user($user, ['password_required' => $password_required, "email_is_already_taken" => $email_is_already_taken]);
    if (!empty($errors)) {
        return $errors;
    }
    $hashed_password = password_hash($user['password'], PASSWORD_BCRYPT);
    $edit = "UPDATE users SET ";
    $edit .= "fname = '" . db_escape($db, $user['fname']) . "',";
    $edit .= "sname = '" . db_escape($db, $user['sname']) . "',";
    $edit .= "email= '" . db_escape($db, $user['email']) . "',";
    if ($password_required) {
        $edit .= "pw = '" . db_escape($db, $hashed_password) . "',";
    }
    $edit .= "priv = '" . db_escape($db, $user['priv']) . "',";
    $edit .= "active = '" . db_escape($db, $user['active']) . "' ";
    $edit .= "WHERE id = '" . db_escape($db, $user['id']) . "'";

    $result = mysqli_query($db, $edit);
    if ($result) {
        return true;
    } else {
        // UPDATE failed
        echo "Update failed: " . mysqli_errno($db);
        db_disconnect($db);
        exit;
    }
}

function delete_user($id)
{
    global $db;
    $delete_request = "DELETE FROM users WHERE id = '" . db_escape($db, $id) . "' LIMIT 1";
    $result = mysqli_query($db, $delete_request);
    if ($result) {
        return true;
    } else {
        // DELETE failed
        echo mysqli_errno($db);
        db_disconnect($db);
        exit;
    }
}

//
// PARTICIPANTS queries
//

function find_all_participants()
{
    global $db;
    $query = "SELECT * FROM participants";
    $result = mysqli_query($db, $query);
    confirm_result($result);
    return $result;
}

function count_all_participants()
{
    global $db;
    $query = "SELECT count(*) FROM participants";
    $result = mysqli_query($db, $query);
    confirm_result($result);
    return $result;
}

function search_participant($participant)
{
    global $db;
    if (!empty($participant)) {
        $query = "SELECT * FROM participants WHERE ";
        $query .= "(lower(fname) LIKE '%" . db_escape($db, $participant) . "%' OR ";
        $query .= "lower(sname) LIKE '%" . db_escape($db, $participant) . "%' OR ";
        $query .= "lower(email) LIKE '%" . db_escape($db, $participant) . "%')";
        $result = mysqli_query($db, $query);
        confirm_result($result);
        // $search_result = mysqli_fetch_assoc($result);
        return $result;
    }
}

function find_participant_by_id($id, $return_array = true)
{
    global $db;
    $query = "SELECT * FROM participants WHERE part_id= '" . db_escape($db, $id) . "'";
    $result = mysqli_query($db, $query);
    confirm_result($result);
    if ($return_array) {
        $participant = mysqli_fetch_assoc($result);
        return $participant;
    } else {
        return $result;
    }
}

function find_participant_by_email($email)
{
    global $db;
    $query = "SELECT * FROM participants WHERE email = '" . db_escape($db, $email) . "'";
    $result = mysqli_query($db, $query);
    confirm_result($result);
    $participant = mysqli_fetch_assoc($result);
    return $participant;
}

function count_participants_by_privilege($privilege_level)
{
    global $db;

    $query = "SELECT count(priv) FROM participants ";
    $query .= "GROUP BY priv HAVING priv = '" . db_escape($db, $privilege_level) . "'";
    $result = mysqli_query($db, $query);
    confirm_result($result);
    $total = mysqli_fetch_row($result)[0];
    return $total;
}


function validate_participant($participant, $options = [])
{
    $password_required = $options['password_required'] ?? true;
    $email_is_already_taken = $options['email_is_already_taken'];

    // FIRST NAME
    if (is_empty($participant['fname'])) {
        $errors[] = "First Name cannot be blank.";
    } elseif (strlen($participant['fname']) < 2 || strlen($participant['fname']) > 20) {
        $errors[] = "First Name must be between 2 and 255 characters.";
    }

    // LAST NAME
    if (is_empty($participant['sname'])) {
        $errors[] = "Last Name cannot be blank.";
    } elseif (strlen($participant['sname']) < 2 || strlen($participant['sname']) > 20) {
        $errors[] = "Last Name must be between 2 and 255 characters.";
    }

    // EMAIL
    if (is_empty($participant['email'])) {
        $errors[] = "Email cannot be blank.";
    } elseif (!has_valid_email($participant['email'])) {
        $errors[] = "Email must be a valid format.";
    } elseif ($email_is_already_taken) {
        $errors[] = "Email is already taken.";
    }

    // CLASS NAME
    if (is_empty($participant['class_name'])) {
        $errors[] = "Class Name cannot be blank.";
    } elseif (strlen($participant['class_name']) < 2 || strlen($participant['class_name']) > 20) {
        $errors[] = "Class Name must be between 2 and 20 characters.";
    }

    // CLASS DURATION
    if (is_empty($participant['class_duration'])) {
        $errors[] = "Class duration cannot be blank.";
    } elseif ($participant['class_duration'] < 30 || $participant['class_duration'] > 60) {
        $errors[] = "Class duration must be between 30 and 60 minutes.";
    }

    // CLASS DATE
    if (is_empty($participant['class_date'])) {
        $errors[] = "Class date cannot be blank.";
    }

    // CLASS TIME
    if (is_empty($participant['class_name'])) {
        $errors[] = "Class Time cannot be blank.";
    }

    return $errors;
}

function create_new_participant($participant, $options = [])
{
    global $db;
    $email_is_already_taken = $options['email_is_already_taken'];
    $errors = validate_participant($participant, ["email_is_already_taken" => $email_is_already_taken]);
    if (!empty($errors)) {
        return $errors;
    }

    $insert = "INSERT INTO participants ";
    $insert .= "(email, fname, sname, class_name, class_duration, class_date, class_time) ";
    $insert .= "VALUES (";
    $insert .= "'" . db_escape($db, $participant['email']) . "',";
    $insert .= "'" . db_escape($db, $participant['fname']) . "',";
    $insert .= "'" . db_escape($db, $participant['sname']) . "',";
    $insert .= "'" . db_escape($db, $participant['class_name']) . "',";
    $insert .= "'" . db_escape($db, $participant['class_duration']) . "',";
    $insert .= "'" . db_escape($db, $participant['class_date']) . "',";
    $insert .= "'" . db_escape($db, $participant['class_time']) . "'";
    $insert .= ")";
    $result = mysqli_query($db, $insert);
    if ($result) {
        return true;
    } else {
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

function edit_participant($participant, $options = [])
{
    global $db;

    $email_is_already_taken = $options['email_is_already_taken'];
//    $email_is_already_taken = has_unique_email($_POST['email'])['email'] === $participant['email'];

    $errors = validate_participant($participant, ["email_is_already_taken" => $email_is_already_taken]);
    if (!empty($errors)) {
        return $errors;
    }

    $edit = "UPDATE participants SET ";
    $edit .= "email = '" . db_escape($db, $participant['email']) . "',";
    $edit .= "fname = '" . db_escape($db, $participant['fname']) . "',";
    $edit .= "sname = '" . db_escape($db, $participant['sname']) . "',";
    $edit .= "class_name = '" . db_escape($db, $participant['class_name']) . "',";
    $edit .= "class_duration = '" . db_escape($db, $participant['class_duration']) . "',";
    $edit .= "class_date = '" . db_escape($db, $participant['class_date']) . "',";
    $edit .= "class_time = '" . db_escape($db, $participant['class_time']) . "' ";
    $edit .= "WHERE part_id = '" . db_escape($db, $participant['part_id']) . "'";

    $result = mysqli_query($db, $edit);
    if ($result) {
        return true;
    } else {
        // UPDATE failed
        echo "Update failed: " . mysqli_errno($db);
        db_disconnect($db);
        exit;
    }
}

function delete_participant($id)
{
    global $db;
    $delete_request = "DELETE FROM participants WHERE part_id = '" . db_escape($db, $id) . "' LIMIT 1";
    $result = mysqli_query($db, $delete_request);
    if ($result) {
        return true;
    } else {
        // DELETE failed
        echo mysqli_errno($db);
        db_disconnect($db);
        exit;
    }
}
