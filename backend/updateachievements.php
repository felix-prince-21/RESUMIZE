<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Max-Age: 86400"); // 24 hours cache

include 'db.php'; 

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect JSON data from the request body
    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data);

    // Validate and sanitize the data (you might need to improve this based on your requirements)
    $achievementId = intval($data->achievement_id);
    $achievement_name = mysqli_real_escape_string($conn, $data->achievementName);
    $organization_name = mysqli_real_escape_string($conn, $data->OrganizationName);
    $year_attained = mysqli_real_escape_string($conn, $data->yearAttained);

    // Update data in the 'education' table using prepared statements
    $sql = "UPDATE skill SET achievementName=?, OrganizationName=?, yearAttained=?, WHERE achievement_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $achievement_name, $organization_name, $year_attained, $achievementId);

    if ($stmt->execute()) {
        echo json_encode(array("message" => "Record"));
    } else {
        echo json_encode(array("error" => "Error: " . $stmt->error));
    }

    $stmt->close();
} else {
    echo json_encode(array("error" => "Invalid request method"));
}

// Close the database connection
$conn->close();
?>