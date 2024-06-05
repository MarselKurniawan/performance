<?php
header('Content-Type: application/json');

$host = 'localhost';
$db = 'hotel_data';
$user = 'root';
$pass = '';
$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Koneksi gagal: " . $mysqli->connect_error);
}

// Query untuk mengambil data summary dari tabel yang relevan
$query = "SELECT metric, current_mtd, current_onhand, predicted_achievement, budget, gap_budget, lysm, gap_lysm FROM summary";
$result = $mysqli->query($query);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$mysqli->close();

echo json_encode(['data' => $data]);
?>