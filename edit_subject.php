<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("includes/connection.php");
require_once("includes/functions.php");
find_selected_page();


/* -----------------------------
   Validate subject id in URL
------------------------------ */
if (!isset($_GET['subj']) || intval($_GET['subj']) == 0) {
    redirect_to("content.php");
    exit;
}

/* -----------------------------
   Form submission handling
------------------------------ */
if (isset($_POST['submit'])) {

    $errors = [];

    /* Required fields validation */
    $required_fields = ['menu_name', 'position', 'visible'];
    foreach ($required_fields as $fieldname) {
        if (!isset($_POST[$fieldname]) || (empty($_POST[$fieldname]) && $_POST[$fieldname] !=0)) {
            $errors[] = $fieldname;
        }
    }

    /* Max length validation */
    $fields_with_lengths = ['menu_name' => 30];
    foreach ($fields_with_lengths as $fieldname => $maxlength) {
        if (isset($_POST[$fieldname]) && strlen(trim($_POST[$fieldname])) > $maxlength) {
            $errors[] = $fieldname;
        }
    }

    /* Perform update if no errors */
    if (empty($errors)) {

        $id        = (int) $_GET['subj'];
        $menu_name = mysqli_real_escape_string($connection, $_POST['menu_name']);
        $position  = (int) $_POST['position'];
        $visible   = (int) $_POST['visible'];

        $query = "UPDATE subjects SET
                    menu_name = '{$menu_name}',
                    position  = {$position},
                    visible   = {$visible}
                  WHERE id = {$id}
                  LIMIT 1";

        $result = mysqli_query($connection, $query);

        if (mysqli_affected_rows( $connection) == 1) {
            $message = "The subject was successfully updated";
        } else {
            $message = "Subject update failed.";
            $message .= "<br />" .mysqli_error($connection);
        }
        

    } else{
        $message ="There Were " . count($errors) . "errors in the form.";
    }
}
?>

<?php include("includes/header.php"); ?>

<table id="structure">
    <tr>
        <!-- Navigation -->
        <td id="navigation">
            <?php echo navigation($sel_subject, $sel_page); ?>
        </td>

        <!-- Page Content -->
        <td id="page">
            <h2>Edit Subject: <?php echo $sel_subject['menu_name']; ?></h2>
            <?php if(!empty($message)){
    echo "<p class=\"message\">" . htmlspecialchars($message) . "</p>";
} ?>
            <?php 
                //output a list of the fields that had errors
                if(!empty($errors)) {
                    echo "<p class=\"errors\">";
                    echo "Please review the following fields:<br />";
                    foreach ($errors as $error) {
                        echo " - " . $error . "<br />";
                    }
                    echo "</p>";
                }
            ?>

            <form action="edit_subject.php?subj=<?php echo urlencode($sel_subject['id']); ?>" method="post">

                <p>
                    Subject Name:
                    <input type="text" name="menu_name"
                        value="<?php echo htmlspecialchars($sel_subject['menu_name']); ?>" />
                </p>

                <p>
                    Position:
                    <select name="position">
                        <?php
                        $subject_set   = get_all_subject($connection);
                        $subject_count = mysqli_num_rows($subject_set);

                        for ($count = 1; $count <= $subject_count; $count++) {
                            echo "<option value=\"{$count}\"";
                            if ($sel_subject['position'] == $count) {
                                echo " selected";
                            }
                            echo ">{$count}</option>";
                        }
                        ?>
                    </select>
                </p>

                <p>
                    Visible:
                    <input type="radio" name="visible" value="0"
                        <?php if ($sel_subject['visible'] == 0) { echo "checked"; } ?> /> No
                    &nbsp;
                    <input type="radio" name="visible" value="1"
                        <?php if ($sel_subject['visible'] == 1) { echo "checked"; } ?> /> Yes
                </p>

                <input type="submit" name="submit" value="Edit Subject" />
                &nbsp;&nbsp;
                <a href="delete_subject.php?subj=<?php echo urlencode($sel_subject['id']); ?>" onclick="return confirm('are you sure?');">Delete subject</a>
                </form>
<br />
<a href="content.php">Cancel</a>

<div style="margin-top: 2em; border-top: 1px solid #000000;">
    <h3>Pages in this subject:</h3>
    <ul>
        <?php
        $subject_pages = get_pages_for_subject($connection,$sel_subject['id']);
        while ($page = mysqli_fetch_array($subject_pages)) {
            echo "<li>
                    <a href=\"content.php?page={$page['id']}\">
                        {$page['menu_name']}
                    </a>
                  </li>";
        }
        ?>
    </ul>
    <br />
    <a href="new_page.php?subj=<?php echo $sel_subject['id']; ?>">
        Add a new page to this subject
    </a>
</div>
</td>
</tr>
</table>

<?php require("includes/footer.php"); ?>

