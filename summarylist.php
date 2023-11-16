<?php
    require_once 'authenticate.php';

    include 'includes/header.php';
    include 'includes/phpFunctionList.php';
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

                            <form action="summarylist.php" id="main" method="get">
                                <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">                               
                                    <select class="form-control" name="status" id="status">
                                        <option>STATUS</option>
                                        <option value="IS NOT NULL">APPROVED</option>
                                        <option value="IS NULL">WAITING FOR APPROVAL</option>
                                    </select>
                                </div>
                            </form>

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

                                    <!--Set Description for row status either Draft, Partial, Approved-->
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
                                        <td class="text-center font-weight-bold"><a href="summary.php?document_id=<?php echo $row['document_id'] ?>" target="_blank" rel="noopener noreferrer"><?= $row['document_id'] ?></a></td>

                                        <td class="text-center font-weight-bold">
                                            <?php $originalDate = $row['created_date'];
                                            $formattedDate = date("F j, Y, g:i A", strtotime($originalDate));
                                            echo $formattedDate; ?>
                                        </td>
                                        
                                        <td class="text-center font-weight-bold"><?= $row['username'] ?></td>
                                        
                                        <!--If table myc is not null show image-->
                                        <td class="text-center font-weight-bold">
                                            <?php if ($row['myc'] != NULL): ?>
                                                <img src="assets/img/check.png" alt="View Image">
                                            <?php endif; ?>
                                        </td>

                                        <!--If table hcc is not null show image-->
                                        <td class="text-center font-weight-bold">
                                            <?php if ($row['hcc'] != NULL): ?>
                                                <img src="assets/img/check.png" alt="View Image">
                                            <?php endif; ?>
                                        </td>

                                        <!--This part row status will be executed-->
                                        <td class="text-center font-weight-bold"><?= $status ?></td>

                                        <!--This part will determine if tables myc or hcc has a value it will show description. if both myc and hcc has a value the user is allowed to print the price summary-->
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
            </section>
        </div>
    
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/css/sweetalert2@11.css"></script>
        <script src="assets/js/jquery-3.6.0.min.js"></script>

        <script>

            $(document).ready(function () {
                $("button[name='cancel']").on("click", function () {
                    var cancel1 = $(this).val();
                    var id = $("input[name='id']").val();

                    $.ajax({
                        type: "POST",
                        url: "insert/cancel.php",
                        data: { id: id, doc_status: cancel1 },
                        success: function (response) {
                            if (response === 'success') {
                                Swal.fire({
                                    title: "Info",
                                    text: "PRICE SUMMARY HAS BEEN CANCELED",
                                    icon: "info"
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

                //status function
                $('#status').change(function () {
                    $('#main').submit();
                });
            });

        </script>

    <?php include("includes/footer.php"); ?>

</body>
</html>

