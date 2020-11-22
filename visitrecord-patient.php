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

$datanote = check_note($cmpid);
$totalnotes = sizeof($datanote);

if (isset($_POST["back"])){
    header('location:patient-detail.php');
}

$columns = array('visitrecord_id', 'visit_date','patient_name','docotor_name','visit_bill','disease','procedure','medicine');
$column = isset($_GET['column']) && in_array($_GET['column'], $columns) ? $_GET['column'] : $columns[0];
$sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';
//$perpage = 20;

if (isset($_SESSION['Visitid'])){
    $patient_id=$_SESSION['Visitid'];
}else{
    header('location:patient-detail.php');
}
// var_dump($patient_id);
$sql = "select * from patientvisitdoctor where patient_id LIKE '%" . $_SESSION['Visitid'] . "%' ORDER BY " . $column . ' ' . $sort_order;

$result = mysqli_query($conn, $sql);
// var_dump($sql);
// var_dump($result);
$totalrow = mysqli_num_rows($result);

if ($totalrow != 0) {
    $up_or_down = str_replace(array('ASC', 'DESC'), array('up', 'down'), $sort_order);
    $asc_or_desc = $sort_order == 'ASC' ? 'desc' : 'asc';
    $add_class = ' class="highlight"';

    while ($arr = mysqli_fetch_array($result)) {
        $data[] = $arr;
    }
}

?>

<?php

for ($i = 0; $i < @count(@$data); $i++) {
    $tem = "trash" . $i;
    if (isset($_REQUEST["{$tem}"])) {
        $_REQUEST["{$tem}"] = 0;
        $sqldelvisitrecord = "DELETE FROM `visitrecord` WHERE visitrecord_id='" . $data[$i]['visitrecord_id'] . "'";
        mysqli_query($conn, $sqldelvisitrecord);
        $sqldelpatientvisitdoctor = "DELETE FROM `patientvisitdoctor` WHERE visitrecord_id='" . $data[$i]['visitrecord_id'] . "'";
        mysqli_query($conn, $sqldelpatientvisitdoctor);
        $sqldeldiagnosis = "DELETE FROM `diagnosis` WHERE visitrecord_id='" . $data[$i]['visitrecord_id'] . "'";
        mysqli_query($conn, $sqldeldiagnosis);
        header('location: ' . $_SERVER['HTTP_REFERER']);
        break;
    }
}


//编辑后获取sku存入session在edit界面调取
//for ($i = 0; $i < $perpage; $i++) {
// $ind = ($page - 1) * $perpage + $i;
// if ($ind >= count($data))
//   break;
//  else {

for ($i = 0; $i < @count(@$data); $i++) {
    $tem = "edit" . $i;
    if (isset($_REQUEST["{$tem}"])) {
        $_REQUEST["{$tem}"] = 0;
        $_SESSION['editsku'] = $data[$i]['visitrecord_id'];
        // var_dump($_SESSION['editsku']);
        header('location:visitrecord-edit.php');
        break;
    }
}

require_once 'sidebar.php';
?>

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
                                    <h2>Patinet Visit Record List</h2>
                                    <p>Welcome to East Care Administration System <span class="bread-ntd"></span></p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="product-status mg-b-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap">
                    <h4>Visit Record List </h4>
                    <div class="add-product" >                                    

                       <!--  <a  href="visitrecord-edit.php">Print Bill</a> -->
                    </div>
                    <div>
                        <div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
                            <div class="header-top-menu tabl-d-n">
                                <div class="breadcome-heading">
                                    <form method="post" role="search" class="">


                                       <!--  <div style="width:200px;float:left;"><input name="searchtext" type="text" placeholder="Search visitrecord number....." value="<?php
                                            if (isset($_SESSION['visitrecord-list_searchtext'])) {
                                                print $_SESSION['visitrecord-list_searchtext'];
                                            }
                                            ?>" ></div> -->
                                        <div style="color:#fff;width:000px;float:left;">
                                           <!--  <button name="search" type="submit" value="search" class="pd-setting-ed"><i class="fa fa-search-plus" aria-hidden="true"></i></button> -->

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form action="" method="post" name="form">


                        <table >

                            <tr>
                                <th><a style="color: #fff" href="visitrecord-list.php?column=visitrecord_id&order=<?php echo $asc_or_desc; ?>">Visit Record Number <i class=" fa fa-sort<?php echo $column == 'visitrecord_id' ? '-' . $up_or_down : ''; ?>"></i></a></th>
                                <th><a style="color: #fff" href="visitrecord-list.php?column=visit_date&order=<?php echo $asc_or_desc; ?>">Visit Date <i class=" fa fa-sort<?php echo $column == 'visit_date' ? '-' . $up_or_down : ''; ?>"></i></a></th>
                                <th><a style="color: #fff" href="visitrecord-list.php?column=doctor_name&order=<?php echo $asc_or_desc; ?>">Doctor <i class=" fa fa-sort<?php echo $column == 'doctor_name' ? '-' . $up_or_down : ''; ?>"></i></a></th>
                                <th><a style="color: #fff" href="visitrecord-list.php?column=patient_name&order=<?php echo $asc_or_desc; ?>">Patient <i class=" fa fa-sort<?php echo $column == 'patient_name' ? '-' . $up_or_down : ''; ?>"></i></a></th>
                                <th><a style="color: #fff" href="visitrecord-list.php?column=disease_name&order=<?php echo $asc_or_desc; ?>">Disease <i class=" fa fa-sort<?php echo $column == 'disease_name' ? '-' . $up_or_down : ''; ?>"></i></a></th>
                                <th><a style="color: #fff" href="visitrecord-list.php?column=procedure_name&order=<?php echo $asc_or_desc; ?>">Procedure <i class=" fa fa-sort<?php echo $column == 'procedure_name' ? '-' . $up_or_down : ''; ?>"></i></a></th>
                                <th><a style="color: #fff" href="visitrecord-list.php?column=medicine_info&order=<?php echo $asc_or_desc; ?>">Medicine <i class=" fa fa-sort<?php echo $column == 'medicine_info' ? '-' . $up_or_down : ''; ?>"></i></a></th>
                                
                                <th><a style="color: #fff" href="visitrecord-list.php?column=visit_bill&order=<?php echo $asc_or_desc; ?>">Visit Bill<i class=" fa fa-sort<?php echo $column == 'visit_bill' ? '-' . $up_or_down : ''; ?>"></i></a></th>
  
                                <th>Setting</th>
                            </tr>



                            <?php
