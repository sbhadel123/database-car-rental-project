<?php 
//Group Members: Sudeep Bhadel , Diwakar Parajuli
$VehicleIDErr = $BrandNameErr = $ModelErr = $TypeErr = $CategoryErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
  if (empty($_POST["VehicleID"])) {
    $VehicleIDErr = "VehicleID is required";
  } 

  if (empty($_POST["BrandName"])) {
    $BrandNameErr = "Brand Name is required";
  } 
  if (empty($_POST["Model"])) {
    $ModelErr = "Model is required";
  } 
   if (empty($_POST["Category"])) {
    $CategoryErr = "Category is required";
  } 
  if (empty($_POST["Type"])) {
    $TypeErr = "Type is required";
  } 
 
  else{
  // Creating a connection
$conn = new mysqli("127.0.0.1", "root", "Candy123!", "carrental2019");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$vin = $_POST['VehicleID'];
$brand = $_POST['BrandName'];
$model = $_POST['Model'];
$description = $brand." ".$model;
$year = $_POST['Year'];
$category = $_POST['Category'] - 1;
$type = $_POST['Type'];

$sql = "INSERT INTO vehicle VALUES ('$vin','$description','$year','$type','$category')";

if ($conn->query($sql) === TRUE) {
    echo "New Vehicle added successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();


}
 }

 ?>

<!DOCTYPE HTML>
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>
<h2>Add New Vehicle</h2>
<p><span class="error">* Required Field</span></p>

<form action="#" method="post" >
VehicleID: <input type="text" name="VehicleID">
<span class="error">* <?php echo $VehicleIDErr;?></span>
  <br><br>
BrandName: <input type="text" name="BrandName">
<span class="error">* <?php echo $BrandNameErr;?></span>
  <br><br>
Model: <input type="text" name="Model">
<span class="error">* <?php echo $ModelErr;?></span>
  <br><br>

Year: <select name="Year">
<?php 
   for($i = 2010 ; $i <= date('Y'); $i++){
      echo "<option>$i</option>";
   }
?>
</select><br><br>
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
<input type="submit" value="Add Vehicle">
<?php
echo "<a href=http://localhost/Code/main_page.php>Back</a>"; ?>
</form>


</body>
</html>