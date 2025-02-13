<?php
session_start();
require_once "includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email) || empty($password)) {
        echo "Խնդրում ենք լրացնել բոլոր դաշտերը։";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Խնդրում ենք մուտքագրել ճիշտ էլ. փոստ։";
        exit;
    }

    $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        
        echo "Մուտքը հաջողվեց։";
        // header("Location: index.php");
    } else {
        echo "Սխալ էլ․ փոստ կամ գաղտնաբառ։";
    }
}
?>

<form action="login.php" method="POST">
    <label for="email">Էլ. փոստ</label>
    <input type="email" name="email" id="email" required>

    <label for="password">Գաղտնաբառ</label>
    <input type="password" name="password" id="password" required>

    <button type="submit">Մուտք</button>
</form>