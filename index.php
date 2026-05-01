<?php
session_start();

/* ---------- DATABASE CONNECTION ---------- */
$conn = new mysqli("localhost", "root", "", "login_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* ---------- REGISTER ---------- */
if(isset($_POST['register'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password)
            VALUES ('$username', '$email', '$password')";
    $conn->query($sql);
}

/* ---------- LOGIN ---------- */
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    $user = $result->fetch_assoc();

    if($user && password_verify($password, $user['password'])){
        $_SESSION['username'] = $user['username'];
    } else {
        $error = "Invalid Login!";
    }
}

/* ---------- LOGOUT ---------- */
if(isset($_GET['logout'])){
    session_destroy();
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login System</title>

<style>
body {
    font-family: Arial;
    background: linear-gradient(to right, #6a11cb, #2575fc);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
.container {
    background: white;
    padding: 25px;
    border-radius: 10px;
    width: 300px;
    text-align: center;
}
input {
    width: 90%;
    padding: 10px;
    margin: 8px 0;
}
button {
    padding: 10px;
    width: 100%;
    background: #2575fc;
    color: white;
    border: none;
}
button:hover {
    background: #1a5edb;
}
a {
    color: #2575fc;
    text-decoration: none;
}
</style>

</head>
<body>

<div class="container">

<?php if(isset($_SESSION['username'])): ?>

    <!-- DASHBOARD -->
    <h2>Welcome <?php echo $_SESSION['username']; ?> 🎉</h2>
    <a href="?logout=true"><button>Logout</button></a>

<?php else: ?>

    <!-- SWITCH BETWEEN LOGIN & REGISTER -->
    <?php $page = isset($_GET['page']) ? $_GET['page'] : 'login'; ?>

    <?php if($page == 'register'): ?>

        <h2>Register</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="register">Register</button>
        </form>

        <p><a href="index.php">Already have account? Login</a></p>

    <?php else: ?>

        <h2>Login</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>

        <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <p><a href="?page=register">Create account</a></p>

    <?php endif; ?>

<?php endif; ?>

</div>

</body>
</html>
