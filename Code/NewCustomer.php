<?php 
//Group Members: Sudeep Bhadel , Diwakar Parajuli

$NameErr = $PhoneErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
  if (empty($_POST["FName"])) {
    $NameErr = "First Name is required";
  } 
  if (empty($_POST["LName"])) {
    $NameErr = "Last Name is required";
  } 

    if (empty($_POST["Phone"])) {
    $PhoneErr = "Phone is required";
  } 
  else{
  // Creating a connection
$conn = new mysqli("127.0.0.1", "root", "Candy123!", "carrental2019");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$fname = $_POST['FName'];
$lname = $_POST['LName'];
$fini = substr($fname,0,1);
$name = $fini."."." ".$lname;
$phone = $_POST['Phone'];


$sql = "INSERT INTO customer (Name,Phone) VALUES ('$name','$phone');";

if ($conn->query($sql) === TRUE) {
    echo "New customer added successfully";
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
<h2>Add New Customer</h2>
<p><span class="error">* Required Field</span></p>

<form action="#" method="post" >
First Name: <input type="text" name="FName">
<span class="error">* <?php echo $NameErr;?></span>
Last Name: <input type="text" name="LName">
<span class="error">* <?php echo $NameErr;?></span>
  <br><br>
Phone: <input type="text" name="Phone"><br>
<p>Phone should be in Format  (xxx) xxx-xxxx</p>
<span class="error">* <?php echo $PhoneErr;?></span>
  <br>
<input type="submit" value="Add Customer">

<?php echo "<a href=http://localhost/Code/main_page.php>Back</a>" ?>

</form>

</body>
</html>