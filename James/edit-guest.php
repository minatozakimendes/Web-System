<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    die("Access denied: You are not logged in.");
}

$mysqli = require __DIR__ . "/database.php";

// Check if the logged-in user is an admin
$sql = "SELECT role FROM users WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user["role"] !== "admin") {
    die("Access denied: Admins only.");
}

// Fetch the guest's details
if (!isset($_GET["id"])) {
    die("No guest ID provided.");
}

$guest_id = $_GET["id"];

$sql = "SELECT id, fullname FROM users WHERE id = ? AND role = 'guest'";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $guest_id);
$stmt->execute();
$result = $stmt->get_result();
$guest = $result->fetch_assoc();

if (!$guest) {
    die("Guest not found or is not a guest.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_fullname = $_POST["fullname"];

    if (empty($new_fullname)) {
        die("Full Name cannot be empty.");
    }

    $sql = "UPDATE users SET fullname = ? WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("si", $new_fullname, $guest_id);

    if ($stmt->execute()) {
        header("Location: admin-panel.php");
        exit;
    } else {
        die("Error updating full name: " . $mysqli->error);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Guest</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <h1>Edit Guest</h1>

    <form method="post">
        <div>
            <label for="fullname">Full Name</label>
            <input type="text" id="fullname" name="fullname" value="<?= htmlspecialchars($guest["fullname"]) ?>" required>
        </div>
        <button type="submit">Save Changes</button>
    </form>

    <p><a href="admin-panel.php">Back to Admin Panel</a></p>
</body>
</html>
