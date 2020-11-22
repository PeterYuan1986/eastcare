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
$sql = "SELECT MAX(`doctor_id`) FROM `doctor`";
$result = mysqli_query($conn, $sql);
$nextid = mysqli_fetch_array($result)[0] + 1;

$sql = "SELECT * FROM disease";
$result = mysqli_query($conn, $sql);
while ($arr = mysqli_fetch_array($result)) {
    $data[] = $arr;
}



if (isset($_POST["save"])) {
    $ibirthday = @$_POST['ibirthday'];
    $igender = @$_POST['igender'];
    $iname = @$_POST["iname"];
    $iaddress = @$_POST["iaddress"];
    $iaddress2 = @$_POST["iaddress2"];
    $icity = @$_POST["icity"];
    $istate = @$_POST["istate"];
    $izipcode = @$_POST["izipcode"];
    $iphone = @$_POST["iphone"];
    $inote = @$_POST["inote"];
    updatestr();
    $sql = "INSERT INTO `doctor`(`birthday` , `gender`, `name`,`address`,`address2`, `city`, `state`, `zipcode`, `phone`, profession) VALUES('" . $ibirthday . "','" . $igender . "','" . $iname . "','" . $iaddress . "','" . $iaddress2 . "','" . $icity . "','" . $istate . "','" . $izipcode . "','" . $iphone . "','" . $inote . "')";
    $result = mysqli_query($conn, $sql);
    var_dump($_POST);
    var_dump($sql);
    var_dump($result);
    if ($result) {
        print '<script>alert("Add Successful!")</script>';
        print '<script> location.replace("doctor-list.php"); </script>';
    } else {
        print '<script>alert("Add Failed! Please check and try again!")</script>';
    }
}

if (isset($_POST["update"])) {
    $ibirthday = @$_POST['ibirthday'];
    $igender = @$_POST['igender'];
    $iname = @$_POST["iname"];
    $iaddress = @$_POST["iaddress"];
    $iaddress2 = @$_POST["iaddress2"];
    $icity = @$_POST["icity"];
    $istate = @$_POST["istate"];
    $izipcode = @$_POST["izipcode"];
    $iphone = @$_POST["iphone"];
    $inote = @$_POST["inote"];
    updatestr();
    $sql = "UPDATE `doctor` SET `profession`='" . $inote . "', `birthday`='" . $ibirthday . "', `gender`='" . $igender . "', `name`='" . $iname . "',`address`='" . $iaddress . "', `city`='" . $icity . "', `state`='" . $istate . "',  `zipcode`='" . $izipcode . "', `phone`='" . $iphone . "',address2='" . $iaddress2 . "'WHERE doctor_id='" . $_SESSION['updatedoctorid'] . "'";
    $result = mysqli_query($conn, $sql);
     var_dump($sql)."<br/>";
    var_dump($result);   
    if ($result) {
        print '<script>alert("Edit Successful!")</script>';
        unset($_SESSION['updatedoctorid']);
        print '<script> location.replace("doctor-list.php"); </script>';
    } else {
        print '<script>alert("Edit Failed! Please check and try again!")</script>';
    }
}

if (isset($_POST["delete"])) {
    if ($doctor_id != $nextid) {
        $sql = "DELETE from doctor  WHERE doctor='" . $_SESSION['updatedoctorid'] . "'";
        $result = mysqli_query($conn, $sql);
        unset($_SESSION['updatedoctorid']);
        print '<script>alert("Delete Successful!")</script>';
        header('Location:' . $_SERVER["PHP_SELF"]);
    } else {
        print '<script>alert("Delete Unsuccessful!Please check the doctor ID")</script>';
    }
}

function updatestr() {
    @$isku = strexchange($isku);
    @$ibatch = strexchange($ibatch);
    @$ireceiver = strexchange($ireceiver);
    @$iaddress = strexchange($iaddress);
    @$iaddress2 = strexchange($iaddress2);
    @$icity = strexchange($icity);
    @$iphone = strexchange($iphone);
    @$inote = strexchange($inote);
}

$datanote = check_note($cmpid);
$totalnotes = sizeof($datanote);



if (isset($_SESSION['editsku'])) {
    $doctor_id = $_SESSION['editsku'];
    $_SESSION['updatedoctorid'] = $doctor_id;
    $sql = "SELECT * FROM `doctor` WHERE doctor_id ='" . $doctor_id . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $birthday = $row['birthday'];
    $gender = $row['gender'];
    $name = $row['name'];
    $address = $row['address'];
    $address2 = $row['address2'];
    $city = $row['city'];
    $state = $row['state'];
    $zipcode = $row['zipcode'];
    $phone = $row['phone'];
    $profession = $row['profession'];
    unset($_SESSION['editsku']);
} else {
    if (isset($_REQUEST['search'])) {
        $sql = "SELECT *  FROM `doctor` WHERE doctor_id ='" . $_POST['searcheditorder'] . "'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);
        if ($row > 0) {
            $doctor_id = $_POST['searcheditorder'];
            $_SESSION['updatedoctorid'] = $doctor_id;
            $birthday = $row['birthday'];
            $gender = $row['gender'];
            $phone = $row['phone'];
            $state = $row['state'];
            $address = $row['address'];
            $address2 = $row['address2'];
            $city = $row['city'];
            $zipcode = $row['zipcode'];
            $profession = $row['profession'];
            $name = $row['name'];
        } else {
            $doctor_id = $nextid;
            $birthday = 0;
            $gender = 0;
            $name = 0;
            $phone = 0;
            $state = 0;
            $address = 0;
            $address2 = 0;
            $city = 0;
            $zipcode = 0;
            $profession = 0;
            print '<script>alert("This doctor doesn not exist!")</script>';
        }
    } else {
        $doctor_id = $nextid;
        $birthday = 0;
        $gender = 0;
        $name = 0;
        $phone = 0;
        $state = 0;
        $address = 0;
        $address2 = 0;
        $city = 0;
        $zipcode = 0;
        $profession = 0;
    }
}

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
                                    <h2>Doctor Edit</h2>
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


                                        <div style="width:200px;float:left;"><input name="searcheditorder" type="text" placeholder="Search Doctor ID" value="<?php
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

                                                    <span class="input-group-addon">Doctor ID: <?php
