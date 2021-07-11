<?php 
//Group Members: Sudeep Bhadel , Diwakar Parajuli
 class rental{
 public $startDate;
  public $returnDate;
  public $category;
  public $type;
  public $id;
  public $paydate;


  public function setStartDate($startDate){
    $this->startDate=$startDate;
  }
  public function setReturnDate($returnDate){
    $this->returnDate=$returnDate;
  }
  public function setCat($category){
    $this->category=$category;
  }
  public function setType($type){
    $this->type=$type;
  }
  public function setId($id){
    $this->id=$id;
  }
  public function setPayO($paydate){
    $this->paydate=$paydate;
  }
 }

$b = new rental();
// Creating a connection
$conn = new mysqli("127.0.0.1", "root", "Candy123!", "carrental2019");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$CategoryErr = $TypeErr = $PayErr = $IDErr =  "";

 if (isset($_POST['find'])){

   if (empty($_POST["Category"])) {
    $CategoryErr = "Category is required";
  } 
  if (empty($_POST["Type"])) {
    $TypeErr = "Type is required";
  }
  if (empty($_POST["options"])) {
    $TypeErr = "Type is required";
  }
  if (empty($_POST["CustID"])) {
    $TypeErr = "Type is required";
  }

 
  else{


$start = $_POST['Year']."-".$_POST['Month']."-".$_POST['Day'];
$return = $_POST['RYear']."-".$_POST['RMonth']."-".$_POST['RDay'];
$startDate = date("Y-m-d", strtotime($start));
$returnDate = date("Y-m-d", strtotime($return));
$msDate = date( "Y-m-d", strtotime( $startDate . "-1 day"));
$mrDate = date( "Y-m-d", strtotime( $returnDate . "-1 day"));
$category = $_POST['Category'] - 1;
$type = $_POST['Type'];
$id = $_POST['CustID'];
$paydate = $_POST['options'];

$b->setStartDate($startDate);
$b->setReturnDate($returnDate);
$b->setCat($category);
$b->setType($type);
$b->setId($id);
$b->setPayO($paydate);

//$renta = new rental($startDate,$returnDate,$orderDate,$type,$category,$id,$paydate);

$sql = "(SELECT Description FROM vehicle AS V,rental AS R
        WHERE V.Type = $type AND V.Category = $category AND V.VehicleID = R.VehicleID
        AND ((V.VehicleID NOT IN (SELECT VehicleID FROM rental WHERE StartDate BETWEEN '$startDate' AND '$mrDate'))
        AND (V.VehicleID NOT IN (SELECT VehicleID FROM rental WHERE ReturnDate BETWEEN '$msDate' AND '$returnDate')))
        GROUP BY V.VehicleID) 
        UNION 
        (SELECT Description FROM vehicle as V WHERE V.VehicleID not in (select VehicleID from rental) and V.Type = $type and V.Category = $category) ";

$result = $conn->query($sql);

if ( $result == TRUE) {

  echo "<form action='#' method='post'>";
  echo "<input type = 'hidden' name = 'startDate' value = '$b->startDate'>";
  echo "<input type = 'hidden' name = 'returnDate' value = '$b->returnDate'>";
  echo "<input type = 'hidden' name = 'category' value = '$b->category'>";
  echo "<input type = 'hidden' name = 'type' value = '$b->type'>";
  echo "<input type = 'hidden' name = 'id' value = '$b->id'>";
  echo "<input type = 'hidden' name = 'paydate' value = '$b->paydate'>";
 

  echo "Available Vehicles List<br>";

 echo "<select name ='AvailableVehicles'>";

 while ($row = $result->fetch_assoc()){

  echo "<option value = '{$row['Description']}'>{$row['Description']}</option>";
 }

echo "<input type='submit' name = 'Rent' value='Rent'>";
echo "</select>";
 echo "</form>";


    
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
 

}
}

