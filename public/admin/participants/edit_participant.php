<?php require_once '../../../private/initialize.php'; ?>

<?php $page_title = "Edit Participant"; ?>
<?php include SHARED_PATH . '/admin_header.php'; ?>

<?php
require_login();
check_if_timeout();
?>

<?php
if (!isset($_GET['id'])) {
    redirect_to(url('/admin/participants/index.php'));
}

$id = $_GET['id'];
?>
<?php
if (is_post_request()) {
    $participant['part_id'] = $id;
    $participant['fname'] = $_POST['fname'] ?? "";
    $participant['sname'] = $_POST['sname'] ?? "";
    $participant['email'] = $_POST['email']?? "";
    $participant['class_name'] = $_POST['class_name'] ?? "";
    $participant['class_duration'] = $_POST['class_duration'] ?? "";
    $participant['class_date'] = $_POST['class_date'] ?? "";
    $participant['class_time'] = $_POST['class_time'] ?? "";


    $email_is_already_taken =  find_participant_by_id($id)['email'] !== find_participant_by_email($_POST['email'])['email'] && find_participant_by_email($_POST['email'])['email'];
    $result = edit_participant($participant, ["email_is_already_taken" => $email_is_already_taken]);

    if ($result === true) {
        redirect_to(url('admin/participants/view_participant.php?id=' . $id));
    } else {
        $errors = $result;
    }
} else {
    $participant = find_participant_by_id($id);
}
?>

<section class="content">
    <?php echo display_errors($errors); ?>
    <form class="form" action="<?php echo url('/admin/participants/edit_participant.php?id=' . h(u($id))); ?>" method="post"
          autocomplete="off">
        <h1 class="formTitle">Edit Participant</h2>
            <p type="First Name:"><input type="text" name="fname" placeholder="First name goes here" value="<?php echo $participant['fname'] ?? ''; ?>"/></p>
            <p type="Last name:"><input type="text" name="sname" placeholder="Surname goes here" value="<?php echo $participant['sname'] ?? ''; ?>"/></p>
            <p type="Email:"><input type="text" name="email" placeholder="Type your email here" value="<?php echo $participant['email'] ?? ''; ?>"/></p>
            <p type="Class Name:"><input type="text" name="class_name" placeholder="Input class name" value="<?php echo $participant['class_name'] ?? ''; ?>"/></p>
            <p type="Class Duration:"><input type="number" name="class_duration" placeholder="Input class duration" value="<?php echo $participant['class_duration'] ?? ''; ?>"/></p>
            <p type="Class Date:"><input type="date" name="class_date" placeholder="Input class date" value="<?php echo $participant['class_date'] ?? ''; ?>"/></p>
            <p type="Class Time:"><input type="time" name="class_time" placeholder="Input class time" value="<?php echo convert_time($participant['class_time']) ?? ''; ?>"/></p>
            <button type="submit" name="submit">Submit</button>
    </form>

</section>


<?php include SHARED_PATH . '/admin_footer.php'; ?>
