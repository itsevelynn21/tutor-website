<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "Tutor") {
    exit("Δεν έχετε άδεια να διαγράψετε ανακοινώσεις.");
}

if (!isset($_POST["announcement_id"])) {
    exit("Το ID της ανακοίνωσης λείπει.");
}

$announcement_id = intval($_POST["announcement_id"]); 

// διαγραφή της ανακοίνωσης από τη βάση δεδομένων
$stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
$stmt->bind_param("i", $announcement_id);

if ($stmt->execute()) {
    echo "Η ανακοίνωση διαγράφηκε επιτυχώς!";
} else {
    echo "Σφάλμα κατά τη διαγραφή: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
