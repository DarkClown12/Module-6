<?php
$databaseConnection = mysqli_connect("localhost", "root", "root", "krspymedia");

// Check if the database connection was successful
if (mysqli_connect_error()) {
    exit("Database connection failed!");
}

// Initialize the errors array
$errors = [];

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signUpClicked'])) {
    $nickname = mysqli_real_escape_string($databaseConnection, $_POST['nickname']);
    $password = mysqli_real_escape_string($databaseConnection, $_POST['password']);

    // Validate form inputs
    if (empty($nickname)) {
        $errors[] = "Please enter a nickname";
    }
    if (empty($password)) {
        $errors[] = "Please enter a password";
    }

    // If there are no errors, proceed with inserting the user
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (nickname, hashedPassword) VALUES ('$nickname', '$hashedPassword')";

        $userInsertedSuccessfully = mysqli_query($databaseConnection, $sql);

        if ($userInsertedSuccessfully) {
            header("Location: login.php");
            exit();
        } else {
            echo mysqli_error($databaseConnection);
            mysqli_close($databaseConnection);
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        /* Styles omitted for brevity */
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
</head>

<body>
<title>    <h3>Sign up</h3>
</title> 

    <?php if (!empty($errors)): ?>
        <div style="color: red; text-align: center;">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form action="signup.php" method="post">
        <p> <h3>Sign up</h3>
</p>
        <input type="text" name="nickname" placeholder="Nickname">
        <input type="password" name="password" placeholder="Password">
        <input type="submit" value="Join" name="signUpClicked">
    </form>
</body>
</html>