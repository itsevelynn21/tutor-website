<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "Tutor") {
    exit("Δεν έχετε πρόσβαση σε αυτή τη σελίδα.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $message = trim($_POST["message"]);
    $date = date("Y-m-d H:i:s");

    if (!empty($title) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO announcements (title, message, date) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $message, $date);

        if ($stmt->execute()) {
            echo "Η ανακοίνωση προστέθηκε επιτυχώς!";
        } else {
            echo "Σφάλμα κατά την αποθήκευση: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Συμπληρώστε όλα τα πεδία.";
    }
}
$conn->close();
?>



