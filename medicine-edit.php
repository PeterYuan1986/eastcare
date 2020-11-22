<?php
require_once 'header.php';
$pageoffice = 'all';           //设置页面属性 office ：  nc, sh, all
$pagelevel = 2;       // //设置页面等级 0： 只有admin可以访问； 1：库存系统用户； 2:代发用户
check_session_expiration();
$user = $_SESSION['user_info']['userid'];
$fn = $_SESSION['user_info']['firstname'];
$ln = $_SESSION['user_info']['lastname'];
$useroffice = $_SESSION['user_info']['office'];
$userlevel = $_SESSION['user_info']['level'];           //userlevel  0: admin; else;
$cmpid = $_SESSION['user_info']['cmpid'];
$childid = $_SESSION['user_info']['childid'];
check_access($useroffice, $userlevel, $pageoffice, $pagelevel);
$sql = "SELECT MAX(`medicine_id`) FROM `medicine`";
$result = mysqli_query($conn, $sql);
$nextid = mysqli_fetch_array($result)[0] + 1;

$sql = "SELECT * FROM medicine";
$result = mysqli_query($conn, $sql);
while ($arr = mysqli_fetch_array($result)) {
    $data[] = $arr;
}



if (isset($_POST["save"])) {
 
    $imedicineinfo = @$_POST["imedicineinfo"];
    $inote = @$_POST["inote"];
 
    updatestr();
    $sql = "INSERT INTO `medicine`(`medicine_info`, `medicine_note`) VALUES('" . $imedicineinfo . "','" . $inote . "')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        print '<script>alert("Add Successful!")</script>';
        print '<script> location.replace("medicine-list.php"); </script>';
    } else {
        print '<script>alert("Add Failed! Please check and try again!")</script>';
    }
}

if (isset($_POST["update"])) {

    $imedicineinfo = @$_POST["imedicineinfo"];
    $inote = @$_POST["inote"];

    updatestr();
    $sql = "UPDATE `medicine` SET `medicine_note`='" . $inote . "', `medicine_info`='" . $imedicineinfo . "' WHERE medicine_id='" . $_SESSION['uptademedicineid'] . "'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        print '<script>alert("Edit Successful!")</script>';
        unset($_SESSION['uptademedicineid']);
        print '<script> location.replace("medicine-list.php"); </script>';
    } else {
        print '<script>alert("Edit Failed! Please check and try again!")</script>';
    }
}

if (isset($_POST["delete"])) {
    if ($medicine_id != $nextid) {
        $sql = "DELETE from medicine  WHERE medicine='" . $_SESSION['uptademedicineid'] . "'";
        $result = mysqli_query($conn, $sql);
        unset($_SESSION['uptademedicineid']);
        print '<script>alert("Delete Successful!")</script>';
        header('Location:' . $_SERVER["PHP_SELF"]);
    } else {
        print '<script>alert("Delete Unsuccessful!Please check the Disease ID")</script>';
    }
}

function updatestr() {
    @$isku = strexchange($isku);
    @$inote = strexchange($inote);
}

$datanote = check_note($cmpid);
$totalnotes = sizeof($datanote);



if (isset($_SESSION['editsku'])) {
    $medicine_id = $_SESSION['editsku'];
    $_SESSION['uptademedicineid'] = $medicine_id;
    $sql = "SELECT * FROM `medicine` WHERE medicine_id ='" . $medicine_id . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);    
    $medicine_info = $row['medicine_info'];
    $medicine_note = $row['medicine_note'];
    unset($_SESSION['editsku']);

} else {
    if (isset($_REQUEST['search'])) {
        $sql = "SELECT *  FROM `medicine` WHERE medicine_id ='" . $_POST['searcheditorder'] . "'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);
        if ($row > 0) {
            $medicine_id = $_POST['searcheditorder'];
            $_SESSION['uptademedicineid'] = $medicine_id;
            $medicine_note = $row['medicine_note'];
            $medicine_info = $row['medicine_info'];
        } else {
            $medicine_id = $nextid;
            $medicine_info = 0;
            $medicine_note = 0;
            print '<script>alert("This medicine doesn not exist!")</script>';
        }
    } else {
        $medicine_id = $nextid;
        $medicine_info = 0;
        $medicine_note = 0;
    }
}

function checkinput($isku) {
    if (isEmpty($isku)) {
        print '<script>alert("The name should not be empty!")</script>';
        return FALSE;
    }
    return TRUE;
}

require_once 'sidebar.php';
?>

<!-- Mobile Menu end -->
<div class="breadcome-area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="breadcome-list">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="breadcomb-wp">
                                <div class="breadcomb-icon">
                                    <i class="icon nalika-edit"></i>
                                </div>
                                <div class="breadcomb-ctn">
                                    <h2>Medicine Edit</h2>
                                    <p>Welcome to EastCare Admin System <span class="bread-ntd"></span></p>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Single pro tab start-->
