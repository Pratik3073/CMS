<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>

<?php
    find_selected_page();
?>

<?php include("includes/header.php"); ?>

<table id="structure">
    <tr>

        <!-- NAVIGATION -->
        <td id="navigation">
            <?php echo navigation($sel_subject, $sel_page); ?>
            <br />
            <a href="new_subject.php">+ Add a new subject</a>
        </td>

        <!-- PAGE CONTENT -->
        <td id="page">

        <?php if (!is_null($sel_page)) { ?>

            <!-- PAGE SELECTED -->
            <h2><?php echo htmlspecialchars($sel_page['menu_name']); ?></h2>

            <div class="page-content">
                <?php echo nl2br(htmlspecialchars($sel_page['content'])); ?>
            </div>

            <br />

            <!-- ✅ EDIT PAGE LINK (THIS WAS MISSING) -->
            <a href="edit_page.php?page=<?php echo urlencode($sel_page['id']); ?>">
                ✏️ Edit this page
            </a>

        <?php } elseif (!is_null($sel_subject)) { ?>

            <!-- SUBJECT SELECTED -->
            <h2><?php echo htmlspecialchars($sel_subject['menu_name']); ?></h2>

            <br />

            <!-- ✅ EDIT SUBJECT LINK -->
            <a href="edit_subject.php?subj=<?php echo urlencode($sel_subject['id']); ?>">
                ✏️ Edit this subject
            </a>

            <br /><br />

            <!-- ✅ ADD PAGE LINK -->
            <a href="new_page.php?subj=<?php echo urlencode($sel_subject['id']); ?>">
                ➕ Add a new page to this subject
            </a>

        <?php } else { ?>

            <h2>Select a subject or page to edit</h2>

        <?php } ?>

        </td>
    </tr>
</table>

<?php require("includes/footer.php"); ?>
