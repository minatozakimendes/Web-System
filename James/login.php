<?php
$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mysqli = require __DIR__ . "/database.php";

    $sql = sprintf(
        "SELECT * FROM users WHERE email = '%s'",
        $mysqli->real_escape_string($_POST["email"])
    );

    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();

    if ($user && password_verify($_POST["password"], $user["password_hash"])) {
        session_start();
        session_regenerate_id();
        $_SESSION["user_id"] = $user["id"];
        header("Location: index.php");
        exit;
    }

    $is_invalid = true;

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mysqli = require __DIR__ . "/database.php";

    $sql = sprintf(
        "SELECT * FROM users WHERE email = '%s'",
        $mysqli->real_escape_string($_POST["email"])
    );

    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();

    if ($user && password_verify($_POST["password"], $user["password_hash"])) {
        session_start();
        session_regenerate_id();
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_role"] = $user["role"]; // Store the role in the session
        header("Location: index.php");
        exit;
    }

    $is_invalid = true;
}

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <h1>Login</h1>
    <?php if ($is_invalid): ?>
        <em>Invalid login</em>
    <?php endif; ?>
    <form method="post">
        <div>
            <input type="email" id="email" name="email" placeholder="Email Address" value="<?= htmlspecialchars($_POST["email"] ?? "") ?>" required>
        </div>
        <div>
            <input type="password" id="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit">Login</button>
    </form>

    <hr>
    <!-- Signup button -->
    <div style="text-align: center; margin-top: 20px;">
        <p>Don't have an account?</p>
        <a href="signup.html">
            <button type="button" style="padding: 10px 20px;">Signup</button>
        </a>
    </div>
</body>
</html>
