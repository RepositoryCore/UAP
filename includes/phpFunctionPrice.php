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
    
    if (isset($_GET['BasePriceOrig'])) {
        $_SESSION['BasePriceOrig'] = $_GET['BasePriceOrig'];
    }

    if (isset($_GET['ItemCode'])) {
        $_SESSION['ItemCode'] = $_GET['ItemCode'];
    }

    if (isset($_GET['ItemLocation'])) {
        $_SESSION['ItemLocation'] = $_GET['ItemLocation'];
    }

    // Set default values for session variables
    $BasePriceOrig = isset($_SESSION['BasePriceOrig']) ? $_SESSION['BasePriceOrig'] : -17.0;
    $ItemCode = isset($_SESSION['ItemCode']) ? $_SESSION['ItemCode'] : 'G33X6M';
    $ItemLocation = isset($_SESSION['ItemLocation']) ? $_SESSION['ItemLocation'] : 'CEBU';

        // Check ItemLocation
        if ($ItemLocation === 'CEBU') {
            $sql = "SELECT db.BasePrice, db.ItemCode FROM [tuc].[Defbars] db WHERE db.ItemCode = '$ItemCode' AND db.BasePrice = '$BasePriceOrig' AND db.ItemLocation = 'CEBU'";
            
        } elseif ($ItemLocation === 'MANILA') {
            $sql = "SELECT db.BasePrice, db.ItemCode FROM [tuc].[Defbars] db WHERE db.ItemCode = '$ItemCode' AND db.BasePrice = '$BasePriceOrig' AND db.ItemLocation = 'MANILA'";
        }

        $result = $obj_admin->manage_all_info($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $_SESSION['BasePriceOrig'] = $row['BasePrice'];
        $BasePriceOrig = $row['BasePrice'];
    
        $_SESSION['ItemCode'] = $row['ItemCode'];
        $ItemCode = $row['ItemCode'];// Update the variable
    }


    // Prepare and execute SQL queries
    try {
        $sql = "EXEC [tuc].[Revised2_SP] @parDiscount = :BasePriceOrig, @parITemCode = :ItemCode, @parItemLocation = :ItemLocation";
        $stmt = $obj_admin->db->prepare($sql);
        $stmt->bindParam(':BasePriceOrig', $BasePriceOrig, PDO::PARAM_INT);
        $stmt->bindParam(':ItemCode', $ItemCode, PDO::PARAM_STR);
        $stmt->bindParam(':ItemLocation', $ItemLocation, PDO::PARAM_STR);
        $stmt->execute();
    
        // Fetch the results for the first query (Item header info)
        $itemHeaderInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->nextRowset();
    
        // Fetch the results for the second query (Price grid)
        $priceGrid = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->nextRowset();

        $priceComputed = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->nextRowset();
    
        // Fetch the results for the third query (Item Information)
        $itemInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->nextRowset();
    
        // Fetch the results for the fourth query (Tax Setup)
        $taxSetup = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
    }

?>