print $doctor_id;
?></span>

                                                </div>

                                                <div class="input-group mg-b-pro-edt">
                                                    <span class="input-group-addon"><i class="icon nalika-menu" aria-hidden="true"></i></span>
                                                    <span class="input-group-addon">Date of Birth</span>
                                                    <input name="ibirthday" type="date" class="form-control pro-edt-select form-control-primary" <?php
if ($birthday) {
    print "value='" . $birthday . "'";
}
?>>
                                                </div>
                                                <div class="input-group mg-b-pro-edt">
                                                    <span class="input-group-addon"><i class="icon nalika-info" aria-hidden="true"></i></span>
                                                    <span class="input-group-addon">Gender</span>
                                                    <select name="igender" class="form-control pro-edt-select form-control-primary">

                                                        <option value="Male"    <?php
                                                    if ($gender === 'Male') {
                                                        print "selected";
                                                    }
                                                    ?>>Male</option>
                                                        <option value="Female"  <?php
                                                    if ($gender === 'Female') {
                                                        print "selected";
                                                    }
                                                    ?>>Female</option>

                                                    </select>
                                                </div>
                                                <div class="input-group mg-b-pro-edt">
                                                    <span class="input-group-addon"><i class="fa fa-newspaper-o" aria-hidden="true"></i></span>
                                                    <span class="input-group-addon">Profession</span>
                                                    <select name="inote" class="form-control pro-edt-select form-control-primary">
                                                        <?php
                                                        for ($index = 0; $index < @count($data); $index++) {
                                                            print "<option value='" . $data[$index]['disease_id'] . "'";
                                                            if ($profession == $data[$index]['disease_id']) {
                                                                print " selected";
                                                            }
                                                            print ">" . $data[$index]['disease_info'] . "</option>";
                                                        }
                                                        ?>                                                                   

                                                    </select>                                                    

                                                </div>


                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <div class="review-content-section">                                                            
                                                <div class="input-group mg-b-pro-edt">
                                                    <span class="input-group-addon"><i class="fa fa-male" aria-hidden="true"></i></span>
                                                    <span class="input-group-addon">Doctor Name</span>
                                                    <input name="iname" type="text" required="" class="form-control" placeholder="" <?php
                                                        if ($name) {
                                                            print "value='" . $name . "'";
                                                        }
                                                        ?>>
                                                </div>
                                                <div class="input-group mg-b-pro-edt">
                                                    <span class="input-group-addon"><i class="fa fa-home" aria-hidden="true"></i></span>
                                                    <span class="input-group-addon">Address Line 1</span>
                                                    <input name="iaddress" type="text" required="" class="form-control" placeholder="" <?php
                                                        if ($address) {
                                                            print "value='" . $address . "'";
                                                        }
                                                        ?>>
                                                </div>   
                                                <div class="input-group mg-b-pro-edt">
                                                    <span class="input-group-addon"><i class="fa fa-home" aria-hidden="true"></i></span>
                                                    <span class="input-group-addon">Address Line 2</span>
                                                    <input name="iaddress2" type="text"  class="form-control" placeholder="" <?php
                                                    if ($address) {
                                                        print "value='" . $address2 . "'";
                                                    }
                                                        ?>>
                                                </div>  

                                                <div class="input-group mg-b-pro-edt">
                                                    <span class="input-group-addon"><i class="fa fa-home" aria-hidden="true"></i></span>
                                                    <span class="input-group-addon">City</span>
                                                    <input name="icity" type="text" required="" class="form-control" placeholder="" <?php
                                                    if ($city) {
                                                        print "value='" . $city . "'";
                                                    }
                                                    ?>>
                                                    <span class="input-group-addon"><i class="fa fa-home" aria-hidden="true"></i></span>
                                                    <span class="input-group-addon">State</span>
                                                    <input name="istate" type="text" required="" class="form-control" placeholder="" <?php
                                                           if ($city) {
                                                               print "value='" . $state . "'";
                                                           }
                                                    ?>>
                                                    <span class="input-group-addon"><i class="fa fa-home" aria-hidden="true"></i></span>
                                                    <span class="input-group-addon">ZipCode</span>
                                                    <input name="izipcode" type="text" class="form-control" placeholder="" <?php
                                                    if ($zipcode) {
                                                        print "value='" . $zipcode . "'";
                                                    }
                                                    ?>>
                                                </div>
                                                <div class="input-group mg-b-pro-edt">
                                                    <span class="input-group-addon"><i class="fa fa-phone" aria-hidden="true"></i></span>
                                                    <span class="input-group-addon">Cell Phone / Email Address</span>
                                                    <input name="iphone" type="text" required="" class="form-control" placeholder="" <?php
                                                    if ($phone) {
                                                        print "value='" . $phone . "'";
                                                    }
                                                    ?>>

                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="text-center custom-pro-edt-ds">
                                                <input name="save" type="submit" class="btn btn-ctl-bt waves-effect waves-light m-r-10" value="Add New Doctor">
                                                <input name="update" type="submit" class="btn btn-ctl-bt waves-effect waves-light m-r-10" value="Update Doctor Info">
                                                <input name="delete" type="submit" class="btn btn-ctl-bt waves-effect waves-light m-r-10" value="Delete Doctor">
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
