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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postID = isset($_POST['postID']) ? (int)$_POST['postID'] : 0;
    $comment = isset($_POST['comment']) ? $conn->real_escape_string($_POST['comment']) : '';

    if ($postID && $comment) {
        $sql = "INSERT INTO comments (postID, content) VALUES ($postID, '$comment')";
        if ($conn->query($sql) === TRUE) {
            // Redirect back to the feed page after adding the comment
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>


