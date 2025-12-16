
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php include("includes/header.php"); ?>

<table id="structure">
    <tr>
        <td id="navigation">

        <ul class="subjects">
            <?php
            // 3. Perform database query
            $subject_set = mysqli_query($connection, "SELECT * FROM subjects");

            if (!$subject_set) {
                die("Database query failed: " . mysqli_error($connection));
            }

            // 4. Use returned data
            while ($subject = mysqli_fetch_assoc($subject_set)) {
                echo "<li>{$subject["menu_name"]}</li>";
                
                $page_set = mysqli_query($connection, "SELECT * FROM pages WHERE subject_id = {$subject["id"]}");

                if (!$page_set) {
                    die("Database query failed: " . mysqli_error($connection));
                }
                echo "<ul class=\"pages\">";
                // 4. Use returned data
                while ($page = mysqli_fetch_assoc($page_set)) {
                    echo "<li>{$page["menu_name"]}</li>";
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


