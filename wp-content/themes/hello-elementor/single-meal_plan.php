<?php get_header(); ?>

<div class="meal-plan-single">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <h1><?php the_title(); ?></h1> <!-- Title of the Meal Plan -->

        <!-- Display meal plan fields (breakfast, lunch, dinner) -->
        <div>
            <h4>Breakfast: <?php echo get_post_meta(get_the_ID(), 'breakfast', true); ?></h4>
        </div>
        <div>
            <h4>Lunch: <?php echo get_post_meta(get_the_ID(), 'lunch', true); ?></h4>
        </div>
        <div>
            <h4>Dinner: <?php echo get_post_meta(get_the_ID(), 'dinner', true); ?></h4>
        </div>

    <?php endwhile; endif; ?>
</div>

<?php get_footer(); ?>
 