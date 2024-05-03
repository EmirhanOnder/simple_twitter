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

$sql = "SELECT UserID FROM Users WHERE UserName='{$_SESSION['username']}'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$userId = $row['UserID'];

$sql = "CALL ShowHomePage($userId)";
$result = $conn->query($sql);

$conn->next_result();

echo "<form action='homepage.php' method='post'>";
echo "<input type='text' name='search' placeholder='Search users'>";
echo "<input type='submit' value='Search'>";
echo "</form>";


if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $sql = "SELECT * FROM Users WHERE UserName='$search'";
    $result2 = $conn->query($sql);

    if ($result2->num_rows > 0) {
		$row = $result2->fetch_assoc();
        echo "<p><strong>Search Results:</strong></p>";
		echo "<p><strong>Username:</strong> {$row['Username']}</p>";
        echo "<p><strong>Full Name:</strong> {$row['Name']}</p>";
		echo "<form action='follow.php' method='post'>";
		echo "<input type='hidden' name='followUserID' value='{$row['UserID']}'>";
        echo "<input type='submit' value='Follow'>";
        echo "</form>";
    } else {
        echo "<p>User not found.</p>";
    }
	$conn->next_result();
}


echo "<h2>Tweets from the Users you follow:</h2>";

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<p><strong>{$row['UserName']}:</strong> {$row['Tweet']} ({$row['TimePosted']})</p>";
    }
} else {
    echo "<p>You are not following anyone or they haven't tweeted yet.</p>";
}


echo "<form action='profile.php' method='post'><input type='submit' value='Profile'></form>";
echo "<form action='logout.php' method='post'><input type='submit' value='Log Out'></form>";

?>
