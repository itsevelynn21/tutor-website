<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();	
   }
   
include 'db_connect.php';
$query = "SELECT id, title, message, date FROM announcements ORDER BY date DESC";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ανακοινώσεις</title>
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
    <script>
        function openPopup() {
        fetch('add_announcement.php')
          .then(response => response.text())
          .then(html => {
            document.getElementById("announcementPopup").innerHTML = html;
            document.getElementById("announcementPopup").style.display = "block";

            let form = document.getElementById("announcementForm");
            if (form) {
                form.addEventListener("submit", function(event) {
                    event.preventDefault();
                    let formData = new FormData(form);

                    fetch("add_announcement_submit.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        console.log("Απάντηση από τον Server:", data);
                        if (data.includes("επιτυχώς")) {
                            document.getElementById("successMessage").style.display = "block";
                            setTimeout(() => {
                                closePopup();
                                window.location.href = "announcement.php";
                            }, 1500);
                        } else {
                            alert("Σφάλμα: " + data);
                        }
                    })
                    .catch(error => console.error("Σφάλμα AJAX:", error));
                });
            }
        });
}
 

        function closePopup() {
            document.getElementById("announcementPopup").style.display = "none";
        }
    </script>
    <style>
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.5);
            border-radius: 5px;
            z-index: 1000;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        .btn-add {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn-add:hover {
            background-color: #0056b3;
        }

        .announcement-header {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .delete-btn {
            background-color: #ff4d4d;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
        }
        .delete-btn:hover {
            background-color: #cc0000;
        }

        .edit-btn {
            background-color: #ffc107;
            olor: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
        }
        .edit-btn:hover {
            background-color: #e0a800;
        }
    </style>
</head>
<body>
    <div id="header">
        <h1>Ανακοινώσεις</h1>
    </div>
    <div id="container">
        <?php include 'sidebar.php'; ?>
        <div id="content">
            
            <?php if ($_SESSION["role"] == "Tutor"): ?>
                <button class="btn-add" onclick="openPopup()">Προσθήκη Νέας Ανακοίνωσης</button>
            <?php endif; ?>
            
            <div id="announcementPopup" class="popup"></div>
            <div id="overlay" class="overlay"></div>

            <?php
            if ($result->num_rows > 0) {
                $counter = $result->num_rows;// Αριθμός ανακοινώσεων
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='announcement'>";
                    echo "<div class='announcement-header'>";
                    echo "<h2>Ανακοίνωση " . $counter . "</h2>";
                    $counter--;
                    
                    if ($_SESSION["role"] == "Tutor") {
                        echo "<button class='delete-btn' onclick='deleteAnnouncement(" . $row['id'] . ")'>🗑 Διαγραφή</button>";
                        echo "<button class='edit-btn' onclick='openEditPopup(" . $row['id'] . ")'>✏️ Επεξεργασία</button>";
                    }
                    
                    
                    echo "</div>";
                    echo "<h3>Ημερομηνία: " . date("d/m/Y", strtotime($row['date'])) . "</h3>";
                    echo "<h3>Θέμα: " . htmlspecialchars($row['title']) . "</h3>";
                
                    // 1. Μετατρέπω ειδικούς χαρακτήρες σε HTML entities
                    $messageSafe = htmlspecialchars($row['message'], ENT_QUOTES, 'UTF-8');
                    // 2. ψάχνω τη λέξη "εργασίες" και τη μετατρέπω σε σύνδεσμο
                    $pattern = '/\bεργασίες\b/ui';
                    $replace = '<a href="homework.php">$0</a>';
                    $messageWithLink = preg_replace($pattern, $replace, $messageSafe);
                    echo "<h4>" . nl2br($messageWithLink) . "</h4>";
                
                    echo "</div><hr>";
                }

                
            } else {
                echo "<p>Δεν υπάρχουν διαθέσιμες ανακοινώσεις.</p>";
            }
            $conn->close();
            ?>

        </div>
    </div>

    <?php if (isset($_SESSION["user_id"])): ?>
            <form action="logout.php" method="post">
                <button type="submit" id="logout-btn">Αποσύνδεση</button>
            </form>
        <?php else: ?>
            <a href="login.php" class="btn">Σύνδεση</a>
        <?php endif; ?>

<script>
    function deleteAnnouncement(id) {
        if (!confirm("Είστε σίγουρος ότι θέλετε να διαγράψετε αυτή την ανακοίνωση;")) {
            return;
        }

        let formData = new FormData();
        formData.append("announcement_id", id);

        fetch("delete_announcement.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log("Απάντηση από το Server:", data);
            if (data.includes("επιτυχώς")) {
                alert("Η ανακοίνωση διαγράφηκε!");
                window.location.reload(); // ανανεώνει τη σελίδα για να εξαφανιστεί η διαγραμμένη ανακοίνωση
            } else {
                alert("Σφάλμα: " + data);
            }
        })
        .catch(error => console.error("⚠️ Σφάλμα AJAX:", error));
    }
    
    function openEditPopup(announcementId) {
    // παιρνω το HTML της φόρμας με AJAX
    fetch("edit_announcement.php?id=" + announcementId)
      .then(response => response.text())
      .then(html => {
        document.getElementById("announcementPopup").innerHTML = html;
        document.getElementById("announcementPopup").style.display = "block";
        
        const form = document.querySelector("#announcementPopup form");
        if (form) {
            form.addEventListener("submit", function(event) {
                event.preventDefault();
                
                let formData = new FormData(form);
                
                fetch("update_announcement.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    console.log("Απάντηση από Server:", data);
                    if (data.includes("επιτυχώς")) {
                        alert("Η ανακοίνωση ενημερώθηκε επιτυχώς!");
                        closeEditPopup();
                        window.location.reload();
                    } else {
                        alert("Σφάλμα: " + data);
                    }
                })
                .catch(error => console.error("Σφάλμα AJAX:", error));
            });
        }
      })
      .catch(error => console.error("Σφάλμα στο fetch edit_announcement.php:", error));
    }

    function closeEditPopup() {
       document.getElementById("announcementPopup").style.display = "none";
    }


</script>
    

    <a href="#" id="topButton">Top</a>

</body>
</html>
