<?php
/*
Plugin Name: User Meal Planner
Description: A plugin that allows users to create, customize, save, and edit meal plans.
Version: 1.0
Author: Nandana S Krishnan
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Display Meal Plan Creation Form
function display_meal_plan_form() {
    $recipes = get_posts(['post_type' => 'recipe', 'posts_per_page' => -1]);

    ob_start(); // Start output buffering

    echo '<form method="POST" action="">';
    echo '<h3>Create Your Meal Plan</h3>';

    // Days of the week
    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

    foreach ($days as $day) {
        echo "<h4>$day</h4>";

        // Loop through meal types (breakfast, lunch, dinner)
        foreach (['breakfast', 'lunch', 'dinner'] as $meal_type) {
            echo "<label>$meal_type:</label>";
            echo "<select name='meal_plan[$day][$meal_type]'>";
            echo '<option value="">Select a recipe</option>';
            foreach ($recipes as $recipe) {
                echo '<option value="' . esc_attr($recipe->ID) . '">' . esc_html($recipe->post_title) . '</option>';
            }
            echo '</select><br>';
        }

        // Snacks option
        echo '<label>Snacks:</label>';
        echo '<input type="text" name="meal_plan[' . $day . '][snacks]" placeholder="Add any snacks"><br>';
    }

    echo '<input type="submit" name="save_meal_plan" value="Save Meal Plan">';
    echo '</form>';

    return ob_get_clean(); // Return buffered content
}

// Handle Meal Plan Form Submission
function save_user_meal_plan() {
    if (isset($_POST['save_meal_plan']) && isset($_POST['meal_plan'])) {
        $user_id = get_current_user_id();
        $meal_plan = $_POST['meal_plan'];

        // Save the meal plan data as user meta
        update_user_meta($user_id, 'user_meal_plan', $meal_plan);

        echo '<p>Your meal plan has been saved successfully!</p>';
    }
}
add_action('init', 'save_user_meal_plan');

// Display Saved Meal Plans on Profile Page
function display_saved_meal_plans() {
    $user_id = get_current_user_id();
    $meal_plan = get_user_meta($user_id, 'user_meal_plan', true);

    ob_start();

    echo '<h3>Your Saved Meal Plans</h3>';
    if ($meal_plan) {
        foreach ($meal_plan as $day => $meals) {
            echo "<h4>$day</h4>";
            echo '<p><strong>Breakfast:</strong> ' . (isset($meals['breakfast']) ? get_the_title($meals['breakfast']) : 'None') . '</p>';
            echo '<p><strong>Lunch:</strong> ' . (isset($meals['lunch']) ? get_the_title($meals['lunch']) : 'None') . '</p>';
            echo '<p><strong>Dinner:</strong> ' . (isset($meals['dinner']) ? get_the_title($meals['dinner']) : 'None') . '</p>';
            echo '<p><strong>Snacks:</strong> ' . esc_html($meals['snacks'] ?? 'None') . '</p>';
            echo '<hr>';
        }
    } else {
        echo '<p>No saved meal plans found.</p>';
    }

    return ob_get_clean();
}

// Shortcodes for Displaying Forms and Saved Plans
add_shortcode('meal_plan_form', 'display_meal_plan_form');
add_shortcode('saved_meal_plans', 'display_saved_meal_plans');

