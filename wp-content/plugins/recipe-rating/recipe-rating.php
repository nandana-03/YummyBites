<?php
/*
Plugin Name: Recipe Rating System
Description: Adds a rating system to recipes, allowing users to rate recipes and view the average rating.
Version: 1.1
Author: Nandana S Krishnan
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Function to display the rating section
function display_recipe_rating() {
    if (get_post_type() !== 'recipe') return; // Only show on recipe posts

    // Get the current recipe ID
    $recipe_id = get_the_ID();

    // Get rating data
    $rating_count = get_post_meta($recipe_id, 'rating_count', true) ?: 0;
    $rating_sum = get_post_meta($recipe_id, 'rating_sum', true) ?: 0;
    $average_rating = $rating_count > 0 ? round($rating_sum / $rating_count, 1) : 0;

    echo '<div id="recipe-rating">';
    echo '<h3>Average Rating: ' . esc_html($average_rating) . ' / 5</h3>';
    echo '<div class="stars">';

    for ($i = 1; $i <= 5; $i++) {
        echo '<span class="star" data-rating="' . $i . '">&#9733;</span>';
    }
    echo '</div>';
    echo '<p>Click on a star to rate!</p>';
    echo '</div>';

    // JavaScript for handling star rating via AJAX
    ?>
    <style>
    #recipe-rating .stars {
        display: flex;
        gap: 5px;
    }

    #recipe-rating .star {
        font-size: 36px;
        cursor: pointer;
        color: goldenrod;
        transition: color 0.3s ease;
    }

    #recipe-rating .star:hover {
        color: orange;
    }
</style>

    <script>
document.querySelectorAll('.star').forEach(star => {
    star.addEventListener('click', function() {
        let rating = this.getAttribute('data-rating');

        // Highlight selected stars
        document.querySelectorAll('.star').forEach(s => s.classList.remove('selected'));
        for (let i = 1; i <= rating; i++) {
            document.querySelector(`.star[data-rating="${i}"]`).classList.add('selected');
        }

        // Send rating to server via AJAX
        fetch("<?php echo admin_url('admin-ajax.php'); ?>", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: new URLSearchParams({
                action: "submit_recipe_rating",
                recipe_id: "<?php echo $recipe_id; ?>",
                rating: rating
            })
        })
        .then(response => response.text())
        .then(data => {
            alert("Thank you for your rating!");
            location.reload(); // Reload to update average rating
        });
    });

    // Change color on hover to give feedback
    star.addEventListener('mouseover', function() {
        let rating = this.getAttribute('data-rating');
        document.querySelectorAll('.star').forEach(s => s.classList.remove('selected'));
        for (let i = 1; i <= rating; i++) {
            document.querySelector(`.star[data-rating="${i}"]`).classList.add('selected');
        }
    });

    // Remove highlight on mouse out
    star.addEventListener('mouseout', function() {
        document.querySelectorAll('.star').forEach(s => s.classList.remove('selected'));
    });
});
</script>

    <?php
}
add_action('wp_footer', 'display_recipe_rating');

// Function to handle AJAX rating submission
function submit_recipe_rating() {
    if (isset($_POST['recipe_id']) && isset($_POST['rating'])) {
        $recipe_id = intval($_POST['recipe_id']);
        $rating = intval($_POST['rating']);

        $rating_count = get_post_meta($recipe_id, 'rating_count', true) ?: 0;
        $rating_sum = get_post_meta($recipe_id, 'rating_sum', true) ?: 0;

        // Update the rating data
        update_post_meta($recipe_id, 'rating_count', ++$rating_count);
        update_post_meta($recipe_id, 'rating_sum', $rating_sum + $rating);

        echo "Rating submitted!";
    }
    wp_die();
}
add_action('wp_ajax_submit_recipe_rating', 'submit_recipe_rating');
add_action('wp_ajax_nopriv_submit_recipe_rating', 'submit_recipe_rating');