// if ($totalrow != 0) {
//    for ($i = 0; $i < $perpage; $i++) {
//       $index = ($page - 1) * $perpage + $i;
//      if ($index >= count($data))
//           break;
//      else {', '', '', '', '', ''
                            for ($index = 0; $index < @count($data); $index++) {
                                print '<tr>';
                                print "<td>{$data[$index]['visitrecord_id']}</td>";
                                // print "<td>{$data[$index]['visit_date']}</td>";
                                $sql="select visit_date from visitrecord NATURAL JOIN patientvisitdoctor WHERE visitrecord_id='" . $data[$index]['visitrecord_id'] . "'";
                                $result=mysqli_query($conn, $sql);
                                $row = mysqli_fetch_array($result);
                                print "<td>$row[0]</td>"; 
                                $sql="select  doctor.name from doctor NATURAL JOIN patientvisitdoctor NATURAL JOIN visitrecord WHERE visitrecord_id='" . $data[$index]['visitrecord_id'] . "'";
                                $result = mysqli_query($conn, $sql);
                                // var_dump($sql);
                                $row = mysqli_fetch_array($result);
                                print "<td>$row[0]</td>";          

                                $sql="select  patient.name from patient NATURAL JOIN patientvisitdoctor NATURAL JOIN visitrecord WHERE visitrecord_id='" . $data[$index]['visitrecord_id'] . "'";
                                $result = mysqli_query($conn, $sql);
                                // var_dump($sql);
                                $row = mysqli_fetch_array($result);
                                print "<td>$row[0]</td>";  

                                $sql="select  disease.disease_info from disease NATURAL JOIN diagnosis WHERE visitrecord_id='" . $data[$index]['visitrecord_id'] . "'";
                                $result = mysqli_query($conn, $sql);
                                // var_dump($sql);
                                $row = mysqli_fetch_array($result);
                                print "<td>$row[0]</td>";  

                                $sql="select  procedur.procedure_name from procedur NATURAL JOIN diagnosis WHERE visitrecord_id='" . $data[$index]['visitrecord_id'] . "'";
                                $result = mysqli_query($conn, $sql);
                                // var_dump($sql);
                                $row = mysqli_fetch_array($result);
                                print "<td>$row[0]</td>";  

                                $sql="select  medicine.medicine_info from medicine NATURAL JOIN diagnosis WHERE visitrecord_id='" . $data[$index]['visitrecord_id'] . "'";
                                $result = mysqli_query($conn, $sql);
                                // var_dump($sql);
                                $row = mysqli_fetch_array($result);
                                print "<td>$row[0]</td>";  

                                // print "<td>{$data[$index]['visit_bill']}</td>";
                                $sql="select visit_bill from visitrecord NATURAL JOIN patientvisitdoctor WHERE visitrecord_id='" . $data[$index]['visitrecord_id'] . "'";
                                $result=mysqli_query($conn, $sql);
                                $row = mysqli_fetch_array($result);
                                print "<td>$row[0]</td>"; 
                                $edit = "edit" . $index;
                                $trash = "trash" . $index;
                                ?>

                                <td>
                                    <button data-toggle="tooltip" name ="<?php print $edit; ?>"    type="submit" title="Edit" onclick="return confirmation()" class="pd-setting-ed"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                    <button data-toggle="tooltip" name ="<?php print $trash; ?>"     type="submit" title="Edit" onclick="return confirmation()" class="pd-setting-ed"><i class="fa fa-trash-o" aria-hidden="true"></i></button>

                                </td >  
                                </tr>
                                <?php
                            }
                            ?>
                        </table><!--
<div class="custom-pagination "  >
    <ul class="pagination ">

                        <?php
                        for ($i = 1; $i <= $totalpage; $i++) {
                            if ($i == $page) {
                                printf("<li ><a >%d</a></li>", $i);
                            } else {
                                printf("<li class='page-item'><a class='page-link' href='%s?page=%d'>%d</a></li>", $_SERVER["PHP_SELF"], $i, $i);
                            }
                        }
                        ?>


    </ul>
</div>-->
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="text-center custom-pro-edt-ds">
                                                <input name="back" type="submit" class="btn btn-ctl-bt waves-effect waves-light m-r-10" value="Patient Detail">
                                                <input name="bill" type="submit" class="btn btn-ctl-bt waves-effect waves-light m-r-10" value="Print Bill">
                                                
                                            </div>
                                        </div>
                                    </div>
                    </form>
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

                                        // function confirmation(url) {

                                        //     return confirm('Are you sure?');
                                        // }


</script>
</body>

</html>