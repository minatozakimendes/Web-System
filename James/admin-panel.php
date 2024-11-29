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

// Fetch all guests from the database
$sql = "SELECT id, fullname, email, role FROM users WHERE role = 'guest'";
$result = $mysqli->query($sql);

// Fetch the data into an array
$guests = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $guests[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <h1>Admin Panel</h1>
    <p>Welcome, Admin!</p>

    <h2>List of Guests</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($guests) > 0): ?>
                <?php foreach ($guests as $guest): ?>
                    <tr>
                        <td><?= htmlspecialchars($guest["id"]) ?></td>
                        <td><?= htmlspecialchars($guest["fullname"]) ?></td>
                        <td><?= htmlspecialchars($guest["email"]) ?></td>
                        <td><?= htmlspecialchars($guest["role"]) ?></td>
                        <td>
                            <a href="edit-guest.php?id=<?= $guest["id"] ?>">Edit</a> |
                            <a href="delete-guest.php?id=<?= $guest["id"] ?>" onclick="return confirm('Are you sure you want to delete this guest?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No guests found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <p><a href="index.php">Go to Home</a></p>
    <p><a href="logout.php">Log out</a></p>
</body>
</html>
