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
        if (isset($_POST["id"]) && isset($_POST["doc_status"])) {
            // Get the values from the POST data
            $id = $_POST["id"];
            $cancel1 = $_POST["doc_status"];

            // Prepare an SQL query to update the database
            $sql = "UPDATE [tuc].[For_Approval_list] SET doc_status = :cancel WHERE document_id = :id";
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':cancel', $cancel1, PDO::PARAM_STR);
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
