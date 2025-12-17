<?php
// this file is the place to store all basic functions.

function redirect_to($location = NULL){
    if($location != NULL){
    header("location: " . $location);
    exit;
    }
}
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
function get_subject_by_id( $subject_id){
    global $connection;
    $query ="SELECT * ";
    $query .= "FROM subjects ";
    $query .= "WHERE id=" . $subject_id ." ";
    $query .= "LIMIT 1";
    $result_set = mysqli_query($connection, $query);
    confirm_query($result_set, $connection);

    //if no row are return fetch array return false 
        if ($subject = mysqli_fetch_array($result_set)){

            return $subject;
        }else{
            return NULL;
        }
}

function get_by_page_id($page_id)
{
    global $connection;

    if (!isset($page_id) || $page_id === "") {
        return null;
    }

    $query  = "SELECT * ";
    $query .= "FROM pages ";
    $query .= "WHERE id = {$page_id} ";
    $query .= "LIMIT 1";

    $result_set = mysqli_query($connection, $query);
    confirm_query($result_set, $connection);

    if ($page = mysqli_fetch_assoc($result_set)) {
        return $page;
    } else {
        return null;
    }
}

function find_selected_page(){
    global $sel_subject;
    global $sel_page;
    if(isset($_GET['subj'])){
        $sel_subject=get_subject_by_id($_GET['subj']);
        $sel_page = NULL;
     }elseif(isset($_GET['page'])){
        $sel_page = get_by_page_id($_GET['page']);
        $sel_subject = get_subject_by_id($sel_page['subject_id']);
    
    }else{
        $sel_subject = NULL;
        $sel_page = NULL;
     }
}
function navigation($sel_subject,$sel_page){
    global $connection;

    $output ="<ul class=\"subjects\">";
    
    $subject_set = get_all_subject($connection);


    while ($subject = mysqli_fetch_assoc($subject_set)) {

        // open <li> and add selected class if needed
        $output .= "<li";
        if (!is_null($sel_subject) && $subject["id"] == $sel_subject['id']) {

            $output.= " class=\"selected\"";
        }
        $output.= "><a href=\"edit_subject.php?subj=" . urlencode($subject["id"]) . 
                "\">
                {$subject["menu_name"]}</a></li>";

        // pages
        $page_set = get_pages_for_subject($connection, $subject["id"]);

        $output.= "<ul class=\"pages\">";
        while ($page = mysqli_fetch_assoc($page_set)) {
            $output.= "<li";
            if (!is_null($sel_page) && $page["id"] == $sel_page['id']) {

                $output.= " class=\"selected\"";
            }
            $output.= " >
                    <a href=\"content.php?page=" . urlencode($page["id"]) . "\">
                        {$page['menu_name']}
                    </a>
                  </li>";
        }
        $output.= "</ul>";
    }
    
    $output.= "</ul>";
    return $output;
}
?>
