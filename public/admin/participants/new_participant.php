<?php require_once '../../../private/initialize.php'; ?>

<?php
    require_login();
    check_if_timeout();

if (is_post_request()) {
    $participant['fname'] = $_POST['fname'] ?? "";
    $participant['sname'] = $_POST['sname'] ?? "";
    $participant['email'] = $_POST['email']?? "";
    $participant['class_name'] = $_POST['class_name'] ?? "";
    $participant['class_duration'] = $_POST['class_duration'] ?? "";
    $participant['class_date'] = $_POST['class_date'] ?? "";
    $participant['class_time'] = $_POST['class_time'] ?? "";

    $email_is_already_taken =  find_participant_by_email($_POST['email'])['email'] === $_POST['email'];

    $result = create_new_participant($participant, ["email_is_already_taken" => $email_is_already_taken]);
    if ($result === true) {
        $newId = mysqli_insert_id($db);
        redirect_to(url('/admin/participants/view_participant.php?id='. $newId));
    } else {
        $errors = $result;
        // var_dump($errors);
    }
} else {
  // display the blank form
    $participant = [];
    $participant['fname'] = "";
    $participant['sname'] = "";
    $participant['email'] = "";
    $participant['class_name'] = "";
    $participant['class_duration'] = "";
    $participant['class_date'] = "";
    $participant['class_time'] = "";
}
    ?>
<?php $page_title = "New Participant"; ?>
<?php include SHARED_PATH . '/admin_header.php'; ?>
         <section class="content">
                    <?php echo display_errors($errors); ?>
                 <form class="form" action="<?php echo url('/admin/participants/new_participant.php'); ?>" method="post" autocomplete="off">
                     <h1 class="formTitle">Add New participant</h2>
                         <p type="First Name:"><input type="text" name="fname" placeholder="First name goes here"/></p>
                         <p type="Last name:"><input type="text" name="sname" placeholder="Surname goes here"/></p>
                         <p type="Email:"><input type="text" name="email" placeholder="Type your email here"/></p>
                         <p type="Class Name:"><input type="text" name="class_name" placeholder="Input class name"/></p>
                         <p type="Class Duration:"><input type="number" name="class_duration" placeholder="Input class duration"/></p>
                         <p type="Class Date:"><input type="date" name="class_date" placeholder="Input class date"/></p>
                         <p type="Class Time:"><input type="time" name="class_time" placeholder="Input class name"/></p>
                         <button type="submit" name="submit">Submit</button>
                 </form>

         </section>


<?php include SHARED_PATH . '/admin_footer.php'; ?>
