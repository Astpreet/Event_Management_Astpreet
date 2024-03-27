<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
@include 'config.php';

session_start();

if (!isset($_SESSION['admin_name'])) {
    header('location:login.php');
    exit();
}

$message = array(); // Initialize message array

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_name = mysqli_real_escape_string($conn, $_POST['event_name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $services = implode(", ", $_POST['services']);

    // Insert the event into the database
    $sql = "INSERT INTO events (event_name, price, services) VALUES ('$event_name', '$price', '$services')";
    if (mysqli_query($conn, $sql)) {
        $message['success'] = "Event created successfully.";
    } else {
        $message['error'] = "Error: " . mysqli_error($conn);
    }
}
$sql = "SELECT * FROM reviews";
$result = mysqli_query($conn, $sql);
$reviews = mysqli_fetch_all($result, MYSQLI_ASSOC);
$currentURL = $_SERVER['REQUEST_URI'];
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="style1.css">
    <link rel="stylesheet" href="admin.css">
    <!-- Add Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        #booked-events-table {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            border: 1px solid #ccc;
            z-index: 9999;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        #booked-events-table th,
        #booked-events-table td {
            padding: 5px 10px;
            border: 1px solid #ccc;
        }
        
        /* Reviews table styles */
        #reviews-table {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            border: 1px solid #ccc;
            z-index: 9999;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        #reviews-table th,
        #reviews-table td {
            padding: 5px 10px;
            border: 1px solid #ccc;
        }
        #created-events-table {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            border: 1px solid #ccc;
            z-index: 9999;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        #created-events-table table {
            width: 100%;
            border-collapse: collapse;
        }

        #created-events-table th,
        #created-events-table td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
    </style>

</head>

