<?php
require_once 'header.php';
$pageoffice = 'all';           //设置页面属性 office ：  nc, sh, all
$pagelevel = 1;       // //设置页面等级 0： 只有admin可以访问； 1：库存系统用户； 2:代发用户
check_session_expiration();
$user = $_SESSION['user_info']['userid'];
$fn = $_SESSION['user_info']['firstname'];
$ln = $_SESSION['user_info']['lastname'];
$useroffice = $_SESSION['user_info']['office'];
$userlevel = $_SESSION['user_info']['level'];           //userlevel  0: admin; else;
$cmpid = $_SESSION['user_info']['cmpid'];
$childid = $_SESSION['user_info']['childid'];
check_access($useroffice, $userlevel, $pageoffice, $pagelevel);

if (isset($_POST['Admit'])) {
    if ($_SESSION['detailinhospital']=='NO') {
        $_SESSION['Admitid'] = $_SESSION['patient_id'];
        unset($_SESSION['patient_id']);
        header('Location:admit-release.php');
    } else {
        $_SESSION['Admitid'] = $_SESSION['patient_id'];
        unset($_SESSION['patient_id']);
        header('Location:discharge.php');
    }
}
if (isset($_POST['Visit'])) {
    $_SESSION['Visitid'] = $_SESSION['patient_id'];
    unset($_SESSION['patient_id']);
    header('location:visitrecord-patient.php');
}
if (isset($_POST['Bill'])) {
    $_SESSION['Billid'] = $_SESSION['patient_id'];

    unset($_SESSION['patient_id']);
    header('location:bill.php');
}

// 换cmpid在页面顶端
if (sizeof($childid) > 1) {
    foreach ($childid as $x) {
        $title = "UCMP" . $x;
        if (isset($_POST["{$title}"])) {
            $_SESSION['user_info']['cmpid'] = $x;
            $cmpid = $_SESSION['user_info']['cmpid'];
        }
    }
}

$datanote = check_note($cmpid);
$totalnotes = sizeof($datanote);

$perpage = 30;

if (!isset($_SESSION['detailpagesearchtext'])) {
    $_SESSION['detailpagesearchtext'] = '';
}
if (isset($_POST['search'])) {
    $_SESSION['detailpagesearchtext'] = $_POST['searchtext'];
}
$sql = "SELECT patient_id, name FROM patient where  name LIKE '%" . @$_SESSION['detailpagesearchtext'] . "%' or  patient_id LIKE '%" . @$_SESSION['detailpagesearchtext'] . "%'";
$result = mysqli_query($conn, $sql);
$totalrow = mysqli_num_rows($result);
$totalpage = ceil($totalrow / $perpage);

if ($totalrow != 0) {
    while ($arr = mysqli_fetch_array($result)) {
        $data[] = $arr;
    }
    if (empty(@$_GET['page']) || !is_numeric(@$_GET['page']) || @$_GET['page'] < 1 || isset($_POST['search']) || @$_GET['page'] > $totalpage) {
        $page = 1;
    } else
        $page = $_GET['page'];
}else {
    $page = 1;
}
?>



