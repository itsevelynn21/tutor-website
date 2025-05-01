<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "Tutor") {
    exit("Δεν έχετε άδεια.");
}

if (!isset($_POST["id"], $_POST["title"], $_POST["message"])) {
    exit("Λείπουν δεδομένα.");
}

$announcement_id = intval($_POST["id"]);
$title = trim($_POST["title"]);
$message = trim($_POST["message"]);

// ΒΔ
$stmt = $conn->prepare("UPDATE announcements SET title = ?, message = ? WHERE id = ?");
$stmt->bind_param("ssi", $title, $message, $announcement_id);

if ($stmt->execute()) {
    echo "Οι αλλαγές αποθηκεύτηκαν επιτυχώς!";
} else {
    echo "Σφάλμα κατά την αποθήκευση: " . $stmt->error;
}

$stmt->close();
$conn->close();


