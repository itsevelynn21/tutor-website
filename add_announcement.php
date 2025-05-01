<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "Tutor") {
    exit("<p>Δεν έχετε πρόσβαση σε αυτή τη σελίδα.</p>");
}
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
    <span class="close" onclick="closePopup()">&times;</span>
    <div class="popup-header">Προσθήκη Νέας Ανακοίνωσης</div>
    <div class="popup-content">
        <form id="announcementForm">
            <label for="title">Τίτλος:</label>
            <input type="text" id="title" name="title" required>
            
            <label for="message">Περιεχόμενο:</label>
            <textarea id="message" name="message" rows="5" required></textarea>
            
            <button type="submit">Προσθήκη</button>
        </form>
        <p id="successMessage" style="display:none; color:green; font-weight:bold;">Η ανακοίνωση αποθηκεύτηκε!</p>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        let form = document.getElementById("announcementForm");
        if (!form) {
            console.error("Το 'announcementForm' δεν βρέθηκε!");
            return; 
        }

        console.log("Το 'announcementForm' βρέθηκε σωστά.");

        form.addEventListener("submit", function(event) {
            event.preventDefault(); 

            let formData = new FormData(form);

            fetch("add_announcement_submit.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log("🔍 Απάντηση από το Server:", data); 
                if (data.includes("επιτυχώς")) {
                    document.getElementById("successMessage").style.display = "block";
                    setTimeout(() => {
                        closePopup();
                        window.location.href = "announcements.php"; // κανει ανανεωση στη σωστη σελιδα
                    }, 1500);
                } else {
                    alert("Σφάλμα από τον Server: " + data);
                }
            })
            .catch(error => console.error("Σφάλμα AJAX:", error));
        });
    });
</script>


