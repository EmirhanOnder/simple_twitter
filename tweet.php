<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: index.html');
}

$servername = "localhost";
$username = "root";
$password = "mysql";
$dbname = "twitterclone";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM Users WHERE UserName='{$_SESSION['username']}'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

$stmt = $conn->prepare("INSERT INTO Tweets (UserID, Tweet, TimePosted) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $userid, $tweet, $timeposted);

$userid = $user['UserID'];
$tweet = $_POST['tweet'];
$timeposted = date("Y-m-d H:i:s");

$stmt->execute();

echo "New record created successfully";
$stmt->close();
$conn->close();

header('Location: profile.php');
?>