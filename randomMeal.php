<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $response = "";

    // Fetch random meal
    if ($action === 'getRandomMeal') {
        $apiUrl = "https://www.themealdb.com/api/json/v1/1/random.php";
        $apiResponse = file_get_contents($apiUrl);
        $mealData = json_decode($apiResponse, true);

        if (!empty($mealData['meals'])) {
            $meal = $mealData['meals'][0];
            $response .= "
                <div class='meal-item'>
                    <div class='meal-img'>
                        <img src='{$meal['strMealThumb']}' alt='{$meal['strMeal']}'>
                    </div>
                    <div class='meal-name'>
                        <h3>{$meal['strMeal']}</h3>
                        <p><strong>Category:</strong> {$meal['strCategory']}</p>
                        <p><strong>Area:</strong> {$meal['strArea']}</p>
                        <p><strong>Instructions:</strong> {$meal['strInstructions']}</p>
                    </div>
                </div>
            ";
        } else {
            $response = "Sorry, no random meal found!";
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
  <title>Random Meal</title>
  <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background: linear-gradient(to right, #ffecd2, #fcb69f);
        color: #333;
    }

    .container {
        max-width: 800px;
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
        width: 100%;
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
        margin-top: 20px;
    }

    .btn:hover {
        background-color: #e65a50;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>🍽️ Get a Random Meal</h1>
    <form method="POST" action="">
      <input type="hidden" name="action" value="getRandomMeal">
      <button type="submit" class="btn">Get Random Meal</button>
    </form>
    <div id="meal">
      <!-- PHP will dynamically populate the random meal here -->
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
        document.getElementById('meal').innerHTML = result;
    });
  </script>
</body>
</html>