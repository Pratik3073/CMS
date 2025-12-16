<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php include("includes/header.php"); ?>

<table id="structure">
    <tr>
        <td id="navigation">
            <ul class="subjects">
                <?php
                // Perform database query
                $subject_set = get_all_subject($connection);

                // Use returned data
                while ($subject = mysqli_fetch_assoc($subject_set)) {
                    echo "<li>{$subject['menu_name']}</li>";

                    $page_set = get_pages_for_subject($connection, $subject["id"]);

                    echo "<ul class=\"pages\">";
                    while ($page = mysqli_fetch_assoc($page_set)) {
                        echo "<li>{$page['menu_name']}</li>";
                    }
                    echo "</ul>";
                }
                ?>
            </ul>
        </td>

        <td id="page">
            <h2>Content Area</h2>
        </td>
    </tr>
</table>

<?php require("includes/footer.php"); ?>
