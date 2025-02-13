<?php
require_once "includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $full_name = trim($_POST["full_name"]);

    if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
        echo "Խնդրում ենք լրացնել բոլոր դաշտերը։";
        exit;
    }

    if (strlen($username) < 3) {
        echo "Օգտանունը պետք է պարունակի առնվազն 3 նիշ։";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Խնդրում ենք մուտքագրել ճիշտ էլ. փոստ։";
        exit;
    }

    if (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
        echo "Գաղտնաբառը պետք է լինի առնվազն 8 նիշ և պարունակի մեծատառ, փոքրատառ, թիվ և հատուկ նշան։";
        exit;
    }

    if (strlen($full_name) < 5) {
        echo "Ամբողջական անունը պետք է պարունակի առնվազն 5 նիշ։";
        exit;
    }

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo "Այս էլ. փոստը արդեն գրանցված է։";
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, created_at) VALUES (?, ?, ?, ?, NOW())");
    if ($stmt->execute([$username, $email, $hashed_password, $full_name])) {
        echo "Գրանցումը հաջողվեց։ Կարող եք մուտք գործել։";
    } else {
        echo "Ինչ-որ բան սխալ գնաց։";
    }
}
?>

<form action="register.php" method="POST">
    <label for="username">Օգտանուն</label>
    <input type="text" name="username" id="username" required>

    <label for="email">Էլ. փոստ</label>
    <input type="email" name="email" id="email" required>

    <label for="password">Գաղտնաբառ</label>
    <input type="password" name="password" id="password" required>

    <label for="full_name">Ամբողջական անուն</label>
    <input type="text" name="full_name" id="full_name" required>

    <button type="submit">Գրանցվել</button>
</form>