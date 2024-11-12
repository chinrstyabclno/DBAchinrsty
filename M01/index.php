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

// Define an array with specific names for each post
$names = ["Jennie", "Lisa", "Rose", "Jisoo", "Yunjin", "Nayeon", "Yeji", "Ryujin", "Irene", "Seulgi", "Chaeyoung", "Mina"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messaging App - Feed</title>
    <!-- Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
            padding-top: 20px;
        }
        h1 {
            margin-bottom: 30px;
            font-size: 2rem;
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
            align-items: flex-start;
            justify-content: center;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .post-icons {
            margin-top: 10px;
            font-size: 1.2rem;
            display: flex;
            gap: 15px;
        }
        .post-icons i {
            cursor: pointer;
            color: #6c757d;
        }
        .liked {
            color: red; /* When the heart is clicked, it turns red */
        }
        .container {
            max-width: 1200px;
        }
        .comments-section {
            margin-top: 20px;
        }
        .comment-box {
            margin-top: 10px;
            padding: 10px;
            background-color: #f1f1f1;
            border-radius: 5px;
        }
        .modal-img {
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Feed</h1>

        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                $counter = 0; // Initialize the counter to start at 0
                while ($row = $result->fetch_assoc()) {
                    $imagePath = !empty($row['imagePath']) ? $row['imagePath'] : 'picture/placeholder.jpg';
                    $name = isset($names[$counter]) ? $names[$counter] : "Unnamed Post"; // Use name from array or fallback
                    echo "<div class='col-md-3'>
                            <div class='card'>
                                <img src='" . htmlspecialchars($imagePath) . "' class='card-img-top' alt='Feed Image' data-toggle='modal' data-target='#commentModal-" . $row['postID'] . "'>
                                <div class='card-body'>
                                    <h5 class='card-title'>" . htmlspecialchars($name) . "</h5>
                                    <div class='post-icons'>
                                        <i class='far fa-heart' id='heart-" . $row['postID'] . "' onclick='toggleHeart(" . $row['postID'] . ")'></i>
                                        <i class='far fa-comment' data-toggle='modal' data-target='#commentModal-" . $row['postID'] . "'></i>
                                    </div>
                                </div>
                            </div>
                          </div>";

                    // Modal for the post comments and comment form
                    echo "<div class='modal fade' id='commentModal-" . $row['postID'] . "' tabindex='-1' role='dialog' aria-labelledby='commentModalLabel' aria-hidden='true'>
                            <div class='modal-dialog' role='document'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title' id='commentModalLabel'>Comments for Post</h5>
                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                            <span aria-hidden='true'>&times;</span>
                                        </button>
                                    </div>
                                    <div class='modal-body'>
                                        <img src='" . htmlspecialchars($imagePath) . "' class='modal-img' alt='Image for Post'>
                                        <div class='comments-section' id='comments-section-" . $row['postID'] . "'>
                                            <!-- Existing comments will be loaded here -->
                                        </div>
                                        <form method='POST' action='comment.php'>
                                            <input type='hidden' name='postID' value='" . $row['postID'] . "'>
                                            <textarea name='comment' class='form-control' placeholder='Write a comment...'></textarea>
                                            <button type='submit' class='btn btn-primary mt-2'>Submit Comment</button>
                                        </form>
                                    </div>
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
    <script>
        function toggleHeart(postID) {
            const heartIcon = document.getElementById('heart-' + postID);
            
            // Toggle between the empty and filled heart icons
            if (heartIcon.classList.contains('far')) {
                heartIcon.classList.remove('far');
                heartIcon.classList.add('fas');
            } else {
                heartIcon.classList.remove('fas');
                heartIcon.classList.add('far');
            }

            // Add 'liked' class to turn the heart red
            heartIcon.classList.toggle('liked');
        }

        // Function to load and display comments for each post in the modal
        function loadComments(postID) {
            const commentSection = document.getElementById('comments-section-' + postID);

            // Fetch comments from the database using AJAX
            fetch('fetch_comments.php?postID=' + postID)
                .then(response => response.json())
                .then(data => {
                    commentSection.innerHTML = ''; // Clear previous comments
                    data.comments.forEach(comment => {
                        const commentBox = document.createElement('div');
                        commentBox.classList.add('comment-box');
                        commentBox.innerHTML = `<strong>User ${comment.userID}</strong>: ${comment.content}`;
                        commentSection.appendChild(commentBox);
                    });
                })
                .catch(error => console.log('Error fetching comments:', error));
        }

        // Automatically load comments when the modal is shown
        $('#commentModal').on('shown.bs.modal', function (e) {
            const postID = $(e.relatedTarget).data('postid');
            loadComments(postID);
        });
    </script>
</body>
</html>

<?php
$conn->close(); // Close the connection
?> 


