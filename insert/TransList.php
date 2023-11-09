<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Database connection settings
        $dsn = "sqlsrv:Server=10.14.1.10,1433;Database=IPA;ConnectionPooling=0";
        // Create a PDO connection
        $pdo = new PDO($dsn);

        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if the required POST data exists
        if (isset($_POST["id"]) && isset($_POST["myc"])) {
            // Get the values from the POST data
            $id = $_POST["id"];
            $approval1 = $_POST["myc"];

            // Prepare an SQL query to update the database
            $sql = "UPDATE [tuc].[For_Approval_list] SET myc = :approval1 WHERE document_id = :id";
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':approval1', $approval1, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Execute the query
            $stmt->execute();

            // Check if the query was successful
            if ($stmt->rowCount() > 0) {
                echo 'success';
            } else {
                echo 'No rows updated';
            }

        } elseif (isset($_POST["id"]) && isset($_POST["hcc"])) {
            // Get the values from the POST data
            $id = $_POST["id"];
            $approval2 = $_POST["hcc"];

            // Prepare an SQL query to update the database
            $sql = "UPDATE [tuc].[For_Approval_list] SET hcc = :approval2 WHERE document_id = :id";
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':approval2', $approval2, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Execute the query
            $stmt->execute();

            // Check if the query was successful
            if ($stmt->rowCount() > 0) {
                echo 'success';
            } else {
                echo 'No rows updated';
            }


        }  elseif (isset($_POST["id"]) && isset($_POST["deny"])) {
            // Get the values from the POST data
            $id = $_POST["id"];
            $deny = $_POST["deny"];

            // Prepare an SQL query to update the database
            $sql = "UPDATE [tuc].[For_Approval_list] SET doc_status = :deny WHERE document_id = :id";
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':deny', $deny, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Execute the query
            $stmt->execute();

            // Check if the query was successful
            if ($stmt->rowCount() > 0) {
                echo 'success';
            } else {
                echo 'No rows updated';
            }


        } else {
            echo 'Missing POST data';
        }
    } catch (PDOException $e) {
        echo "SQL Error: " . $e->getMessage();
    } finally {
        // Close the database connection
        $pdo = null;
    }
}
?>
