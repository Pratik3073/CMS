<?php
require_once("includes/connection.php");
require_once("includes/functions.php");
require_once("includes/form_functions.php");

/* -----------------------------
   Find selected subject/page
------------------------------ */
find_selected_page();

/* -----------------------------
   Validate page id
------------------------------ */
if (!isset($_GET['page']) || intval($_GET['page']) === 0) {
    redirect_to("content.php");
}

$page_id = (int) $_GET['page'];

/* -----------------------------
   Form Processing
------------------------------ */
if (isset($_POST['submit'])) {

    // Initialize errors array
    $errors = [];

    // Required field validation
    $required_fields = ['menu_name', 'position', 'visible', 'content'];
    $errors = array_merge($errors, check_required_fields($required_fields));

    // Length validation
    $fields_with_lengths = ['menu_name' => 30];
    $errors = array_merge($errors, check_max_field_lengths($fields_with_lengths));

    // Sanitize inputs (MySQLi)
    $menu_name = mysqli_real_escape_string(
        $connection,
        trim($_POST['menu_name'])
    );
    $position = (int) $_POST['position'];
    $visible  = (int) $_POST['visible'];
    $content  = mysqli_real_escape_string(
        $connection,
        $_POST['content']
    );

    // Update only if no errors
    if (empty($errors)) {

        $query = "UPDATE pages SET
                    menu_name = '{$menu_name}',
                    position  = {$position},
                    visible   = {$visible},
                    content   = '{$content}'
                  WHERE id = {$page_id}
                  LIMIT 1";

        $result = mysqli_query($connection, $query);

        if ($result && mysqli_affected_rows($connection) === 1) {
            $message = "The page was successfully updated.";
        } else {
            $message  = "The page could not be updated.";
            $message .= "<br />" . mysqli_error($connection);
        }

    } else {
        $message = (count($errors) === 1)
            ? "There was 1 error in the form."
            : "There were " . count($errors) . " errors in the form.";
    }
}
?>

<?php include("includes/header.php"); ?>

<table id="structure">
<tr>

    <!-- Navigation -->
    <td id="navigation">
        <?php echo navigation($sel_subject, $sel_page); ?>
        <br />
        <a href="new_subject.php">Add a new subject</a>
    </td>

    <!-- Page Content -->
    <td id="page">
        <h2>
            Edit Page:
            <?php echo htmlspecialchars($sel_page['menu_name']); ?>
        </h2>

        <?php
            if (!empty($message)) {
                echo "<p class=\"message\">" .
                     htmlspecialchars($message) .
                     "</p>";
            }

            if (!empty($errors)) {
                display_errors($errors);
            }
        ?>

        <form
            action="edit_page.php?page=<?php echo urlencode($sel_page['id']); ?>"
            method="post"
        >

            <?php include("page_form.php"); ?>

            <input type="submit" name="submit" value="Update Page" />
            &nbsp;&nbsp;

            <a href="delete_page.php?page=<?php echo urlencode($sel_page['id']); ?>"
               onclick="return confirm('Are you sure you want to delete this page?');">
                Delete page
            </a>
        </form>

        <br />

        <a href="content.php?page=<?php echo urlencode($sel_page['id']); ?>">
            Cancel
        </a>
    </td>

</tr>
</table>

<?php include("includes/footer.php"); ?>
