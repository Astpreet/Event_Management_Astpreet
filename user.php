<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Include the configuration file and start the session
@include 'config.php';
session_start();
// Assuming $currentURL holds the current URL path
$currentURL = $_SERVER['REQUEST_URI'];

// Modify the condition to hide forms when the current URL is the user page or the homepage
if ($currentURL !== "/user.php" && $currentURL !== "/") {
    // Redirect users to the login page if they're not logged in
    if (!isset($_SESSION['user_name'])) {
        header('location:login.php');
        exit();
    }
}

// Fetch events from the database
$sql = "SELECT * FROM events";
$result = mysqli_query($conn, $sql);
$events = mysqli_fetch_all($result, MYSQLI_ASSOC);

$message = array(); // Initialize message array



// Handle event booking form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['event-type'], $_POST['event-date'])) {
    $eventType = isset($_POST['event-type']) ? mysqli_real_escape_string($conn, $_POST['event-type']) : '';
    $eventDate = isset($_POST['event-date']) ? mysqli_real_escape_string($conn, $_POST['event-date']) : '';
    $paymentMethod = isset($_POST['payment-method']) ? mysqli_real_escape_string($conn, $_POST['payment-method']) : '';
    $services = isset($_POST['services']) ? implode(', ', $_POST['services']) : '';

    // No user ID association
    $userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';

    $sql = "INSERT INTO bookings (user_name, event_id, event_date, services, payment_method, created_at) 
            VALUES ('$userName', '$eventType', '$eventDate', '$services', '$paymentMethod', NOW())";

    if (mysqli_query($conn, $sql)) {
        $message['success'] = "Event booked successfully!";
    } else {
        $message['error'] = "Error: " . mysqli_error($conn);
    }
}

// Fetch booked events for the user
if (isset($_SESSION['user_name'])) {
    $userName = $_SESSION['user_name'];
    $sqlBookedEvents = "SELECT * FROM bookings WHERE user_name = '$userName'";
    $resultBookedEvents = mysqli_query($conn, $sqlBookedEvents);
    $bookedEvents = mysqli_fetch_all($resultBookedEvents, MYSQLI_ASSOC);
}





elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['review'])) {
    // Handle review form submission
    // Validate and sanitize form data
    $review = isset($_POST['review']) ? mysqli_real_escape_string($conn, $_POST['review']) : '';

    // Get the user ID from the session
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

    // Ensure that the user ID is not empty before proceeding
    if (!empty($userId)) {
        // Insert review data into the reviews table
        $sql = "INSERT INTO reviews (user_id, review, created_at) 
                VALUES ('$userId', '$review', NOW())";

        if (mysqli_query($conn, $sql)) {
            $message['success'] = "Review sent successfully!";
        } else {
            $message['error'] = "Error: " . mysqli_error($conn); // Capture the SQL error
        }
    } else {
        // Handle the case where the user ID is empty
        $message['error'] = "Error: Invalid user ID.";
    }
}
?>







<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Page</title>
    <link rel="stylesheet" href="style1.css">
    <link rel="stylesheet" href="user.css">
    <link rel="stylesheet" href="admin.css">
    
    <!-- Add Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
    form {
    display: flex;
    flex-direction: column;
    width: 50%;
    margin: auto;
}


.booking-form {
    display: none;
    flex-direction: column;
    align-items: center; /* Center horizontally */
    justify-content: center; /* Center vertically */
    width: 70%; /* Adjust the width as needed */
    margin: auto;
    padding: 30px; /* Add padding for space */
    background-color: rgba(249, 249, 249, 0.9);
    border-radius: 30px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    font-size: 1.2rem; /* Increase text size */
}

.booking-form label {
    margin-bottom: 10px; /* Add space between labels */
}

.booking-form select,
.booking-form input[type="date"] {
    width: 100%; /* Make inputs take up full width */
    padding: 10px; /* Add padding for better readability */
    margin-bottom: 20px; /* Add space between inputs */
}

