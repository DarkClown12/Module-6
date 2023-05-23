<?php 
$databaseConnection = mysqli_connect("localhost", "root", "root", "krspymedia");

// Storing errors in an array
$errors = [];
session_start();

if (mysqli_connect_error()) {
    exit("Database connection failed!");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signInClicked'])) {
    $nickname = mysqli_real_escape_string($databaseConnection, $_POST['nickname']);
    $password = mysqli_real_escape_string($databaseConnection, $_POST['password']);

    // Find user by their username
    $sql = "SELECT * FROM users ";
    $sql .= "WHERE nickname='" . $nickname . "'";

    $user = mysqli_query($databaseConnection, $sql);
    $user = mysqli_fetch_assoc($user);

    if ($user) {
        // Verify their password
        if (password_verify($password, $user['hashedPassword'])) {
            // Login user
            session_regenerate_id(); // Security feature fixation
            $_SESSION['userId'] = $user['id'];
            $_SESSION['nickname'] = $user['nickname'];
            header("Location: index.php");
            exit();
        } else {
            // Passwords don't match
            $errors[] = "Wrong username or password.";
        }
    } else {
        $errors[] = "Invalid username or password.";
    }
}
?>

<span class="error">
    <?php 
    foreach ($errors as $currentError) {
        echo($currentError);
    }
    ?>
</span>
<body>

<style>
     body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
        }

        h3 {
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 300px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f2f2f2;
        }

        input[type="text"],
        input[type="password"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 14px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
</style>
</body>

<form action="login.php" method="post">
    <h3>Login</h3>
    <input type="text" name="nickname" placeholder="Nickname">
    <input type="password" name="password" placeholder="Password">
    <input type="submit" value="Sign in" name="signInClicked">
</form>