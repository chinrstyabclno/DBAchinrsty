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
$postID = intval($_GET['postID']);

// SQL query to fetch data for the selected post
$sql = "SELECT postID, userID, content, dateTime FROM posts WHERE postID = $postID";
$result = $conn->query($sql);
$post = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed Details</title>
    <!-- Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Feed Details</h1>

        <?php if ($post): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Feed ID: <?php echo htmlspecialchars($post['postID']); ?></h5>
                    <p><strong>User ID:</strong> <?php echo htmlspecialchars($post['userID']); ?></p>
                    <p><strong>Content:</strong> <?php echo htmlspecialchars($post['content']); ?></p>
                    <p><strong>Date/Time:</strong> <?php echo htmlspecialchars($post['dateTime']); ?></p>
                </div>
            </div>
            <a href="index.php" class="btn btn-primary">Back to Feed</a>
        <?php else: ?>
            <p class="text-center">Feed not found.</p>
            <a href="index.php" class="btn btn-primary">Back to Feed</a>
        <?php endif; ?>

    </div>

    <!-- Optional Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close(); // Close the connection
?>

