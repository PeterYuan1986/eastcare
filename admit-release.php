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
$str1 = date("Y-m-d", time());




if (isset($_SESSION['Admitid'])) {
    $patient_id = $_SESSION['Admitid'];
} else {
    header('location:patient-detail.php');
}
$sql = "select * from hospitalizationrecord where patient_id ='" . $patient_id . "' order by hospitalization_id DESC";
$result = mysqli_query($conn, $sql);
$totalrow = mysqli_num_rows($result);
if ($totalrow > 0) {
    $row = mysqli_fetch_array($result);
    if ($row['discharge_date'] == NULL) {
        @$admission_date = $row['admission_date'];
        @$room_number = $row['room_number'];
        @$discharge_date = NULL;
        $_SESSION['hospitalizationid'] = $row[0];
    } else {
        $admission_date = NULL;
        $room_number = NULL;
        $discharge_date = NULL;
    }
} else {
    $admission_date = NULL;
    $room_number = NULL;
    $discharge_date = NULL;
}
$sql = "SELECT `room_number` FROM `roomtype` where engaged='N' ORDER BY room_number DESC";
$result = mysqli_query($conn, $sql);
while ($arr = mysqli_fetch_array($result)) {
    $roomdata[] = $arr;
}


if (isset($_POST["admit"])) {
    $admission_date = @$_POST['admission'];
    $room_number = @$_POST["room"];
    $sql = "INSERT INTO `hospitalizationrecord`(`admission_date` , `room_number`, patient_id) VALUES('" . $admission_date . "','" . $room_number . "','" . $patient_id . "')";
    $result = mysqli_query($conn, $sql);
print '<script>alert("Successful!")</script>';
}

if (isset($_POST["disc"])) {
    $discharge_date = $_POST['discharge'];
    $sql = "UPDATE `hospitalizationrecord` SET `discharge_date`='" . $discharge_date . "'  where hospitalization_id= '" . $_SESSION['hospitalizationid'] . "'";
    if ($discharge_date != NULL && $admission_date < $discharge_date) {
        $result = mysqli_query($conn, $sql);
        print '<script>alert("Add Successful!")</script>';
        print '<script> location.replace("patient-detail.php"); </script>';
        
    } else {
        print '<script>alert("Failed!Discharge time must be later than admission time!")</script>';
    }
}


if (isset($_POST["cancel"])) {
    header('location:patient-detail.php');
}

function updatestr() {
    @$isku = strexchange($isku);
    @$ibatch = strexchange($ibatch);
    @$ireceiver = strexchange($ireceiver);
    @$iaddress = strexchange($iaddress);
    @$iaddress2 = strexchange($iaddress2);
    @$icity = strexchange($icity);
    @$iphone = strexchange($iphone);
    @$inote = strexchange($insurance_info);
}

$datanote = check_note($cmpid);
$totalnotes = sizeof($datanote);

function checkinput($isku) {
    if (isEmpty($isku)) {
        print '<script>alert("The user name should not be empty!")</script>';
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
                                    <h2>Admit/Discharge</h2>
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



                    <div class="review-tab-pro-inner">

                        <div id="myTabContent" class="tab-content custom-product-edit">

                            <div class="product-tab-list tab-pane fade active in" id="description">
                                <form name="form" method="post" action="">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <div class="review-content-section">

                                                <div class="input-group mg-b-pro-edt">

                                                    <span class="input-group-addon">Patient ID: <?php
                                                        print $patient_id;
                                                        ?></span>

                                                </div>

                                                <div class="input-group mg-b-pro-edt">
                                                    <span class="input-group-addon"><i class="icon nalika-menu" aria-hidden="true"></i></span>
                                                    <span class="input-group-addon">Admission Date</span>
                                                    <input name="admission" type="date" class="form-control pro-edt-select form-control-primary" <?php
                                                    if ($admission_date) {
                                                        print "value='" . $admission_date . "'";
                                                    }
                                                    ?>>
                                                </div>
                                                <div class="input-group mg-b-pro-edt">
                                                    <span class="input-group-addon"><i class="icon nalika-info" aria-hidden="true"></i></span>
                                                    <span class="input-group-addon">Room number</span>

                                                    <select name="room" class="form-control pro-edt-select form-control-primary">
                                                        <option value=''></option>
                                                        <?php
                                                        for ($index = 0; $index < @count($roomdata); $index++) {
                                                            print "<option value='" . $roomdata[$index]['room_number'] . "'";
                                                            if ($room_number == $roomdata[$index]['room_number']) {
                                                                print " selected";
                                                            }

                                                            print ">" . $roomdata[$index]['room_number'] . "</option>";
                                                        }
                                                        ?>  
                                                    </select>
                                                </div>
                                                <div class="input-group mg-b-pro-edt">
                                                    <span class="input-group-addon"><i class="icon nalika-menu" aria-hidden="true"></i></span>
                                                    <span class="input-group-addon">Release Date</span>
                                                    <input name="discharge" type="date" class="form-control pro-edt-select form-control-primary" <?php
                                                    if ($discharge_date) {
                                                        print "value='" . $discharge_date . "'";
                                                    }
                                                    ?>>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="text-center custom-pro-edt-ds">
                                                <input name="admit" type="submit" class="btn btn-ctl-bt waves-effect waves-light m-r-10" value="Admit"> 
                                                <input name="disc" type="submit" class="btn btn-ctl-bt waves-effect waves-light m-r-10" value="Discharge">\
                                                <input name="cancel" type="submit" class="btn btn-ctl-bt waves-effect waves-light m-r-10" value="Cancel">
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
