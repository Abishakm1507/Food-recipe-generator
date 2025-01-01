<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $response = "";

    // Fetch meals by first letter of the meal's name
    if ($action === 'getMealsByFirstLetter') {
        $letter = htmlspecialchars($_POST['letter']);
        $apiUrl = "https://www.themealdb.com/api/json/v1/1/search.php?f=" . urlencode($letter);
        $apiResponse = file_get_contents($apiUrl);
        $mealsData = json_decode($apiResponse, true);

        if (!empty($mealsData['meals'])) {
            $response .= "<div class='meal-container'>";
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
                            <button class='btn get-recipe' data-id='{$meal['idMeal']}'>Get Recipe</button>
                            <div class='recipe-details' id='recipe-{$meal['idMeal']}' style='display: none;'></div>";
        
                if (!empty($meal['strYoutube'])) {
                    $response .= "<p><strong>Watch Video:</strong> <a href='{$meal['strYoutube']}' target='_blank'>Video Link</a></p>";
                }
        
                $response .= "</div></div>";
            }
            $response .= "</div>";
        }
         else {
            $response = "Sorry, no meals found starting with this letter!";
        }
    }

    echo $response;
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['mealId'])) {
    $mealId = $_GET['mealId'];
    $apiUrl = "https://www.themealdb.com/api/json/v1/1/lookup.php?i=" . urlencode($mealId);
    $apiResponse = file_get_contents($apiUrl);
    $mealData = json_decode($apiResponse, true);

    if (!empty($mealData['meals'])) {
        $meal = $mealData['meals'][0];
        $recipe = "<h3>{$meal['strMeal']}</h3>
                   <p><strong>Instructions:</strong> {$meal['strInstructions']}</p>
                   <p><strong>Ingredients:</strong></p>
                   <ul>";

        for ($i = 1; $i <= 20; $i++) {
            if (!empty($meal["strIngredient{$i}"])) {
                $recipe .= "<li>{$meal["strIngredient{$i}"]} - {$meal["strMeasure{$i}"]}</li>";
            }
        }

        $recipe .= "</ul>";

        echo $recipe;
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Meals by First Letter</title>
  <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background: linear-gradient(to right, #ffecd2, #fcb69f);
        color: #333;
    }

    .container {
    max-width: 1200px; /* Increase width */
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

    
    .meal-img img {
        max-width: 100%;
        border-radius: 5px;
    }

    .meal-name {
        margin-top: 10px;
        font-size: 1em;
        color: #333;
    }
    .meal-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.meal-item {
    flex: 1 0 calc(33.333% - 20px); /* Adjust width for 3 items per row */
    max-width: calc(33.333% - 20px);
    margin: 10px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    text-align: center;
    background-color: #f9f9f9;
    box-sizing: border-box;
}


    .btn {
        background-color: #ff6f61;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1em;
        margin-top: 20px;
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
        margin-top: 20px;
        text-align: left;
        padding: 15px;
        background-color: #f1f1f1;
        border-radius: 5px;
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
    }
    .meal-item {
    min-height: 300px; /* Adjust based on your content */
}

    @media (max-width: 768px) {
    .meal-item {
        flex: 1 0 calc(50% - 20px); /* Two items per row */
    }
}

@media (max-width: 480px) {
    .meal-item {
        flex: 1 0 calc(100% - 20px); /* One item per row */
    }
}

  </style>
</head>
<body>
  <div class="container">
    <h1>🔤 Search Meals by First Letter</h1>
    <form method="POST" action="">
      <input type="hidden" name="action" value="getMealsByFirstLetter">
      <select name="letter" required>
        <option value="" disabled selected>Select the First Letter</option>
        <option value="a">A</option>
        <option value="b">B</option>
        <option value="c">C</option>
        <option value="d">D</option>
        <option value="e">E</option>
        <option value="f">F</option>
        <option value="g">G</option>
        <option value="h">H</option>
        <option value="i">I</option>
        <option value="j">J</option>
        <option value="k">K</option>
        <option value="l">L</option>
        <option value="m">M</option>
        <option value="n">N</option>
        <option value="o">O</option>
        <option value="p">P</option>
        <option value="q">Q</option>
        <option value="r">R</option>
        <option value="s">S</option>
        <option value="t">T</option>
        <option value="u">U</option>
        <option value="v">V</option>
        <option value="w">W</option>
        <option value="x">X</option>
        <option value="y">Y</option>
        <option value="z">Z</option>
      </select>
      <button type="submit" class="btn">Search Meals</button>
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

        // Check if recipe is currently visible
        if (recipeDiv.style.display === 'block') {
            recipeDiv.style.display = 'none'; // Hide recipe
            e.target.textContent = 'Get Recipe'; // Update button text
        } else {
            // Check if recipe content is already fetched
            if (recipeDiv.innerHTML.trim() === '') {
                const response = await fetch(`?mealId=${mealId}`);
                const recipe = await response.text();
                recipeDiv.innerHTML = recipe; // Populate recipe details
            }
            recipeDiv.style.display = 'block'; // Show recipe
            e.target.textContent = 'Hide Recipe'; // Update button text
        }
    }
});

  </script>
</body>
</html>
