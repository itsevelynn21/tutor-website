<?php
session_start();
include 'db_connect.php';

// Έλεγχος αν είναι Tutor
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Tutor") {
    exit("❌ Δεν έχετε δικαίωμα πρόσβασης.");
}

if (!isset($_POST["ekfonisi"], $_POST["paradotea"], $_POST["stoxoi"], $_POST["date"])) {
    exit("❌ Λείπουν δεδομένα.");
}

$ekfonisi = trim($_POST["ekfonisi"]);
$paradotea = trim($_POST["paradotea"]);
$stoxoi = trim($_POST["stoxoi"]);
$date = trim($_POST["date"]);


// ΒΔ
$stmt = $conn->prepare("
    INSERT INTO ergasies (ekfonisi, paradotea, stoxoi, date)
    VALUES (?, ?, ?, ?)
");
$stmt->bind_param("ssss", $ekfonisi, $paradotea, $stoxoi, $date);

if ($stmt->execute()) {
    echo "Η εργασία προστέθηκε επιτυχώς!";
} else {
    echo "Σφάλμα κατά την εισαγωγή: " . $stmt->error;
}

$stmt->close();

// 2) μετράω πόσες εργασίες υπάρχουν συνολικά
$resCount = $conn->query("SELECT COUNT(*) AS total FROM ergasies");
$rowCount = $resCount->fetch_assoc();
$ergasiaNumber = $rowCount['total'];  

// εισαγωγή αντίστοιχης ανακοίνωσης
$annDate = date("Y-m-d H:i:s");
$annTitle = "Υποβλήθηκε η εργασία " . $ergasiaNumber;
$annMessage = "Η ημερομηνία παράδοσης της εργασίας είναι " . $date;

$stmt2 = $conn->prepare("
    INSERT INTO announcements (title, message, date) 
    VALUES (?, ?, ?)
");
$stmt2->bind_param("sss", $annTitle, $annMessage, $annDate);
$stmt2->execute();
$stmt2->close();
