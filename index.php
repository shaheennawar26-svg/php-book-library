<?php
//Name: Shaheen Anwar Abo Lehya
//ID: 120202890

// Start the session to store data while the site is running
session_start();

// Define the current year and create the allowed genres list
$current_year = (int) date("Y");
$genres = ["Fiction", "Non-Fiction", "Science", "History", "Biography", "Technology"];

// Add default data if the session is new
if (!isset($_SESSION['books'])) {
    $_SESSION['books'] = [
        [
            "id" => 1,
            "title" => "Introduction to Algorithms",
            "author" => "Thomas H. Cormen",
            "genre" => "Technology",
            "year" => 2009,
            "pages" => 1292,
            "image_url" => "https://covers.openlibrary.org/b/isbn/9780262033848-M.jpg",
        ],
        [
            "id" => 2,
            "title" => "Guns, Germs, and Steel",
            "author" => "Jared Diamond",
            "genre" => "History",
            "year" => 1997,
            "pages" => 480,
            "image_url" => "https://covers.openlibrary.org/b/isbn/9780393317558-M.jpg",
        ],
        [
            "id" => 3,
            "title" => "Thinking, Fast and Slow",
            "author" => "Daniel Kahneman",
            "genre" => "Science",
            "year" => 2011,
            "pages" => 499,
            "image_url" => "https://covers.openlibrary.org/b/isbn/9780374275631-M.jpg",
        ],
    ];
}

// Link the books variable to the session data
$all_books = &$_SESSION['books'];

// Array for storing errors
$errors = [];

// Default form data
$submittedData = ['title' => '','author' => '','genre' => '','year' => '','pages' => '','image_url' => '',];

// Variable to know whether a book is being edited
$edit_id = null;

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Delete a book from the list
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $delete_id = (int) $_POST['delete_id'];

        // Remove the book by its ID
        $_SESSION['books'] = array_values(
            array_filter($_SESSION['books'], fn($b) => $b['id'] !== $delete_id)
        );

        // Success message
        $_SESSION['success'] = "Book removed successfully.";

        // Reload the page
        header("Location: index.php");
        exit;
    }

    // Add or edit a book
    else {

        // Clean the submitted data
        foreach ($submittedData as $key => $val) {
            $submittedData[$key] = htmlspecialchars(trim($_POST[$key] ?? ''));
        }

        // Get the book ID if this is an edit
        $edit_id = isset($_POST['edit_id']) ? (int) $_POST['edit_id'] : null;

        // Validate the title
        if (
            empty($submittedData['title']) ||
            strlen($submittedData['title']) < 3 ||
            strlen($submittedData['title']) > 120
        ) {
            $errors['title'] = "Title is required (3-120 characters).";
        }

        // Validate the author name
        if (empty($submittedData['author'])) {
            $errors['author'] = "Author name is required.";
        } elseif (count(explode(' ', trim($submittedData['author']))) < 2) {
            $errors['author'] = "Must contain at least two words.";
        }

        // Validate the genre
        if (empty($submittedData['genre']) || !in_array($submittedData['genre'], $genres)) {
            $errors['genre'] = "Please select a valid genre.";
        }

        // Validate the publication year
        if (empty($submittedData['year']) || !preg_match('/^\d{4}$/', $submittedData['year'])) {
            $errors['year'] = "Enter a valid 4-digit year.";
        } elseif ($submittedData['year'] < 1000 || $submittedData['year'] > $current_year) {
            $errors['year'] = "Year must be between 1000 and " . $current_year . ".";
        }

        // Validate the number of pages
        if (
            empty($submittedData['pages']) ||
            !filter_var($submittedData['pages'], FILTER_VALIDATE_INT) ||
            $submittedData['pages'] <= 0
        ) {
            $errors['pages'] = "Pages must be greater than 0.";
        }

        // Validate the image URL
        if (
            !empty($submittedData['image_url']) &&
            !preg_match('/\.(jpg|jpeg|png|gif)$/i', $submittedData['image_url'])
        ) {
            $errors['image_url'] = "Image URL must end with .jpg, .png, or .gif.";
        }

        // If there are no errors, save the data
        if (empty($errors)) {

            // Update an existing book
            if ($edit_id) {
                foreach ($all_books as &$book) {
                    if ($book['id'] === $edit_id) {
                        $book = array_merge(['id' => $edit_id], $submittedData);
                        break;
                    }
                }
                unset($book);

                $_SESSION['success'] = "Book updated successfully.";
            }

            // Add a new book
            else {
                $max_id = 0;

                // Find the largest existing ID
                foreach ($all_books as $b) {
                    if ($b['id'] > $max_id) {
                        $max_id = $b['id'];
                    }
                }

                // Add the new book
                $all_books[] = array_merge(['id' => $max_id + 1], $submittedData);

                $_SESSION['success'] = "Book added successfully.";
            }

            // Reload the page to prevent duplicate submission
            header("Location: index.php");
            exit;
        }
    }
}

