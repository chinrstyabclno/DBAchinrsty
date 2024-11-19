<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "messagingapp";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$postID = isset($_GET['postID']) ? (int)$_GET['postID'] : 0;
$sql = "SELECT userID, content FROM comments WHERE postID = $postID";
$result = $conn->query($sql);

$comments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
}

echo json_encode(['comments' => $comments]);

$conn->close();
?>


