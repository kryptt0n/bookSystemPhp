<?php
require('book.php');
session_start();

if (!isset($_SESSION['books'])) {
    $_SESSION['books'] = [];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header('Content-Type: application/json');

    $title = $_POST['title'];
    $author = $_POST['author'];
    $pubYear = $_POST['pubYear'];

    try {
        if (empty($title) || empty($author) || empty($pubYear)) {
            throw new Exception('All fields are required.');
        }
        $book = new Book($title, $author, $pubYear);
        $_SESSION['books'][] = $book;
        $booksArray = array_map(function($b) {
            return [
                'title' => $b->getTitle(),
                'author' => $b->getAuthor(),
                'year' => $b->getYear()
            ];
        }, $_SESSION['books']);

        echo json_encode(['success' => true, 'books' => $booksArray]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Management System</title>
    <script>
        function submitForm(event) {
            event.preventDefault();
            const formData = new FormData(event.target);

            fetch('index.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayBooks(data.books);
                } else {
                    alert(data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function displayBooks(books) {
            const bookList = document.getElementById('bookList');
            bookList.innerHTML = '';

            if (books.length === 0) {
                bookList.innerHTML = '<p>No books have been added yet.</p>';
                return;
            }

            const table = document.createElement('table');
            table.innerHTML = `<tr>
                <th>Title</th>
                <th>Author</th>
                <th>Publication Year</th>
            </tr>`;

            books.forEach(book => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${book.title}</td>
                    <td>${book.author}</td>
                    <td>${book.year}</td>
                `;
                table.appendChild(row);
            });

            bookList.appendChild(table);
        }
    </script>
</head>
<body>
    <div id="bookList">
        <p>No books have been added yet.</p>
    </div>
    <form id="bookForm" method="post" onsubmit="submitForm(event)">
        <br>
        <label for="title">Title</label>
        <input type="text" name="title" id="title" required>
        <br>
        <label for="author">Author</label>
        <input type="text" name="author" id="author" required>
        <br>
        <label for="pubYear">Publication year</label>
        <input type="number" name="pubYear" id="pubYear" required>
        <br>
        <input type="submit" value="Add">
    </form>
</body>
</html>
