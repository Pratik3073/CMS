<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>

<?php 
$errors = [];

// ✅ FIX 1: run validation ONLY when form is submitted
if (isset($_POST['submit'])) {

    //form Validation
    $required_fields = ["menu_name","position","visible"];
    foreach ($required_fields as $fieldname) {
        if(!isset($_POST[$fieldname]) || trim($_POST[$fieldname]) === ""){
            $errors[] = $fieldname;
        }
    }

    $fields_with_length = ['menu_name' => 30];
    foreach ($fields_with_length as $fieldname => $maxLength) {
        if (isset($_POST[$fieldname])) {
            if (strlen(trim($_POST[$fieldname])) > $maxLength) {
                $errors[] = $fieldname;
            }
        }
    }

    // ✅ FIX 2: stop execution after redirect
    if(!empty($errors)){
        redirect_to("new_subject.php");
        exit;
    }

    // ✅ FIX 3: correct escaping
    $menu_name = mysqli_real_escape_string($connection, $_POST['menu_name']);
    $position  = (int) $_POST['position'];   // integer
    $visible   = (int) $_POST['visible'];    // integer

    $query = "INSERT INTO subjects (
        `menu_name`, `position`, `visible`
    ) VALUES (
        '{$menu_name}', {$position}, {$visible}
    )";

    // ✅ FIX 4: run query ONLY ONCE
    $result = mysqli_query($connection, $query);

    if ($result) {
        redirect_to("content.php");
        exit;
    } else {
        echo "<p>Subject creation failed.</p>";
        echo "<p>" . mysqli_error($connection) . "</p>";
    }
}
?>

<?php mysqli_close($connection); ?>
