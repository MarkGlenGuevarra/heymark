<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'user_management');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role']; // can be 'user' or 'admin'

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $role);

    if ($stmt->execute()) {
        echo "Registration successful.";
        header('Location: login.php');
    } else {
        echo "Error: " . $stmt->error;
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

        .register-container {
            background-color: #ffffff;
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

        .register-container p {
            font-size: 14px;
            color: #777;
        }

        .register-container p a {
            color: #6a11cb;
            text-decoration: none;
        }

        .register-container p a:hover {
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
    </head>
<form method="POST">

    <div class="register-container">
    <h2>Register</h2>
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" id="password" name="password" placeholder="Password" required>
    <input type="checkbox" id="showPassword"
     onclick="togglePassword()">Show Password
      
        
    <label for="role">Select Role:</label>
    <select name="role">
        <option value="user">User</option>
        <option value="admin">Admin</option>
    </select><br>
  
            <button type="submit">Register</button>
        </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
    
    </div>

</form>
