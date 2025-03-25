<?php
include('db_con.php');
session_start();

if (isset($_SESSION['type'])) {
    header("location:index.php");
    exit;
}

$message = '';

if (isset($_POST["login"])) {
    $query = "
    SELECT * FROM user_details 
    WHERE user_email = :user_email
    ";
    $statement = $pdo->prepare($query);
    $statement->execute(['user_email' => $_POST["user_email"]]);

    $count = $statement->rowCount();
    if ($count > 0) {
        $result = $statement->fetchAll();
        foreach ($result as $row) {
            if ($row['user_status'] == 'Active') {
                if (password_verify($_POST["user_password"], $row["user_password"])) {
                    $_SESSION['type'] = $row['user_type'];
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['user_name'] = $row['user_name'];
                    header("location:index.php");
                    exit;
                } else {
                    $message = "<label>Wrong Password</label>";
                }
            } else {
                $message = "<label>Your account is disabled, Contact Master</label>";
            }
        }
    } else {
        $message = "<label>Wrong Email Address</label>";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Inventory Management System</title>        
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </head>
    <body>
        <br />
        <div class="container">
            <h2 align="center">Inventory Management System</h2>
            <br />
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <form method="post">
                        <?php echo $message; ?>
                        <div class="form-group">
                            <label>User Email</label>
                            <input type="email" name="user_email" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="user_password" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <input type="submit" name="login" value="Login" class="btn btn-info" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
