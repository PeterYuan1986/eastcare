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
$sql = "SELECT MAX(`visitrecord_id`) FROM `patientvisitdoctor`";
$result = mysqli_query($conn, $sql);
$nextid = mysqli_fetch_array($result)[0] + 1;

$sql = "SELECT * FROM disease";
$result = mysqli_query($conn, $sql);
while ($arr = mysqli_fetch_array($result)) {
    $data[] = $arr;
}

$sql = "SELECT * FROM doctor";
$result = mysqli_query($conn, $sql);
while ($arr = mysqli_fetch_array($result)) {
    $datadoctor[] = $arr;
}

$sql = "SELECT * FROM patient";
$result = mysqli_query($conn, $sql);
while ($arr = mysqli_fetch_array($result)) {
    $datapatient[] = $arr;
}

$sql = "SELECT * FROM procedur";
$result = mysqli_query($conn, $sql);
while ($arr = mysqli_fetch_array($result)) {
    $dataprocedure[] = $arr;
}

$sql = "SELECT * FROM medicine";
$result = mysqli_query($conn, $sql);
while ($arr = mysqli_fetch_array($result)) {
    $datamedicine[] = $arr;
}



if (isset($_POST["save"])) {
    $idate = @$_POST['idate'];
    $ipatient=@$_POST['ipatient'];
    $idoctor=@$_POST['idoctor'];
    $idisease=@$_POST['idisease'];
    $iprocedure=@$_POST['iprocedure'];
    $imedicine=@$_POST['imedicine'];
    $ivisitbill=@$_POST['ivisitbill'];

    updatestr();
    $sqlpatientvisitdoctor = "INSERT INTO `patientvisitdoctor`(visitrecord_id,patient_id,doctor_id) VALUES('".$nextid."','" . $ipatient . "','" . $idoctor . "')";
    $resultpatientvisitdoctor = mysqli_query($conn, $sqlpatientvisitdoctor);
    // $ivisitrecord_id=mysql_insert_id(mysqli);
    $sqlvisitrecord = "INSERT INTO `visitrecord`(visitrecord_id,visit_date,visit_bill) VALUES('" . $nextid . "','" . $idate . "','" . $ivisitbill . "')";
    $resultvisitrecord = mysqli_query($conn, $sqlvisitrecord);
    $sqldiagnosis = "INSERT INTO `diagnosis`(visitrecord_id,disease_id,medicine_id,procedure_id) VALUES('" . $nextid . "','" . $idisease . "','" . $imedicine . "','" . $iprocedure . "')";
    $resultdiagnosis = mysqli_query($conn, $sqldiagnosis);
    // var_dump($_POST)."<br/>";
    // var_dump($sqlpatientvisitdoctor);
    // echo "<br>";
    // var_dump($resultpatientvisitdoctor);
    echo "<br>";
    // var_dump($sqlvisitrecord);
    echo "<br>";
    // var_dump($resultvisitrecord);
    echo "<br>";
    // var_dump($sqldiagnosis);
    echo "<br/>";
    // var_dump($resultdiagnosis);
    if ($result) {
        print '<script>alert("Add Successful!")</script>';
        print '<script> location.replace("visitrecord-list.php"); </script>';
    } else {
        print '<script>alert("Add Failed! Please check and try again!")</script>';
    }
}

if (isset($_POST["update"])) {
    $idate = @$_POST['idate'];
    $ipatient=@$_POST['ipatient'];
    $idoctor=@$_POST['idoctor'];
    $idisease=@$_POST['idisease'];
    $iprocedure=@$_POST['iprocedure'];
    $imedicine=@$_POST['imedicine'];
    $ivisitbill=@$_POST['ivisitbill'];

    updatestr();
    $sqlupdatepaientvisitdoctor = "UPDATE `patientvisitdoctor` SET `patient_id`='" . $ipatient . "', `doctor_id`='" . $idoctor . "' WHERE visitrecord_id='" . $_SESSION['updatevisitrecordid'] . "'";
    $resultupdatepaientvisitdoctor = mysqli_query($conn, $sqlupdatepaientvisitdoctor);
    $sqlupdatevisitrecord = "UPDATE `visitrecord` SET `visit_date`='" . $idate . "', `visit_bill`='" . $ivisitbill . "' WHERE visitrecord_id='" . $_SESSION['updatevisitrecordid'] . "'";
    $resultupdatevisitrecord = mysqli_query($conn, $sqlupdatevisitrecord);    
    $sqlupdatediagnosis = "UPDATE `diagnosis` SET `disease_id`='" . $idisease . "', `procedure_id`='" . $iprocedure . "',`medicine_id`='" . $imedicine . "' WHERE visitrecord_id='" . $_SESSION['updatevisitrecordid'] . "'";
    $resultupdatediagnosis = mysqli_query($conn, $sqlupdatediagnosis);



    // var_dump($sqlupdatepaientvisitdoctor)."<br/>";
    // var_dump($resultupdatepaientvisitdoctor)."<br/>";   
    //     var_dump($sqlupdatevisitrecord)."<br/>";
    // var_dump($resultupdatevisitrecord)."<br/>";   
    //     var_dump($sqlupdatediagnosis)."<br/>";
    // var_dump($resultupdatediagnosis)."<br/>";   
    if ($result) {
        print '<script>alert("Edit Successful!")</script>';
        unset($_SESSION['updatevisitrecordid']);
        print '<script> location.replace("visitrecord-list.php"); </script>';
    } else {
        print '<script>alert("Edit Failed! Please check and try again!")</script>';
    }
}

