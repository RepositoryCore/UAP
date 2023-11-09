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

        // Prepare and execute SQL queries
        try {
            $sql = "SELECT * FROM [tuc].[For_Approval_list] WHERE doc_status IS NULL ";
            $stmt = $obj_admin->db->prepare($sql);
            $stmt->execute();

            $summary = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo "Database Error: " . $e->getMessage();
        }

        include 'includes/sidebar.php';
?>

<div class="content-wrapper"><br/>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-header">
                            <h5 class="font-weight-bold" style="color:#39698a"><i class="fa-solid fa-list-check"></i> Summary list</h5>
                        </div>

                        <div class="card-body">

                            <table id="list" class="table-bordered dt-responsive nowrap" style="width:100%">
                                <thead class="font-weight-bold" style="background-color:#dad7d7">
                                    <tr>
                                        <th>Document No.</th>
                                        <th>Created Date</th>
                                        <th>User</th>
                                        <th>MYC</th>
                                        <th>HCC</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($summary as $row) { $status = 'Draft';
                                        if ($row['myc'] == NULL && $row['hcc'] == NULL) {
                                            $status = 'Draft';
                                        } elseif ($row['myc'] != NULL && $row['hcc'] == NULL) {
                                            $status = 'Partial Approve';
                                        } elseif ($row['hcc'] != NULL && $row['myc'] == NULL) {
                                            $status = 'Partial Approve';
                                        } elseif ($row['myc'] != NULL && $row['hcc'] != NULL) {
                                            $status = 'Approved';
                                        }
                                    ?>

                                    <tr>
                                        <td class="text-center font-weight-bold">
                                            <?php if ($row['myc'] !== null && $row['hcc'] !== null): ?>
                                                <span><?= $row['document_id'] ?></span>
                                            <?php else: ?>
                                                <a href="summary.php?document_id=<?php echo $row['document_id'] ?>" target="_blank" rel="noopener noreferrer"><?= $row['document_id'] ?></a>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center font-weight-bold"><?= $row['created_date'] ?></td>
                                        <td class="text-center font-weight-bold"><?= $row['username'] ?></td>
                                        <td class="text-center font-weight-bold">
                                            <?php if ($row['myc'] != NULL): ?>
                                                <img src="assets/img/check.png" alt="View Image">
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center font-weight-bold">
                                            <?php if ($row['hcc'] != NULL): ?>
                                                <img src="assets/img/check.png" alt="View Image">
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center font-weight-bold"><?= $status ?></td>
                                        <td class="text-center font-weight-bold">
                                            <?php if ($row['myc'] == NULL && $row['hcc'] == NULL): ?>
                                            <!-- Handle this case -->
                                            <?php elseif ($row['myc'] != NULL && $row['hcc'] == NULL): ?>
                                                <span class="badge badge-warning">Waiting for Approval</span>
                                                <form id="myForm">
                                                    <input type="hidden" name="id" value="<?php echo $row['document_id'] ?>" readonly>
                                                    <button type="button" class="btn" name="cancel" value="deny" style="background-color: transparent;">
                                                        <span class="badge badge-danger">Cancel</span>
                                                    </button>
                                                </form>
                                            <?php elseif ($row['myc'] == NULL && $row['hcc'] != NULL): ?>
                                                <span class="badge badge-warning">Waiting for Approval</span>
                                                <form id="myForm">
                                                    <input type="hidden" name="id" value="<?php echo $row['document_id'] ?>"/>
                                                    <button type="button" class="btn" name="cancel" value="deny" style="background-color: transparent;">
                                                        <span class="badge badge-danger">Cancel</span>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <a href="print.php?document_id=<?php echo $row['document_id'] ?>" target="_blank" rel="noopener noreferrer">
                                                    <img src="assets/img/print.png" alt="View Image">
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>    			  
                </div>
            </div>    			  
        </div>
    </section>
</div>
    

<script src="assets/js/jquery.min.js"></script>
<script src="assets/css/sweetalert2@11.css"></script>
<script src="assets/js/jquery-3.6.0.min.js"></script>

<script>
    /*setInterval(function(){
        $("#list").load(location.href + " #list>*","");
    }, 5000); */ // refresh every 5 seconds

    $(document).ready(function () {
            $("button[name='cancel']").on("click", function () {
                var cancel = $(this).val();
                var id = $("input[name='id']").val();

                $.ajax({
                    type: "POST",
                    url: "insert/cancel.php",
                    data: { id: id, cancel: cancel },
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
</script>

<?php include("includes/footer.php"); ?>

</body>
</html>

