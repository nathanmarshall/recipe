<?php

require_once('includes/db.php');
require_once('includes/MySQL.php');

$db = new MySQL($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);

//# Get Recipe List 
if (isset($_GET['recipeId']) && !(isset($_POST['search']))) {

  $userId = $_GET['recipeId'];

  $sql = "SELECT recipeName, recipeId FROM recipes INNER JOIN users ON recipes.userId = users.userId WHERE recipes.userId = users.userId ";

  $stm = $db->dbConn->prepare($sql);
     
  $stm->execute(); 

  $results = $stm->fetchAll();

  echo $_GET['callback']. '('.json_encode($results).')';

}

//# Search Recipe List 
if (isset($_POST['search'])) {

  $userId = $_POST['recipeId'];
  $search = $_POST['search'];

  $sql = "SELECT recipeName, recipeId 
  FROM recipes 
  INNER JOIN users 
  ON recipes.userId = users.userId 
  WHERE recipes.userId = users.userId AND recipes.recipeName LIKE '%$search%' ";

  $stm = $db->dbConn->prepare($sql);
     
  $stm->execute(); 

  $results = $stm->fetchAll();

  echo json_encode($results);

}

//# Get Entire Recipe  
if (isset($_POST['recipeClicked'])) {

  $recipeClicked = $_POST['recipeClicked'];

  //Recipe
  $sql = "SELECT recipeName, recipes.recipeId, recipeTime, recipeServes, categories.categoryName FROM recipes 
  INNER JOIN  categories ON recipes.categoryId = categories.categoryId 
  WHERE recipes.recipeId = ? ";

  //Steps
  $sqlStep = "SELECT steps, stepNumber FROM steps WHERE recipeId = ? ORDER BY stepNumber ";

  //Ingredients
  $sqlIng = "SELECT ingredient, quantity FROM ingredients WHERE recipeId = ? ";

  function query($db, $sql, $filter) {
  
    $stm = $db->dbConn->prepare($sql);
     
    $stm->execute(array($filter)); 

    $results = $stm->fetchAll();

    return $results;
  }

  $recipe = query($db, $sql, $recipeClicked );
  $steps = query($db, $sqlStep, $recipeClicked );
  $ingredients = query($db, $sqlIng, $recipeClicked );

  $data = array();
  $data['recipe'] = $recipe;
  $data['steps'] = $steps;
  $data['ingredients'] = $ingredients;


  echo json_encode($data);

}

//# Add Recipe
if (isset($_POST['addedRecipe'])) {

  $addedRecipe = $_POST['addedRecipe'];
  $recipeTime = $_POST['time'];
  $recipeServes = $_POST['serves'];
  $categoryId = $_POST['category'];
  $userId = 1;
  $results = 'success';

  $sql = "INSERT INTO recipes (recipeName, userId, recipeTime, recipeServes, categoryId  ) VALUES (?,?,?,?,?)";

  $stm = $db->dbConn->prepare($sql);
     
  $stm->execute(array($addedRecipe, $userId, $recipeTime, $recipeServes, $categoryId )); 

  echo json_encode($results);

}

?>