if (isset($_POST["delete"])) {
    if ($visitrecord_id != $nextid) {
        $sql = "DELETE from patientvisitdoctor  WHERE visitrecord_id='" . $_SESSION['updatevisitrecordid'] . "'";
        $result = mysqli_query($conn, $sql);
        $sqlvisitrecord = "DELETE from visitrecord  WHERE visitrecord_id='" . $_SESSION['updatevisitrecordid'] . "'";
        $resultvisitrecord = mysqli_query($conn, $sqlvisitrecord);
        unset($_SESSION['updatevisitrecordid']);
        print '<script>alert("Delete Successful!")</script>';
        header('Location:' . $_SERVER["PHP_SELF"]);
    } else {
        print '<script>alert("Delete Unsuccessful!Please check ")</script>';
    }
}

function updatestr() {
    @$isku = strexchange($isku);

    @$idate = strexchange($idate);
    @$ipatient = strexchange($ipatient);
    @$idoctor = strexchange($idoctor);
    @$idisease = strexchange($idisease);
    @$iprocedure = strexchange($iprocedure);
    @$imedicine = strexchange($imedicine);
    @$ivisitbill = strexchange($ivisitbill);
}

$datanote = check_note($cmpid);
$totalnotes = sizeof($datanote);



if (isset($_SESSION['editsku'])) {
    $visitrecord_id = $_SESSION['editsku'];
    $_SESSION['updatevisitrecordid'] = $visitrecord_id;
    $sql = "select visitrecord.visitrecord_id,visit_date, visit_bill,patient_id, doctor_id,disease_id,procedure_id, medicine_id from visitrecord left join patientvisitdoctor  on visitrecord.visitrecord_id = patientvisitdoctor.visitrecord_id left join diagnosis on visitrecord.visitrecord_id = diagnosis.visitrecord_id where visitrecord.visitrecord_id ='" . $visitrecord_id . "'";
    $result = mysqli_query($conn, $sql);
    // var_dump($sql);
    // echo "<br/>";
    // var_dump($result);
    $row = mysqli_fetch_array($result);
    $visitrecord_id = $row['visitrecord_id'];
    $visit_date = $row['visit_date'];
    $visit_bill = $row['visit_bill'];
    $patient_id = $row['patient_id'];
    $doctor_id = $row['doctor_id'];
    $disease_id = $row['disease_id'];
    $procedure_id = $row['procedure_id'];
    $medicine_id = $row['medicine_id'];



    unset($_SESSION['editsku']);
} else {
    if (isset($_REQUEST['search'])) {
        $sql = "SELECT *  FROM `visitrecord` WHERE visitrecord_id ='" . $_POST['searcheditorder'] . "'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);
        if ($row > 0) {
        $visitrecord_id = $row['visitrecord_id'];
        $visit_date = $row['visit_date'];
        $visit_bill = $row['visit_bill'];        
        } else {
            $visitrecord_id = $nextid;
            $visit_date = 0;
            $patient_id = 0;
            $doctor_id = 0;
            $disease_id = 0;
            $procedure_id = 0;
            $medicine_id = 0;
            $visit_bill = 0;
            print '<script>alert("This doctor doesn not exist!")</script>';
        }
    } else {
            $visitrecord_id = $nextid;
            $visit_date = 0;
            $patient_id = 0;
            $doctor_id = 0;
            $disease_id = 0;
            $procedure_id = 0;
            $medicine_id = 0;
            $visit_bill = 0;
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
                                    <h2>Visit Record Edit</h2>
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

                                                    <span class="input-group-addon">Visit Record ID: <?php
                                                        if($visitrecord_id){
                                                        print $visitrecord_id;}
                                                        ?></span>
                                                </div>

                                                <div class="input-group mg-b-pro-edt">
                                                    <span class="input-group-addon"><i class="fa fa-newspaper-o" aria-hidden="true"></i></span>
                                                    <span class="input-group-addon">Visit Date </span>
                                                    <input name="idate" type="date" class="form-control pro-edt-select form-control-primary" <?php
                                                        if($visit_date){
                                                            print "value='".$visit_date."'";
                                                        }
                                                       
                                                       ?>>
                                                        
                                                </div>

                                                    <div class="input-group mg-b-pro-edt">
                                                    <span class="input-group-addon"><i class="fa fa-newspaper-o" aria-hidden="true"></i></span>
                                                    <span class="input-group-addon">Patient</span>
                                                    
                                                    <select name="ipatient" class="form-control pro-edt-select form-control-primary">
                                                        <?php
                                                        print "<option value=''";
                                                        print "selected";
                                                        print "</option>";
                                                        for ($index = 0; $index < @count($datapatient); $index++) {
                                                            print "<option value='" . $datapatient[$index]['patient_id'] . "'";
                                                            if ($patient_id == $datapatient[$index]['patient_id']) {
                                                                print " selected";
                                                            }
                                                            print ">" . $datapatient[$index]['name'] . "</option>";
                                                        }
                                                        ?>                                                                   

                                                    </select>                                                    

                                                </div>

                                                </div>

                                                    <div class="input-group mg-b-pro-edt">
                                                    <span class="input-group-addon"><i class="fa fa-newspaper-o" aria-hidden="true"></i></span>
                                                    <span class="input-group-addon">Doctor</span>
                                                    
                                                    <select name="idoctor" class="form-control pro-edt-select form-control-primary">
                                                        <?php
                                                        print "<option value=''";
                                                        print "selected";
                                                        print "</option>";
                                                        for ($index = 0; $index < @count($datadoctor); $index++) {
                                                            print "<option value='" . $datadoctor[$index]['doctor_id'] . "'";
                                                            if ($doctor_id == $datadoctor[$index]['doctor_id']) {
                                                                print " selected";
                                                            }
                                                            print ">" . $datadoctor[$index]['name'] . "</option>";
                                                        }
                                                        ?>                                                                   

                                                    </select>                                                    

                                                </div>
                                                <div class="input-group mg-b-pro-edt">
                                                    <span class="input-group-addon"><i class="fa fa-newspaper-o" aria-hidden="true"></i></span>
                                                    <span class="input-group-addon">Disease </span>
                                                    <select name="idisease" class="form-control pro-edt-select form-control-primary">
                                                        <?php
                                                        print "<option value=''";
                                                        print "selected";
                                                        print "</option>";
                                                        for ($index = 0; $index < @count($data); $index++) {
                                                            print "<option value='" . $data[$index]['disease_id'] . "'";
                                                            if ($disease_id == $data[$index]['disease_id']) {
                                                                print " selected";
                                                            }
                                                            print ">" . $data[$index]['disease_info'] . "</option>";
                                                        }
                                                        ?>>                                                                   

                                                    </select>                                                    

                                                </div>
                                                <div class="input-group mg-b-pro-edt">
                                                    <span class="input-group-addon"><i class="fa fa-newspaper-o" aria-hidden="true"></i></span>
                                                    <span class="input-group-addon">Procedure</span>
                                                    <select name="iprocedure" class="form-control pro-edt-select form-control-primary">
                                                        <?php
                                                        print "<option value=''";
                                                        print "selected";
                                                        print "</option>";
                                                        for ($index = 0; $index < @count($dataprocedure); $index++) {
                                                            print "<option value='" . $dataprocedure[$index]['procedure_id'] . "'";
                                                            if ($procedure_id == $dataprocedure[$index]['procedure_id']) {
                                                                print " selected";
                                                            }
                                                            print ">" . $dataprocedure[$index]['procedure_name'] . "</option>";
                                                        }
                                                        ?>                                                                   

                                                    </select>                                                    

                                                </div>
                                                <div class="input-group mg-b-pro-edt">
                                                    <span class="input-group-addon"><i class="fa fa-newspaper-o" aria-hidden="true"></i></span>
                                                    <span class="input-group-addon">Medicine</span>
                                                    <select name="imedicine" class="form-control pro-edt-select form-control-primary">
                                                        <?php
                                                        print "<option value=''";
                                                        print "selected";
                                                        print "</option>";
                                                        for ($index = 0; $index < @count($data); $index++) {
                                                            print "<option value='" . $datamedicine[$index]['medicine_id'] . "'";
                                                            if ($medicine_id == $datamedicine[$index]['medicine_id']) {
                                                                print " selected";
                                                            }
                                                            print ">" . $datamedicine[$index]['medicine_info'] . "</option>";
                                                        }
                                                        ?>                                                                   

                                                    </select>                                                    

                                                </div>
                                                <div class="input-group mg-b-pro-edt">
                                                    <span class="input-group-addon"><i class="fa fa-newspaper-o" aria-hidden="true"></i></span>
                                                    <span class="input-group-addon">Visit Bill</span>
                                                    <input name="ivisitbill" type="text"  class="form-control" placeholder="Please enter here" <?php
                                                    if ($visit_bill) {
                                                        print "value='" . $visit_bill . "'";
                                                    }
                                                    ?>>
                                                </div>

                                            </div>
                                        </div>



                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="text-center custom-pro-edt-ds">
                                                <input name="save" type="submit" class="btn btn-ctl-bt waves-effect waves-light m-r-10" value="Add New Record">
                                                <input name="update" type="submit" class="btn btn-ctl-bt waves-effect waves-light m-r-10" value="Update Record Info">
                                                <input name="delete" type="submit" class="btn btn-ctl-bt waves-effect waves-light m-r-10" value="Delete Record">
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
