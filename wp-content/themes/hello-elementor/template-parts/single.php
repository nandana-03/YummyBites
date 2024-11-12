<?php
/**
 * The template for displaying singular post-types: posts, pages and user-defined custom post types.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

while ( have_posts() ) :
	the_post();
	?>
	<?php get_header(); ?>

<div class="recipe-container">
    <!-- Display the Recipe Title -->
    <h1><?php the_title(); ?></h1>

    <!-- Display the Recipe Description -->
    <div class="recipe-description">
        <?php the_content(); ?> <!-- This will display the main description/content first -->
    </div>

    <?php
    // Retrieve custom fields (meta fields) for ingredients, instructions, cooking time, and servings
    $ingredients = get_post_meta(get_the_ID(), 'recipe_ingredients', true);
    $instructions = get_post_meta(get_the_ID(), 'recipe_instructions', true);
    $cooking_time = get_post_meta(get_the_ID(), 'recipe_cooking_time', true);
    $servings = get_post_meta(get_the_ID(), 'recipe_servings', true);
    ?>

    <div class="recipe-details">
        <!-- Display Cooking Time -->
        <?php if ($cooking_time) : ?>
            <p><strong>Cooking Time:</strong> <?php echo esc_html($cooking_time); ?></p>
        <?php endif; ?>

        <!-- Display Servings -->
        <?php if ($servings) : ?>
            <p><strong>Servings:</strong> <?php echo esc_html($servings); ?></p>
        <?php endif; ?>

        <!-- Display Ingredients -->
        <?php if ($ingredients) : ?>
            <h3>Ingredients</h3>
            <p><?php echo nl2br(esc_html($ingredients)); ?></p> <!-- Preserves line breaks -->
        <?php endif; ?>

        <!-- Display Instructions -->
        <?php if ($instructions) : ?>
            <h3>Instructions</h3>
            <p><?php echo nl2br(esc_html($instructions)); ?></p> <!-- Preserves line breaks -->
        <?php endif; ?>
    </div>

</div>

<?php get_footer(); ?>

	<?php comments_template(); ?>

</main>

	<?php
endwhile;
