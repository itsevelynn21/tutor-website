<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loginame = $_POST["loginame"];
    $password = $_POST["password"];

    // Αναζήτηση χρήστη στη βάση
    $sql = "SELECT * FROM users WHERE loginame = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $loginame);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        if ($password == $user["password"]) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["role"] = $user["role"];
            $_SESSION["loginame"] = $user["loginame"];

            header("Location: index.php");
            exit();
        } else {
            echo "Λάθος κωδικός!";
        }
    } else {
        echo "Ο χρήστης δεν βρέθηκε!";
    }

    $stmt->close();
    $conn->close();
}
?>
