<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'users_management');

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];

    // Use a prepared statement to update the item
    $stmt = $conn->prepare("UPDATE items SET item_name = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssi", $item_name, $description, $id);
    $stmt->execute();

    // Redirect back to the dashboard
    header('Location: admin_dashboard.php');
    exit();
} else {
    // Use a prepared statement to fetch the item by id
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item</title>
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
    <h2>Edit Item</h2>

    <!-- The form now prepopulates with the current item details -->
    <form method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($item['id']) ?>">

        <label for="item_name">Item Name</label>
        <input type="text" id="item_name" name="item_name" value="<?= htmlspecialchars($item['item_name']) ?>" required maxlength="500"><br>

        <label for="description">Description</label>
        <textarea id="description" name="description" required maxlength="1000"><?= htmlspecialchars($item['description']) ?></textarea><br>

        <button type="submit">Update Item</button>
    </form>
</div>

</body>
</html>
