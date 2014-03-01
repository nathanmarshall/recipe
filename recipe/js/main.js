/*
	Main JS file for PHP template package

*/

//Set UserId
var userId = window.localStorage.getItem("RecipeAppUserId");

console.log(userId);

var url = 'http://recipe.nathanm.ca/functions.php';

 if(! userId){ //If user doesn't exist

  $.ajax({
      url: url,
      type: 'GET',
      dataType: 'jsonp',
      data: "create=true",
      success: function(data){
        console.log("userId: " + data);
        window.localStorage.setItem("RecipeAppUserId", data ); //Stores userid locally
      },
       error: function(data){
          console.log('AJAX failed');
        }
    });
  }

var userId = window.localStorage.getItem("RecipeAppUserId"); //gets user ID

function recipeListAll() {
    //Get Recipe List
    $.ajax({
      url: url,
      type: 'GET',
      dataType: 'jsonp',
      data: "userId=" + userId,
      success: function(data){
        $('.recipe-list-item').html('');
        $.each(data, function(i, data) {
          console.log('success:' + data.recipeName );
          $('.recipe-list-item').append('<li data-theme="c"><a class="recipe-link" href="#page1" data-link="'+ data.recipeId +'" data-transition="slide" data-recipe="">' + data.recipeName + '</a></li>');
        });
        $('.recipe-list-item').listview('refresh');
      },
       error: function(data){
          console.log('AJAX failed');
        }
    });
  }

//Search Recipes
$(document).on('keyup', '#searchinput1', function() {

  var search = $(this).val();

if (search !== '') {

    $.ajax({
      url: url,
      type: 'GET',
      dataType: 'jsonp',
      data: "userId=" + userId + "&search=" + search ,
      success: function(data){
        $('.recipe-list-item').html('');
        $.each(data, function(i, data) {
          console.log('success:' + data.recipeName );
          $('.recipe-list-item').append('<li data-theme="c"><a class="recipe-link" href="#page1" data-link="'+ data.recipeId +'" data-transition="slide" data-recipe="">' + data.recipeName + '</a></li>');
        });
        $('.recipe-list-item').listview('refresh');
      },
       error: function(data){
          console.log('AJAX failed');
        }
    });
  } else {
    recipeListAll();
  }
});

//View Recipe
$(document).on('click', '.recipe-link', function(){

  var recipeClicked = $(this).data('link');

  $.ajax({
    url: url,
    type: 'GET',
    dataType: 'jsonp',
    data: "recipeClicked=" + recipeClicked ,
    success: function(data) {
        $('.recipe-title').html(data.recipe[0].recipeName);
        $('.time').html(data.recipe[0].recipeTime);
        $('.category').html(data.recipe[0].categoryName);
        $('.serves').html(data.recipe[0].recipeServes);
        $('.recipe-list').html('');
        $('.ingredients-list').html('');
        $.each( data.steps , function(i, data){
          console.log(data);
          $('.recipe-list').append('<li>' + data.steps + '</li><br>');
        });
        $.each( data.ingredients, function(i, data){
          console.log(data);
          $('.ingredients-list').append('<span>'+data.ingredient+'</span><span>'+data.quantity+'</span>');
        });
    },
    error: function(data) {
      console.log('AJAX failed');
    }
  });
});

//Add Recipe
$(document).on('click', '.add-btn', function(){

  var addedRecipe = $('#textinput1').val();
  var time = $('#textinput2').val();
  var serves = $('#textinput3').val();
  var category = $('#textinput4').val();
  var ingredients = $('#textinput5').val();
  var instructions = $('#textinput6').val();
  console.log(addedRecipe);

  $.ajax({
    url: url,
    type: 'GET',
    dataType: 'jsonp',
    data: "addedRecipe=" + addedRecipe + "&time=" + time + "&serves=" + serves + "&category=" + category + "&userId=" + userId + "&ingredients=" + ingredients + "&instructions=" + instructions,
    success: function(data) {
        console.log('success:' + data );
        for (var i; i < 7; i++) {
          $('#textinput' + i).val('');
        }
        location.reload();
    },
    error: function(data) {
      console.log('AJAX failed');
    }
  });
});

recipeListAll();