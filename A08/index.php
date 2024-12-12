<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "pupairport"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$filter_airline = isset($_GET['airlineName']) ? $_GET['airlineName'] : '';
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'flightNumber'; 
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC'; 

$sql = "SELECT flightNumber, departureAirportCode, arrivalAirportCode, departureDatetime, arrivalDatetime, flightDurationMinutes, airlineName, aircraftType 
        FROM flightlogs 
        WHERE airlineName LIKE '%$filter_airline%' 
        ORDER BY $sort_by $sort_order";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            color: #333;
        }

        header {
            background-color: #8B0000;
            color: #FFD200;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        header img {
            height: 50px;
            margin-right: 20px;
        }

        header h1 {
            font-size: 24px;
            margin: 0;
        }

        h2 {
            text-align: center;
            font-size: 28px;
            color: #8B0000;
            margin-top: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .table-hover tbody tr:hover {
            background-color: #f8d7da;
        }
    </style>
</head>
<body>

<header>
    <img src="images/logo.png" alt="PUP Airport Logo">
    <h1>PUP Airport</h1>
</header>

<div class="container mt-4">
    <h2>Flight Logs</h2>

    <div class="card p-3 shadow-sm mt-4">
        <form action="" method="get" class="row g-3">
            <div class="col-md-4">
                <label for="airlineName" class="form-label">Search:</label>
                <input type="text" id="airlineName" name="airlineName" value="<?php echo $filter_airline; ?>" class="form-control" placeholder="Enter airline name...">
            </div>
            <div class="col-md-3">
                <label for="sort_by" class="form-label">Sort By:</label>
                <select name="sort_by" id="sort_by" class="form-select">
                    <option value="flightNumber" <?php echo ($sort_by == 'flightNumber') ? 'selected' : ''; ?>>Flight Number</option>
                    <option value="departureDatetime" <?php echo ($sort_by == 'departureDatetime') ? 'selected' : ''; ?>>Departure Datetime</option>
                    <option value="arrivalDatetime" <?php echo ($sort_by == 'arrivalDatetime') ? 'selected' : ''; ?>>Arrival Datetime</option>
                    <option value="flightDurationMinutes" <?php echo ($sort_by == 'flightDurationMinutes') ? 'selected' : ''; ?>>Flight Duration</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="sort_order" class="form-label">Order:</label>
                <select name="sort_order" id="sort_order" class="form-select">
                    <option value="ASC" <?php echo ($sort_order == 'ASC') ? 'selected' : ''; ?>>Ascending</option>
                    <option value="DESC" <?php echo ($sort_order == 'DESC') ? 'selected' : ''; ?>>Descending</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-danger w-100">Apply</button>
            </div>
        </form>
    </div>

    <div class="table-responsive mt-4">
        <?php
        if ($result->num_rows > 0) {
            echo "<table class='table table-bordered table-hover'>
                    <thead class='table-danger'>
                        <tr>
                            <th>Flight Number</th>
                            <th>Departure Airport Code</th>
                            <th>Arrival Airport Code</th>
                            <th>Departure Datetime</th>
                            <th>Arrival Datetime</th>
                            <th>Flight Duration (Minutes)</th>
                            <th>Airline Name</th>
                            <th>Aircraft Type</th>
                        </tr>
                    </thead>
                    <tbody>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['flightNumber']}</td>
                        <td>{$row['departureAirportCode']}</td>
                        <td>{$row['arrivalAirportCode']}</td>
                        <td>{$row['departureDatetime']}</td>
                        <td>{$row['arrivalDatetime']}</td>
                        <td>{$row['flightDurationMinutes']}</td>
                        <td>{$row['airlineName']}</td>
                        <td>{$row['aircraftType']}</td>
                      </tr>";
            }
            echo "</tbody>
                  </table>";
        } else {
            echo "<p class='text-center text-danger fs-4'>No flight logs found.</p>";
        }

        $conn->close();
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
