<?php
session_start();

//chech if user is logged in or not
if (!isset($_SESSION['username'])) {
    header('Location: index.html');
}

if (!isset($_POST['followUserID'])) {
    header('Location: homepage.php');
}

$servername = "localhost";
$username = "root";
$password = "mysql";
$dbname = "twitterclone";

//database connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT UserID FROM Users WHERE UserName='{$_SESSION['username']}'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$userId = $row['UserID'];
$date=date("Y/m/d");

//assign followerID
$followUserID = $_POST['followUserID'];

//create sql query and execute
$sql = "SELECT * FROM Follows WHERE FollowerID=$userId AND FollowingID=$followUserID";
$result = $conn->query($sql);


if ($result->num_rows > 0) {
    echo "You are already following this user";
} else {
	$stmt = $conn->prepare("INSERT INTO Follows (FollowerID, FollowingID, FollowDate) VALUES (?, ?, ?)");
	$stmt->bind_param("iis" , $userId, $followUserID, $date);
	
    if ($stmt->execute()) {
        echo "You are now following user with ID: $followUserID";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $sql = "UPDATE Users SET Following = Following + 1 WHERE UserID = $userId";
    if ($conn->query($sql) !== TRUE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $sql = "UPDATE Users SET Followers = Followers + 1 WHERE UserID = $followUserID";
    if ($conn->query($sql) !== TRUE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

header('Location: homepage.php');
?>
