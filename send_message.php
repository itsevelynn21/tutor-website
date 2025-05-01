<?php
session_start();
include 'db_connect.php';

if (!isset($_POST['sender'], $_POST['subject'], $_POST['body'])) {
    exit("Λείπουν δεδομένα: sender, subject, body.");
}

$sender  = trim($_POST['sender']);
$subject = trim($_POST['subject']);
$body    = trim($_POST['body']);

$stmt = $conn->prepare("SELECT loginame FROM users WHERE role = 'Tutor'");
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    exit("Δεν βρέθηκαν χρήστες με ρόλο Tutor.");
}

$headers  = "From: $sender\r\n";
$headers .= "Reply-To: $sender\r\n";

$successCount = 0;
while ($row = $result->fetch_assoc()) {
    $tutorEmail = $row["loginame"]; 

    if (@mail($tutorEmail, $subject, $body, $headers)) {
        $successCount++;
    }
}

$stmt->close();
$conn->close();

if ($successCount > 0) {
    echo "Το μήνυμα εστάλη επιτυχώς σε $successCount Tutor(s).";
} else {
    echo "Αποτυχία αποστολής.";
}
