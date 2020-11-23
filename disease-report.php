<?php
require 'header.php';

$str1 = date("Y-m-d", time());


$sql = "select hospitalization_id, admission_date, discharge_date from hospitalizationrecord ";
$result = mysqli_query($conn, $sql);
$totalrow = mysqli_num_rows($result);

while ($arr = mysqli_fetch_array($result)) {
    $data[] = $arr;
}

for($i=0;$i<count($data);$i++){
    $hspital_i=$data[$i][0];
    $admittime=$data[$i][1];
    $dischagetime=$data[$i][2];
    if($dischagetime==NULL){
        $dischagetime=$str1;
    }
    $days = floor((strtotime($dischagetime) - strtotime($admittime)) / 86400);
    $sql="UPDATE hospitalizationrecord SET timeinterval='".$days."' where hospitalization_id='".$data[$i][0]."'" ;
    $result = mysqli_query($conn, $sql);
}

$sql="SELECT disease_id,disease_info, AVG(timeinterval) FROM hospitalizationrecord natural join disease GROUP BY disease_id order by disease_id ";
$result = mysqli_query($conn, $sql);
while ($arr = mysqli_fetch_array($result)) {
    $diseasedata[] = $arr;
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

                        <h2>Average length of stay in terms of disease</h2>
                        
                        <table >

                            <tr>
                                <th><a  >Disease ID</a></th>
                                <th><a >Disease Info </a></th>
                                <th><a  >Average length of stay </a></th>
                            </tr>
<?php
// if ($totalrow != 0) {
//    for ($i = 0; $i < $perpage; $i++) {
//       $index = ($page - 1) * $perpage + $i;
//      if ($index >= count($data))
//           break;
//      else {', '', '', '', '', ''
$totalvist = 0;
for ($index = 0; $index < @count($diseasedata); $index++) {
    print '<tr>';
    print "<td>{$diseasedata[$index][0]}</td>";
    print "<td>{$diseasedata[$index][1]}</td>";
    print "<td>{$diseasedata[$index][2]}</td>";
    print '</tr>';
}
?>
                        </table>
                        
                        <img src="img/uni.jpg" width="150" height="150">
                        <p><i>Email:contact@eastcare.com</i></p>
                        
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>