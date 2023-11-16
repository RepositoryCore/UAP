<?php

    if (!isset($_SESSION['admin_id'])) {
        session_destroy(); 
        header('Location: ../');
        exit();

    } else {
        // Assign user session data
        $user_id = $_SESSION['admin_id'];
        $user_name = $_SESSION['name'];
        $user_role = $_SESSION['user_role'];
        $department = $_SESSION['department'];
    }

    if (isset($_GET['status'])) {
        $_SESSION['status'] = $_GET['status'];
    }
    $status = isset($_SESSION['status']) ? $_SESSION['status'] : 'IS NULL';
        // Prepare and execute SQL queries
        try {
            $sql = "SELECT * FROM [tuc].[For_Approval_list] WHERE doc_status $status ";
            $stmt = $obj_admin->db->prepare($sql);
            $stmt->execute();

            $summary = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo "Database Error: " . $e->getMessage();
        }

?>