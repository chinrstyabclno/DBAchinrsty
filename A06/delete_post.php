<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "messagingapp";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the postID from the URL
$postID = isset($_GET['postID']) ? (int)$_GET['postID'] : 0;

// Check if postID is valid
if ($postID) {
    // SQL query to delete the post
    $sql = "DELETE FROM posts WHERE postID = $postID";
    
    if ($conn->query($sql) === TRUE) {
        // Redirect to the feed page after deletion
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Invalid Post ID";
}

$conn->close(); // Close the connection
?>
