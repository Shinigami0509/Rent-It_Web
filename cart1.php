<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rentals - Rent It</title>
    <style>
        /* Add CSS styles for the search bar */
        .search-form {
            margin-bottom: 20px;
            text-align: center;
        }

        .search-input {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Rent It</h1>
            <!-- Navigation code -->
        </div>
    </header>

    <section id="rentals">
        <div class="container">
            <h2>Available Rentals</h2>
            <form class="search-form" method="GET" action="rentals.php">
                <input class="search-input" type="text" name="search" placeholder="Search rentals">
                <input class="search-button" type="submit" value="Search">
            </form>
            <!-- Rental items code -->
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Rent It</p>
        </div>
    </footer>
</body>
</html>
