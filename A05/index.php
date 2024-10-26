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

// SQL query to fetch data from posts table
$sql = "SELECT postID, userID, content, dateTime, imagePath FROM posts ORDER BY dateTime DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messaging App - Posts</title>
    <!-- Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
        }
        h1 {
            margin-bottom: 20px;
        }
        .card {
            margin: 15px;
            cursor: pointer;
        }
        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .card-body {
            display: flex;
            flex-direction: column;
            align-items: flex-start; /* Left-align text inside card body */
            justify-content: center;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .card-text {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Posts</h1>

        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                $counter = 1; // Initialize the counter to 1
                while ($row = $result->fetch_assoc()) {
                    // Use image path from the database or a default placeholder if none is provided
                    $imagePath = !empty($row['imagePath']) ? $row['imagePath'] : 'picture/placeholder.jpg';
                    echo "<div class='col-md-3'>
                            <div class='card' onclick=\"window.location.href='detail.php?postID=" . $row['postID'] . "'\">
                                <img src='" . htmlspecialchars($imagePath) . "' class='card-img-top' alt='Post Image'>
                                <div class='card-body'>
                                    <h5 class='card-title'>Post " . $counter . "</h5> <!-- Use counter for numbering -->
                                    <p class='card-text'>" . htmlspecialchars($row['content']) . "</p>
                                    <p class='card-text'><small class='text-muted'>Posted on " . htmlspecialchars($row['dateTime']) . "</small></p>
                                </div>
                            </div>
                          </div>";
                    $counter++; // Increment the counter
                }
            } else {
                echo "<p class='text-center'>No records found</p>";
            }
            ?>
        </div>
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


