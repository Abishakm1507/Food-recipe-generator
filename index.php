<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food ❤️ | Recipe Generator</title>
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff5e6; /* Light orange background */
        }

        /* Header Styles */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ff6f61;
            color: white;
            padding: 15px 30px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 28px;
            margin: 0;
        }

        header .auth-buttons {
            display: flex;
            gap: 10px;
        }

        header .auth-buttons a {
            text-decoration: none;
            background-color: white;
            color: #ff6f61;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: bold;
        }

        header .auth-buttons a:hover {
            background-color: #ff8c78;
            color: white;
        }

        /* Main Section */
        .main {
            text-align: center;
            padding: 50px 20px;
        }

        .main h2 {
            font-size: 36px;
            color: #ff6f61;
        }

        .main p {
            font-size: 18px;
            color: #555;
            margin: 20px 0;
        }

        /* What We Offer Section */
        .offer-section {
    display: flex;
    flex-wrap: wrap;
    gap: 40px; /* Larger space between cards */
    justify-content: center;
    padding: 40px;
}

.offer-card {
    display: flex;
    flex-direction: column; /* Stack image and text vertically */
    align-items: center;
    text-decoration: none;
    color: #333; /* Default text color */
    background-color: #fff; /* Background color for the card */
    border: 1px solid #ddd; /* Light border around each card */
    border-radius: 15px; /* Larger rounded corners */
    width: 320px; /* Substantially larger card width */
    padding: 30px; /* Increased padding for more spacious cards */
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2); /* Stronger shadow for depth */
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* Smooth hover effects */
    text-align: center;
}

.offer-card img {
    width: 200px; /* Significantly larger image size */
    height: 200px; /* Keep the image square */
    margin-bottom: 20px; /* Add spacing between image and text */
    border-radius: 50%; /* Make the image circular */
    object-fit: cover; /* Ensure the image fits within the circle */
}

.offer-card span {
    font-size: 24px; /* Bigger font size for emphasis */
    font-weight: bold; /* Keep the text bold for clarity */
}

.offer-card:hover {
    transform: translateY(-15px); /* Lift card higher on hover */
    box-shadow: 0px 12px 25px rgba(0, 0, 0, 0.3); /* Even stronger shadow on hover */
    background-color: #f2f2f2; /* Slightly lighter background */
}

.offer-section:hover > .offer-card:not(:hover) {
    opacity: 0.5; /* Dim non-hovered cards more for focus effect */
}



        /* Chatbot Section */
        .chatbot {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background-color: #ff6f61;
            border-radius: 50%;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 30px;
            cursor: pointer;
            z-index: 1000;
        }

        .chatbot:hover {
            background-color: #ff8c78;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <h1>Food ❤️</h1>
        <div class="auth-buttons">
            <a href="login.php">Login/SignUp</a>
        </div>
    </header>

    <!-- Main Content -->
    <div class="main">
        <h2>Welcome to Food ❤️</h2>
        <p>Your one-stop solution for exploring delicious recipes from around the world!</p>
    </div>

    <!-- What We Offer Section -->
    <section class="offer-section">
    <a href="searchByName.php" class="offer-card">
        <img src="images/searchByName.jpg" alt="Search by Meal Name">
        <span>Search by Meal Name</span>
    </a>
    <a href="searchByArea.php" class="offer-card">
        <img src="images/searchByArea.jpg" alt="Search by Area/Region">
        <span>Search by Area/Region</span>
    </a>
    <a href="searchByIngredients.php" class="offer-card">
        <img src="images/searchByIngredients.jpg" alt="Search by Ingredients">
        <span>Search by Ingredients</span>
    </a>
    <a href="searchByLetter.php" class="offer-card">
        <img src="images/searchByLetter.jpg" alt="Search by First Letter">
        <span>Search by First Letter</span>
    </a>
    <a href="searchByCategory.php" class="offer-card">
        <img src="images/searchByCategory.jpg" alt="Search by Category">
        <span>Search by Category</span>
    </a>
    <a href="randomMeal.php" class="offer-card">
        <img src="images/randomMeal.jpg" alt="Random Meal Generator">
        <span>Random Meal Generator</span>
    </a>
</section>


    <!-- Chatbot Button -->
    <div class="chatbot" title="Chat with us!">
        <a href="chatbot1.php" style="text-decoration:none">💬</a>
    </div>
</body>
</html>
8