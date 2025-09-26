<?php
session_start();
include("config/db.php");
if ($_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}


if (isset($_POST['borrow'])) {
    $id = $_POST['id'];
    $conn->query("UPDATE books SET available=FALSE WHERE id=$id AND available=TRUE");
}


if (isset($_POST['return'])) {
    $id = $_POST['id'];
    $conn->query("UPDATE books SET available=TRUE WHERE id=$id");
}


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
<head><title>Student</title></head>
<body>
    <h2>Student Dashboard</h2>
    <a href="login.php">Logout</a>
    <hr>

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
                <?php if ($row['available']) { ?>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button name="borrow">Borrow</button>
                    </form>
                <?php } else { ?>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button name="return">Return</button>
                    </form>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
