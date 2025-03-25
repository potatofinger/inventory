<?php

include('db_con.php');

if (isset($_POST['btn_action'])) {

    if ($_POST['btn_action'] == 'Add') {
        // Insert new user
        $query = "
        INSERT INTO user_details (user_email, user_password, user_name, user_type, user_status) 
        VALUES (:user_email, :user_password, :user_name, :user_type, :user_status)
        ";    
        $statement = $pdo->prepare($query);
        $statement->execute([
            ':user_email' => $_POST["user_email"],
            ':user_password' => password_hash($_POST["user_password"], PASSWORD_DEFAULT),
            ':user_name' => $_POST["user_name"],
            ':user_type' => 'user',
            ':user_status' => 'active'
        ]);

        // Check if row was inserted
        if ($statement->rowCount() > 0) {
            echo 'New User Added';
        } else {
            echo 'Error adding user';
        }
    }

    if ($_POST['btn_action'] == 'fetch_single') {
        // Fetch single user data
        $query = "
        SELECT * FROM user_details WHERE user_id = :user_id
        ";
        $statement = $pdo->prepare($query);
        $statement->execute([':user_id' => $_POST["user_id"]]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode([
                'user_email' => $result['user_email'],
                'user_name' => $result['user_name']
            ]);
        }
    }

    if ($_POST['btn_action'] == 'Edit') {
        // Edit user details
        $query = "
        UPDATE user_details SET 
            user_name = :user_name, 
            user_email = :user_email
        ";
        // Only update password if it's provided
        if ($_POST['user_password'] != '') {
            $query .= ", user_password = :user_password";
        }
        $query .= " WHERE user_id = :user_id";

        // Prepare data for execution
        $params = [
            ':user_name' => $_POST["user_name"],
            ':user_email' => $_POST["user_email"],
            ':user_id' => $_POST["user_id"]
        ];

        if ($_POST['user_password'] != '') {
            $params[':user_password'] = password_hash($_POST["user_password"], PASSWORD_DEFAULT);
        }

        // Execute query
        $statement = $pdo->prepare($query);
        $statement->execute($params);

        // Check if row was updated
        if ($statement->rowCount() > 0) {
            echo 'User Details Edited';
        } else {
            echo 'No changes made';
        }
    }

    if ($_POST['btn_action'] == 'status') {
        // Change user status (active/inactive)
        $status = ($_POST['status'] == 'Active') ? 'Inactive' : 'Active';

        $query = "
        UPDATE user_details 
        SET user_status = :user_status 
        WHERE user_id = :user_id
        ";
        $statement = $pdo->prepare($query);
        $statement->execute([
            ':user_status' => $status,
            ':user_id' => $_POST["user_id"]
        ]);

        if ($statement->rowCount() > 0) {
            echo 'User Status changed to ' . $status;
        } else {
            echo 'Failed to change status';
        }
    }

    if ($_POST['btn_action'] == 'delete') {
        // Delete user
        $query = "
        DELETE FROM user_details 
        WHERE user_id = :user_id
        ";
        $statement = $pdo->prepare($query);
        $statement->execute([':user_id' => $_POST["user_id"]]);

        if ($statement->rowCount() > 0) {
            echo 'User deleted';
        } else {
            echo 'User deletion failed';
        }
    }
}

?>
