<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Database connection settings
        $dsn = "sqlsrv:Server=10.14.1.10,1433;Database=IPA;ConnectionPooling=0";
        // Create a PDO connection
        $pdo = new PDO($dsn);

        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Find the current maximum document_id in the database
        $sqlMaxDocumentId = "SELECT MAX(document_id) FROM [tuc].[Defbars_TransList_Summary]";
        $stmtMaxDocumentId = $pdo->query($sqlMaxDocumentId);
        $maxDocumentId = $stmtMaxDocumentId->fetchColumn();

        // Determine the next available document_id
        $document_id = ($maxDocumentId === false) ? 1000 : max($maxDocumentId, 999) + 1;

        // Iterate through the input fields and insert each row individually
        for ($index = 0; isset($_POST["ItemName$index"]); $index++) {
            // Retrieve data from the form
            $itemName = $_POST["ItemName$index"];
            $itemPercent = $_POST["ItemPercent$index"];
            $base10mm = $_POST["Base10mm$index"];
            $base12mm = $_POST["Base12mm$index"];
            $base16mm = $_POST["Base16mm$index"];
            $base20mm = $_POST["Base20mm$index"];
            $base25mm = $_POST["Base25mm$index"];

            // Prepare an SQL query to insert data into [tuc].[Defbars_TransList_Summary] table
            $sql = "INSERT INTO [tuc].[Defbars_TransList_Summary] (document_id, description, percentage, Base10mm, Base12mm, Base16mm, Base20mm, Base25mm) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            // Prepare and execute the query
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$document_id, $itemName, $itemPercent, $base10mm, $base12mm, $base16mm, $base20mm, $base25mm]);
        }

        // Insert data into the [tuc].[For_Approval_list] table
        $user = $_POST["user"];
        $sql1 = "INSERT INTO [tuc].[For_Approval_list] (username) VALUES (?)";
        $stmt1 = $pdo->prepare($sql1);
        $stmt1->execute([$user]);

        // Redirect after successful insertion
        echo 'success';
        exit();
    } catch (PDOException $e) {
        echo "SQL Error: " . $e->getMessage();
        exit; // Terminate the script
    } finally {
        // Close the database connection
        $pdo = null;
    }
}
?>
