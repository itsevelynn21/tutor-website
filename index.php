<?php session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();	
   } ?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Αρχική Σελίδα</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        #header {
            width: 100%;
            background-color: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
            position: fixed;
            top: 0;
            z-index: 1000;
        }
        #header h1 {
            margin: 0;
        }
        #container {
            display: flex;
            margin-top: 60px; 
        }
        #sidebar {
            width: 20%;
            background-color: #f0f0f0;
            padding: 10px;
            position:fixed;
            top: 60px; 
            bottom: 0; 
            overflow-y: auto; 
    
        }
        #content {
            flex: 1;
            margin-left: 25%;
            padding: 20px;
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

        #topButton {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #333;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            font-size: 14px;
            border-radius: 5px;
        }

        /*    */
        #topButton:hover {
            background-color: #555;
        }

		#logout-btn {
            position: fixed;
            top: 60px;
            right: 20px;
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
        <h1>Αρχική Σελίδα</h1>
    </div>
    <div id="container">
        <?php include 'sidebar.php'; ?> 
        <div id="content">
            <h2>Καλωσορίσατε στο μάθημα "Web Development"!</h2>
            <p>Σας καλωσορίζουμε σε αυτό το συναρπαστικό ταξίδι στον κόσμο της ανάπτυξης ιστοσελίδων.</p>

            <h3>Στόχοι Μαθήματος</h3>
            <ul>
                <li>Κατανόηση των βασικών εννοιών HTML, CSS και JavaScript.</li>
                <li>Εξοικείωση με τη δημιουργία διαδραστικών ιστοσελίδων.</li>
                <li>Εισαγωγή στις σύγχρονες τεχνολογίες ανάπτυξης ιστοσελίδων (π.χ. frameworks).</li>
                <li>Εφαρμογή πρακτικών βέλτιστης ανάπτυξης (best practices).</li>
                <li>Δημιουργία ενός τελικού έργου (project) με βάση τις γνώσεις που αποκτήθηκαν.</li>
            </ul>

            <h3>Πλοήγηση στο Site</h3>
            <p>Η ιστοσελίδα αυτή περιέχει τις εξής ενότητες:</p>
            <ul>
                <li><strong>Ανακοινώσεις:</strong> Ενημερωθείτε για όλες τις σημαντικές πληροφορίες σχετικά με το μάθημα.</li>
                <li><strong>Επικοινωνία:</strong> Βρείτε πληροφορίες για το πώς να επικοινωνήσετε με τον διδάσκοντα ή τους βοηθούς διδασκαλίας.</li>
                <li><strong>Έγγραφα Μαθήματος:</strong> Πρόσβαση σε σημειώσεις, διαφάνειες και άλλο εκπαιδευτικό υλικό.</li>
                <li><strong>Εργασίες:</strong> Δείτε τις αναθέσεις εργασιών και τις οδηγίες υποβολής.</li>
            </ul>

            <img src="image1.png" alt="Web Development" style="width:100%; max-width:600px; display:block; margin:20px auto;">
        </div>

		
        <?php if (isset($_SESSION["user_id"])): ?>
            <form action="logout.php" method="post">
                <button type="submit" id="logout-btn">Αποσύνδεση</button>
            </form>
        <?php else:
            ?>

            <a href="login.php" class="btn">Σύνδεση</a>
        <?php endif; ?>

    <a href="#" id="topButton">Top</a>
</body>
</html>
