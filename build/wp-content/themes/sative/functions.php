<?php
/**
 * WP Bootstrap Starter functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WP_Bootstrap_Starter
 */

require_once get_template_directory() . '/inc/jshrink.php';
require_once get_template_directory() . '/inc/htmlcompress.php';

function wp_html_compression_finish($html)
{
    return new WP_HTML_Compression($html);
}

function wp_html_compression_start()
{
    ob_start('wp_html_compression_finish');
}
add_action('get_header', 'wp_html_compression_start');

if ( ! function_exists( 'sative_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function sative_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on WP Bootstrap Starter, use a find and replace
	 * to change 'sative' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'sative', get_template_directory() . '/languages' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'sative' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'comment-form',
		'comment-list',
		'caption',
	) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

    function sative_add_editor_styles() {
        add_editor_style( 'custom-editor-style.css' );
    }
    add_action( 'admin_init', 'sative_add_editor_styles' );
}
endif;
add_action( 'after_setup_theme', 'sative_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function sative_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'sative_content_width', 1170 );
}
add_action( 'after_setup_theme', 'sative_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function sative_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'sative' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here.', 'sative' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer 1', 'sative' ),
        'id'            => 'footer-1',
        'description'   => esc_html__( 'Add widgets here.', 'sative' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer 2', 'sative' ),
        'id'            => 'footer-2',
        'description'   => esc_html__( 'Add widgets here.', 'sative' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer 3', 'sative' ),
        'id'            => 'footer-3',
        'description'   => esc_html__( 'Add widgets here.', 'sative' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'sative_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function sative_scripts() {
	wp_enqueue_script('jquery');
    // Internet Explorer HTML5 support
    wp_enqueue_script( 'html5hiv',get_template_directory_uri().'/inc/assets/js/html5.js', array(), '3.7.0', false );
    wp_script_add_data( 'html5hiv', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'sative-skip-link-focus-fix', get_template_directory_uri() . '/inc/assets/js/skip-link-focus-fix.min.js', array(), '20151215', true );
}
add_action( 'wp_enqueue_scripts', 'sative_scripts' );

function sative_password_form() {
    global $post;
    $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
    $o = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">
    <div class="d-block mb-3">' . __( "To view this protected post, enter the password below:", "sative" ) . '</div>
    <div class="form-group form-inline"><label for="' . $label . '" class="mr-2">' . __( "Password:", "sative" ) . ' </label><input name="post_password" id="' . $label . '" type="password" size="20" maxlength="20" class="form-control mr-2" /> <input type="submit" name="Submit" value="' . esc_attr__( "Submit", "sative" ) . '" class="btn btn-primary"/></div>
    </form>';
    return $o;
}
add_filter( 'the_password_form', 'sative_password_form' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';
/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';
/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';
/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';
/**
 * Load plugin compatibility file.
 */
require get_template_directory() . '/inc/plugin-compatibility/plugin-compatibility.php';
/**
 * Load custom WordPress nav walker.
 */
if ( ! class_exists( 'wp_bootstrap_navwalker' )) {
    require_once(get_template_directory() . '/inc/wp_bootstrap_navwalker.php');
}



/*
* Creating a function to create our CPT
*/
 
function custom_post_type() 
{
 
// Set UI labels for Custom Post Type
$labels = array(
    'name'                => _x( 'Jobs', 'Post Type General Name', 'sative' ),
    'singular_name'       => _x( 'Job', 'Post Type Singular Name', 'sative' ),
    'menu_name'           => __( 'Jobs', 'sative' ),
    'parent_item_colon'   => __( 'Parent Job', 'sative' ),
    'all_items'           => __( 'All Jobs', 'sative' ),
    'view_item'           => __( 'View Job', 'sative' ),
    'add_new_item'        => __( 'Add New Job', 'sative' ),
    'add_new'             => __( 'Add New', 'sative' ),
    'edit_item'           => __( 'Edit Job', 'sative' ),
    'update_item'         => __( 'Update Job', 'sative' ),
    'search_items'        => __( 'Search Job', 'sative' ),
    'not_found'           => __( 'Not Found', 'sative' ),
    'not_found_in_trash'  => __( 'Not found in Trash', 'sative' ),
);
    
// Set other options for Custom Post Type
$args = array(
    'label'               => __( 'jobs', 'sative' ),
    'description'         => __( 'Jobs', 'sative' ),
    'labels'              => $labels,
    // Features this CPT supports in Post Editor
    'supports'            => array( 'title', 'editor', 'revisions', 'custom-fields' ),
    // You can associate this CPT with a taxonomy or custom taxonomy. 
    'taxonomies'          => array( 'job-categories', 'job-types', 'job-locations' ),
    /* A hierarchical CPT is like Pages and can have
    * Parent and child items. A non-hierarchical CPT
    * is like Posts.
    */ 
    'hierarchical'        => false,
    'public'              => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_nav_menus'   => true,
    'show_in_admin_bar'   => true,
    'menu_position'       => 1,
    'menu_icon'           => 'dashicons-media-document',
    'can_export'          => true,
    'has_archive'         => true,
    'exclude_from_search' => false,
    'publicly_queryable'  => true,
    'capability_type'     => 'post',
);   
// Registering your Custom Post Type
register_post_type( 'jobs', $args );
}
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
add_action( 'init', 'custom_post_type', 0 );


/**
 * Add job category taxonomies
 *
 * Additional custom taxonomies can be defined here
 * http://codex.wordpress.org/Function_Reference/register_taxonomy
 */
function add_job_category_taxonomies() 
{
    // Hierarchical taxonomy (like categories)
    register_taxonomy('job-category', 'jobs', 
        array(
            'hierarchical' => true,
            // This array of options controls the labels displayed in the WordPress Admin UI
            'labels' => array(
            'name' => _x( 'Categories', 'taxonomy general name' ),
            'singular_name' => _x( 'Category', 'taxonomy singular name' ),
            'search_items' =>  __( 'Search Categories' ),
            'all_items' => __( 'All Categories' ),
            'parent_item' => __( 'Parent Category' ),
            'parent_item_colon' => __( 'Parent Category:' ),
            'edit_item' => __( 'Edit Category' ),
            'update_item' => __( 'Update Category' ),
            'add_new_item' => __( 'Add New Category' ),
            'new_item_name' => __( 'New Category Name' ),
            'menu_name' => __( 'Categories' ),
        ),
        // Control the slugs used for this taxonomy
        'rewrite' => array(
            'slug' => 'job-categories', // This controls the base slug that will display before each term
            'with_front' => false, // Don't display the category base before "/locations/"
            'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
        ),
    ));
}
add_action( 'init', 'add_job_category_taxonomies', 0 );


/**
 * Add job type taxonomies
 *
 * Additional custom taxonomies can be defined here
 * http://codex.wordpress.org/Function_Reference/register_taxonomy
 */
function add_job_type_taxonomies() 
{
    // Add new "Locations" taxonomy to Posts
    // Hierarchical taxonomy (like categories)
    register_taxonomy('job-type', 'jobs', 
        array(
            'hierarchical' => true,
            // This array of options controls the labels displayed in the WordPress Admin UI
            'labels' => array(
            'name' => _x( 'Types', 'taxonomy general name' ),
            'singular_name' => _x( 'Type', 'taxonomy singular name' ),
            'search_items' =>  __( 'Search Types' ),
            'all_items' => __( 'All Types' ),
            'parent_item' => __( 'Parent Type' ),
            'parent_item_colon' => __( 'Parent Type:' ),
            'edit_item' => __( 'Edit Type' ),
            'update_item' => __( 'Update Type' ),
            'add_new_item' => __( 'Add New Type' ),
            'new_item_name' => __( 'New Type Name' ),
            'menu_name' => __( 'Types' ),
        ),
        // Control the slugs used for this taxonomy
        'rewrite' => array(
            'slug' => 'job-types', // This controls the base slug that will display before each term
            'with_front' => false, // Don't display the category base before "/locations/"
            'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
        ),
    ));
}
add_action( 'init', 'add_job_type_taxonomies', 0 );

function add_job_location_taxonomies() 
{
    // Add new "Locations" taxonomy to Posts
    // Hierarchical taxonomy (like categories)
    register_taxonomy('job-location', 'jobs', 
        array(
            'hierarchical' => true,
            // This array of options controls the labels displayed in the WordPress Admin UI
            'labels' => array(
            'name' => _x( 'Locations', 'taxonomy general name' ),
            'singular_name' => _x( 'Location', 'taxonomy singular name' ),
            'search_items' =>  __( 'Search Locations' ),
            'all_items' => __( 'All Locations' ),
            'parent_item' => __( 'Parent Location' ),
            'parent_item_colon' => __( 'Parent Location:' ),
            'edit_item' => __( 'Edit Location' ),
            'update_item' => __( 'Update Location' ),
            'add_new_item' => __( 'Add New Location' ),
            'new_item_name' => __( 'New Location Name' ),
            'menu_name' => __( 'Locations' ),
        ),
        // Control the slugs used for this taxonomy
        'rewrite' => array(
            'slug' => 'job-locations', // This controls the base slug that will display before each term
            'with_front' => false, // Don't display the category base before "/locations/"
            'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
        ),
    ));
}
add_action( 'init', 'add_job_location_taxonomies', 0 );

function slugify($text)
{
    // replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
    // trim
    $text = trim($text, '-');
    // remove duplicate -
    $text = preg_replace('~-+~', '-', $text);
    // lowercase
    $text = strtolower($text);
    if (empty($text)) {
        return 'n-a';
    }
    return $text;
}

function add_to_yoast_seo($post_id, $metatitle, $metadesc, $metakeywords)
{
    try {
        
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
    catch (InvalidArgumentException $e) {
        echo $e->getMessage();
    }
}


function xmlRead()
{
    $postsArr = jobList();
    $job_ids = array();
    $xml = simplexml_load_file('https://jobs.searchsoftware.nl/searchit.xml') or die("Error: Cannot create object");

    foreach($xml->vacancy as $job) {

        array_push($job_ids, $job->id);

        $date = date("Y-m-d H:i:s", strtotime($job->publish_date));

        if(!empty($job->url_title)) {
            $slug = strval($job->url_title);
        } else {
            $slug = slugify( $job->title.'-'.$job->id );
        }

        if(!empty($job->salary_fixed)){
            $salary_min = preg_replace("/\./", "", $job->salary_fixed);
        } else {
            $salary_min = 0;
        }
        if(!empty($job->salary_bonus)){
            $salary_max = preg_replace("/\./", "", $job->salary_bonus);
        } else {
            $salary_max = 0;
        }

        if($job->meta) {
            $meta_title = strval($job->meta);
        } else {
            $meta_title = null;
        }
        if($job->custom_apply_text) {
            $meta_keywords = strval($job->custom_apply_text);
        } else {
            $meta_keywords = null;
        }
        if($job->custom_callback_button) {
            $meta_description = strval($job->custom_callback_button);
        } else {
            $meta_description = null;
        }

        if($job->contact) {
            $recruiter = strval($job->contact);
        }

        $job_categories = $job->categories->category;

        $jobArray = array(
            'post_type'     => 'jobs',
            'post_status'   => 'publish',
            'post_title'    => $job->title,
            'post_content'  => $job->description,
            'post_date'     => $date,
            'post_modified' => $date,
            'post_name'     => $slug,
        );

        if( in_array( $slug, $postsArr ) ) {
            //var_dump('check');
            wp_reset_query();
            $args = array(
                'name'        => $slug,
                'post_type'   => 'jobs',
                'post_status' => 'publish',
                'numberposts' => 1
            );
            $my_posts = get_posts($args);
            if( $my_posts ) {
                $postDate = $my_posts[0]->post_date;
                $postID = $my_posts[0]->ID;
            }

            if(strval($postDate) != strval($date)) {

                unset($jobArray['post_name']);
                unset($jobArray['post_type']);
                unset($jobArray['post_status']);

                wp_update_post( $jobArray, true );

                update_field( 'salary_min', $salary_min, $postID );
                update_field( 'salary_max', $salary_max, $postID );
                update_field( 'location', strval($job->address), $postID );
                update_field( 'latitude', floatval($job->lat), $postID );
                update_field( 'longitude', floatval($job->lng), $postID );
                update_field( 'recruiter', $recruiter, $postID );
                update_field( 'meta_title', $meta_title, $postID );
                update_field( 'meta_description', $meta_description, $postID );
                update_field( 'meta_keywords', $meta_keywords, $postID );
                update_post_meta( 1, '_yoast_wpseo_title', $meta_title);
                update_post_meta( 1, '_yoast_wpseo_metadesc', $meta_description);
                update_post_meta( 1, '_yoast_wpseo_metakeywords', $meta_keywords);

            }

            wp_reset_query();

        } else {

            $postID = wp_insert_post( $jobArray, true );
            update_field( 'salary_min', $salary_min, $postID );
            update_field( 'salary_max', $salary_max, $postID );
            update_field( 'location', strval($job->address), $postID );
            update_field( 'latitude', floatval($job->lat), $postID );
            update_field( 'longitude', floatval($job->lng), $postID );
            update_field( 'recruiter', $recruiter, $postID );
            update_field( 'meta_title', $meta_title, $postID );
            update_field( 'meta_description', $meta_description, $postID );
            update_field( 'meta_keywords', $meta_keywords, $postID );
            update_post_meta( 1, '_yoast_wpseo_title', $meta_title);
            update_post_meta( 1, '_yoast_wpseo_metadesc', $meta_description);
            update_post_meta( 1, '_yoast_wpseo_metakeywords', $meta_keywords);

            foreach($job_categories as $category) {
                if($category['group'] == '#2 Skill Area') {
                    
                    $term = get_term_by('name', strval($category), 'job-category');
                    if(!$term) {
                        wp_insert_term(strval($category), 'job-category');
                        $term = get_term_by('name', strval($category), 'job-category');
                    }
                    $termID = $term->term_id;
                    wp_set_post_terms($postID, $termID, 'job-category', true);
    
                } else if($category['group'] == '#3 Skill IT') {

                    $parent = get_term_by('slug', 'it', 'job-category');
                    $parentID = $parent->term_id;
                    $term = get_term_by('name', strval($category), 'job-category');
                    if(!$term) {
                        wp_insert_term(strval($category), 'job-category', array('parent' => $parentID));
                        $term = get_term_by('name', strval($category), 'job-category');
                    }
                    $termID = $term->term_id;
                    wp_set_post_terms($postID, $termID, 'job-category', true);

                } else if($category['group'] == '#1 Availability') {

                    $term = get_term_by('name', strval($category), 'job-type');
                    if(!$term) {
                        wp_insert_term(strval($category), 'job-type');
                        $term = get_term_by('name', strval($category), 'job-type');
                    }
                    $termID = $term->term_id;
                    wp_set_post_terms($postID, $termID, 'job-type', true);

                }
    
            }

        }

    }
    //var_dump($job_ids);
}

function jobList()
{
    $postsArr = array();
    wp_reset_query();
    $args = array(
        'post_type'      => 'jobs',
        'post_status'    => 'publish',
        'posts_per_page' => -1
    );
    $posts = new WP_Query( $args );

    if ( $posts->have_posts() ) :
        while ( $posts->have_posts() ) : $posts->the_post();
            var_dump(get_post_field( 'post_name', get_the_ID()) );
            array_push($postsArr, get_post_field( 'post_name', get_the_ID() ));
        endwhile;
    endif;

    wp_reset_query();
    return $postsArr;
}

function jobAdd()
{

}

function jobFulfilled()
{

}

function jobUpdate()
{

}


function jobDisplayHelper()
{
    $helper = array(
        'supCatName' => '',
        'type' => '',
    );
    
    $categories = get_the_terms(get_the_ID(), 'job-category');
    if(is_array($categories)) {
        foreach($categories as $category) {
            if($category->parent == 0) {
                $helper['supCatName'] = $category->slug;
                break;
            }
        }
    }

    $types = get_the_terms(get_the_ID(), 'job-type');
    if(is_array($types)) {
        foreach($types as $type) {
            if($type->parent == 0) {
                $helper['type'] = $type->name;
                break;
            }
        }
    }

    return $helper;
}



$toTranslate = array(
    'Contact',
    'Get in touch',
    'Share this content',
    'open jobs',
    'jobs showing',
    'More info'
);

foreach($toTranslate as $string) {
    pll_register_string('sative', $string);
}