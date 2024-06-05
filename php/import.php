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

    // Clear existing data
    $conn->query("TRUNCATE TABLE room_stats");

    foreach ($sheetData as $row) {
        if ($row['A'] != 'Date' && $row['A'] != '') { // Skipping the header row
            $day = $conn->real_escape_string($row['A']);
            $total_available = $conn->real_escape_string($row['D']);
            $room_sold = $conn->real_escape_string($row['E']);
            $occupancy = $conn->real_escape_string($row['F']);
            $room_revenue = $conn->real_escape_string($row['H']);
            $adr = $conn->real_escape_string($row['K']);
            $total_revenue = $conn->real_escape_string($row['P']);

            // Ensure values are properly formatted as numbers
            // $room_revenue = is_numeric($room_revenue) ? $room_revenue : 0;
            // $adr = is_numeric($adr) ? $adr : 0;
            // $total_revenue = is_numeric($total_revenue) ? $total_revenue : 0;

            $sql = "INSERT INTO room_stats (day, total_available, room_sold, occupancy, room_revenue, adr, total_revenue, estimate_room_revenue, estimate_total_revenue)
                    VALUES ('$day', '$total_available', '$room_sold', '$occupancy', '$room_revenue', '$adr', '$total_revenue', '$room_revenue','$total_revenue')";
            $conn->query($sql);
        }
    }
}

$conn->close();

header("Location: ../index.html");
exit();
