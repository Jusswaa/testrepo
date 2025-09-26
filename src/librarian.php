<?php
session_start();
include("config/db.php");
if ($_SESSION['role'] != 'librarian') {
    header("Location: login.php");
    exit();
}

// Handle new book
if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $conn->query("INSERT INTO books (title, author, available) VALUES ('$title', '$author', TRUE)");
}

// Handle edit
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $conn->query("UPDATE books SET title='$title', author='$author' WHERE id=$id");
}

// Handle delete
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $conn->query("DELETE FROM books WHERE id=$id");
}

// Search
$search = "";
if (isset($_POST['search'])) {
    $search = $_POST['keyword'];
    $books = $conn->query("SELECT * FROM books WHERE title LIKE '%$search%' OR author LIKE '%$search%'");
} else {
    $books = $conn->query("SELECT * FROM books");
}
?>

<!DOCTYPE html>
<html>
<head><title>Librarian</title></head>
<body>
    <h2>Librarian Dashboard</h2>
    <a href="login.php">Logout</a>
    <hr>

    <h3>Add New Book</h3>
    <form method="POST">
        Title: <input type="text" name="title" required>
        Author: <input type="text" name="author" required>
        <button name="add">Add</button>
    </form>

    <h3>Search Books</h3>
    <form method="POST">
        <input type="text" name="keyword" placeholder="Search title/author">
        <button name="search">Search</button>
    </form>

    <h3>Book Catalog</h3>
    <table border="1">
        <tr><th>ID</th><th>Title</th><th>Author</th><th>Available</th><th>Actions</th></tr>
        <?php while($row = $books->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['title'] ?></td>
            <td><?= $row['author'] ?></td>
            <td><?= $row['available'] ? "Yes" : "No" ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="text" name="title" value="<?= $row['title'] ?>">
                    <input type="text" name="author" value="<?= $row['author'] ?>">
                    <button name="edit">Edit</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button name="delete">Delete</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
