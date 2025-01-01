<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $response = "";

    if ($action === 'getMealsByCategory') {
        $category = htmlspecialchars($_POST['category']);
        $apiUrl = "https://www.themealdb.com/api/json/v1/1/filter.php?c=" . urlencode($category);
        $apiResponse = file_get_contents($apiUrl);
        $mealsData = json_decode($apiResponse, true);

        if (!empty($mealsData['meals'])) {
            foreach ($mealsData['meals'] as $meal) {
                $mealDetailsUrl = "https://www.themealdb.com/api/json/v1/1/lookup.php?i=" . urlencode($meal['idMeal']);
                $mealDetailsResponse = file_get_contents($mealDetailsUrl);
                $mealDetails = json_decode($mealDetailsResponse, true)['meals'][0];

                $mealName = htmlspecialchars($mealDetails['strMeal']);
                $mealThumb = htmlspecialchars($mealDetails['strMealThumb']);
                $category = htmlspecialchars($mealDetails['strCategory']);
                $area = htmlspecialchars($mealDetails['strArea']);
                $videoLink = htmlspecialchars($mealDetails['strYoutube']);
                $instructions = htmlspecialchars($mealDetails['strInstructions']);

                $response .= "
                    <div class='meal-item'>
                        <div class='meal-img'>
                            <img src='$mealThumb' alt='$mealName'>
                        </div>
                        <div class='meal-name'>
                            <h3>$mealName</h3>
                            <p><strong>Category:</strong> $category</p>
                            <p><strong>Area:</strong> $area</p>
                            " . (!empty($videoLink) ? "<p><strong>Watch Video:</strong> <a href='$videoLink' target='_blank'>Video Link</a></p>" : "") . "
                            <button class='btn get-recipe-btn' data-recipe='$instructions'>Get Recipe</button>
                        </div>
                    </div>
                ";
            }
        } else {
            $response = "<p>Sorry, no meals found for this category!</p>";
        }
    }

    echo $response;
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Filter Meals by Category</title>
  <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background: linear-gradient(to right, #ffecd2, #fcb69f);
        color: #333;
    }

    .container {
        max-width: 1200px;
        margin: 50px auto;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    h1 {
        font-size: 2em;
        margin-bottom: 20px;
        color: #ff6f61;
    }

    .meal-item {
        display: inline-block;
        margin: 10px;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        text-align: center;
        background-color: #f9f9f9;
        width: calc(33.333% - 20px);
        box-sizing: border-box;
    }

    .meal-img img {
        max-width: 100%;
        border-radius: 5px;
    }

    .meal-name {
        margin-top: 10px;
        font-size: 1em;
        color: #333;
    }

    .btn {
        background-color: #ff6f61;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1em;
        margin-top: 10px;
    }

    .btn:hover {
        background-color: #e65a50;
    }

    select {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1em;
        width: 100%;
        margin-bottom: 20px;
    }

    .recipe-popup {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.3);
        display: none;
        z-index: 1000;
    }

    .recipe-popup h3 {
        margin: 0 0 10px;
    }

    .recipe-popup p {
        margin: 10px 0;
        text-align: left;
    }

    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        z-index: 999;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>🍴 Filter Meals by Category</h1>
    <form method="POST" action="">
      <input type="hidden" name="action" value="getMealsByCategory">
      <select name="category" required>
        <option value="" disabled selected>Select a Category</option>
        <option value="Seafood">Seafood</option>
        <option value="Vegetarian">Vegetarian</option>
        <option value="Dessert">Dessert</option>
        <option value="Beef">Beef</option>
        <option value="Chicken">Chicken</option>
      </select>
      <button type="submit" class="btn">Show Meals</button>
    </form>
    <div id="meals">
      <!-- PHP dynamically populates meals here -->
    </div>
  </div>

  <div class="overlay"></div>
  <div class="recipe-popup">
    <h3>Recipe Instructions</h3>
    <p id="recipe-text"></p>
    <button class="btn close-btn">Close</button>
  </div>

  <script>
    document.querySelector('form').addEventListener('submit', async function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const response = await fetch('', {
            method: 'POST',
            body: formData
        });

        const result = await response.text();
        document.getElementById('meals').innerHTML = result;

        // Add event listeners for "Get Recipe" buttons
        document.querySelectorAll('.get-recipe-btn').forEach(button => {
            button.addEventListener('click', () => {
                const recipe = button.dataset.recipe;
                document.getElementById('recipe-text').innerText = recipe;
                document.querySelector('.overlay').style.display = 'block';
                document.querySelector('.recipe-popup').style.display = 'block';
            });
        });
    });

    document.querySelector('.close-btn').addEventListener('click', () => {
        document.querySelector('.overlay').style.display = 'none';
        document.querySelector('.recipe-popup').style.display = 'none';
    });
  </script>
</body>
</html>
