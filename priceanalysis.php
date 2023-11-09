<?php
    require_once 'authenticate.php';
    include 'includes/header.php';

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
            $sql = "SELECT db.BasePrice, db.ItemCode FROM [tuc].[Defbars] db WHERE db.ItemCode = '$ItemCode' AND db.ItemLocation = 'CEBU'";
            
        } elseif ($ItemLocation === 'MANILA') {
            $sql = "SELECT db.BasePrice, db.ItemCode FROM [tuc].[Defbars] db WHERE db.ItemCode = '$ItemCode' AND db.ItemLocation = 'MANILA'";
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

    include 'includes/sidebar.php';

?>

<div class="content-wrapper"><br/>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-7">
                    <form action="DAME.php" id="main" method="get">

                        <div class="d-flex justify-content-between">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-secondary" type="button"><i class="fa-solid fa-peseta-sign"></i></button>
                                </div>
                                <input type="text" id="" name="" class="form-control col-4 " placeholder="" value="<?php echo $BasePriceOrig; ?>">
                            </div>

                            <div class="input-group mb-3">
                                <select name="ItemCode" id="ItemCode" class="custom-select">
                                    <option value="<?php echo $ItemCode; ?>"><?php echo $ItemCode; ?></option>
                                    <?php 
                                        $sql = "SELECT ItemName, BasePrice, ItemCode, ItemLocation FROM [tuc].[Defbars]";
                                        $info = $obj_admin->manage_all_info($sql);   
                                    ?>

                                    <?php while($row = $info->fetch(PDO::FETCH_ASSOC)){ ?>
                                        <option value="<?php echo $row['ItemCode']; ?>"><?php echo $row['ItemCode']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

 
                        </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="font-weight-bold" style="color:#39698a"><i class="fa-solid fa-tags"></i> Base Price</h5>
                        </div>

                        <div class="card-body">
                            <table id="example" class="table table-sm table-bordered-color-dark dt-responsive nowrap" style="width:100%"> <!--table-hover-->
                                <thead class="font-weight-bold" style="background-color:#dad7d7">
                                    <tr>
                                        <th>(+/-)</th>
                                        <th>10mm</th>
                                        <th>12mm</th>
                                        <th>16mm</th>
                                        <th>20mm</th>
                                        <th>25mm</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php foreach ($priceGrid as $row) { ?>

                                    <tr>
                                        <td class="text-center font-weight-bold" style="background-color: <?= $row['PointPrice'] ? '#39698a'  : 'transparent' ?>; color: <?= $row['PointPrice'] ? '#fff'  : '' ?>"><?= $row['discount'] ?></td>
                                        <td class="text-center font-weight-bold" style="background-color: <?= $row['PointPrice'] ? '#39698a'  : 'transparent' ?>; color: <?= $row['PointPrice'] ? '#fff'  : '' ?>"><?= $row['10mm'] ?></td>
                                        <td class="text-center font-weight-bold" style="background-color: <?= $row['PointPrice'] ? '#39698a'  : 'transparent' ?>; color: <?= $row['PointPrice'] ? '#fff'  : '' ?>"><?= $row['12mm'] ?></td>
                                        <td class="text-center font-weight-bold" style="background-color: <?= $row['PointPrice'] ? '#39698a'  : 'transparent' ?>; color: <?= $row['PointPrice'] ? '#fff'  : '' ?>"><?= $row['16mm'] ?></td>
                                        <td class="text-center font-weight-bold" style="background-color: <?= $row['PointPrice'] ? '#39698a'  : 'transparent' ?>; color: <?= $row['PointPrice'] ? '#fff'  : '' ?>"><?= $row['20mm'] ?></td>
                                        <td class="text-center font-weight-bold" style="background-color: <?= $row['PointPrice'] ? '#39698a'  : 'transparent' ?>; color: <?= $row['PointPrice'] ? '#fff'  : '' ?>"><?= $row['25mm'] ?></td>
                                        <td class="text-center font-weight-bold" style="background-color: <?= $row['PointPrice'] ? '#39698a'  : 'transparent' ?>; color: <?= $row['PointPrice'] ? '#fff'  : '' ?>"><?= $row['PointPrice'] ?></td>
                                    </tr> 

                                    <?php }?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                            
                <div class="col-sm-5">

                    <div class="card">
                        <div class="card-header">
                            <h5 class="font-weight-bold" style="color:#39698a"><i class="fa-solid fa-paste"></i> Item Summary</h5>
                        </div>

                        <div class="card-body">
                            <table id="PriceSummary" class="table table-sm table-bordered-color-dark dt-responsive nowrap">                                    
                                <thead style="background-color:#dad7d7">
                                    <tr>
                                        <th>Description</th>
                                        <th>Percentage</th>
                                        <th>10mm</th>
                                        <th>12mm</th>
                                        <th>16mm</th>
                                        <th>20mm</th>
                                        <th>25mm</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php foreach ($priceComputed as $row) { ?>

                                    <tr>
                                        <td class="font-weight-bold" style="background-color: <?= $row['ItemPercent'] ? '#39698a'  : 'transparent' ?>; color: <?= $row['ItemPercent'] ? '#fff'  : '' ?>"><?= $row['ItemName'] ?></td>
                                        <td class="text-center font-weight-bold" style="background-color: <?= $row['ItemPercent'] ? '#39698a'  : 'transparent' ?>; color: <?= $row['ItemPercent'] ? '#fff'  : '' ?>"><?= $row['ItemPercent'] ?></td>
                                        <td class="text-center font-weight-bold" style="background-color: <?= $row['ItemPercent'] ? '#39698a'  : 'transparent' ?>; color: <?= $row['ItemPercent'] ? '#fff'  : '' ?>"><?= $row['Base10mm'] ?></td>
                                        <td class="text-center font-weight-bold" style="background-color: <?= $row['ItemPercent'] ? '#39698a'  : 'transparent' ?>; color: <?= $row['ItemPercent'] ? '#fff'  : '' ?>"><?= $row['Base12mm'] ?></td>
                                        <td class="text-center font-weight-bold" style="background-color: <?= $row['ItemPercent'] ? '#39698a'  : 'transparent' ?>; color: <?= $row['ItemPercent'] ? '#fff'  : '' ?>"><?= $row['Base16mm'] ?></td>
                                        <td class="text-center font-weight-bold" style="background-color: <?= $row['ItemPercent'] ? '#39698a'  : 'transparent' ?>; color: <?= $row['ItemPercent'] ? '#fff'  : '' ?>"><?= $row['Base20mm'] ?></td>
                                        <td class="text-center font-weight-bold" style="background-color: <?= $row['ItemPercent'] ? '#39698a'  : 'transparent' ?>; color: <?= $row['ItemPercent'] ? '#fff'  : '' ?>"><?= $row['Base25mm'] ?></td>
                                    </tr> 

                                    <?php }?>
                                </tbody>
                            </table>      
                        </div>
                    </div>
                    <br>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="font-weight-bold" style="color:red"><i class="fa-solid fa-dolly"></i> ITEM INFORMATION</h5>
                        </div>

                            <div class="card-body">
                                <select name="ItemLocation" id="ItemLocation" class="custom-select col-4 font-weight-bold">
                                    <?php foreach (['CEBU', 'MANILA' ] as $ItemLocation) : ?>
                                        <option <?php if (isset($_SESSION['ItemLocation']) && $_SESSION['ItemLocation'] == $ItemLocation) echo 'selected'; ?>
                                            value="<?= $ItemLocation; ?>"> <?= $ItemLocation; ?>
                                        </option>   
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        <br/>
                        <br/>
                    
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="table-responsive">
							<table class="table table-sm table-bordered " id="invoiceItem"> <!--table-hover-->	
								<tr style="background-color:#dad7d7">
									<th width="1%"></th>
									<th width="18%">Stock Code</th>
									<th width="15%">Truck</th>
									<th width="15%">Bundle</th>
									<th width="15%">Basic Price</th>    
                                    <th width="1%"></th>
									<th width="12%">Expenses:</th>
								<tr class="font-weight-bold">									  
									<td>10mm</td>
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo $itemInfo[0]['Code10mm']; ?>" readonly></td>	
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo $itemInfo[0]['Truck10mm']; ?>" readonly></td>			
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo $itemInfo[0]['Bandle10mm']; ?>" readonly></td>		
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo number_format($itemInfo[0]['Base10mm'], 2); ?>" readonly></td>
                                    <td>Freight</td>
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo $itemInfo[0]['Freight']; ?>" readonly></td>
                                   
								<tr class="font-weight-bold">									  
									<td>12mm</td>
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo $itemInfo[0]['Code12mm']; ?>" readonly></td>	
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo $itemInfo[0]['Truck12mm']; ?>" readonly></td>			
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo $itemInfo[0]['Bandle12mm']; ?>" readonly></td>			
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo number_format($itemInfo[0]['Base12mm'], 2); ?>" readonly></td>
                                    <td>Handling</td>
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo $itemInfo[0]['Handling']; ?>" readonly></td>
                                    
								<tr class="font-weight-bold">									  
									<td>16mm</td>
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo $itemInfo[0]['Code16mm']; ?>" readonly></td>	
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo $itemInfo[0]['Truck16mm']; ?>" readonly></td>			
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo $itemInfo[0]['Bandle16mm']; ?>" readonly></td>			
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo number_format($itemInfo[0]['Base16mm'], 2); ?>" readonly></td>
                                    <td>ForkLift</td>
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo $itemInfo[0]['ForkLift']; ?>" readonly></td>

                                    
								<tr class="font-weight-bold">									  
									<td>20mm</td>
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo $itemInfo[0]['Code20mm']; ?>" readonly></td>	
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo $itemInfo[0]['Truck20mm']; ?>" readonly></td>			
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo $itemInfo[0]['Bandle20mm']; ?>" readonly></td>			
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo number_format($itemInfo[0]['Base20mm'], 2); ?>" readonly></td>
                                    <td>Checker</td>
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo $itemInfo[0]['Checker']; ?>" readonly></td>

								<tr class="font-weight-bold"    >									  
									<td>25mm</td>
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo $itemInfo[0]['Code25mm']; ?>" readonly></td>	
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo $itemInfo[0]['Truck25mm']; ?>" readonly></td>		
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo $itemInfo[0]['Bandle25mm']; ?>" readonly></td>		
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo number_format($itemInfo[0]['Base25mm'], 2); ?>" readonly></td>
                                    <td>Others</td>
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo $itemInfo[0]['Others']; ?>" readonly></td>
							</table>
                            </div>              
						</div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="table-responsive">
							<table class="table table-sm table-bordered table-hover" id="invoiceItem">	
								<tr class="font-weight-bold">
									<th width="9%">TAX:</th>
								<tr class="font-weight-bold">									  
									<td>VAT</td>
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo number_format($taxSetup[0]['Vat'], 2); ?>" readonly></td>	
                                    <td>IncomeTax</td>
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo number_format($taxSetup[0]['IncomeTax'], 2); ?>" readonly></td>
                                    <td>CityTax</td>
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo number_format($taxSetup[0]['CityTax'], 2); ?>" readonly></td>	
                                    <td>IMU</td>
									<td><input type="text" name="" id="" class="form-control form-control-sm" autocomplete="off" value="<?php echo number_format($taxSetup[0]['ItemMarkUp'], 2); ?>" readonly></td>
							</table>
                        </div>                          
					</div>
                </div>
            </div>
            <br/>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 d-flex justify-content-end">
                <form id="myForm">
                    <?php foreach ($priceComputed as $index => $row) : ?>
                        <input type="hidden" name="ItemName<?= $index ?>" value="<?= $row['ItemName'] ?>" readonly>
                        <input type="hidden" name="ItemPercent<?= $index ?>" value="<?= $row['ItemPercent'] ?>" readonly>
                        <input type="hidden" name="Base10mm<?= $index ?>" value="<?= $row['Base10mm'] ?>" readonly>
                        <input type="hidden" name="Base12mm<?= $index ?>" value="<?= $row['Base12mm'] ?>" readonly>
                        <input type="hidden" name="Base16mm<?= $index ?>" value="<?= $row['Base16mm'] ?>" readonly>
                        <input type="hidden" name="Base20mm<?= $index ?>" value="<?= $row['Base20mm'] ?>" readonly>
                        <input type="hidden" name="Base25mm<?= $index ?>" value="<?= $row['Base25mm'] ?>" readonly>
                    <?php endforeach; ?>

                    <input type="hidden" name="user" value="<?= $user_name ?>" readonly>
                    <button type="submit" class="btn" id="submitForm" style="background-color: #39698a; color: #fff;"><i class="fas fa-hdd"></i> Save</button>
                </form>
            </div>
		</div>				  
    </div>
</section>
</div>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>  
    <script src="assets/css/sweetalert2@11.css"></script>

    <script> 

        $(document).ready(function () {
            $('#ItemCode').change(function () {
                $('#main').submit();
            });

            $('#ItemLocation').change(function () {
                $('#main').submit();
            });

            $('#example tbody tr',).click(function() {
                $(this).css('background-color', '#A6FF96').siblings().css('background-color', '');
            });

            $('#PriceSummary tbody tr',).click(function() {
                $(this).css('background-color', '#A6FF96').siblings().css('background-color', '');
            });
        });
    
        $(document).ready(function () {
            $("#submitForm").on("click", function (e) {
            e.preventDefault(); // Prevent the default form submission behavior

            var formData = $("#myForm").serialize();

            $.ajax({
                type: "POST",
                url: "insert/summary.php",
                data: formData,
                success: function (response) {
                    if (response === 'success') {
                        Swal.fire({
                            title: "Price Summary Generated",
                            text: "Price Summary is subject for Approval",
                            icon: "success"
                        }).then(function() {
                            window.location = "summarylist.php"; // Redirect to another page
                        });

                    } else {
                        Swal.fire({
                            title: "Error",
                            text: "Something went wrong!",
                            icon: "error"
                        }).then(function() {
                            window.location = "priceanalysis.php"; // Redirect to another page
                        });
                    }
                },
                error: function () {
                    alert('AJAX request failed');
                }
            });
        });
    });
</script>
<?php include("includes/footer.php"); ?>
</body>

</html>