.booking-form input[type="submit"] {
    width: 50%; /* Adjust the width of the submit button */
    margin-top: 20px; /* Add space above the button */
    padding: 12px; /* Add padding for better appearance */
}

.review-box {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    z-index: 1000;
   width: 60%;
   size: 44px;
   font-size: 16px;
   
}
.review-box textarea {
    width: 100%; /* Make the textarea take up full width */
    height: 200px; /* Set the height as desired */
    margin-bottom: 20px; /* Add some space below the textarea */
    padding: 10px; /* Add padding for better readability */
    font-size: 16px; /* Adjust the font size as desired */
    border: 1px solid #ccc; /* Add a light border */
    border-radius: 5px; /* Add border radius for smoother edges */
}

.review-box textarea:focus {
    outline: none; /* Remove the default focus outline */
    border-color: #333; /* Change border color on focus */
}


</style>
</head>

<body>
<header class="header">
        <div class="flex">
            <a href="user_page.php" class="logo">User<span>Panel</span></a>
            <nav class="navbar">
                <a href="https://localhost/Event_Management/user.php">Home</a>
                <a href="#" onclick="openBookingForm()">Book Events</a>
                <a href="#" onclick="showBookedEvents()">Booked Events</a>
                <a href="#" onclick="openReviewForm()">Send Review</a>
                <a href="index.html" class="btn">Logout</a>
            </nav>
            <div class="icons">
                <div id="menu-btn" class="fas fa-bars"></div>
                <div id="user-btn" class="fas fa-user" onmouseover="showUserAccountBox()"></div>
                 <!-- User account box -->
                 <div class="user-account-box" id="user-account-box">
                    <p>Username: <span><?php echo $_SESSION['user_name']; ?></span></p>
                </div>
            </div>
            <div class="account-box">
                <p>Username: <span><?php echo $_SESSION['user_name']; ?></span></p>
                <!-- Assuming you have 'user_email' in the session -->
                <p>Email: <span><?php echo $_SESSION['user_email']; ?></span></p>
                <a href="logout.php" class="delete-btn">Logout</a>
                <div>New <a href="login.php">Login</a> | <a href="register.php">Register</a></div>
            </div>
        </div>
        <div class="container">
            <div class="content">
                <h3>Hi, <span>User</span></h3>
                <h1>Welcome <span><?php echo $_SESSION['user_name']; ?></span></h1>
                <p>This is a User page</p>
            </div>
        </div>
    </header>
    <?php if ($currentURL !== "/" && $currentURL !== "/user.php"): ?>
        <div id="bookingForm" class="booking-form">
    <h2>Book Events</h2>
    <?php if (isset($message['success'])): ?>
        <div class="success-message"><?php echo $message['success']; ?></div>
    <?php elseif (isset($message['error'])): ?>
        <div class="error-message"><?php echo $message['error']; ?></div>
    <?php endif; ?>
    
    <form id="eventForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="event-type">Event Type:</label>
    <select id="event-type" name="event-type" required>
        <?php foreach ($events as $event) { ?>
            <option value="<?php echo $event['id']; ?>"><?php echo $event['event_name']; ?> - $<?php echo $event['price']; ?></option>
        <?php } ?>
    </select>

    <div id="services">
        <label>Services:</label>
        <input type="checkbox" name="services[]" value="Full Services"> Full Services
        <input type="checkbox" name="services[]" value="Decorations"> Decorations
        <input type="checkbox" name="services[]" value="MusicAndPhotos"> Music And Photos
        <input type="checkbox" name="services[]" value="FoodAndDrinks"> Food And Drinks
        <input type="checkbox" name="services[]" value="InvitationCard"> Invitation Card
    </div>
    

    <label for="event-date">Event Date:</label>
    
    <input type="date" id="event-date" name="event-date" required onchange="validateEventDate()">
