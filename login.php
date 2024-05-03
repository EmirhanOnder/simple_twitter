<?php
$servername = "localhost";
$username = "root";
$password = "mysql";
$dbname = "twitterclone";

//database connection 
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 

//getting value of username and password
$username = $_POST['username'];
$password = $_POST['password'];

//create sql query string and execute
$sql = "SELECT * FROM Users WHERE Username='$username' AND Password='$password'";
$result = $conn->query($sql);


//check if the SQL query return any results
if ($result->num_rows > 0) {
	session_start();
    $row = $result->fetch_assoc();
    $_SESSION["username"] = $row["Username"];
    header('Location: homepage.php');
} else {
    echo "Authentication failed";
}
$conn->close();



?>