<body>
    <header class="header">
        <div class="flex">
            <a href="admin_page.php" class="logo">Admin<span>Panel</span></a>
            <nav class="navbar">
                <a href="https://localhost/Event_Management/admin.php">Home</a>
                <a href="#" onclick="openBookedEvents()">Booked Events</a>
                <a href="#" onclick="openCreateEventForm()">Create New Events</a>
                <a href="#" onclick="openSeeReviews()">See Reviews</a>
                <a href="#" onclick="openCreatedEvents()">Created Events</a>
                <a href="index.html" class="btn">Logout</a>
            </nav>
            <div class="icons">
                <div id="menu-btn" class="fas fa-bars"></div>
                <div id="user-btn" class="fas fa-user" onmouseover="showUserAccountBox()" onmouseout="hideUserAccountBox()"></div>
                <!-- User account box -->
                <div class="user-account-box" id="user-account-box">
                    <p>Admin name: <span><?php echo $_SESSION['admin_name']; ?></span></p>
                </div>
            </div>
            <div class="account-box">
                <p>Adminname: <span><?php echo $_SESSION['admin_name']; ?></span></p>
                <!-- Assuming you have 'admin_email' in the session -->
                <p>Email: <span><?php echo $_SESSION['admin_email']; ?></span></p>
                <a href="logout.php" class="delete-btn">Logout</a>
                <div>New <a href="login.php">Login</a> | <a href="register.php">Register</a></div>
            </div>
        </div>
        <div class="container">
            <div class="content">
                <h3>Hi, <span>Admin</span></h3>
                <h1>Welcome <span><?php echo $_SESSION['admin_name']; ?></span></h1>
                <p>This is an admin page</p>
            </div>
        </div>
    </header>
    <!-- Inside your HTML where you want to display booked events -->
    <div id="booked-events-table" style="display: none;">

    <h2>Booked Events</h2>
    <table>
        <thead>
            <tr>
                <th>User Name</th>
                <th>Event Name</th>
                <th>Event Date</th>
                <th>Services</th>
                <th>Payment Method</th>
                <th>Booking Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sqlBookedEvents = "SELECT bookings.*, events.event_name FROM bookings JOIN events ON bookings.event_id = events.id";
            $resultBookedEvents = mysqli_query($conn, $sqlBookedEvents);
            if ($resultBookedEvents) {
                while ($row = mysqli_fetch_assoc($resultBookedEvents)) {
                    echo "<tr>";
                    echo "<td>" . $row['user_name'] . "</td>";
                    echo "<td>" . $row['event_name'] . "</td>";
                    echo "<td>" . $row['event_date'] . "</td>";
                    echo "<td>" . $row['services'] . "</td>";
                    echo "<td>" . $row['payment_method'] . "</td>";
                    echo "<td>" . $row['created_at'] . "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>

    
    <!-- Create Event Form Box -->
    <div id="create-event-box" class="create-event-box">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <h2>Create Event</h2>
            <?php if (isset($message['error'])) { ?>
                <div class="error"><?php echo $message['error']; ?></div>
            <?php } ?>
            <?php if (isset($message['success'])) { ?>
                <div class="success"><?php echo $message['success']; ?></div>
            <?php } ?>
            <label for="event_name">Event Name:</label>
            <input type="text" id="event_name" name="event_name"><br><br>
            <label for="price">Price:</label>
            <input type="text" id="price" name="price"><br><br>
            <label for="services">Services:</label><br>
            <input type="checkbox" id="full_services" name="services[]" value="Full Services">
            <label for="full_services">Full Services</label><br>
            <input type="checkbox" id="decorations" name="services[]" value="Decorations">
            <label for="decorations">Decorations</label><br>
            <input type="checkbox" id="music_photos" name="services[]" value="Music And Photos">
            <label for="music_photos">Music And Photos</label><br>
            <input type="checkbox" id="food_drinks" name="services[]" value="Food And Drinks">
            <label for="food_drinks">Food And Drinks</label><br>
            <input type="checkbox" id="invitation_card" name="services[]" value="Invitation Card">
            <label for="invitation_card">Invitation Card</label><br><br>
            <input type="submit" value="Create Event">
        </form>
    </div>
    <!-- Inside your HTML where you want to display reviews -->
    <div id="reviews-table">
        <h2>User Reviews</h2>
        <table>
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Review</th>
                    <th>Submitted At</th>
                </tr>
            </thead>
            <tbody>
                
                <?php foreach ($reviews as $review) { ?>
                    <tr>
                        <td><?php echo $review['user_id']; ?></td>
                        <td><?php echo $review['review']; ?></td>
                        <td><?php echo $review['created_at']; ?></td>
                        
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div id="created-events-table" style="display: none;">
    <h2>Created Events</h2>
    <table>
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Price</th>
                <th>Services</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sqlCreatedEvents = "SELECT * FROM events";
            $resultCreatedEvents = mysqli_query($conn, $sqlCreatedEvents);
            if ($resultCreatedEvents) {
                while ($row = mysqli_fetch_assoc($resultCreatedEvents)) {
                    echo "<tr>";
                    echo "<td>" . $row['event_name'] . "</td>";
                    echo "<td>" . $row['price'] . "</td>";
                    echo "<td>" . $row['services'] . "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>
    


    <script>
        // JavaScript to show/hide user account box
        function showUserAccountBox() {
            document.getElementById("user-account-box").style.display = "block";
        }

        function hideUserAccountBox() {
            document.getElementById("user-account-box").style.display = "none";
        }

       
        
    function openCreateEventForm() {
        // Show the create event form
        var createEventBox = document.getElementById("create-event-box");
        createEventBox.style.display = "block";

        // Hide other elements
        document.getElementById("booked-events-table").style.display = "none";
        document.getElementById("reviews-table").style.display = "none";
        document.getElementById("created-events-table").style.display = "none";
        document.querySelector('.container').style.display = 'none';
    }

    // Functions to show other tables when their respective links are clicked
    function openSeeReviews() {
        var reviewsTable = document.getElementById("reviews-table");
        reviewsTable.style.display = "block";

        // Hide other elements
        document.getElementById("booked-events-table").style.display = "none";
        document.getElementById("create-event-box").style.display = "none";
        document.getElementById("created-events-table").style.display = "none";
        document.querySelector('.container').style.display = 'none';
    }

    function openBookedEvents() {
        var bookedEventsTable = document.getElementById("booked-events-table");
        bookedEventsTable.style.display = "block";

        // Hide other elements
        document.getElementById("create-event-box").style.display = "none";
        document.getElementById("reviews-table").style.display = "none";
        document.getElementById("created-events-table").style.display = "none";
        document.querySelector('.container').style.display = 'none';
    }
    function openCreatedEvents() {
            // Show the created events table
            var createdEventsTable = document.getElementById("created-events-table");
            createdEventsTable.style.display = "block";

            // Hide other elements
            document.getElementById("booked-events-table").style.display = "none";
            document.getElementById("create-event-box").style.display = "none";
            document.getElementById("reviews-table").style.display = "none";
            document.querySelector('.container').style.display = 'none';
        }


        
        
        



    </script>
</body>

</html>