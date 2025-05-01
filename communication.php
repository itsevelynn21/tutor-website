<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();	
   }
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Επικοινωνία</title>
    <style>
        /* --- Βασικό Layout και Sidebar --- */
        body {
            font-family: Arial, sans-serif;
            margin: 0; padding: 0;
        }
        #header {
            width: 100%;
            background-color: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
            position: fixed; top: 0; z-index: 1000;
        }
        #header h1 { margin: 0; }
        #container {
            display: flex;
            margin-top: 60px;
            height: calc(100vh - 60px);
        }
        #sidebar {
            width: 20%;
            background-color: #f0f0f0;
            padding: 10px;
            position: fixed; top: 60px; bottom: 0;
            overflow-y: auto;
        }
        #sidebar button {
            display: block;
            width: 100%;
            margin-bottom: 20px;
            padding: 10px;
            border: none;
            background-color: #e7e7e7;
            cursor: pointer;
            text-align: center;
        }
        #sidebar button:hover {
            background-color: #d7d7d7;
        }
        #content {
            flex: 1;
            margin-left: 25%;
            padding: 20px;
        }
        #topButton {
            position: fixed;
            bottom: 20px; right: 20px;
            background-color: #333; color: white;
            padding: 10px 15px; text-decoration: none;
            font-size: 14px; border-radius: 5px;
        }
        #topButton:hover {
            background-color: #555;
        }

        /* --- Στυλ για τη φόρμα Επικοινωνίας --- */
        .form-field {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            gap: 10px; /* Προσθέτει μικρή απόσταση μεταξύ label και input */
        }

        .form-field label {
            width: 100px; /* Σταθερό πλάτος για να ευθυγραμμιστούν όλα τα labels */
            font-weight: bold;
            text-align: left; /* Στοίχιση αριστερά */
        }

        .form-field input,
        .form-field textarea {
            width: 300px;
            padding: 5px;
        }

        .form-field textarea {
            height: 80px;
        }

        /* --- Κουμπί "Αποστολή" --- */
        .btn-send {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 16px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        .btn-send:hover {
            background-color: #0056b3;
        }

        /* --- Ειδοποίηση επιτυχίας/σφάλματος --- */
        #msgContainer {
            display: none;
            padding: 10px 15px;
            margin-left: 80px;
            margin-top: 10px;
            border-radius: 5px;

        }
        .msg-success {
            background-color: #d4edda;
            color: #155724;
        }
        .msg-error {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Κουμπί Αποσύνδεσης */
        #logout-btn {
            position: fixed;
            top: 60px; right: 20px;
            background-color: red;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
            font-weight: bold;
        }
        #logout-btn:hover {
            background-color: darkred;
        }

    </style>
</head>
<body>
<div id="header">
    <h1>Επικοινωνία</h1>
</div>

<div id="container">
    <div id="sidebar">
        <button onclick="location.href='index.php'">Αρχική Σελίδα</button>
        <button onclick="location.href='announcement.php'">Ανακοινώσεις</button>
        <button onclick="location.href='communication.php'">Επικοινωνία</button>
        <button onclick="location.href='document.php'">Έγγραφα Μαθήματος</button>
        <button onclick="location.href='homework.php'">Εργασίες</button>
    </div>

    <div id="content">
        <p>Μπορείτε να επικοινωνήσετε με τον διδάσκοντα με τους εξής 2 τρόπους:</p>
        <ul>
            <li>Μέσω web φόρμας</li>
            <li>Με χρήση e-mail διεύθυνσης</li>
        </ul>

        <h2>Αποστολή μηνύματος μέσω web φόρμας</h2>
        <!-- Δεν βάζουμε action, θα χειριστούμε AJAX -->
        <form id="contactForm">
            <div class="form-field">
                <label for="sender">Αποστολέας:</label>
                <input type="email" id="sender" name="sender" required
                       placeholder="π.χ. your_email@domain.com">
            </div>
            <div class="form-field">
                <label for="subject">Θέμα:</label>
                <input type="text" id="subject" name="subject" required>
            </div>
            <div class="form-field">
                <label for="body">Κείμενο:</label><br>
                <textarea id="body" name="body" required></textarea>
            </div>
            <div class="form-field">
                <button type="submit" class="btn-send">Αποστολή</button>
            </div>
        </form>

        <!-- Εφήμερο μήνυμα (success / error) -->
        <div id="msgContainer"></div>

        <hr style="margin-left: 80px;">
        <h2>Αποστολή μηνύματος με χρήση email διεύθυνσης</h2>
        <p style="margin-left:80px;">
            Εναλλακτικά, μπορείτε να στείλετε e-mail στο 
            <a href="mailto:tutor@csd.auth.test.gr">tutor@csd.auth.test.gr</a>.
        </p>
    </div>
</div>

<a href="#" id="topButton">Top</a>

<script>
document.getElementById("contactForm").addEventListener("submit", function(e) {
    e.preventDefault(); 

    let formData = new FormData(this); // όλα τα πεδία της φόρμας

    fetch("send_message.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log("Απάντηση:", data);

        const msg = document.getElementById("msgContainer");
        msg.style.display = "block"; 

        if (data.includes("εστάλη επιτυχώς")) {
            msg.className = "msg-success";
            msg.textContent = "Το μήνυμα εστάλη επιτυχώς!";
        } else {
            msg.className = "msg-error";
            msg.textContent = "Σφάλμα: " + data;
        }

        // το μήνυμα εξαφανίζεται μετά από 3 δευτερόλεπτα
        setTimeout(() => {
            msg.style.display = "none";
        }, 3000);
    })
    .catch(err => {
        console.error("Σφάλμα AJAX:", err);
        const msg = document.getElementById("msgContainer");
        msg.style.display = "block";
        msg.className = "msg-error";
        msg.textContent = "Πρόβλημα σύνδεσης με τον server.";
        setTimeout(() => {
            msg.style.display = "none";
        }, 3000);
    });
});
</script>

<?php if (isset($_SESSION["user_id"])): ?>
            <form action="logout.php" method="post">
                <button type="submit" id="logout-btn">Αποσύνδεση</button>
            </form>
        <?php else: ?>
            <a href="login.php" class="btn">Σύνδεση</a>
        <?php endif; ?>

    <a href="#" id="topButton">Top</a>

</body>
</html>
