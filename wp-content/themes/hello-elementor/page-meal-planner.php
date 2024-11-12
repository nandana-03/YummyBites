<?php
/**
 * Template Name: Meal Planner
 */

get_header(); // Include the header
?>

<div class="meal-planner">
    <h2>Create Your Meal Plan</h2>
    <form method="POST" action="">
        <label for="meal-plan-title">Meal Plan Title:</label>
        <input type="text" name="meal_plan_title" placeholder="Enter title" required />

        <div class="meal-plan-section">
            <?php
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            $meal_types = ['Breakfast', 'Lunch', 'Dinner'];

            foreach ($days as $day) {
                echo "<h3>$day</h3>";
                foreach ($meal_types as $meal_type) {
                    ?>
                    <label for="<?php echo strtolower($meal_type); ?>-<?php echo strtolower($day); ?>">
                        <?php echo $meal_type; ?>:
                    </label>
                    <input type="text" name="meal_plan[<?php echo strtolower($day); ?>][<?php echo strtolower($meal_type); ?>]" placeholder="Type in a recipe" required />
                    <?php
                }
            }
            ?>
        </div>

        <button type="submit" name="submit_meal_plan">Create Meal Plan</button>
    </form>
</div>

<?php
// Process form submission
if (isset($_POST['submit_meal_plan'])) {
    // Handle form data (save to database or post meta)
    $meal_plan_title = sanitize_text_field($_POST['meal_plan_title']);
    $meal_plan_data = $_POST['meal_plan'];

    $meal_plan = [
        'title' => $meal_plan_title,
        'data' => $meal_plan_data,
    ];

    // Save the meal plan using post meta or custom post type
    $meal_plan_id = wp_insert_post([
        'post_title'   => $meal_plan_title,
        'post_type'    => 'meal_plan',
        'post_status'  => 'publish',
        'meta_input'   => [
            'meal_plan_data' => serialize($meal_plan),
        ]
    ]);

    if ($meal_plan_id) {
        echo "<p>Meal Plan successfully created!</p>";
    }
}

get_footer(); // Include the footer
?>
