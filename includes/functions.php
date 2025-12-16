<?php
// this file is the place to store all basic functions.

function confirm_query($result_set, $connection)
{
    if (!$result_set) {
        die("Database query failed: " . mysqli_error($connection));
    }
}

function get_all_subject($connection)
{
    $query = "SELECT * 
              FROM subjects 
              ORDER BY position ASC";

    $subject_set = mysqli_query($connection, $query);
    confirm_query($subject_set, $connection);

    return $subject_set;
}

function get_pages_for_subject($connection, $subject_id)
{
    $query = "SELECT * 
              FROM pages 
              WHERE subject_id = {$subject_id}
              ORDER BY position ASC";

    $page_set = mysqli_query($connection, $query);
    confirm_query($page_set, $connection);

    return $page_set;
}
?>
