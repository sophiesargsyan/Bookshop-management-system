<?php
require_once "includes/db.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $full_name = trim($_POST["full_name"]);

    if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
        $error = "Please fill in all fields.";
    }

    if (empty($error) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    }

    if (empty($error) && !preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
        $error = "Password must be at least 8 characters long and contain an uppercase letter, a lowercase letter, a number, and a special character.";
    }

    if (empty($error)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "This email is already registered.";
        }
    }


    if (empty($error)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, created_at) VALUES (?, ?, ?, ?, NOW())");
        if ($stmt->execute([$username, $email, $hashed_password, $full_name])) {
            header("Location: index.php");
            exit;
        } else {
            $error = "Something went wrong.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <div class="form-container">
        <h2>Registration</h2>

        <?php if (!empty($error)) : ?>
            <p class="error"><?php echo $error; ?></p>
        <?php elseif (!empty($success)) : ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <label for="full_name">Full Name</label>
            <input type="text" name="full_name" id="full_name" value="<?php echo htmlspecialchars($_POST['full_name'] ?? '', ENT_QUOTES); ?>" required>
            
            <label for="username">Username</label>
            <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES); ?>" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES); ?>" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Register</button>
        </form>

        <div class="login-link">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>

</body>
</html>