<?php
for ($i = 0; $i < $perpage; $i++) {
    $ind = ($page - 1) * $perpage + $i;
    $tem = "sss" . $ind;
    if ($i > @count($data)) {
        break;
    } else if (isset($_POST["{$tem}"])) {
        $_SESSION['patient_id'] = $data[$ind]['patient_id'];
        $sql = "select * from patient where  patient_id='" . $_SESSION['patient_id'] . "'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);
        @$_SESSION['detailpatientname'] = $row[1];
        @$_SESSION['detailpatientbirth'] = $row[2];
        @$_SESSION['detailpatientgender'] = $row[3];
        @$_SESSION['detailpatientinsuranceinfo'] = $row[4];
        @$_SESSION['detailpatientaddress'] = $row[5];
        @$_SESSION['detailpatientaddress2'] = $row[6];
        @$_SESSION['detailpatientcity'] = $row[7];
        @$_SESSION['detailpatientstate'] = $row[8];
        @$_SESSION['detailpatientzipcode'] = $row[9];
        @$_SESSION['detailpatientphone'] = $row[10];

        $sql = "select * from hospitalizationrecord where patient_id ='" . $_SESSION['patient_id'] . "' order by hospitalization_id DESC";
        $result = mysqli_query($conn, $sql);
        $totalrows = mysqli_num_rows($result);
        if ($totalrows > 0) {
            $row = mysqli_fetch_array($result);
            @$_SESSION['detailadmission_date'] = $row['admission_date'];
            @$_SESSION['detailroom_number'] = $row['room_number'];
            @$_SESSION['detaildischarge_date'] = $row['discharge_date'];
            if (@$_SESSION['detaildischarge_date'] != NULL) {
                @$_SESSION['detailinhospital'] = 'NO';
                @$_SESSION['detailroom_number'] = NULL;
            } else {
                @$_SESSION['detailinhospital'] = 'YES';
                @$_SESSION['detailroom_number'] = $row['room_number'];
            }
        } else {
            @$_SESSION['detailadmission_date'] = NULL;
            @$_SESSION['detailroom_number'] = NULL;
            @$_SESSION['detaildischarge_date'] = NULL;
            @$_SESSION['detailinhospital'] = 'NO';
        }
    }
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
                                    <h2>Patient Detail</h2>
                                    <p>Welcome to East Care Admin System <span class="bread-ntd"></span></p>
                                    <p>---------------------------------------------------------------------<span class="bread-ntd"></span></p>
                                    <form method="post" role="search" class="">


                                        <div style="width:200px;float:left;"><input name="searchtext" type="text" placeholder="Search Patient Name....." value="<?php
                                            if (isset($_SESSION['detailpagesearchtext'])) {
                                                print $_SESSION['detailpagesearchtext'];
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
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Single pro tab start-->
<form action="" method="post" name="form">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="single-product-pr">
                    <div class="row">
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                            <div id="myTabContent1" class="tab-content" style="width:400px" >




                                <table style="width: 100%;margin:auto;color: #fff">

                                    <tr>
                                        <th>Patient ID </th>
                                        <th>Patient Name </th>
                                        <!--<th><a style="color: #fff" href="product-detail.php?column=brand&order=<?php echo $asc_or_desc; ?>">CN STOCK <i class=" fa fa-sort<?php echo $column == 'brand' ? '-' . $up_or_down : ''; ?>"></i></a></th>
                                        <th><a style="color: #fff" href="product-detail.php?column=category&order=<?php echo $asc_or_desc; ?>">IN TRANSIT <i class="fa fa-sort<?php echo $column == 'category' ? '-' . $up_or_down : ''; ?>"></i></a></th> 
                                        <th><a style="color: #fff" href="product-detail.php?column=price&order=<?php echo $asc_or_desc; ?>">US STOCK <i class="fa fa-sort<?php echo $column == 'price' ? '-' . $up_or_down : ''; ?>"></i></a></th>-->
                                        <th>CHECK</th>


                                    </tr>



                                    <?php
                                    if ($totalrow != 0) {
                                        for ($i = 0; $i < $perpage; $i++) {
                                            $index = ($page - 1) * $perpage + $i;
                                            if ($index >= count($data))
                                                break;
                                            else {


                                                print "<td>{$data[$index]['patient_id']}</td>";
                                                print "<td>{$data[$index]['name']}</td>";
                                                // print "<td>{$data[$index]['shanghai']}</td>";
                                                // print "<td>{$data[$index]['transit']}</td>";
                                                // print "<td>{$data[$index]['nc']}</td>";
                                                $detail = "sss" . $index;
                                            }
                                            ?>

                                            <td>
                                                <button data-toggle="tooltip" name ="<?php print $detail; ?>"    type="submit" title="detail"  class="pd-setting-ed"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

                                            </td >  
                                            <?php
                                            print '</tr>';
                                        }
                                    }
                                    ?>
                                </table>
                                <div class="custom-pagination "  >
                                    <ul class="pagination ">

                                        <?php
                                        for ($i = 1; $i <= $totalpage; $i++) {
                                            if ($i == $page) {
                                                printf("<li ><a style='color:ff2' >%d</a></li>", $i);
                                            } else {
                                                printf("<li class='page-item'><a class='page-link' href='%s?page=%d'>%d</a></li>", $_SERVER["PHP_SELF"], $i, $i);
                                            }
                                        }
                                        ?>


                                    </ul>
                                </div>



                            </div>

                        </div>


                        <div class="col-lg-5 col-md-7 col-sm-7 col-xs-12">
                            <div class="single-product-details res-pro-tb">
                                <div class="single-pro-price">
                                    <span class="single-regular"><?php print "Patiend ID: " . @$_SESSION['patient_id']; ?></span>
                                </div>                                        
                                <span class="single-pro-star">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </span>
                                <h4 style="color:#fff" >    <?php print @$_SESSION['detailpatientname']; ?></h4>


                                <!--    
                                        $_SESSION['detailpatientinsuranceinfo'] = $row[4];
                                        $_SESSION['detailpatientinhospital'] = $row[5];
                                        $_SESSION['detailpatientaddress'] = $row[6];
                                        $_SESSION['detailpatientaddress2'] = $row[7];
                                        $_SESSION['detailpatientcity'] = $row[8];
                                        $_SESSION['detailpatientstate'] = $row[9];
                                        $_SESSION['detailpatientzipcode'] = $row[10];
                                        $_SESSION['detailpatientphone'] = $row[11];-->


                                <div class="single-pro-size">
                                    <h4 style="color:#fff" >Date of Birth:<?php print @$_SESSION['detailpatientbirth']; ?></h4>
                                    <h4 style="color:#fff"  >Gender: <?php print @$_SESSION['detailpatientgender']; ?></h4>
                                    <h4 style="color:#fff"  >Insurance Info: <?php print @$_SESSION['detailpatientinsuranceinfo']; ?></h4>
                                    <h4 style="color:#fff"  >In Hosipital: <?php print @$_SESSION['detailinhospital']; ?></h4>
                                    <h4 style="color:#fff"  >Room Info: <?php print @$_SESSION['detailroom_number']; ?></h4>
                                    <h4 style="color:#fff"  >Last admission date: <?php print @$_SESSION['detailadmission_date']; ?></h4>
                                    <h4 style="color:#fff"  >Last discharge date: <?php print @$_SESSION['detaildischarge_date']; ?></h4>


                                </div>
                                <div class="color-quality-pro">
                                    <div class="color-quality-details">
                                        <h5>Private Address:</h5>
                                        <h5 style="color:#fff" ><?php
                                            print @$_SESSION['detailpatientaddress'] . ' ' . @$_SESSION['detailpatientaddress2'] . ',' . @$_SESSION['detailpatientcity'] . ','
                                                    . @$_SESSION['detailpatientstate'] . ' ' . @$_SESSION['detailpatientzipcode'];
                                            ?></h5>                                                
                                        <h5 style="color:#fff"  >Cell: <?php print @$_SESSION['detailpatientphone']; ?></h5>

                                    </div>     
                                </div>

                                <div class="clear"></div>
                                <div >

                                    <div >
                                        <input type="submit" name='Admit' value="Admit/Discharge" >
                                        <input type="submit" name='Visit' value="Visit Record" >
                                        <input type="submit" name='Bill' value="Print Bill" >
                                    </div>

                                </div>
                                <div class="clear"></div>
                                <div class="single-social-area">
                                    <h3>share this on</h3>
                                    <a href="#"><i class="fa fa-facebook"></i></a>
                                    <a href="#"><i class="fa fa-google-plus"></i></a>
                                    <a href="#"><i class="fa fa-feed"></i></a>
                                    <a href="#"><i class="fa fa-twitter"></i></a>
                                    <a href="#"><i class="fa fa-linkedin"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</form>

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