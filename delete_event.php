<?php
// Include your database connection
@include 'config.php';

// Check if the event ID is provided in the URL
if(isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Delete event from the database
    $sql = "DELETE FROM events WHERE id='$event_id'";
    if (mysqli_query($conn, $sql)) {
        header('Location:admin.php '); // Redirect after successful deletion
        exit();
    } else {
        echo "Error deleting event: " . mysqli_error($conn);
    }
} else {
    // No event ID provided, redirect or show error message
    echo "Event ID not provided.";
    exit();
}
?>
