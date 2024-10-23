<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'user_management');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $username, $hashed_password, $role);
    $stmt->fetch();

    if ($stmt->num_rows == 1 && password_verify($password, $hashed_password)) {
        $_SESSION['id'] = $id;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        if ($role == 'admin') {
            header('Location: admin_dashboard.php');
        } else {
            header('Location: user_dashboard.php');
        }
    } else {
        echo "Invalid username or password.";
    }
    $stmt->close();
}
?>

<style>
    body {
        background-color: lightgreen;
        font-family: 'Arial', sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .login-container {
        background-color: #f5f5dc; /* Beige color */
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        padding: 40px;
        width: 400px;
        text-align: center;
    }

    h2 {
        margin-bottom: 20px;
        color: #333;
        font-size: 24px;
    }

    input[type="text"], input[type="email"], input[type="password"], select {
        width: 100%;
        padding: 12px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-sizing: border-box;
    }

    input:focus, select:focus {
        border-color: #6a11cb;
        outline: none;
    }

    label {
        text-align: left;
        display: block;
        font-size: 14px;
        color: #666;
        margin-bottom: 5px;
    }

    button {
        background-color: #6a11cb;
        color: #ffffff;
        border: none;
        padding: 12px;
        width: 100%;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #540fa8;
    }

    .login-container p {
        font-size: 14px;
        color: #777;
    }

    .login-container p a {
        color: #6a11cb;
        text-decoration: none;
    }

    .login-container p a:hover {
        text-decoration: underline;
    }
</style>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const passwordCheckbox = document.getElementById('showPassword');

        if (passwordCheckbox.checked) {
            passwordInput.type = 'text'; // Show password
        } else {
            passwordInput.type = 'password'; // Hide password
        }
    }
</script>

<form method="POST">
    <div class="login-container">
        <h2>Login</h2>
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" id="password" required><br>

        <label>
            <input type="checkbox" id="showPassword" onclick="togglePassword()"> Show Password
        </label>

        <button type="submit">Login</button>
    </div>
</form>
