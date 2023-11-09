<?php 
    require_once 'authenticate.php';

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
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Ultrasteel Analysis Portal</title> 

    <!-- Favicons -->
    <link rel="icon" type="" href="assets/img/UAP_TL.png">
    <!--<link rel="icon" type="image/x-icon" href="http://example.com/favicon.ico" />-->

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>  
    <section id="hero" class="d-flex align-items-center" style="padding-top: 0px;">
        <div class="container position-relative" data-aos="fade-up" data-aos-delay="100">

            <div class="row justify-content-center">
                <div class="col-xl-9 col-lg-9 text-center">
                    <a><img src="assets/img/UAP.png" alt="uap_logo" style="width: 30%; padding-bottom: 20px;"></a>
                    <h1>Ultrasteel Analysis Portal</h1>
                    <h2> | IPA |</h2>         
                </div>
            </div>

            <?php if ($user_id == 1) { ?>
                <div class="row icon-boxes justify-content-center">
                    <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0" data-aos="zoom-in" data-aos-delay="200">
                        <div class="icon-box">
                            <div class="icon"><i class="ri-stack-line"></i></div>
                            <h4 class="title"><a href="priceanalysis.php">IPA<p style="font-size: small; padding-top: 5px;">Item Pricing Analysis</p></a></h4>
                            <p class="description">Study of product prices in the market to compare and improve the profitability of your business.</p>
                        </div>
                    </div>

            <?php } elseif ($user_id == 59) { ?>
                <div class="row icon-boxes justify-content-center">
                    <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0" data-aos="zoom-in" data-aos-delay="200">
                        <div class="icon-box">
                            <div class="icon"><i class="ri-stack-line"></i></div>
                            <h4 class="title"><a href="summarylist.php">IPA<p style="font-size: small; padding-top: 5px;">Item Pricing Analysis</p></a></h4>
                            <p class="description">Study of product prices in the market to compare and improve the profitability of your business.</p>
                        </div>
                    </div>

            <?php } elseif ($user_id == 58) { ?>
                <div class="row icon-boxes justify-content-center">
                    <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0" data-aos="zoom-in" data-aos-delay="200">
                        <div class="icon-box">
                            <div class="icon"><i class="ri-stack-line"></i></div>
                            <h4 class="title"><a href="summarylist.php">IPA<p style="font-size: small; padding-top: 5px;">Item Pricing Analysis</p></a></h4>
                            <p class="description">Study of product prices in the market to compare and improve the profitability of your business.</p>
                        </div>
                    </div>
        
            <?php } else { ?>
                <!---Display else--->
            <?php } ?>
        </div>
  </section>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
</body>
</html>