
<?php 
//Group Members: Sudeep Bhadel , Diwakar Parajuli
// Creating a connection
$conn = new mysqli("127.0.0.1", "root", "Candy123!", "carrental2019");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


if (isset($_POST['Search'])) {
 
  if (!empty($_POST["Description"]) && empty($_POST["VIN"])) {

    $des = $_POST['Description'];
    $description = "%".$des."%";
    $vin = "%%";
    
  } 
  elseif (!empty($_POST["VIN"]) && empty($_POST["Description"])) {

    $vin = $_POST['VIN'];
    $description = "%%"; 
  }

  elseif (!empty($_POST["VIN"]) && !empty($_POST["Description"])) {
    $des = $_POST['Description'];
    $description = "%".$des."%";
    $vin = $_POST['VIN'];
  }
  else {
    $vin = "%%";
    $description = "%%"; 
  }
  

$sql = "SELECT V.VehicleID,V.Description,ROUND(AVG(TotalAmount / (R.RentalType * R.Qty)),2) AS AverageDaily
        FROM vehicle AS V LEFT OUTER JOIN rental AS R ON (V.VehicleID = R.VehicleID)
        WHERE V.VehicleID LIKE '$vin' and V.Description LIKE '$description'
        GROUP BY VehicleID
        ORDER BY AverageDaily ";

$result = $conn->query($sql);
echo "Number of rows: $result->num_rows";
echo "<table border='1'";
echo"<tr><td>VIN</td><td>Description</td><td>Average Daily Rate</td></tr>";

while ($row = $result->fetch_assoc())
{

  if ($row["AverageDaily"] == NULL)
  {
    $amount = "Non-Applicable";
  }
  else{
    $amount = "$".$row["AverageDaily"];
  }
  //printf ( "%s %s %s\n",$row["CustID"],$row["Name"],$row["Phone"]);
  echo "<tr><td>".$row["VehicleID"]."</td><td>".$row["Description"]."</td><td>".$amount."</td></tr>";
}

echo "</table>";

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
<h2>View Vehicle Information</h2>
<p>You can filter your results by typing in any of the fields given below</p>


<form action="#" method="post" >
VIN: <input type="text" name="VIN"><br><br>
Description:
    <input type="text" name="Description"><br><br>
    <p>You can search using just the keywords</p>
<input type="submit" name = "Search" value="Search">
<?php echo "<a href=http://localhost/Code/main_page.php>Back</a>" ?>

</form>

</body>
</html>