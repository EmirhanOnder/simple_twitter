<?php
$servername = "localhost";
$username = "root";
$password = "mysql";
$dbname = "twitterclone";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 

$name = $_POST['name'];
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$date=date("Y/m/d");

$sql_check1 = "SELECT * FROM Users WHERE Username='$username' ";
$sql_check2 = "SELECT * FROM Users WHERE Email='$email' ";
$result1 = $conn->query($sql_check1);
$result2 = $conn->query($sql_check2);


if ($result1->num_rows > 0) {
    echo "Username already taken ! <a href='index.html'>Click here</a> to register again.";
}
elseif($result2->num_rows > 0){
	echo "Email already taken ! <a href='index.html'>Click here</a> to register again.";
}
else{
	$stmt = $conn -> prepare("INSERT INTO Users (Name, Username, Email, Password, JoinDate) VALUES ( ?, ?, ?, ?, ?)");
	$stmt -> bind_param("sssss", $name, $username, $email, $password, $date);

	if($stmt->execute()){
	echo " You successfully registered. <a href='index.html'>Click here</a> to login.";
	}
	else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	
	}
}
	$conn->close();
?>
