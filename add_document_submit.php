<?php
session_start();
include 'db_connect.php';

// Έλεγχος αν ο χρήστης έχει ρόλο Tutor
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Tutor") {
    exit("Δεν έχετε δικαίωμα πρόσβασης.");
}

// ελεγχος αν ελαβε τα απαραιτητα πεδια
if (!isset($_POST["title"], $_POST["description"]) || !isset($_FILES["file"])) {
    exit("Λείπουν δεδομένα.");
}

$title = trim($_POST["title"]);
$description = trim($_POST["description"]);

if ($_FILES["file"]["error"] !== UPLOAD_ERR_OK) {
    exit("Σφάλμα στο ανέβασμα του αρχείου.");
}

$uploadDir = "uploads/";

$originalName = basename($_FILES["file"]["name"]);

$uniqueName = uniqid() . "_" . $originalName;
$targetPath = $uploadDir . $uniqueName;

if (!move_uploaded_file($_FILES["file"]["tmp_name"], $targetPath)) {
    exit("Αποτυχία μεταφοράς του αρχείου στον server.");
}

$stmt = $conn->prepare("INSERT INTO documents (title, description, link) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $title, $description, $uniqueName);

if ($stmt->execute()) {
    echo "Το έγγραφο προστέθηκε επιτυχώς!";
} else {
    echo "Σφάλμα κατά την εισαγωγή: " . $stmt->error;
}

$stmt->close();
$conn->close();