// Load book data for editing
if (isset($_GET['edit_id']) && empty($errors)) {
    $edit_id = (int) $_GET['edit_id'];

    foreach ($all_books as $b) {
        if ($b['id'] === $edit_id) {
            $submittedData = $b;
            break;
        }
    }
}

// Copy books for display in the table
$displayBooks = $all_books;

// Get the search keyword
$keyword = trim($_GET['search'] ?? '');

// Filter books by search
if ($keyword !== '') {
    $displayBooks = array_filter(
        $displayBooks,
        fn($b) =>
            stripos($b['title'], $keyword) !== false ||
            stripos($b['author'], $keyword) !== false
    );
}

// Determine the column used for sorting
$sort_col = $_GET['sort'] ?? 'id';

// Sort data by the selected column
if (in_array($sort_col, ['id', 'title', 'author', 'genre', 'year', 'pages'])) {
    usort($displayBooks, function ($a, $b) use ($sort_col) {
        return is_numeric($a[$sort_col])
            ? $a[$sort_col] <=> $b[$sort_col]
            : strcmp($a[$sort_col], $b[$sort_col]);
    });
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <!-- Make the page mobile responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Personal Library Manager</title>

    <!-- Load Bootstrap for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --main-brown: #5d4037;
            --soft-brown: #8d6e63;
        }

        body {
            background-image: url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?q=80&w=2000&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        h2 {
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            font-weight: bold;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.88) !important;
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 12px;
        }

        .bg-custom-dark {
            background-color: var(--main-brown) !important;
        }

        .table thead th,
        .table thead th a {
            background-color: var(--main-brown) !important;
            color: #ffffff !important;
            font-weight: bold;
            text-decoration: none;
            border: none;
        }

        .table-responsive {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            padding: 15px;
        }

        .btn-primary {
            background-color: var(--main-brown);
            border: none;
        }

        .btn-primary:hover {
            background-color: var(--soft-brown);
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <h2 class="text-center mb-5">📚 Personal Book Library</h2>

        <!-- Show success message -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show fw-bold text-center">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Add and edit book form -->
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header bg-custom-dark text-white">
                        <h5 class="mb-0 fw-bold">
                            <?= $edit_id ? 'Update Book Details' : 'Add New Book' ?>
                        </h5>
                    </div>

                    <div class="card-body">
                        <!-- Show validation errors -->
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger p-2 text-center small fw-bold">
                                Please fill in all required fields correctly.
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <!-- Pass the ID when editing -->
                            <?php if ($edit_id): ?>
                                <input type="hidden" name="edit_id" value="<?= $edit_id ?>">
                            <?php endif; ?>

                            <!-- Title field -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Title *</label>
                                <input
                                    type="text"
                                    name="title"
                                    class="form-control <?= isset($errors['title']) ? 'is-invalid' : '' ?>"
                                    value="<?= htmlspecialchars((string) $submittedData['title']) ?>"
                                >
                                <div class="invalid-feedback">
                                    <?= $errors['title'] ?? '' ?>
                                </div>
                            </div>

                            <!-- Author field -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Author *</label>
                                <input
                                    type="text"
                                    name="author"
                                    class="form-control <?= isset($errors['author']) ? 'is-invalid' : '' ?>"
                                    value="<?= htmlspecialchars((string) $submittedData['author']) ?>"
                                >
                                <div class="invalid-feedback">
                                    <?= $errors['author'] ?? '' ?>
                                </div>
                            </div>

                            <!-- Year and pages fields -->
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label fw-bold">Year *</label>
                                    <input
                                        type="number"
                                        name="year"
                                        class="form-control <?= isset($errors['year']) ? 'is-invalid' : '' ?>"
                                        value="<?= htmlspecialchars((string) $submittedData['year']) ?>"
                                    >
                                    <div class="invalid-feedback">
                                        <?= $errors['year'] ?? '' ?>
                                    </div>
                                </div>

                                <div class="col-6 mb-3">
                                    <label class="form-label fw-bold">Pages *</label>
                                    <input
                                        type="number"
                                        name="pages"
                                        class="form-control <?= isset($errors['pages']) ? 'is-invalid' : '' ?>"
                                        value="<?= htmlspecialchars((string) $submittedData['pages']) ?>"
                                    >
                                    <div class="invalid-feedback">
                                        <?= $errors['pages'] ?? '' ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Genre selection -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Genre *</label>
                                <select name="genre" class="form-select <?= isset($errors['genre']) ? 'is-invalid' : '' ?>">
                                    <option value="">Select...</option>
                                    <?php foreach ($genres as $g): ?>
                                        <option value="<?= $g ?>" <?= $submittedData['genre'] == $g ? 'selected' : '' ?>>
                                            <?= $g ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">
                                    <?= $errors['genre'] ?? '' ?>
                                </div>
                            </div>

                            <!-- Image URL field -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Cover URL (Optional)</label>
                                <input
                                    type="text"
                                    name="image_url"
                                    class="form-control <?= isset($errors['image_url']) ? 'is-invalid' : '' ?>"
                                    value="<?= htmlspecialchars((string) $submittedData['image_url']) ?>"
                                >
                                <div class="invalid-feedback">
                                    <?= $errors['image_url'] ?? '' ?>
                                </div>
                            </div>

                            <!-- Save button -->
                            <button type="submit" class="btn btn-primary w-100 fw-bold">
                                <?= $edit_id ? 'Update Book' : 'Add Book' ?>
                            </button>

                            <!-- Cancel edit button -->
                            <?php if ($edit_id): ?>
                                <a href="index.php" class="btn btn-link w-100 mt-2 text-dark text-decoration-none small text-center d-block">
                                    Cancel Edit
                                </a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Books display table -->
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-body">
                        <!-- Search form -->
                        <form method="GET" class="mb-3">
                            <div class="input-group">
                                <input
                                    type="text"
                                    name="search"
                                    class="form-control"
                                    placeholder="Search by title or author..."
                                    value="<?= htmlspecialchars($keyword) ?>"
                                >
                                <button class="btn btn-primary text-white fw-bold" type="submit">
                                    Search
                                </button>

                                <!-- Clear search button -->
                                <?php if ($keyword): ?>
                                    <a href="index.php" class="btn btn-secondary fw-bold">Clear</a>
                                <?php endif; ?>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <?php
                                        // Table column names
                                        $tableheaders = [
                                            'id' => '#',
                                            'title' => 'Title',
                                            'author' => 'Author',
                                            'genre' => 'Genre',
                                            'year' => 'Year',
                                            'pages' => 'Pages',
                                        ];

                                        // The current column used for sorting
                                        $current_sort = $_GET['sort'] ?? 'id';

                                        // Create table headers with sorting
                                        foreach ($tableheaders as $key => $title): ?>
                                            <th class="text-nowrap">
                                                <a
                                                    href="?sort=<?= $key ?>&search=<?= urlencode($keyword) ?>"
                                                    class="text-white text-decoration-none d-flex align-items-center justify-content-between"
                                                >
                                                    <span><?= $title ?></span>

                                                    <!-- Icon showing sort state -->
                                                    <span
                                                        class="ms-1"
                                                        style="font-size: 0.8rem; opacity: <?= ($current_sort == $key) ? '1' : '0.4' ?>;"
                                                    >
                                                        <?= ($current_sort == $key) ? '▼' : '↕' ?>
                                                    </span>
                                                </a>
                                            </th>
                                        <?php endforeach; ?>

                                        <th class="text-white">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <!-- Display all books -->
                                    <?php foreach ($displayBooks as $book): ?>
                                        <tr>
                                            <td><?= $book['id'] ?></td>

                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <!-- Display the book cover image -->
                                                    <?php if (!empty($book['image_url'])): ?>
                                                        <img
                                                            src="<?= htmlspecialchars($book['image_url']) ?>"
                                                            class="me-2 rounded shadow-sm"
                                                            style="width: 30px; height: 42px; object-fit: cover;"
                                                        >
                                                    <?php endif; ?>

                                                    <span class="fw-semibold">
                                                        <?= htmlspecialchars($book['title']) ?>
                                                    </span>
                                                </div>
                                            </td>

                                            <td><?= htmlspecialchars($book['author']) ?></td>
                                            <td><span class="badge bg-secondary"><?= htmlspecialchars($book['genre']) ?></span></td>
                                            <td><?= (int) $book['year'] ?></td>
                                            <td><?= (int) $book['pages'] ?></td>

                                            <td>
                                                <!-- Edit button -->
                                                <div class="btn-group btn-group-sm">
                                                    <a href="?edit_id=<?= $book['id'] ?>" class="btn btn-warning fw-bold">
                                                        Edit
                                                    </a>

                                                    <!-- Delete button -->
                                                    <button
                                                        type="button"
                                                        class="btn btn-danger fw-bold"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#del<?= $book['id'] ?>"
                                                    >
                                                        Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <!-- Message when no results are found -->
                                    <?php if (empty($displayBooks)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                No books found matching your criteria.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete confirmation modal -->
    <?php foreach ($displayBooks as $book): ?>
        <div class="modal fade" id="del<?= $book['id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center py-4">
                        <h6 class="fw-bold">
                            Delete "<?= htmlspecialchars($book['title']) ?>"?
                        </h6>

                        <form method="POST" class="mt-3">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="delete_id" value="<?= $book['id'] ?>">

                            <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">
                                Cancel
                            </button>

                            <button type="submit" class="btn btn-danger fw-bold">
                                Confirm Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Bootstrap JavaScript file -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
