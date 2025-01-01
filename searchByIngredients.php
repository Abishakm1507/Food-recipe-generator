<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
    $response = "";

    // Fetch meals by ingredient
    if ($action === 'getMealsByIngredient') {
        $ingredient = filter_input(INPUT_POST, 'ingredient', FILTER_SANITIZE_STRING);

        if (empty($ingredient)) {
            echo "<p>Please provide a valid ingredient.</p>";
            exit;
        }

        $apiUrl = "https://www.themealdb.com/api/json/v1/1/filter.php?i=" . urlencode($ingredient);
        $apiResponse = @file_get_contents($apiUrl);

        if ($apiResponse === false) {
            echo "<p>Unable to fetch data. Please try again later.</p>";
            exit;
        }

        $mealsData = json_decode($apiResponse, true);

        if (!empty($mealsData['meals'])) {
            foreach ($mealsData['meals'] as $meal) {
                // Fetch detailed data for each meal to get Category, Area, and Video link
                $mealDetailsUrl = "https://www.themealdb.com/api/json/v1/1/lookup.php?i=" . urlencode($meal['idMeal']);
                $mealDetailsResponse = @file_get_contents($mealDetailsUrl);

                if ($mealDetailsResponse === false) continue;

                $mealDetails = json_decode($mealDetailsResponse, true)['meals'][0] ?? null;

                if (!$mealDetails) continue;

                $category = htmlspecialchars($mealDetails['strCategory']);
                $area = htmlspecialchars($mealDetails['strArea']);
                $videoLink = htmlspecialchars($mealDetails['strYoutube']);
                $mealName = htmlspecialchars($meal['strMeal']);
                $mealThumb = htmlspecialchars($meal['strMealThumb']);
                $mealId = htmlspecialchars($meal['idMeal']);

                $response .= "
                    <div class='meal-item'>
                        <div class='meal-img'>
                            <img src='$mealThumb' alt='$mealName'>
                        </div>
                        <div class='meal-name'>
                            <h3>$mealName</h3>
                            <p><strong>Category:</strong> $category</p>
                            <p><strong>Area:</strong> $area</p>
                            <button class='btn get-recipe' data-id='$mealId'>Get Recipe</button>
                            " . (!empty($videoLink) ? "<p><strong>Watch Video:</strong> <a href='$videoLink' target='_blank'>Video Link</a></p>" : "") . "
                        </div>
                        <div class='recipe-details' id='recipe-$mealId' style='display:none;'></div>
                    </div>
                ";
            }
        } else {
            $response = "<p>Sorry, no meals found with this ingredient!</p>";
        }
    }
    echo $response;
    exit;
}

// Fetch recipe details by meal ID
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['mealId'])) {
    $mealId = filter_input(INPUT_GET, 'mealId', FILTER_SANITIZE_STRING);
    $apiUrl = "https://www.themealdb.com/api/json/v1/1/lookup.php?i=" . urlencode($mealId);
    $apiResponse = @file_get_contents($apiUrl);

    if ($apiResponse === false) {
        echo "<p>Unable to fetch recipe details. Please try again later.</p>";
        exit;
    }

    $mealData = json_decode($apiResponse, true);

    if (!empty($mealData['meals'])) {
        $meal = $mealData['meals'][0];
        $recipe = "<h3>" . htmlspecialchars($meal['strMeal']) . "</h3>
                   <p><strong>Instructions:</strong> " . htmlspecialchars($meal['strInstructions']) . "</p>
                   <p><strong>Ingredients:</strong></p>
                   <ul>";

        for ($i = 1; $i <= 20; $i++) {
            if (!empty($meal["strIngredient{$i}"])) {
                $recipe .= "<li>" . htmlspecialchars($meal["strIngredient{$i}"]) . " - " . htmlspecialchars($meal["strMeasure{$i}"]) . "</li>";
            }
        }

        $recipe .= "</ul>";
        echo $recipe;
    } else {
        echo "<p>No recipe details found.</p>";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Meals by Ingredient</title>
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

form {
    margin-bottom: 20px;
}

input[type="text"] {
    padding: 10px;
    width: 70%;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1em;
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


#meals {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}

.meal-item {
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 15px;
    width: 400px;
    text-align: center;
    box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
}

.meal-img img {
    max-width: 100%;
    border-radius: 10px;
}

.recipe-details {
    margin-top: 10px;
    text-align: left;
    padding: 15px;
    background-color: #f1f1f1;
    border-radius: 5px;
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
}

  </style>
</head>
<body>
  <div class="container">
    <h1>🍲 Search Meals by Ingredient</h1>
    <form method="POST" action="">
      <input type="hidden" name="action" value="getMealsByIngredient">
      <input type="text" name="ingredient" placeholder="Enter ingredient" required>
      <button type="submit" class="btn">Show Meals</button>
    </form>
    <div id="meals">
      <!-- PHP will dynamically populate meals here -->
    </div>
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
    });

    document.addEventListener('click', async function (e) {
        if (e.target.classList.contains('get-recipe')) {
            const mealId = e.target.getAttribute('data-id');
            const recipeDiv = document.getElementById(`recipe-${mealId}`);

            const response = await fetch(`?mealId=${mealId}`);
            const recipe = await response.text();

            recipeDiv.innerHTML = recipe;
            recipeDiv.style.display = 'block';
        }
    });
  </script>
</body>
</html>
