<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'users_management');

// Fetch admin details including profile picture
$username = $_SESSION['username'];
$query = $conn->query("SELECT profile_picture FROM users WHERE username = '$username'");
$user = $query->fetch_assoc();
$profile_picture = !empty($user['profile_picture']) ? $user['profile_picture'] : 'default.jpg';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    // Handle profile picture upload
    $target_dir = "uploads/";

    // Check if uploads directory exists, if not create it
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);  // Create directory if it doesn't exist with the correct permissions
    }

    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a valid image
    $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (limit to 5MB)
    if ($_FILES["profile_picture"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // Move uploaded file
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            // Update the profile picture path in the database
            $conn->query("UPDATE users SET profile_picture = '$target_file' WHERE username = '$username'");
            $profile_picture = $target_file;
            echo "The file " . htmlspecialchars(basename($_FILES["profile_picture"]["name"])) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file. Error: " . $_FILES['profile_picture']['error'];
        }
    }
}

echo "Welcome, Admin " . $_SESSION['username'];

// Fetch items from the database
$result = $conn->query("SELECT * FROM items");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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

        .profile-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-container img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
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

        .print-button {
            display: block;
            margin: 10px auto;
            padding: 10px 20px;
            background-color: #6a11cb;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .print-button:hover {
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

<div class="profile-container">
    <img src="<?= $profile_picture ?>" alt="Profile Picture">
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="profile_picture" required>
        <button type="submit">Upload Profile Picture</button>
    </form>
</div>

<div class="table-container">
    <h2>Item Management</h2>
    <a href="add_item.php">Add Item</a>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Item Name</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
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
    </table>

    <a href="logout.php" class="logout-link">Logout</a> 
    <button class="print-button" onclick="printPage()">Print</button>
</div>

<script>
    function printPage() {
        window.print();
    }
</script>

</body>
</html>
