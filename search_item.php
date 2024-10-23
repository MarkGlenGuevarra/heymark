<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

echo "Welcome, Admin " . $_SESSION['username'];

// Database connection
$conn = new mysqli('localhost', 'root', '', 'users_management');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if search query is set
$searchQuery = '';
if (isset($_GET['search'])) {
    $searchQuery = $conn->real_escape_string($_GET['search']);
    $sql = "SELECT * FROM items WHERE item_name LIKE '%$searchQuery%' OR description LIKE '%$searchQuery%'";
} else {
    $sql = "SELECT * FROM items"; // Default query without search
}

$result = $conn->query($sql);
?>
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
            max-width: 800px;
            margin: 0 auto;
            overflow: auto;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #6a11cb;
            color: #ffffff;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        a {
            text-decoration: none;
            color: #6a11cb;
            padding: 5px 10px;
            border: 1px solid transparent;
            transition: all 0.3s ease;
        }

        a:hover {
            border: 1px solid #6a11cb;
            border-radius: 4px;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .action-buttons a {
            border: 1px solid #6a11cb;
            padding: 5px 10px;
            border-radius: 4px;
            color: #6a11cb;
            background-color: #ffffff;
            transition: background-color 0.3s;
        }

        .action-buttons a:hover {
            background-color: #6a11cb;
            color: #ffffff;
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
    <div class="table-container">
<h2>Item Management</h2>

<!-- Search Form -->
<form method="GET" action="search_item.php">
    <input type="text" name="search" placeholder="Search items..." value="<?= htmlspecialchars($searchQuery) ?>">
    <button type="submit">Search</button>
</form>

<!-- Display Items -->
<table border="1">
    <tr>
        <th>ID</th>
        <th>Item Name</th>
        <th>Description</th>
        <th>Action</th>
    </tr>
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['item_name'] ?></td>
                <td><?= $row['description'] ?></td>
                <td>
                    <a href="edit_item.php?id=<?= $row['id'] ?>">Edit</a>
                    <a href="delete_item.php?id=<?= $row['id'] ?>">Delete</a>
                    <a href="search_item.php?id=<?= $row['id'] ?>">Search</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">No items found</td>
        </tr>
    <?php endif; ?>
</table>

<a href="add_item.php">Add New Item</a>
<a href="logout.php">Logout</a>

