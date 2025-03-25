<?php
// index.php
include('db_con.php');
include('function.php');

// Start the session
session_start();

// Check if the user is logged in, if not, redirect to the login page
if (!isset($_SESSION["type"])) {
    header("location:login.php");
    exit; // Ensure no further code is executed after the redirection
}

include('header.php');
?>

<br />
<div class="row">
    <?php if ($_SESSION['type'] == 'master') { ?>
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Total User</strong></div>
                <div class="panel-body" align="center">
                    <h1><?php echo count_total_user($pdo); ?></h1>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Total Category</strong></div>
                <div class="panel-body" align="center">
                    <h1><?php echo count_total_category($pdo); ?></h1>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Total Brand</strong></div>
                <div class="panel-body" align="center">
                    <h1><?php echo count_total_brand($pdo); ?></h1>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Total Item in Stock</strong></div>
                <div class="panel-body" align="center">
                    <h1><?php echo count_total_product($pdo); ?></h1>
                </div>
            </div>
        </div>
    <?php } ?>
 
</div>

<?php
include("footer.php");
?>