<span id="date-error" style="color: red;"></span>

    <label for="payment-method">Select Payment Method:</label>
    <select id="payment-method" name="payment-method" required>
        <option value="credit-card">Credit Card</option>
        <option value="paypal">PayPal</option>
        <option value="bank-transfer">Bank Transfer</option>
    </select>

    <div id="totalAmount"></div>

    <input type="submit" id="submit-btn" value="Book Event">
    </form>
    

</div>
<!--booked -->
<div id="bookedEvents" style="display:none;">
<h1>Booked Events</h1>
    <table>
        <tr>
            <th>Event Type</th>
            <th>Event Date</th>
            <th>Services</th>
            <th>Payment Method</th>
            <th>Booking Date</th>
        </tr>
        <?php foreach ($bookedEvents as $event): ?>
            <tr>
                <td><?php echo $event['event_id']; ?></td>
                <td><?php echo $event['event_date']; ?></td>
                <td><?php echo $event['services']; ?></td>
                <td><?php echo $event['payment_method']; ?></td>
                <td><?php echo $event['created_at']; ?></td>
                
            </tr>
        <?php endforeach; ?>
    </table>
        </div>
       
       

<!-- Review Form -->
<div id="reviewForm" class="review-box">
    <h2>Send Review</h2>
    <?php if (isset($message['success'])): ?>
        <div class="success-message"><?php echo $message['success']; ?></div>
    <?php elseif (isset($message['error'])): ?>
        <div class="error-message"><?php echo $message['error']; ?></div>
    <?php endif; ?>
    <form id="sendReviewForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="review">Write a Review:</label>
        <textarea id="review" name="review" required></textarea>
        <input type="submit" name="submitReview" value="Send Review">
    </form>
</div>
<?php endif; ?>






<footer>
        <p>Contact us at: <a href="mailto:astpreet8522@gmail.com">astpreet8522@gmail.com</a></p>
    </footer>
    <script>
    function openBookingForm() {
        document.getElementById("bookingForm").style.display = "block";
        document.getElementById("reviewForm").style.display = "none"; // Hide review form if open
        document.getElementById("bookedEvents").style.display = "none"; // Hide booked events table if open
        document.querySelector('.container').style.display = 'none'; // Hide container content
    }

    function showBookedEvents() {
        document.getElementById("bookingForm").style.display = "none";
        document.getElementById("reviewForm").style.display = "none"; // Hide review form if open
        document.getElementById("bookedEvents").style.display = "block";
        document.querySelector('.container').style.display = 'none'; // Hide container content
        // Fetch booked events and display them in the table
        fetchBookedEvents();
    }

    function openReviewForm() {
        document.getElementById("bookingForm").style.display = "none";
        document.getElementById("reviewForm").style.display = "block";
        document.getElementById("bookedEvents").style.display = "none"; // Hide booked events table if open
        document.querySelector('.container').style.display = 'none'; // Hide container content
    }

    function openHomePageContent() {
        document.getElementById("bookingForm").style.display = "none";
        document.getElementById("reviewForm").style.display = "none";
        document.getElementById("bookedEvents").style.display = "none"; // Hide booked events table if open
        document.querySelector('.container').style.display = 'block';
        // Hide other forms or tabs here if any
    }

    function showUserAccountBox() {
        document.getElementById("user-account-box").style.display = "block";
    }

    function hideUserAccountBox() {
        document.getElementById("user-account-box").style.display = "none";
    }

    function validateEventDate() {
        var eventDate = new Date(document.getElementById("event-date").value);
        var currentDate = new Date();

        // Reset previous error messages
        document.getElementById("date-error").innerHTML = "";

        // Compare the selected date with the current date
        if (eventDate < currentDate) {
            document.getElementById("date-error").innerHTML = "Please select a future date.";
            document.getElementById("submit-btn").disabled = true;
        } else {
            document.getElementById("submit-btn").disabled = false;
        }
    }
    
  

</script>

</body>
</html>