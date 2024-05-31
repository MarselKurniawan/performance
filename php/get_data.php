<?php
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

$month = isset($_POST['month']) ? $_POST['month'] : 'all';
$day = isset($_POST['day']) ? $_POST['day'] : 'all';

$sql = "SELECT day, total_available, room_sold, occupancy, room_revenue, adr, total_revenue, available_to_sale, estimate_room_pickup, estimate_adr_pickup, estimate_room_revenue, estimate_total_revenue 
        FROM room_stats ";

if ($month !== 'all' || $day !== 'all') {
    $conditions = [];
    if ($month !== 'all') {
        $conditions[] = "MONTH(day) = ?";
    }
    if ($day !== 'all') {
        $conditions[] = "DAY(day) = ?";
    }
    $sql .= "WHERE " . implode(' AND ', $conditions);
}

$stmt = $conn->prepare($sql);

if ($month !== 'all' && $day !== 'all') {
    $stmt->bind_param('ii', $month, $day);
} elseif ($month !== 'all') {
    $stmt->bind_param('i', $month);
} elseif ($day !== 'all') {
    $stmt->bind_param('i', $day);
}

$stmt->execute();
$result = $stmt->get_result();

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$response = array(
    "data" => $data
);

echo json_encode($response);

$conn->close();
