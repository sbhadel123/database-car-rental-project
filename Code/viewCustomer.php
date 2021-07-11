<?php 
//Group Members: Sudeep Bhadel , Diwakar Parajuli

// Creating a connection
$conn = new mysqli("127.0.0.1", "root", "Candy123!", "carrental2019");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


if (isset($_POST['Search'])) {
 
  if (!empty($_POST["Name"]) && empty($_POST["CustID"])) {

    $name = $_POST['Name'];
    $cust_name = "%".$name."%";
    $id = "%%";
    
  } 
  elseif (!empty($_POST["CustID"]) && empty($_POST["Name"])) {

    $id = $_POST['CustID'];
    $cust_name = "%%"; 
  }

  elseif (!empty($_POST["CustID"]) && !empty($_POST["Name"])) {
    $name = $_POST['Name'];
    $cust_name = "%".$name."%";
    $id = $_POST['CustID'];
  }
  else {
    $id = "%%";
    $cust_name = "%%"; 
  }
  

$sql = "SELECT customer.CustID, Name, SUM(IF(PaymentDate is null,TotalAmount, 0)) as TotalAmount
FROM customer LEFT OUTER JOIN rental ON (customer.CustID = rental.CustID)
WHERE customer.Name LIKE '$cust_name'
        AND customer.CustID LIKE '$id'
Group by CustID
ORDER BY TotalAmount ";

$result = $conn->query($sql);
echo "Number of rows: $result->num_rows";
echo "<table border='1'";
echo"<tr><td>ID</td><td>Name</td><td>Remaining Balance</td></tr>";

while ($row = $result->fetch_assoc())
{

  if ($row["TotalAmount"] == NULL)
  {
    $amount = "0.00";
  }
  else{
    $amount = $row["TotalAmount"];
  }
  //printf ( "%s %s %s\n",$row["CustID"],$row["Name"],$row["Phone"]);
  echo "<tr><td>".$row["CustID"]."</td><td>".$row["Name"]."</td><td>"."$".$amount."</td></tr>";
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
<h2>View Customer Balance</h2>
<p>You can filter your results by typing in any of the fields given below</p>


<form action="#" method="post" >
Name: <input type="text" name="Name"><br><br>

Customer ID:
    <input type="number" name="CustID"><br><br>
<input type="submit" name = "Search" value="Search">
<?php echo "<a href=http://localhost/Code/main_page.php>Back</a>" ?>

</form>

</body>
</html>