if (isset($_POST['Rent'])) {



$des = $_POST['AvailableVehicles'];
$startDate = $_POST['startDate'];
$returnDate = $_POST['returnDate'];
$category = $_POST['category'];
$type = $_POST['type'];
$id = $_POST['id'];
$paydate = $_POST['paydate'];
$loop = 0;
$orderDate = date("Y-m-d ");


$sqlrate = "SELECT Weekly, Daily FROM rate WHERE Type = $type and Category = $category";
$rate = $conn->query($sqlrate);
while ($row = $rate->fetch_assoc()){

  $weekly = $row["Weekly"];
  $daily = $row ["Daily"];
}

 $sqlvid = "SELECT VehicleID FROM vehicle WHERE Description = '$des'";

$vin = $conn->query($sqlvid);

while ($row = $vin ->fetch_assoc()){
  $vin_no = $row["VehicleID"];
}

$diff = strtotime($startDate) - strtotime($returnDate);
$numDays = ceil(abs($diff/86400));
//$numDays = (($returnDate - $startDate)/60/60/24);

if ($numDays < 7)
{
  $rentalType = 1;
  $qty = $numDays;
  $amount = $numDays * $daily;
}
else {
  
  
  if ($numDays%7 != 0){

    $rentalType = 1;
    $qty = $numDays;
    //$temp1 = (int)$numDays/7;
    //$a1 = $weekly *$temp1;
    $amount = $qty * $daily;

  }
  else {
      $rentalType = 7;
      $qty = $numDays/7;
      $amount = $weekly * $qty;
  }
  
}


if ($paydate == 1)
{
  $pdate = date("Y-m-d ");
  $sql_in = "INSERT INTO rental (CustID,VehicleID, StartDate,OrderDate, RentalType, Qty, ReturnDate,TotalAmount,PaymentDate) 
                  VALUES('$id','$vin_no','$startDate','$orderDate',$rentalType,$qty,'$returnDate',$amount,'$pdate');";
}

else {
  $sql_in = "INSERT INTO rental (CustID,VehicleID, StartDate,OrderDate, RentalType, Qty, ReturnDate,TotalAmount) 
                  VALUES('$id','$vin_no','$startDate','$orderDate',$rentalType,$qty,'$returnDate',$amount);";
}

$result = $conn->query($sql_in);

if ( $result == TRUE) {

  echo "Car is reserved <br>"; 
  echo "Save the VehicleID for Rental purpose ". $vin_no;
 
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
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
<?php $TypeErr = $CategoryErr = ""; ?>
<h2>Rental Reservation</h2>
<p><span class="error">* Required Field</span></p>

<form action="#" method="post" >
<p>Select Start Date</p>
Year: <select name="Year">
<?php 
   for($i = 2019 ; $i < 2022; $i++){
      echo "<option>$i</option>";
   }
?>
</select>
Month: <select name="Month">
<?php 
   for($i = 01 ; $i < 13; $i++){
      echo "<option>$i</option>";
   }
?>
</select>
Day: <select name="Day">
<?php 
   for($i = 01 ; $i < 32; $i++){
      echo "<option>$i</option>";
   }
?>
</select><br><br>
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

</select><br>
<p>Select Category and Type of Vehicle to Rent</p>
Category:
  <input type="radio" name="Category" value="1">Basic
  <input type="radio" name="Category" value="2">Luxury
  <span class="error">* <?php echo $CategoryErr;?></span>
  <br><br>
Type:
  <input type="radio" name="Type" value="1">Compact
  <input type="radio" name="Type" value="2">Medium
  <input type="radio" name="Type" value="3">Large
  <input type="radio" name="Type" value="4">SUV
  <input type="radio" name="Type" value="5">Truck
  <input type="radio" name="Type" value="6">Van
  <span class="error">* <?php echo $TypeErr;?></span>
  <br><br>
Payment Options:
  <input type="radio" name="options" value="1">Now
  <input type="radio" name="options" value="2">At Return
  <span class="error">* <?php echo $PayErr;?></span>
  <br><br> 
Customer ID:
    <input type="number" name="CustID">
    <span class="error">* <?php echo $IDErr;?></span>
  <br><br>
<input type="submit" name = "find" value="Find available Vehicles">
<?php
echo "<a href=http://localhost/Code/main_page.php>Back</a><br><br>"
?>


</form>

 </body>
</html>