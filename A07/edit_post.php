<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "messagingapp";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postID = isset($_POST['postID']) ? intval($_POST['postID']) : 0;
    $name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : '';

    if ($postID && $name) {
        $sql = "UPDATE posts SET userID = '$name' WHERE postID = $postID";

        if ($conn->query($sql) === TRUE) {
            header("Location: index.php"); // Redirect back to the feed page
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "Invalid data provided.";
    }
}

$conn->close();
?>
