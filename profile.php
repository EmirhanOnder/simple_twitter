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

$sql = "SELECT * FROM Users WHERE Username='{$_SESSION['username']}'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

$sql = "SELECT COUNT(*) as NumTweets FROM Tweets WHERE UserID={$user['UserID']}";
$result = $conn->query($sql);
$tweetCount = $result->fetch_assoc();

$sql = "CALL ShowProfilePage({$user['UserID']})";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Profile</title>
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body>
    <div class="container">
      <h1>Welcome, <?php echo $user['Username']; ?></h1>
	  <p>Username:  <?php echo $user['Username']; ?></p>
	  <p>Tweets:  <?php echo $tweetCount['NumTweets']; ?></p>
      <p>Followers: <?php echo $user['Followers']; ?></p>
      <p>Following: <?php echo $user['Following']; ?></p>

      <form method="post" action="tweet.php">
        <div class="form-group">
          <label for="tweet">New Tweet:</label>
          <input type="text" id="tweet" name="tweet" required />
        </div>
        <div class="form-group">
          <input type="submit" value="Post" />
        </div>
      </form>

      <h2>Your Tweets</h2>
     
	  <?php
      if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
              echo "<p>{$row['TimePosted']} - {$row['Tweet']}</p>";
          }
      } else {
          echo "<p>You have not posted any tweets yet.</p>";
      }
      ?>

      <a href="logout.php">Logout</a>
      <a href="homepage.php">Back to Homepage</a>
    </div>
  </body>
</html>
<?php

$conn->close();
?>