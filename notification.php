<?php
require_once 'header.php';
check_session_expiration();
$user = $_SESSION['user_info']['userid'];
$fn = $_SESSION['user_info']['firstname'];
$ln = $_SESSION['user_info']['lastname'];
$useroffice = $_SESSION['user_info']['office'];
$userlevel = $_SESSION['user_info']['level'];           //userlevel  0: admin; else;
$cmpid = $_SESSION['user_info']['cmpid'];
$childid = $_SESSION['user_info']['childid'];

$columns = array('date', 'notes', 'status', 'subject');

//$perpage = 20;
if (!isset($_SESSION['notifysearchtext'])) {
    $_SESSION['notifysearchtext'] = '';
}
if (isset($_POST['search'])) {
    $_SESSION['notifysearchtext'] = $_POST['searchtext'];
}
$sql = "SELECT * FROM note where status= '1' AND (notes LIKE '%" . $_SESSION['notifysearchtext'] . "%' OR subject LIKE '%" . $_SESSION['notifysearchtext'] . "%') ORDER BY date DESC";
$result = mysqli_query($conn, $sql);
$totalnotes = mysqli_num_rows($result);
//$totalpage = ceil($totalrow / $perpage);
if ($totalnotes != 0) {
    while ($arr = mysqli_fetch_array($result)) {
        $datanote[] = $arr;
    }
}

//编辑后获取sku存入session在edit界面调取
for ($i = 0; $i < @count(@$datanote); $i++) {
    $tem = "edit" . $i;
    $nnote = "note" . $i;
    if (isset($_REQUEST["{$tem}"])) {
        $_REQUEST["{$tem}"] = 0;
        $nts = $datanote[$i]['notes'];
        $apend = $nts . $str . ">>>" . $fn . " " . $ln . ": " . $_POST["$nnote"] . "<br>";
        $sql = "UPDATE note SET notes='{$apend}', date=CURRENT_TIME WHERE date='" . $datanote[$i]['date'] . "'";
        mysqli_query($conn, $sql);
        header('location: ' . $_SERVER['HTTP_REFERER']);
        break;
    }
}


for ($i = 0; $i < @count(@$datanote); $i++) {
    $tem = "trash" . $i;
    if (isset($_REQUEST["{$tem}"])) {
        $_REQUEST["{$tem}"] = 0;
        $sql = "UPDATE note SET status='0' WHERE date='" . $datanote[$i]['date'] . "'";
        mysqli_query($conn, $sql);

        header('location: ' . $_SERVER['HTTP_REFERER']);
        break;
    }
}
?>

<?php
if (isset($_POST['submit'])) {
    $tem = $str . ">>>" . $fn . " " . $ln . ": " . $_POST['password'] . "<br>";
    $sql = "INSERT INTO note (date,notes,subject,status) VALUES ('" . $str . "','" . $tem . "','" . $_POST['username'] . "','1')";
    mysqli_query($conn, $sql);
    header('location: ' . $_SERVER['HTTP_REFERER']);
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
                                                    <i class="icon nalika-alarm"></i>
                                                </div>
                                                <div class="breadcomb-ctn">
                                                    <h2>Notification</h2>
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
                <form action="" method="post" name="form">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="product-status-wrap">
                                    <h4>To Do List </h4>
                                    <div class="add-product" >                                    

                                        <a  href="#new">Add New Notification</a>
                                    </div>
                                    <div>
                                        <div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
                                            <div class="header-top-menu tabl-d-n">
                                                <div class="breadcome-heading">


                                                    <div style="width:200px;float:left;"><input name="searchtext" type="text" placeholder="Search Content....." value="<?php
                                                        if (isset($_SESSION['notifysearchtext'])) {
                                                            print $_SESSION['notifysearchtext'];
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

                                    <form method="post" role="search" class="">

                                        <table >

                                            <tr>
                                            <th>Date</th>
                                            <th>Subject</th>
                                            <th>Note</th>
                                            <th>New Message</th>
                                            <th>Setting</th>



                                            </tr>



                                            <?php
                                            for ($index = 0; $index < @count(@$datanote); $index++) {
                                                print '<tr>';
                                                print "<td>{$datanote[$index]['date']}</td>";
                                                print "<td>{$datanote[$index]['subject']}</td>";
                                                print "<td>{$datanote[$index]['notes']}</td>";
                                                $newnote = "note" . $index;
                                                $edit = "edit" . $index;
                                                $trash = "trash" . $index;
                                                ?>
                                                <td><input name="<?php print $newnote; ?>" type="text" class="form-control"></td>
                                                <td>

                                                <button data-toggle="tooltip" name ="<?php print $edit; ?>"    type="submit" title="Update" onclick="return confirmation()" class="pd-setting-ed"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                                <button data-toggle="tooltip" name ="<?php print $trash; ?>"     type="submit" title="Done" onclick="return confirmation()" class="pd-setting-ed"><i class="fa fa-trash-o" aria-hidden="true"></i></button>

                                                </td >  
                                                </tr>
                                                <?php
                                            }
                                            ?>                                           
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>               



                    <div class="row">

                        <div class="col-md-12">

                            <div class="panel-body">          
                                <form method="post">
                                    <div>
                                        <h4>Add New Notification</h4>
                                        <label class="control-label" for="username">Subject</label>
                                        <input type="text" placeholder="Please enter the subject" title="" required="" value="" name="username" id="new" class="form-control">
                                     </div>
                                    <label class="control-label">Note</label>
                                    <input type="text" title="" placeholder="Notes" required="" value="" name="password" id="new" class="form-control">


                                    <div>
                                        <input type="submit" name="submit" value="Submit" class="btn btn-success btn-block loginbtn">  

                                    </div>

                                </form>
                            </div>

                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                    </div>

                    <!--div class="col-md-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="product-status-wrap">
                            <h4>Add New Notification</h4>
    
                            <div class="form-group">
                                <label class="control-label" style="color:#fff">Order No.</label>
                                
                            </div>
                        </div>
                    </div-->
                    <div class="footer-copyright-area">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-mg-3">
                                    <div class="footer-copy-right">
                                        <p>Copyright © 2019 <a href="https://www.eastcare.tech">EastCare</a> All rights reserved.</p>
                                    </div>
                                </div>
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

                                                    function confirmation(url) {

                                                        return confirm('Are you sure?');
                                                    }


        </script>
    </body>

</html>