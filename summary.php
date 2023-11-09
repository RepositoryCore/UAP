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

    $document_id = $_GET['document_id'];

    $sql = "SELECT * FROM [tuc].[Defbars_TransList_Summary] WHERE document_id = '$document_id'";
    $stmt = $obj_admin->db->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <br/>
    
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header" style="color:#39698a">
                            <strong class="text-left">DOCUMENT NO. <?php echo $document_id; ?></strong>

                            <div class="float-right">
                                <label><strong>Created Date:</strong> <?php echo date('l, F j, Y'); ?></label>
                            </div>
                        </div>
                            
                        <div class="card-body">
                            <table id="summary" class="table table-sm table-bordered-color-dark dt-responsive nowrap">
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

                                    <?php foreach ($result as $index => $row) : ?>

                                    <tr>
                                        <td class="font-weight-bold" name="ItemName<?= $index ?>" style="background-color: <?= $row['percentage'] ? '#39698a' : 'transparent' ?>; color: <?= $row['percentage'] ? '#fff' : '' ?>"><?= $row['description'] ?></td>
                                        <td class="text-center font-weight-bold" name="ItemPercent<?= $index ?>" style="background-color: <?= $row['percentage'] ? '#39698a' : 'transparent' ?>; color: <?= $row['percentage'] ? '#fff' : '' ?>"><?= $row['percentage'] ?></td>
                                        <td class="text-center font-weight-bold" name="Base10mm<?= $index ?>" style="background-color: <?= $row['percentage'] ? '#39698a' : 'transparent' ?>; color: <?= $row['percentage'] ? '#fff' : '' ?>"><?= $row['Base10mm'] ?></td>
                                        <td class="text-center font-weight-bold" name="Base12mm<?= $index ?>" style="background-color: <?= $row['percentage'] ? '#39698a' : 'transparent' ?>; color: <?= $row['percentage'] ? '#fff' : '' ?>"><?= $row['Base12mm'] ?></td>
                                        <td class="text-center font-weight-bold" name="Base16mm<?= $index ?>" style="background-color: <?= $row['percentage'] ? '#39698a' : 'transparent' ?>; color: <?= $row['percentage'] ? '#fff' : '' ?>"><?= $row['Base16mm'] ?></td>
                                        <td class="text-center font-weight-bold" name="Base20mm<?= $index ?>" style="background-color: <?= $row['percentage'] ? '#39698a' : 'transparent' ?>; color: <?= $row['percentage'] ? '#fff' : '' ?>"><?= $row['Base20mm'] ?></td>
                                        <td class="text-center font-weight-bold" name="Base25mm<?= $index ?>" style="background-color: <?= $row['percentage'] ? '#39698a' : 'transparent' ?>; color: <?= $row['percentage'] ? '#fff' : '' ?>"><?= $row['Base25mm'] ?></td>
                                    </tr>
                            
                                    <?php endforeach; ?>

                                </tbody>
                            </table>

                            <form id="myForm">
                                <input type="hidden" name="id" value="<?php echo $document_id; ?>" readonly>

                                <div class="float-right">
                                    <?php if ($user_id == 59) { ?>

                                        <button type="button" class="btn" name="myc" value="approved" style="background-color: #39698a; color: #fff;">APPROVE</button>&nbsp;

                                    <?php } elseif ($user_id == 58) { ?>

                                        <button type="button" class="btn" name="hcc" value="approved" style="background-color: #39698a; color: #fff;">APPROVE</button>&nbsp;

                                    <?php }  ?>

                                    <button type="button" class="btn" name="deny" value="deny" style="background-color: #39698a; color: #fff;">DENY</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="assets/css/sweetalert2@11.css"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>

    <script>

        $(document).ready(function () {
            $("button[name='myc']").on("click", function () {
                var approval1 = $(this).val();
                var id = $("input[name='id']").val();

                $.ajax({
                    type: "POST",
                    url: "insert/TransList.php",
                    data: { id: id, myc: approval1 },
                    success: function (response) {
                        if (response === 'success') {
                            Swal.fire({
                                title: "Success",
                                text: "Computed price has been Approved",
                                icon: "success"
                            }).then(function() {
                                window.location = "summarylist.php"; // Redirect to another page
                            });

                        } else {
                            Swal.fire({
                                title: "Error",
                                text: "Something went Wrong!",
                                icon: "error"
                            });
                        }
                    },
                    error: function () {
                        alert('AJAX request failed');
                    }
                });
            });
        });


        $(document).ready(function () {
            $("button[name='hcc']").on("click", function () {
                var approval2 = $(this).val();
                var id = $("input[name='id']").val();

                $.ajax({
                    type: "POST",
                    url: "insert/TransList.php",
                    data: { id: id, hcc: approval2 },
                    success: function (response) {
                        if (response === 'success') {
                            Swal.fire({
                                title: "Success",
                                text: "Computed price has been Approved",
                                icon: "success"
                            }).then(function() {
                                window.location = "summarylist.php"; // Redirect to another page
                            });

                        } else {
                            Swal.fire({
                                title: "Error",
                                text: "Something went Wrong!",
                                icon: "error"
                            });
                        }
                    },
                    error: function () {
                        alert('AJAX request failed');
                    }
                });
            });
        });

        $(document).ready(function () {
            $("button[name='deny']").on("click", function () {
                var deny = $(this).val();
                var id = $("input[name='id']").val();

                $.ajax({
                    type: "POST",
                    url: "insert/TransList.php",
                    data: { id: id, deny: deny },
                    success: function (response) {
                        if (response === 'success') {
                            Swal.fire({
                                title: "Success",
                                text: "Price summary has been Denied",
                                icon: "success"
                            }).then(function() {
                                window.location = "summarylist.php"; // Redirect to another page
                            });

                        } else {
                            Swal.fire({
                                title: "Error",
                                text: "Something went Wrong!",
                                icon: "error"
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

