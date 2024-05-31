<?php
require '../vendor/autoload.php'; // Ensure this path is correct

use PhpOffice\PhpSpreadsheet\IOFactory;

$host = 'localhost';
$db = 'hotel_data';
$user = 'root';
$pass = '';

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_FILES['file']['name']) {
    $file = $_FILES['file']['tmp_name'];
    $spreadsheet = IOFactory::load($file);

    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    foreach ($sheetData as $row) {
        if ($row['A'] != 'Date' && $row['A'] != '') { // Skipping the header row
            $day = $row['A'];
            $total_available = $row['D'];
            $room_sold = $row['E'];
            $occupancy = $row['F'];
            $room_revenue = $row['H'];
            $adr = $row['K'];
            $total_revenue = $row['P'];

            $sql = "INSERT INTO room_stats (day, total_available, room_sold, occupancy, room_revenue, adr, total_revenue)
                    VALUES ('$day', '$total_available', '$room_sold', '$occupancy', '$room_revenue', '$adr', '$total_revenue')";
            $conn->query($sql);
        }
    }
}

$conn->close();

header("Location: ../index.html");
