<?php
/*
Plugin Name: Recipe Shopping List
Description: Allows users to add recipe ingredients to a shopping list and view or manage them on a dedicated shopping list page.
Version: 1.4
Author: Nandana S Krishnan
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Function to add "Add to Shopping List" button and JavaScript on each recipe page
function add_shopping_list_button() {
    if (get_post_type() === 'recipe') { // Only show on recipe pages
        ?>
        <button onclick="addToShoppingList()">Add Ingredients to Shopping List</button>

        <script>
        function addToShoppingList() {
            console.log("Button clicked!"); // Check if function is being called
    // Fetch ingredients from PHP
    var ingredients = <?php echo json_encode(get_post_meta(get_the_ID(), 'ingredients', true)); ?>;
    console.log("Ingredients from PHP:", ingredients); // Check ingredients data

    // Fetch ingredients from PHP (assuming they’re stored as meta field)
    var ingredients = <?php echo json_encode(get_post_meta(get_the_ID(), 'ingredients', true)); ?>;

    // Get existing shopping list from localStorage
    let shoppingList = JSON.parse(localStorage.getItem('shoppingList')) || {};
    

    // Add ingredients to shopping list, consolidating quantities
    for (let ingredient in ingredients) {
    if (shoppingList[ingredient]) {
        shoppingList[ingredient] = shoppingList[ingredient] + ', ' + ingredients[ingredient]; // Consolidate quantity if needed
    } else {
        shoppingList[ingredient] = ingredients[ingredient];
    }
}


    // Save updated shopping list back to localStorage
    localStorage.setItem('shoppingList', JSON.stringify(shoppingList));

    // Redirect to the shopping list page after confirming
    window.location.href = "<?php echo site_url('/shopping-list'); ?>";
}

        </script>
        <?php
    }
}
add_action('wp_footer', 'add_shopping_list_button');

// Function to display the shopping list on a dedicated page
function display_shopping_list_page(): bool|string {
    ob_start();
    ?>

    <h2>Your Shopping List</h2>
    <ul id="shopping-list"></ul>

    <button onclick="clearShoppingList()">Clear List</button>
    <button onclick="downloadShoppingList()">Download List</button>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Retrieve shopping list from localStorage
        let shoppingList = JSON.parse(localStorage.getItem('shoppingList')) || {};
        let shoppingListContainer = document.getElementById('shopping-list');

        for (let ingredient in shoppingList) {
            let listItem = document.createElement('li');
            listItem.textContent = `${ingredient}: ${shoppingList[ingredient]}`;
            shoppingListContainer.appendChild(listItem);
        }
    });

    // Clear the shopping list
    function clearShoppingList() {
        localStorage.removeItem('shoppingList');
        location.reload();
    }

    // Download the shopping list as a text file
    function downloadShoppingList() {
        let shoppingList = JSON.parse(localStorage.getItem('shoppingList')) || {};
        let listText = "Shopping List:\n";
        for (let ingredient in shoppingList) {
            listText += `${ingredient}: ${shoppingList[ingredient]}\n`;
        }

        let blob = new Blob([listText], { type: "text/plain" });
        let link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = "shopping_list.txt";
        link.click();
    }
    </script>

    <?php
    return ob_get_clean();
}
add_shortcode('shopping_list_page', 'display_shopping_list_page');




