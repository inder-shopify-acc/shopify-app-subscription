<?php
header('Content-Type: application/json');
$dirPath = dirname((__DIR__));
require_once($dirPath . "/env.php");
define('API_KEY', '51c99e547d70d5a12fbdd4f209ad7b16ef20e26ab6fc457f38e11d8395c142a4');


// Get API key from request
$provided_key = isset($_GET['key']) ? $_GET['key'] : '';

// Validate API key
if ($provided_key !== API_KEY) {
    echo json_encode(['status' => 'fail', 'message' => 'Invalid API key']);
    exit;
}

// Database credentials
$host = getDotEnv('MYSQL_HOST');// Change if needed
$dbname = getDotEnv('MYSQL_DB'); // Update with your database name
$username = getDotEnv('MYSQL_USER'); // Update with your DB username
$password = getDotEnv('MYSQL_PASS'); // Update with your DB password

// Connect to MySQL database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

// Get email from request
$email = isset($_GET['email']) ? trim($_GET['email']) : '';

if (empty($email)) {
    echo json_encode(['status' => 'fail', 'message' => 'Email is required']);
    exit;
}

// Prepare and execute query
$stmt = $conn->prepare("SELECT id FROM customers WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Check if email exists
if ($result->num_rows > 0) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'fail']);
}

// Close connections
$stmt->close();
$conn->close();
?>
