<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'users_management');

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];

    // Prepare the insert statement
    $stmt = $conn->prepare("INSERT INTO items (item_name, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $item_name, $description);

    // Execute the statement and check if the item was added successfully
    if ($stmt->execute()) {
        header('Location: admin_dashboard.php');
        exit();
    } else {
        $error_message = "Error adding item: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .table-container {
            max-width: 600px;
            margin: 0 auto;
            overflow: auto;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            padding: 20px;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 20px;
            background-color: #6a11cb;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #540fa8;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        .logout-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #6a11cb;
        }

        .logout-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="table-container">
    <h2>Add Item</h2>

    <!-- Display error message if there is an issue adding the item -->
    <?php if (isset($error_message)): ?>
        <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="item_name">Item Name</label>
        <input type="text" id="item_name" name="item_name" placeholder="Item Name" required maxlength="500">

        <label for="description">Description</label>
        <textarea id="description" name="description" placeholder="Description" required maxlength="1000"></textarea>

        <button type="submit">Add Item</button>
    </form>

    <a href="admin_dashboard.php" class="logout-link">Back to Dashboard</a>
</div>

</body>
</html>
