<body class="hold-transition sidebar-mini layout-fixed">
    
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a role="button" data-widget="pushmenu" href="#" class="nav-link">
                        <i class="fa-solid fa-bars fa-xl"></i>
                    </a>
                </li>

                <li class="nav-item d-none d-sm-inline-block">
                    <a href="" class="nav-link"></a>
                </li>

                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link"></a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">

                <?php if($user_id == 4 || $user_id == 59) { ?>
                    <li class="nav-item">
                        <span class="nav-link text-black font-weight-bold" style="font-size: 14px">&#128105; <?php echo $user_name ?></span>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <span class="nav-link text-black font-weight-bold" style="font-size: 14px">&#128104; <?php echo $user_name ?></span>
                    </li>
                <?php } ?>    

                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fa-solid fa-expand fa-xl"></i>
                    </a>
                </li>
            </ul>
        </nav>

        <aside class="main-sidebar elevation-4 sidebar-dark-white" style="background-color: #39698a;">

            <a href="uap.php" class="brand-link">
                <img src="assets/img/UAP.png" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-bold text-white" style="font-size: 15px;">Item Pricing Analysis</span>
            </a>

            <div class="sidebar">
                <nav class="mt-3">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-header text-white">PRICING</li>
                            <!--li class="nav-item">
                                <a href="" class="nav-link text-dark font-weight-bold">
                                    <i class="nav-icon fa-solid fa-tag"></i><del> Cement Pricing</del>
                                </a>
                            </!li-->
                        <?php if ($user_id == 1 || $user_id == 4) { ?>

                            <li class="nav-item">
                                <a href="priceanalysis.php" class="nav-link text-white font-weight-bold">
                                    <i class="nav-icon fa-solid fa-tag"></i><p>  Deform Bar Pricing</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="summarylist.php" class="nav-link text-white font-weight-bold">
                                    <i class="nav-icon fa-solid fa-list-check"></i><p>Deform Bar Price List</p>
                                </a>
                            </li>

                        <?php } elseif ($user_id == 58) { ?>

                            <li class="nav-item">
                                <a href="summarylist.php" class="nav-link text-white font-weight-bold">
                                    <i class="nav-icon fa-solid fa-list-check"></i><p>Deform Bar Price List</p>
                                </a>
                            </li>

                        <?php } elseif ($user_id == 59) { ?>

                            <li class="nav-item">
                                <a href="summarylist.php" class="nav-link text-white font-weight-bold">
                                    <i class="nav-icon fa-solid fa-list-check"></i><p>Deform Bar Price List</p>
                                </a>
                            </li>
                            
                        <?php } else { ?>
                            <!---Display else--->
                        <?php } ?>
                    </ul>

                    <div class="foot">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            <li class="nav-item">
                                <a href="?logout=logout" onclick="window.close();" class="nav-link text-white font-weight-bold">
                                    <img src="assets/img/UAP.png" class="nav-icon fa-solid fa-right-from-bracket"></img>
                                    <p>Logout<i class="nav-icon fa-solid fa-arrow-right-from-bracket right"></i></p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </aside>
    </div>




<!--This is for sidebar logout bottom css and Get current datetime in JavaScript-->
<style>
    .foot {
        position:absolute;
        bottom:0;
        left:0;
        width:100%;
    }
</style>
    
<script>
    const currentDate = moment().format('MMMM D YYYY, dddd');
    document.getElementById("dateYana").textContent = currentDate;
</script>