
<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();	
   }
include 'db_connect.php';

$query = "SELECT id ,title ,description ,link, date FROM documents ORDER BY date DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Έγγραφα</title>
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
            height: calc(100vh - 60px); 
        }
        #sidebar {
            width: 20%;
            background-color: #f0f0f0;
            padding: 10px;
            position: fixed; 
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

        /*       */
        #topButton:hover {
            background-color: #555;
        }
        .indented {
        margin-left: 80px;
        }

        .link-right {
            display: inline-block;
            margin-left: 80px; 
            text-decoration: none;
            color: blue; 
            font-weight: bold; 
        }

        .popup {
            display: none;
            position: fixed;
            top: 50%; left: 50%;
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
            top: 0; left: 0;
            width: 100%; height: 100%;
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
            margin-bottom: 20px;
        }
        .btn-add:hover {
            background-color: #0056b3;
        }
        .document {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .document-header {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .document-date {
            color: #888;
            font-size: 14px;
            margin-bottom: 10px;
        }

    </style>
</head>
<body>
    <div id="header">
        <h1>Έγγραφα</h1>
    </div>

    <div id="container">
        <?php include 'sidebar.php'; ?>
    <div id="content">

        <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "Tutor"): ?>
            <button class="btn-add" onclick="openAddDocumentPopup()">Προσθήκη Νέου Εγγράφου</button>
        <?php endif; ?>

        <?php
        if ($result->num_rows > 0) {
            $count = 1;
            while ($row = $result->fetch_assoc()) {
                
                echo "<div class='document'>";
                echo "<h2>\"" . htmlspecialchars($row['title']) . "\"</h2>";
                echo "<p><strong>Περιγραφή:</strong> " . htmlspecialchars($row['description']) . "</p>";
                echo "<p><a href='uploads/" . htmlspecialchars($row['link']) . "' target='_blank'>📄 Κατεβάστε το αρχείο εδώ</a></p>";

                echo "</div><hr>";

            }
        } else {
            echo "<p>Δεν υπάρχουν έγγραφα.</p>";
        }
        $conn->close();
        ?>
    </div>

    <a href="#" id="topButton">Top</a>

<div id="documentPopup" class="popup"></div>
<div id="overlay" class="overlay"></div>

<script>
function openAddDocumentPopup() {
    fetch("add_document.php")
      .then(response => response.text())
      .then(html => {
        document.getElementById("documentPopup").innerHTML = html;
        document.getElementById("documentPopup").style.display = "block";
        document.getElementById("overlay").style.display = "block";

        const form = document.querySelector("#documentPopup form");
        if (form) {
            form.addEventListener("submit", function(event) {
                event.preventDefault();
                let formData = new FormData(form);

                fetch("add_document_submit.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    console.log("Απάντηση από Server:", data);
                    if (data.includes("επιτυχώς")) {
                        alert("Το έγγραφο προστέθηκε επιτυχώς!");
                        closeDocumentPopup();
                        window.location.reload();
                    } else {
                        alert("Σφάλμα: " + data);
                    }
                })
                .catch(error => console.error("Σφάλμα AJAX:", error));
            });
        }
      })
      .catch(error => console.error("Σφάλμα fetch add_document.php:", error));
}

function closeDocumentPopup() {
    document.getElementById("documentPopup").style.display = "none";
    document.getElementById("overlay").style.display = "none";
}
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
