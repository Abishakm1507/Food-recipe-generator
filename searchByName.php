<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $response = "";

    if ($action === 'searchMeals') {
        $mealName = htmlspecialchars($_POST['mealName']);
        $apiUrl = "https://www.themealdb.com/api/json/v1/1/search.php?s=" . urlencode($mealName);
        $apiResponse = file_get_contents($apiUrl);
        $mealsData = json_decode($apiResponse, true);

        if (!empty($mealsData['meals'])) {
            foreach ($mealsData['meals'] as $meal) {
                $response .= "
                    <div class='meal-item'>
                        <div class='meal-img'>
                            <img src='{$meal['strMealThumb']}' alt='{$meal['strMeal']}'>
                        </div>
                        <div class='meal-name'>
                            <h3>{$meal['strMeal']}</h3>
                            <p><strong>Category:</strong> {$meal['strCategory']}</p>
                            <p><strong>Area:</strong> {$meal['strArea']}</p>
                            <button class='btn toggle-recipe'>Get Recipe</button>
                            <p><strong>Watch Video:</strong> <a href='{$meal['strYoutube']}' target='_blank'>Video Link</a></p>
                        </div>
                        <div class='recipe-details' style='display: none;'>
                            <p><strong>Instructions:</strong> {$meal['strInstructions']}</p>
                            <p><strong>Ingredients:</strong></p>
                            <ul>";
                
                for ($i = 1; $i <= 20; $i++) {
                    if (!empty($meal["strIngredient{$i}"])) {
                        $ingredient = $meal["strIngredient{$i}"];
                        $measure = $meal["strMeasure{$i}"];
                        $response .= "<li>{$ingredient} - {$measure}</li>";
                    }
                }

                $response .= "
                            </ul>
                        </div>
                    </div>";
            }
        } else {
            $response = "<p>Sorry, no meals found with that name!</p>";
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
  <title>Search Meals by Name</title>
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
    input[type="text"] {
        width: 60%;
        padding: 15px;
        font-size: 1rem;
        border: 2px solid #e6e6e6;
        border-radius: 12px;
        background-color: #f9f9f9;
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
    .recipe-details {
        margin-top: 20px;
        text-align: left;
        padding: 15px;
        background-color: #f1f1f1;
        border-radius: 5px;
        display: none;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>🍽️ Search Meals by Name</h1>
    <form method="POST" action="">
      <input type="hidden" name="action" value="searchMeals">
      <input type="text" name="mealName" placeholder="Enter meal name" required>
      <button type="submit" class="btn">Search Meals</button>
    </form>
    <div id="meals"></div>
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

        // Attach toggle functionality to dynamically loaded buttons
        document.querySelectorAll('.toggle-recipe').forEach(button => {
            button.addEventListener('click', () => toggleRecipe(button));
        });
    });

    function toggleRecipe(button) {
        const recipeDetails = button.closest('.meal-item').querySelector('.recipe-details');
        if (recipeDetails.style.display === 'none' || recipeDetails.style.display === '') {
            recipeDetails.style.display = 'block';
            button.textContent = 'Hide Recipe';
        } else {
            recipeDetails.style.display = 'none';
            button.textContent = 'Get Recipe';
        }
    }
  </script>
</body>
</html>
