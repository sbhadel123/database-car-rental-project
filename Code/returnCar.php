<?php 
//Group Members: Sudeep Bhadel , Diwakar Parajuli

// Creating a connection
$conn = new mysqli("127.0.0.1", "root", "Candy123!", "carrental2019");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$NameErr = $vinErr = "";

if (isset($_POST['Search'])) {
 
  if (empty($_POST["FName"])) {
    $NameErr = "First Name is required";
  } 
  if (empty($_POST["LName"])) {
    $NameErr = "Last Name is required";
  } 

    if (empty($_POST["vin"])) {
    $PhoneErr = "VIN is required";
  } 
  else{
  
$fname = $_POST['FName'];
$lname = $_POST['LName'];
$fini = substr($fname,0,1);
$name = $fini."."." ".$lname;
$vin = $_POST['vin'];
$return = $_POST['RYear']."-".$_POST['RMonth']."-".$_POST['RDay'];
$returnDate = date("Y-m-d", strtotime($return));


$sql = "SELECT C.CustID,R.TotalAmount,R.PaymentDate FROM rental AS R, customer AS C 
        WHERE C.Name = '$name'AND C.CustID = R.CustID 
        AND R.ReturnDate = '$return' AND R.VehicleID = '$vin'";

$result = $conn->query($sql);

while ($row = $result ->fetch_assoc()){
  $ID = $row["CustID"];
  $Amount = $row["TotalAmount"];
  $paydt = $row["PaymentDate"];
 /*
  if ($returnDate == $row["StartDate"] && $vin == $row["VehicleID"])
  {
    $loop = 1;

  }
  else {
    $loop = 0;
  }
  */
}

if ($paydt == NULL){
echo $name." your total amount due is ".$Amount;
}
else{
  echo $name." your balance of ".$Amount." has already been paid.";
}

echo "<form action='#' method='post'>";
  echo "<input type = 'hidden' name = 'name' value = '$name'>";
  //echo "<input type = 'hidden' name = 'loop' value = '$loop'>";
  echo "<input type = 'hidden' name = 'ID' value = '$ID'>";
  echo "<input type = 'hidden' name = 'paydt' value = '$paydt'>";
  echo "<input type = 'hidden' name = 'vin' value = '$vin'>";
  echo "<input type = 'hidden' name = 'returnDate' value = '$returnDate'>";
  
echo "<input type='submit' name = 'Pay' value='Return'>";
echo "</select>";
 echo "</form>";

}
 }


if (isset($_POST['Pay'])){

$name = $_POST['name'];
$ID = $_POST['ID'];
$vin = $_POST['vin'];
$paydt = $_POST['paydt'];
$returnDate = $_POST['returnDate'];
//$loop = $_POST['loop'];


if ($paydt == NULL){

  $payDate = date("Y-m-d ");
  $sql1 = "UPDATE rental 
      SET PaymentDate = '$payDate' , Returned = 1
    WHERE CustID = $ID and rental.ReturnDate = '$returnDate' 
    and rental.VehicleID= '$vin';";

    $result1 = $conn->query($sql1);
/*
if ($loop == 1){
  $sql2 = "UPDATE rental 
      SET PaymentDate = '$payDate' , Returned = 1
    WHERE CustID = $ID and rental.StartDate = '$returnDate' 
    and rental.VehicleID= '$vin';";

    $result2 = $conn->query($sql2);
}

*/
if ($result1 == TRUE){
echo "Thank You for your Payment.";
}else {
    echo "Error: " . $sql1 . "<br>" . $conn->error;
}

}
else {

  $sql2 = "UPDATE rental 
      SET Returned = 1
    WHERE CustID = $ID and rental.ReturnDate = '$returnDate' 
    and rental.VehicleID= '$vin';";

    $result2 = $conn->query($sql2);
/*
if ($loop == 1){
  $sql3= "UPDATE rental 
      SET Returned = 1
    WHERE CustID = $ID and rental.StartDate = '$returnDate' 
    and rental.VehicleID= '$vin';";

    $result3 = $conn->query($sql3);
}
*/
if ($result2 == TRUE){
echo "Vehicle has been returned.";
}
else {
    echo "Error: " . $sql2 . "<br>" . $conn->error;
}
}




}
 $conn->close();

 ?>

<!DOCTYPE HTML>
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>
<h2>Return Vehicle</h2>
<p><span class="error">* Required Field</span></p>

<form action="#" method="post" >
First Name: <input type="text" name="FName">
<span class="error">* <?php echo $NameErr;?></span>
Last Name: <input type="text" name="LName">
<span class="error">* <?php echo $NameErr;?></span>
  <br>

<p>Select Return Date</p>
Year: <select name="RYear">
<?php 
   for($i = 2019 ; $i < 2022; $i++){
      echo "<option>$i</option>";
   }
?>
</select>
Month: <select name="RMonth">
<?php 
   for($i = 01 ; $i < 13; $i++){
      echo "<option>$i</option>";
   }
?>
</select>
Day: <select name="RDay">
<?php 
   for($i = 01 ; $i < 32; $i++){
      echo "<option>$i</option>";
   }
?>
</select><br><br>
VIN: <input type="text" name="vin"><br>
<p>Enter the Vehicle Identification of Rented Car</p>
<span class="error">* <?php echo $vinErr;?></span>
  <br>
<input type="submit" name = "Search" value="Search">
<?php echo "<a href=http://localhost/Code/main_page.php>Back</a>" ?>

</form>

</body>
</html>