<div class="single-product-tab-area mg-b-30">
    <!-- Single pro tab review Start-->
    <div class="single-pro-review-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div>
                        <div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
                            <div class="header-top-menu tabl-d-n">
                                <div class="breadcome-heading">
                                    <form method="post" role="search" class="">


                                        <div style="width:200px;float:left;"><input name="searcheditorder" type="text" placeholder="Search Disease ID" value="<?php
if (isset($_SESSION['orderidserchtext'])) {
    print $_SESSION['orderidserchtext'];
}
?>" ></div>
                                        <div style="color:#fff;width:000px;float:left;">
                                            <button name="search" type="submit" value="search" class="pd-setting-ed"><i class="fa fa-search-plus" aria-hidden="true"></i></button>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="review-tab-pro-inner">

                        <div id="myTabContent" class="tab-content custom-product-edit">

                            <div class="product-tab-list tab-pane fade active in" id="description">
                                <form name="form" method="post" action="">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <div class="review-content-section">

                                                <div class="input-group mg-b-pro-edt">

                                                    <span class="input-group-addon">Medicine ID: <?php
print $medicine_id;
?></span>

                                                </div>

                                                <div class="input-group mg-b-pro-edt">
                                                    <span class="input-group-addon"><i class="fa fa-newspaper-o" aria-hidden="true"></i></span>
                                                    <span class="input-group-addon">Medicine Info</span>
                                                    <input name="imedicineinfo" type="text" class="form-control pro-edt-select form-control-primary" <?php
                                                        if ($medicine_info) {
                                                            print "value='" . $medicine_info . "'";
                                                        }
                                                        ?>>
                                                </div>
                                                <div class="input-group mg-b-pro-edt">
                                                    <span class="input-group-addon"><i class="fa fa-newspaper-o" aria-hidden="true"></i></span>
                                                    <span class="input-group-addon">Note</span>
                                                    <input name="inote" type="text" class="form-control pro-edt-select form-control-primary" <?php
                                                        if ($medicine_note) {
                                                            print "value='" . $medicine_note . "'";
                                                        }
                                                        ?>>
                                                </div>                                        
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="text-center custom-pro-edt-ds">
                                                <input name="save" type="submit" class="btn btn-ctl-bt waves-effect waves-light m-r-10" value="Add New Disease">
                                                <input name="update" type="submit" class="btn btn-ctl-bt waves-effect waves-light m-r-10" value="Update Disease Info">
                                                <input name="delete" type="submit" class="btn btn-ctl-bt waves-effect waves-light m-r-10" value="Delete Disease">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="footer-copyright-area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="footer-copy-right">
                    <p>Copyright © 2019 EastCare. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jquery
            ============================================ -->
<script src="js/vendor/jquery-1.12.4.min.js"></script>
<!-- bootstrap JS
            ============================================ -->
<script src="js/bootstrap.min.js"></script>
<!-- wow JS
            ============================================ -->
<script src="js/wow.min.js"></script>
<!-- price-slider JS
            ============================================ -->
<script src="js/jquery-price-slider.js"></script>
<!-- meanmenu JS
            ============================================ -->
<script src="js/jquery.meanmenu.js"></script>
<!-- owl.carousel JS
            ============================================ -->
<script src="js/owl.carousel.min.js"></script>
<!-- sticky JS
            ============================================ -->
<script src="js/jquery.sticky.js"></script>
<!-- scrollUp JS
            ============================================ -->
<script src="js/jquery.scrollUp.min.js"></script>
<!-- mCustomScrollbar JS
            ============================================ -->
<script src="js/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="js/scrollbar/mCustomScrollbar-active.js"></script>
<!-- metisMenu JS
            ============================================ -->
<script src="js/metisMenu/metisMenu.min.js"></script>
<script src="js/metisMenu/metisMenu-active.js"></script>
<!-- sparkline JS
            ============================================ -->
<script src="js/sparkline/jquery.sparkline.min.js"></script>
<script src="js/sparkline/jquery.charts-sparkline.js"></script>
<!-- calendar JS
            ============================================ -->
<script src="js/calendar/moment.min.js"></script>
<script src="js/calendar/fullcalendar.min.js"></script>
<script src="js/calendar/fullcalendar-active.js"></script>
<!-- float JS
        ============================================ -->
<script src="js/flot/jquery.flot.js"></script>
<script src="js/flot/jquery.flot.resize.js"></script>
<script src="js/flot/curvedLines.js"></script>
<script src="js/flot/flot-active.js"></script>
<!-- plugins JS
            ============================================ -->
<script src="js/plugins.js"></script>
<!-- main JS
            ============================================ -->
<script src="js/main.js"></script>


<script type="text/javascript">
    function openNewWin(url)
    {
        window.open(url);
    }
</script>
</body>

</html>