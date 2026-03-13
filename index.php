<?php

session_start();

$errorMessage = $_SESSION['errorMessage'] ?? '';
$successMessage = $_SESSION['successMessage'] ?? '';

unset($_SESSION['errorMessage'], $_SESSION['successMessage']);

require("PHPRefs/connect.php");

$allowedFilters = ["title","author","genre","year"];

$filter = $_POST['filter'] ?? '';
$value = $_POST['value'] ?? '';

$rows = [];

if(isset($_POST['search'])){

    if(!in_array($filter,$allowedFilters)){
        die("Invalid filter");
    }

    if($value == ""){

        $sql = "SELECT * FROM books";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

    } else {

        $sql = "SELECT * FROM books WHERE $filter LIKE :value";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':value' => "%$value%"]);

    }

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library</title>
    <link rel="stylesheet" href="Styling/style.css">
</head>

<body>
<header>
    <?php require_once("PHPRefs/toppanel.php"); ?>
</header>
<script src="Script/script.js"></script>


<?php if(!empty($errorMessage)): ?>
    <div class="alert"><?= htmlspecialchars($errorMessage) ?></div>
<?php endif; ?>

<?php if(!empty($successMessage)): ?>
    <div class="alert" style="background-color: #22c55e;"><?= htmlspecialchars($successMessage) ?></div>
<?php endif; ?>
<form method="post">

    <label>Search by:</label>

    <select name="filter">

        <option value="title" <?= $filter=="title" ? "selected":"" ?>>Title</option>

        <option value="author" <?= $filter=="author" ? "selected":"" ?>>Author</option>

        <option value="genre" <?= $filter=="genre" ? "selected":"" ?>>Genre</option>

        <option value="year" <?= $filter=="year" ? "selected":"" ?>>Year</option>

    </select>

    <br><br>

    <label>Value:</label>
    <input type="text" name="value" value="<?= htmlspecialchars($value) ?>">

    <br><br>

    <input type="submit" name="search" value="Search">

</form>

<?php if(!empty($rows)): ?>

    <h2>Search Results</h2>

    <table border="1">

        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Genre</th>
            <th>Year</th>
        </tr>

        <?php foreach($rows as $book): ?>

            <tr>
                <td><?= htmlspecialchars($book['title']) ?></td>
                <td><?= htmlspecialchars($book['author']) ?></td>
                <td><?= htmlspecialchars($book['genre']) ?></td>
                <td><?= htmlspecialchars($book['year']) ?></td>
            </tr>

        <?php endforeach; ?>

    </table>

<?php elseif(isset($_POST['search'])): ?>

    <p>No books found.</p>

<?php endif; ?>


<?php
require("PHPRefs/footer.php");
?>
</body>
</html>

