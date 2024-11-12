<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_VERSION', '3.1.1' );

if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}

if ( ! function_exists( 'hello_elementor_setup' ) ) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function hello_elementor_setup() {
		if ( is_admin() ) {
			hello_maybe_update_theme_version_in_db();
		}

		if ( apply_filters( 'hello_elementor_register_menus', true ) ) {
			register_nav_menus( [ 'menu-1' => esc_html__( 'Header', 'hello-elementor' ) ] );
			register_nav_menus( [ 'menu-2' => esc_html__( 'Footer', 'hello-elementor' ) ] );
		}

		if ( apply_filters( 'hello_elementor_post_type_support', true ) ) {
			add_post_type_support( 'page', 'excerpt' );
		}

		if ( apply_filters( 'hello_elementor_add_theme_support', true ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support(
				'html5',
				[
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
					'script',
					'style',
				]
			);
			add_theme_support(
				'custom-logo',
				[
					'height'      => 100,
					'width'       => 350,
					'flex-height' => true,
					'flex-width'  => true,
				]
			);

			/*
			 * Editor Style.
			 */
			add_editor_style( 'classic-editor.css' );

			/*
			 * Gutenberg wide images.
			 */
			add_theme_support( 'align-wide' );

			/*
			 * WooCommerce.
			 */
			if ( apply_filters( 'hello_elementor_add_woocommerce_support', true ) ) {
				// WooCommerce in general.
				add_theme_support( 'woocommerce' );
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support( 'wc-product-gallery-zoom' );
				// lightbox.
				add_theme_support( 'wc-product-gallery-lightbox' );
				// swipe.
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'hello_elementor_setup' );

function hello_maybe_update_theme_version_in_db() {
	$theme_version_option_name = 'hello_theme_version';
	// The theme version saved in the database.
	$hello_theme_db_version = get_option( $theme_version_option_name );

	// If the 'hello_theme_version' option does not exist in the DB, or the version needs to be updated, do the update.
	if ( ! $hello_theme_db_version || version_compare( $hello_theme_db_version, HELLO_ELEMENTOR_VERSION, '<' ) ) {
		update_option( $theme_version_option_name, HELLO_ELEMENTOR_VERSION );
	}
}

if ( ! function_exists( 'hello_elementor_display_header_footer' ) ) {
	/**
	 * Check whether to display header footer.
	 *
	 * @return bool
	 */
	function hello_elementor_display_header_footer() {
		$hello_elementor_header_footer = true;

		return apply_filters( 'hello_elementor_header_footer', $hello_elementor_header_footer );
	}
}

if ( ! function_exists( 'hello_elementor_scripts_styles' ) ) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function hello_elementor_scripts_styles() {
		$min_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		if ( apply_filters( 'hello_elementor_enqueue_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor',
				get_template_directory_uri() . '/style' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( apply_filters( 'hello_elementor_enqueue_theme_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor-theme-style',
				get_template_directory_uri() . '/theme' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( hello_elementor_display_header_footer() ) {
			wp_enqueue_style(
				'hello-elementor-header-footer',
				get_template_directory_uri() . '/header-footer' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_scripts_styles' );

if ( ! function_exists( 'hello_elementor_register_elementor_locations' ) ) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function hello_elementor_register_elementor_locations( $elementor_theme_manager ) {
		if ( apply_filters( 'hello_elementor_register_elementor_locations', true ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'hello_elementor_register_elementor_locations' );

if ( ! function_exists( 'hello_elementor_content_width' ) ) {
	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	function hello_elementor_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'hello_elementor_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'hello_elementor_content_width', 0 );

if ( ! function_exists( 'hello_elementor_add_description_meta_tag' ) ) {
	/**
	 * Add description meta tag with excerpt text.
	 *
	 * @return void
	 */
	function hello_elementor_add_description_meta_tag() {
		if ( ! apply_filters( 'hello_elementor_description_meta_tag', true ) ) {
			return;
		}

		if ( ! is_singular() ) {
			return;
		}

		$post = get_queried_object();
		if ( empty( $post->post_excerpt ) ) {
			return;
		}

		echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $post->post_excerpt ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'hello_elementor_add_description_meta_tag' );

// Admin notice
if ( is_admin() ) {
	require get_template_directory() . '/includes/admin-functions.php';
}

// Settings page
require get_template_directory() . '/includes/settings-functions.php';

// Header & footer styling option, inside Elementor
require get_template_directory() . '/includes/elementor-functions.php';

if ( ! function_exists( 'hello_elementor_customizer' ) ) {
	// Customizer controls
	function hello_elementor_customizer() {
		if ( ! is_customize_preview() ) {
			return;
		}

		if ( ! hello_elementor_display_header_footer() ) {
			return;
		}

		require get_template_directory() . '/includes/customizer-functions.php';
	}
}
add_action( 'init', 'hello_elementor_customizer' );

if ( ! function_exists( 'hello_elementor_check_hide_title' ) ) {
	/**
	 * Check whether to display the page title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function hello_elementor_check_hide_title( $val ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter( 'hello_elementor_page_title', 'hello_elementor_check_hide_title' );

/**
 * BC:
 * In v2.7.0 the theme removed the `hello_elementor_body_open()` from `header.php` replacing it with `wp_body_open()`.
 * The following code prevents fatal errors in child themes that still use this function.
 */
if ( ! function_exists( 'hello_elementor_body_open' ) ) {
	function hello_elementor_body_open() {
		wp_body_open();
	}
}

function display_recipes() {
    ob_start();
    $args = array(
        'post_type' => 'recipe', // or your custom post type for recipes
        'posts_per_page' => 10,
		'capability_type' => 'post',
		'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
		'public' => true,'show_ui' => true
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            ?>
            <div class="recipe-item">
                <h2><?php the_title(); ?></h2>
                <div><?php the_excerpt(); ?></div>
                <a href="<?php the_permalink(); ?>">Read More</a>
            </div>
            <?php
        }
    } else {
        echo '<p>No recipes found.</p>';
    }
    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode('show_recipes', 'display_recipes');

function custom_search_shortcode() {
    ob_start();
    ?>
    <form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
        <label>
            <input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search …', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />
        </label>
        <input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>" />
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('custom_search', 'custom_search_shortcode');

// Create a shortcode for the login form
function custom_login_form() {
    if (is_user_logged_in()) {
        return '<p>You are already logged in. <a href="/profile">Go to your profile</a>.</p>';
    } else {
        ob_start();
        wp_login_form(); // Display WordPress login form
        return ob_get_clean();
    }
}
add_shortcode('custom_login_form', 'custom_login_form');

// Create a shortcode for the registration form
function custom_registration_form() {
    if (is_user_logged_in()) {
        return '<p>You are already registered and logged in.</p>';
    } else {
        ob_start();
        ?>
        <form action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post">
            <p>
                <label for="username">Username</label>
                <input type="text" name="username" required>
            </p>
            <p>
                <label for="email">Email</label>
                <input type="email" name="email" required>
            </p>
            <p>
                <label for="password">Password</label>
                <input type="password" name="password" required>
            </p>
            <p>
                <input type="submit" name="submit" value="Register">
            </p>
        </form>
        <?php
        return ob_get_clean();
    }
}
add_shortcode('custom_registration_form', 'custom_registration_form');

function custom_registration() {
    if (isset($_POST['submit'])) {
        $username = sanitize_text_field($_POST['username']);
        $email = sanitize_email($_POST['email']);
        $password = esc_attr($_POST['password']);

        // Error handling
        if (username_exists($username) || email_exists($email)) {
            echo '<p style="color:red;">Username or email already exists!</p>';
        } else {
            // Create user
            $user_id = wp_create_user($username, $password, $email);
            if (!is_wp_error($user_id)) {
                echo '<p style="color:green;">Registration complete. Please <a href="/login">log in</a>.</p>';
            } else {
                echo '<p style="color:red;">An error occurred: ' . $user_id->get_error_message() . '</p>';
            }
        }
    }
}
add_action('init', 'custom_registration');

function custom_login_redirect($redirect_to, $request, $user) {
    // Check if the user has the 'subscriber' role
    if (isset($user->roles) && is_array($user->roles)) {
        if (in_array('subscriber', $user->roles)) {
            return home_url('/profile/'); // Redirect to the profile page
        }
    }
    return $redirect_to; // Default redirect
}
add_filter('login_redirect', 'custom_login_redirect', 10, 3);

// Display user's profile info
function display_user_info() {
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $output = '<h2>Profile Information</h2>';
        $output .= '<p><strong>Name:</strong> ' . esc_html($current_user->display_name) . '</p>';
        $output .= '<p><strong>Email:</strong> ' . esc_html($current_user->user_email) . '</p>';
        
        // Assuming you've added a custom field for phone number in user meta
        $phone = get_user_meta($current_user->ID, 'phone_number', true);
        if ($phone) {
            $output .= '<p><strong>Phone:</strong> ' . esc_html($phone) . '</p>';
        }

        return $output;
    } else {
        return '<p>You need to log in to view your profile.</p>';
    }
}
add_shortcode('user_info', 'display_user_info');



// Function to display user profile information and edit form
function display_user_profile_edit() {
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        
        // Process profile update
        if (isset($_POST['update_profile'])) {
            // Sanitize and update user information
            $username = sanitize_text_field($_POST['username']);
            $email = sanitize_email($_POST['email']);
            $phone = sanitize_text_field($_POST['phone']); // Phone number field

            // Update user info
            wp_update_user(array(
                'ID' => $current_user->ID,
                'user_login' => $username,
                'user_email' => $email,
            ));

            // Update user meta for additional fields
            update_user_meta($current_user->ID, 'phone_number', $phone);

            echo '<p style="color:green;">Profile updated successfully!</p>';
        }

        // Display the profile info
        ob_start();
        ?>
        
        <div id="profile-info">
            <p><strong>Username:</strong> <span id="username-display"><?php echo esc_html($current_user->user_login); ?></span></p>
            <p><strong>Email:</strong> <span id="email-display"><?php echo esc_html($current_user->user_email); ?></span></p>
            <p><strong>Phone:</strong> <span id="phone-display"><?php echo esc_html(get_user_meta($current_user->ID, 'phone_number', true)); ?></span></p>
            <button id="edit-profile-btn" class="edit-icon">Edit Profile</button>
        </div>

        <div id="edit-form" style="display:none;">
            <h3>Edit Profile</h3>
            <form method="post" action="">
                <p>
                    <label for="username">Username:</label>
                    <input type="text" name="username" value="<?php echo esc_attr($current_user->user_login); ?>" required>
                </p>
                <p>
                    <label for="email">Email:</label>
                    <input type="email" name="email" value="<?php echo esc_attr($current_user->user_email); ?>" required>
                </p>
                <p>
                    <label for="phone">Phone:</label>
                    <input type="text" name="phone" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'phone_number', true)); ?>">
                </p>
                <p>
                    <input type="submit" name="update_profile" value="Update Profile">
                </p>
            </form>
        </div>

        <script>
            // Toggle edit form visibility
            document.getElementById('edit-profile-btn').addEventListener('click', function() {
                document.getElementById('profile-info').style.display = 'none'; // Hide profile info
                document.getElementById('edit-form').style.display = 'block'; // Show edit form
            });
        </script>
        <?php
        return ob_get_clean();
    } else {
        return '<p>You need to log in to view your profile.</p>';
    }
}
add_shortcode('user_profile_edit', 'display_user_profile_edit');

function register_recipes_post_type() {
    register_post_type('recipe', // 'recipe' is the custom post type slug
        array(
            'labels' => array(
                'name' => __('Recipes'),
                'singular_name' => __('Recipe')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail','comments'),
            'rewrite' => array('slug' => 'recipes'),
        )
    );
}
add_action('init', 'register_recipes_post_type');


// Display user's uploaded recipes
function display_user_recipes() {
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $args = array(
            'post_type' => 'recipe', // Change to your custom post type for recipes
            'posts_per_page' => -1,
            'author' => $current_user->ID,
        );
        $recipes = new WP_Query($args);
        
        $output = '<h3>Your Uploaded Recipes</h3>';
        if ($recipes->have_posts()) {
            $output .= '<ul>';
            while ($recipes->have_posts()) {
                $recipes->the_post();
                $output .= '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
            }
            $output .= '</ul>';
        } else {
            $output .= '<p>No recipes found.</p>';
        }
        wp_reset_postdata();

        return $output;
    } else {
        return '<p>You need to log in to view your recipes.</p>';
    }
}
add_shortcode('user_recipes', 'display_user_recipes');

// Display user's saved recipes
function display_user_saved_recipes() {
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        
        // Assuming you have a way to track saved recipes
        $saved_recipes = get_user_meta($current_user->ID, 'saved_recipes', true); // Expecting an array of recipe IDs
        $output = '<h3>Your Saved Recipes</h3>';

        if ($saved_recipes && is_array($saved_recipes)) {
            $output .= '<ul>';
            foreach ($saved_recipes as $recipe_id) {
                $output .= '<li><a href="' . get_permalink($recipe_id) . '">' . get_the_title($recipe_id) . '</a></li>';
            }
            $output .= '</ul>';
        } else {
            $output .= '<p>No saved recipes found.</p>';
        }

        return $output;
    } else {
        return '<p>You need to log in to view your saved recipes.</p>';
    }
}
add_shortcode('user_saved_recipes', 'display_user_saved_recipes');

// Function to display the user profile navigation menu
function display_user_profile_menu() {
    if (is_user_logged_in()) {
        ob_start();
        ?>
        <nav>
            <ul>
                <li><a href="<?php echo esc_url(home_url('/profile/')); ?>">Profile</a></li><br>
                <li><a href="<?php echo esc_url(home_url('/recipe/')); ?>">Recipes</a></li><br>
                <li><a href="<?php echo esc_url(home_url('/recipe-submission/')); ?>">Recipe Submission</a></li><br>
                <li><a href="<?php echo esc_url(home_url('/meal-plan/')); ?>">Meal Planner</a></li><br>
				<li><a href="<?php echo wp_logout_url(home_url('/login/')); ?>">Logout</a></li>
            </ul>
        </nav>
       <style>
		/* Style for the profile menu */
nav {
    background-color: #cd3768;; /* Light background */
    padding: 15px; /* Padding around the menu */
    border-radius: 5px; /* Rounded corners */
}

nav ul {
    list-style: none; /* Remove bullet points */
    padding: 0; /* No padding */
    margin: 0; /* No margin */
}

nav ul li {
    margin-bottom: 10px; /* Space between menu items */
}

nav ul li a {
    display: block; /* Make links block-level elements to occupy the full width */
    text-decoration: none; /* No underlines */
    color: white; /* Link color */
    padding: 10px; /* Add padding for clickable area */
    border-radius: 3px; /* Rounded corners for links */
}

nav ul li a:hover {
    background-color: #e0e0e0; /* Change background color on hover */
    color: #005177; /* Darker color on hover */
}

		</style>
        <?php
        return ob_get_clean();
    }
}
add_shortcode('user_profile_menu', 'display_user_profile_menu');

// Change the title for the Recipes archive page
function custom_archive_title($title) {
    if (is_post_type_archive('recipe')) { // Replace 'recipe' with the slug of your custom post type
        $title = 'Recipes'; // Customize the title here
    }
    return $title;
}
add_filter('get_the_archive_title', 'custom_archive_title');

// Recipe Submission Form Shortcode
function recipe_submission_form() {
    if (!is_user_logged_in()) {
        return '<p>You must be logged in to submit a recipe.</p>';
    }

    // Check if the form is submitted
    if (isset($_POST['submit_recipe'])) {
        // Include necessary WordPress files
        require_once(ABSPATH . 'wp-admin/includes/file.php'); // Include the file upload functions

        // Sanitize and collect the form data
        $recipe_title = sanitize_text_field($_POST['recipe_title']);
        $recipe_content = sanitize_textarea_field($_POST['recipe_content']);
        $recipe_ingredients = sanitize_textarea_field($_POST['recipe_ingredients']);
        $recipe_instructions = sanitize_textarea_field($_POST['recipe_instructions']);
        $recipe_cooking_time = sanitize_text_field($_POST['recipe_cooking_time']);
        $recipe_servings = sanitize_text_field($_POST['recipe_servings']);
        $recipe_category = sanitize_text_field($_POST['recipe_category']);
        $recipe_tags = sanitize_text_field($_POST['recipe_tags']); // Comma-separated tags

        // Handle image upload
        $recipe_image = '';
        if (!empty($_FILES['recipe_image']['name'])) {
            $upload = wp_handle_upload($_FILES['recipe_image'], array('test_form' => false));
            if (isset($upload['url'])) {
                $recipe_image = $upload['url'];
            }
        }

        // Create a new post (recipe post type)
        $new_recipe = array(
            'post_title'   => $recipe_title,
            'post_content' => $recipe_content,
            'post_status'  => 'pending', // Submitted recipes will be pending review by admin
            'post_type'    => 'recipe', // Ensure 'recipe' is the custom post type slug
            'post_author'  => get_current_user_id(),
            'tax_input'    => array(
                'recipe_category' => array($recipe_category), // Categorize the recipe
                'recipe_tag'      => explode(',', $recipe_tags), // Add tags (comma-separated)
            ),
        );

        // Insert the post into the database
        $recipe_id = wp_insert_post($new_recipe);

        // Add meta fields (custom fields)
        if ($recipe_id) {
            update_post_meta($recipe_id, 'recipe_ingredients', $recipe_ingredients);
            update_post_meta($recipe_id, 'recipe_instructions', $recipe_instructions);
            update_post_meta($recipe_id, 'recipe_cooking_time', $recipe_cooking_time);
            update_post_meta($recipe_id, 'recipe_servings', $recipe_servings);

            // Attach the image to the recipe if uploaded
            if ($recipe_image) {
                set_post_thumbnail($recipe_id, attachment_url_to_postid($recipe_image));
            }

            echo '<p style="color:green;">Recipe submitted successfully! It is pending review.</p>';
        } else {
            echo '<p style="color:red;">There was a problem submitting your recipe. Please try again.</p>';
        }
    }

    // Display the submission form
    ob_start(); ?>
    <h3>Submit Your Recipe</h3>
    <form action="" method="POST" enctype="multipart/form-data">
        <p>
            <label for="recipe_title">Recipe Title</label><br>
            <input type="text" name="recipe_title" id="recipe_title" required>
        </p>
        <p>
            <label for="recipe_content">Recipe Description</label><br>
            <textarea name="recipe_content" id="recipe_content" rows="4" required></textarea>
        </p>
        <p>
            <label for="recipe_ingredients">Ingredients</label><br>
            <textarea name="recipe_ingredients" id="recipe_ingredients" rows="4" required></textarea>
        </p>
        <p>
            <label for="recipe_instructions">Instructions</label><br>
            <textarea name="recipe_instructions" id="recipe_instructions" rows="6" required></textarea>
        </p>
        <p>
            <label for="recipe_cooking_time">Cooking Time (e.g., 45 mins)</label><br>
            <input type="text" name="recipe_cooking_time" id="recipe_cooking_time" required>
        </p>
        <p>
            <label for="recipe_servings">Servings (e.g., 4 servings)</label><br>
            <input type="text" name="recipe_servings" id="recipe_servings" required>
        </p>
        <p>
            <label for="recipe_category">Recipe Category</label><br>
            <select name="recipe_category" id="recipe_category" required>
                <option value="breakfast">Breakfast</option>
                <option value="lunch">Lunch</option>
                <option value="dinner">Dinner</option>
                <option value="dessert">Dessert</option>
            </select>
        </p>
        <p>
            <label for="recipe_tags">Tags (comma-separated, e.g., vegan, gluten-free, quick meals)</label><br>
            <input type="text" name="recipe_tags" id="recipe_tags">
        </p>
        <p>
            <label for="recipe_image">Recipe Image</label><br>
            <input type="file" name="recipe_image" id="recipe_image">
        </p>
        <p>
            <input type="submit" name="submit_recipe" value="Submit Recipe">
        </p>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('recipe_submission_form', 'recipe_submission_form');

function create_recipe_post_type() {
    $labels = array(
        'name' => __( 'Recipes' ),
        'singular_name' => __( 'Recipe' )
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields','comments' ),
        'rewrite' => array( 'slug' => 'recipes' ),
        'show_in_rest' => true,  
    );

    register_post_type( 'recipe', $args );
}
add_action( 'init', 'create_recipe_post_type' );

function recipe_search_form() {
    ob_start(); ?>
    <form id="recipe-search-form" method="GET" action="">
        <input type="text" name="search" placeholder="Search recipes..." />
        <select name="ingredients">
            <option value="">Select Ingredients</option>
            <option value="chicken">Chicken</option>
            <option value="vegetarian">Vegetarian</option>
            <!-- Add more ingredients -->
        </select>
        <select name="dietary_preferences">
            <option value="">Dietary Preferences</option>
            <option value="vegan">Vegan</option>
            <option value="gluten-free">Gluten-Free</option>
            <!-- Add more preferences -->
        </select>
        <select name="cuisine_type">
            <option value="">Cuisine Type</option>
            <option value="italian">Italian</option>
            <option value="indian">Indian</option>
            <!-- Add more cuisines -->
        </select>
        <input type="number" name="preparation_time" placeholder="Prep Time (mins)" />
        <button type="submit">Search</button>
    </form>
<?php return ob_get_clean();
}

add_shortcode('recipe_search_form', 'recipe_search_form');

// Modify the main query based on the recipe search form inputs
function custom_recipe_search_query($query) {
    // Ensure we are modifying only the main query and only on the front-end
    if (!is_admin() && $query->is_main_query() && !empty($_GET['search'])) {
        
        // Search by post title (recipe name)
        $query->set('s', sanitize_text_field($_GET['search']));

        // Filter by ingredients (if selected)
        if (!empty($_GET['ingredients'])) {
            $meta_query[] = array(
                'key'     => 'ingredients',  // Assuming you store ingredients in post meta
                'value'   => sanitize_text_field($_GET['ingredients']),
                'compare' => 'LIKE',
            );
        }

        // Filter by dietary preferences
        if (!empty($_GET['dietary_preferences'])) {
            $meta_query[] = array(
                'key'     => 'dietary_preferences', // Assuming you store dietary preferences in post meta
                'value'   => sanitize_text_field($_GET['dietary_preferences']),
                'compare' => 'LIKE',
            );
        }

        // Filter by cuisine type
        if (!empty($_GET['cuisine_type'])) {
            $meta_query[] = array(
                'key'     => 'cuisine_type',  // Assuming cuisine type is stored in post meta
                'value'   => sanitize_text_field($_GET['cuisine_type']),
                'compare' => 'LIKE',
            );
        }

        // Filter by preparation time (if provided)
        if (!empty($_GET['preparation_time'])) {
            $meta_query[] = array(
                'key'     => 'preparation_time', // Assuming prep time is stored in post meta
                'value'   => intval($_GET['preparation_time']),
                'compare' => '<=',  // Find recipes with prep time less than or equal to the entered time
                'type'    => 'NUMERIC'
            );
        }

        // If meta_query is set, add it to the query
        if (!empty($meta_query)) {
            $query->set('meta_query', $meta_query);
        }

        // Limit the search to only 'recipe' post type
        $query->set('post_type', 'recipe');
    }
}
add_action('pre_get_posts', 'custom_recipe_search_query');

function custom_recipe_search_filter($query) {
    // Ensure this only affects the front-end and main query
    if (!is_admin() && $query->is_search() && $query->is_main_query()) {
        // Limit search to 'recipe' post type only
        $query->set('post_type', 'recipe');
    }
}
add_action('pre_get_posts', 'custom_recipe_search_filter');

