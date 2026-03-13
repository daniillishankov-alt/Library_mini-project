<?php
session_start();
require("PHPRefs/connect.php");

$errorMessage = '';
$successMessage = '';

if(isset($_POST['submit'])) {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $year = trim($_POST['year'] ?? '');

    if(empty($title) || empty($author) || empty($genre) || empty($year)){
        $errorMessage = "All fields must be filled.";
    } else{
        try {
            $sql = "INSERT INTO books (title, author, genre, year)
                    VALUES (:title, :author, :genre, :year)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                    ':title'=>$title,
                    ':author'=>$author,
                    ':genre'=>$genre,
                    ':year'=>$year
            ]);

            $_SESSION['successMessage'] = "Book successfully added.";

            header("Location: index.php");
            exit();

        } catch(PDOException $e) {
            $errorMessage = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AddBookPage</title>
    <link rel="stylesheet" href="Styling/style.css">
</head>
<body>

<script src="Script/script.js"></script>
<?php
require("PHPRefs/connect.php");
require("PHPRefs/toppanel.php");
?>
<?php if(!empty($errorMessage)): ?>
    <div class="alert"><?= htmlspecialchars($errorMessage) ?></div>
<?php endif; ?>
<form method="post" action="">
    <table border="1">

        <tr>
            <td>Title</td>
            <td><input type="text" name="title"></td>
        </tr>

        <tr>
            <td>Author</td>
            <td><input type="text" name="author"></td>
        </tr>

        <tr>
            <td>Genre</td>
            <td><input type="text" name="genre"></td>
        </tr>

        <tr>
            <td>Year</td>
            <td><input type="number" name="year"></td>
        </tr>

        <tr>
            <td colspan="2">
                <input type="submit" name="submit" value="Submit">
            </td>
        </tr>

    </table>
</form>

<?php
require("PHPRefs/footer.php");
?>
</body>
</html>

