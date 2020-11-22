<?php
require 'header.php';
if (isset($_SESSION['Billid'])) {
    $patient_id = $_SESSION['Billid'];
} else {
    header('location:patient-detail.php');
}

$sql = "SELECT visitrecord_id, `visit_date`, `doctor_id`,`patient_id`,`disease_info`,`procedure_name`,`medicine_info`,`visit_bill` FROM visitrecord NATURAL JOIN medicine NATURAL JOIN procedur NATURAL JOIN disease where patient_id = '" . $patient_id . "' ORDER BY visitrecord_id DESC";
$result = mysqli_query($conn, $sql);
$totalrow = mysqli_num_rows($result);
if ($totalrow != 0) {
    while ($arr = mysqli_fetch_array($result)) {
        $data[] = $arr;
    }
}
$str1 = date("Y-m-d", time());


$sql = "select hospitalization_id ,patient_id,admission_date, discharge_date, hospitalizationrecord.room_number, type_info,type_price from hospitalizationrecord join roomtype join typed where hospitalizationrecord.room_number=roomtype.room_number and roomtype.type_id=typed.type_id and  patient_id ='" . $patient_id . "' order by hospitalization_id DESC";
$result = mysqli_query($conn, $sql);
$totalrows = mysqli_num_rows($result);

if ($totalrows > 0) {
    $row = mysqli_fetch_array($result);
    @$hospitalization_id = $row['hospitalization_id'];
    @$admission_date = $row['admission_date'];
    @$discharge_date = $row['discharge_date'];
    @$room_number = $row['room_number'];
    @$type_info = $row['type_info'];
    @$type_price = $row['type_price'];
    if (@$discharge_date != NULL) {
        @$days = floor((strtotime($discharge_date) - strtotime($admission_date)) / 86400);
        @$hospitalfee = $days * $type_price;
    } else {
        @$discharge_date = $str1;
        @$days = floor((strtotime($discharge_date) - strtotime($admission_date)) / 86400);
        @$hospitalfee = $days * $type_price;
    }
} else {
    @$hospitalization_id = NULL;
    @$admission_date = NULL;
    @$discharge_date = NULL;
    @$room_number = NULL;
    @$type_info = NULL;
    @$type_price = NULL;
    @$days=NULL;
    @$hospitalfee = NULL;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Receipt</title>
        <style>
            body, html {
                height: 100%;
            }
            .bg {                
                height: 100%;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
            }
            #qrbox>div {
                margin: auto;
            }
        </style>
    </head>
    <body class="bg">
        <div class="container" id="panel">            
            <div class="row">
                <div class="col-md-6 " style="background: white; padding: 0px;">
                    <div class="panel-heading">
                        
                        <h2>Receipt</h2>
                        <h3>Visit Record</h3>
                        <table >

                            <tr>
                                <th><a  >Visit Record Number </a></th>
                                <th><a >Visit Date </a></th>
                                <th><a  >Doctor </a></th>
                                <th><a  >Patient</a></th>
                                <th><a >Disease </a></th>
                                <th><a  >Procedure </a></th>
                                <th><a  >Medicine </a></th>
                                <th><a  >Visit Bill</a></th>


                            </tr>
                            <?php
// if ($totalrow != 0) {
//    for ($i = 0; $i < $perpage; $i++) {
//       $index = ($page - 1) * $perpage + $i;
//      if ($index >= count($data))
//           break;
//      else {', '', '', '', '', ''
                            $totalvist = 0;
                            for ($index = 0; $index < @count($data); $index++) {
                                print '<tr>';
                                print "<td>{$data[$index][0]}</td>";
                                print "<td>{$data[$index][1]}</td>";
                                print "<td>{$data[$index][2]}</td>";
                                print "<td>{$data[$index][3]}</td>";
                                print "<td>{$data[$index][4]}</td>";
                                print "<td>{$data[$index][5]}</td>";
                                print "<td>{$data[$index][6]}</td>";
                                print "<td>{$data[$index][7]}</td>";
                                $totalvist = $totalvist + $data[$index][7];
                                print '</tr>';
                            }
                            ?>
                        </table>
                        <p><i>-------------------------------------Visit fee: <?php print $totalvist; ?></i></p>
                        <br>
                        <h3>Hosipitalization Record</h3>
                        <table >

                            <tr>
                                <th><a  >Hospitalization_id</a></th>
                                <th><a >Admission time</a></th>
                                <th><a >Discharge time</a></th>
                                <th><a >Days</a></th>
                                <th><a  >Room Number </a></th>
                                <th><a  >Room Type</a></th>
                                <th><a  >Room Daily Price </a></th>
                                <th><a >Bill </a></th>

                            </tr>
                            <?php
                            print '<tr>';
                            print "<td>{$hospitalization_id}</td>";
                            print "<td>{$admission_date}</td>";
                            print "<td>{$discharge_date}</td>";
                            print "<td>{$days }</td>";
                            print "<td>{$room_number }</td>";
                            print "<td>{$type_info}</td>";
                            print "<td>{$type_price}</td>";
                            print "<td>{$hospitalfee}</td>";
                            $totalvist = $totalvist + $hospitalfee;
                            print '</tr>';
                            ?>
                        </table>
                        <p><i>Total fee: <?php print $totalvist; ?></i></p>
                        <img src="img/uni.jpg" width="150" height="150">
                        <p><i>Email:contact@eastcare.com</i></p>
                        <p><i>TEL:+1(800)est-care</i></p>
                        
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>