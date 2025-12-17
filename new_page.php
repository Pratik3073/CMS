<?php
require_once("includes/connection.php");
require_once("includes/functions.php");
require_once("includes/form_functions.php");

find_selected_page();

/* -----------------------------
   Validate subject id
------------------------------ */
if (!isset($_GET['subj']) || intval($_GET['subj']) == 0) {
    redirect_to("content.php");
}

/* -----------------------------
   Form processing
------------------------------ */
if (isset($_POST['submit'])) {

    $errors = [];

    // validations
    $required_fields = ['menu_name', 'position', 'visible', 'content'];
    $errors = array_merge($errors, check_required_fields($required_fields));

    $fields_with_lengths = ['menu_name' => 30];
    $errors = array_merge($errors, check_max_field_lengths($fields_with_lengths));

    // clean form values
    $subject_id = (int) $_GET['subj'];
    $menu_name  = mysqli_real_escape_string($connection, trim($_POST['menu_name']));
    $position   = (int) $_POST['position'];
    $visible    = (int) $_POST['visible'];
    $content    = mysqli_real_escape_string($connection, $_POST['content']);

    // insert if no errors
    if (empty($errors)) {

        $query = "INSERT INTO pages (
                    subject_id, menu_name, position, visible, content
                  ) VALUES (
                    {$subject_id}, '{$menu_name}', {$position}, {$visible}, '{$content}'
                  )";

        $result = mysqli_query($connection, $query);

        if ($result) {
            redirect_to("content.php");
        } else {
            $message  = "Page creation failed.";
            $message .= "<br />" . mysqli_error($connection);
        }

    } else {
        $message = "There were " . count($errors) . " errors in the form.";
    }
}
?>

<?php include("includes/header.php"); ?>

<table id="structure">
<tr>

<!-- NAVIGATION -->
<td id="navigation">
    <?php echo navigation($sel_subject, $sel_page); ?>
    <br />
    <a href="new_subject.php">Add a new subject</a>
</td>

<!-- PAGE CONTENT -->
<td id="page">
    <h2>Adding New Page</h2>

    <?php
        if (!empty($message)) {
            echo "<p class=\"message\">" . htmlspecialchars($message) . "</p>";
        }

        if (!empty($errors)) {
            display_errors($errors);
        }
    ?>

    <form action="new_page.php?subj=<?php echo urlencode($sel_subject['id']); ?>" method="post">

        <?php
            $new_page = true;
            include("page_form.php");
        ?>

        <input type="submit" name="submit" value="Create Page" />
    </form>

    <br />
    <a href="edit_subject.php?subj=<?php echo urlencode($sel_subject['id']); ?>">
        Cancel
    </a>

    <div style="margin-top: 2em; border-top: 1px solid #000;">
        <h3>Pages in this subject:</h3>
        <ul>
            <?php
                $subject_pages = get_pages_for_subject($connection,$sel_subject['id']);
                while ($page = mysqli_fetch_assoc($subject_pages)) {
                    echo "<li>
                            <a href=\"content.php?page={$page['id']}\">
                                {$page['menu_name']}
                            </a>
                          </li>";
                }
            ?>
        </ul>

        <br />
        <a href="new_page.php?subj=<?php echo urlencode($sel_subject['id']); ?>">
            Add a new page to this subject
        </a>
    </div>

</td>
</tr>
</table>

<?php include("includes/footer.php"); ?>
