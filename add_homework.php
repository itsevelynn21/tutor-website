<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Tutor") {
    exit("Δεν έχετε δικαίωμα πρόσβασης.");
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

    <span class="close" onclick="closeHomeworkPopup()">×</span>

    <div class="popup-header">
        Προσθήκη Νέας Εργασίας
    </div>

    <div class="popup-content">
        <form id="homeworkForm">
            <label for="stoxoi">Στόχοι:</label>
            <textarea id="stoxoi" name="stoxoi" rows="3" required></textarea>

            <label for="ekfonisi">Εκφώνηση:</label>
            <textarea id="ekfonisi" name="ekfonisi" rows="3" required></textarea>

            <label for="paradotea">Παραδοτέα:</label>
            <textarea id="paradotea" name="paradotea" rows="3" required></textarea>


            <label for="date">Ημερομηνία Παράδοσης:</label>
            <input type="datetime-local" id="date" name="date" required>

            <button type="submit">Προσθήκη</button>
        </form>
    </div>
</div>
