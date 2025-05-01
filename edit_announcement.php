<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "Tutor") {
    exit("Δεν έχετε άδεια να επεξεργαστείτε ανακοινώσεις.");
}

if (!isset($_GET["id"])) {
    exit("Λάθος πρόσβαση στη σελίδα.");
}

$announcement_id = intval($_GET["id"]);

$stmt = $conn->prepare("SELECT id, title, message FROM announcements WHERE id = ?");
$stmt->bind_param("i", $announcement_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    exit("Η ανακοίνωση δεν βρέθηκε.");
}

$row = $result->fetch_assoc();

?>

<style>
    .popup-container {
        width: 400px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        padding: 20px;
        position: relative;
        text-align: center;
    }
    .popup-header {
        font-size: 18px;
        font-weight: bold;
        padding-bottom: 10px;
        border-bottom: 1px solid #ddd;
    }
    .popup-content {
        margin-top: 15px;
    }
    .popup-container input,
    .popup-container textarea {
        width: 100%;
        padding: 8px;
        margin: 5px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .popup-container button {
        background-color: #007bff;
        color: white;
        padding: 10px 15px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        margin-top: 10px;
        width: 100%;
    }
    .popup-container button:hover {
        background-color: #0056b3;
    }
    .close {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 20px;
        cursor: pointer;
    }
</style>

<div class="popup-container">
    <span class="close" onclick="closeEditPopup()">×</span>
    <div class="popup-header">
        Επεξεργασία Ανακοίνωσης
    </div>
    <div class="popup-content">
        <form>
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

            <label for="title">Τίτλος:</label>
            <input type="text" id="title" name="title" 
                   value="<?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?>" required>

            <label for="message">Περιεχόμενο:</label>
            <textarea id="message" name="message" rows="5" required><?php 
                echo htmlspecialchars($row['message'], ENT_QUOTES, 'UTF-8'); 
            ?></textarea>

            <button type="submit">Αποθήκευση Αλλαγών</button>
        </form>
    </div>
</div>
