<?php 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $response = "";

    // Fetch meals by area
    if ($action === 'getMealsByArea') {
        $area = htmlspecialchars($_POST['area']);
        $apiUrl = "https://www.themealdb.com/api/json/v1/1/filter.php?a=" . urlencode($area);
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
                $instructions = htmlspecialchars($mealDetails['strInstructions']);
                $videoLink = htmlspecialchars($mealDetails['strYoutube']);
                
                // Combine all ingredients
                $ingredients = "<ul>";
                for ($i = 1; $i <= 20; $i++) {
                    $ingredient = htmlspecialchars($mealDetails["strIngredient$i"] ?? "");
                    $measure = htmlspecialchars($mealDetails["strMeasure$i"] ?? "");
                    if (!empty($ingredient)) {
                        $ingredients .= "<li>$ingredient - $measure</li>";
                    }
                }
                $ingredients .= "</ul>";

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
                            <button class='btn get-recipe' data-mealid='{$mealDetails['idMeal']}'>Get Recipe</button>
                            <button class='btn hide-recipe' style='display: none;' data-mealid='{$mealDetails['idMeal']}'>Hide Recipe</button>
                        </div>
                        <div class='recipe-details' id='recipe-{$mealDetails['idMeal']}' style='display: none;'>
                            <p><strong>Instructions:</strong> $instructions</p>
                            <p><strong>Ingredients:</strong></p>
                            $ingredients
                        </div>
                    </div>
                ";
            }
        } else {
            $response = "<p>Sorry, no meals found for this area!</p>";
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
  <title>Filter Meals by Area</title>
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

    .meal-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .meal-item {
        flex: 1 0 calc(33.333% - 20px);
        max-width: calc(33.333% - 20px);
        margin: 10px;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        text-align: center;
        background-color: #f9f9f9;
        box-sizing: border-box;
        min-height: 400px; /* Ensure all cards align */
    }

    .meal-img img {
        max-width: 100%;
        border-radius: 5px;
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

    .recipe-details {
        margin-top: 15px;
        text-align: left;
        padding: 15px;
        background-color: #f1f1f1;
        border-radius: 5px;
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
        .meal-item {
            flex: 1 0 calc(50% - 20px);
        }
    }

    @media (max-width: 480px) {
        .meal-item {
            flex: 1 0 calc(100% - 20px);
        }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>🌍 Filter Meals by Area</h1>
    <form method="POST" action="">
      <input type="hidden" name="action" value="getMealsByArea">
      <select name="area" required>
        <option value="" disabled selected>Select an Area</option>
        <option value="American">American</option>
        <option value="British">British</option>
        <option value="Chinese">Chinese</option>
        <option value="French">French</option>
        <option value="Indian">Indian</option>
        <option value="Italian">Italian</option>
        <option value="Mexican">Mexican</option>
        <option value="Thai">Thai</option>
      </select>
      <button type="submit" class="btn">Show Meals</button>
    </form>
    <div id="meals" class="meal-container">
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

        attachToggleRecipeButtons();
    });

    function attachToggleRecipeButtons() {
        document.querySelectorAll('.get-recipe').forEach(button => {
            button.addEventListener('click', function () {
                const mealId = this.getAttribute('data-mealid');
                document.getElementById('recipe-' + mealId).style.display = 'block';
                this.style.display = 'none';
                document.querySelector(`.hide-recipe[data-mealid="${mealId}"]`).style.display = 'inline-block';
            });
        });

        document.querySelectorAll('.hide-recipe').forEach(button => {
            button.addEventListener('click', function () {
                const mealId = this.getAttribute('data-mealid');
                document.getElementById('recipe-' + mealId).style.display = 'none';
                this.style.display = 'none';
                document.querySelector(`.get-recipe[data-mealid="${mealId}"]`).style.display = 'inline-block';
            });
        });
    }
  </script>
</body>
</html>
