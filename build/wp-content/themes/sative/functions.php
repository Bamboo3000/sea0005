<?php
/**
 * WP Bootstrap Starter functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WP_Bootstrap_Starter
 */

//require_once get_template_directory() . '/inc/jshrink.php';
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

show_admin_bar(false);

if( function_exists('acf_add_options_page') ) {
    
    acf_add_options_page(array(
        'page_title'    => __('Theme General Settings'),
        'menu_title'    => __('Theme Settings'),
        'menu_slug'     => 'theme-general-settings',
        'capability'    => 'edit_posts',
        'redirect'      => true
    ));

    acf_add_options_sub_page(array(
		'page_title' 	=> 'Footer',
		'menu_title'	=> 'Footer',
		'parent_slug'	=> 'theme-general-settings',
    ));
    
    acf_add_options_sub_page(array(
		'page_title' 	=> 'Other',
		'menu_title'	=> 'Other',
		'parent_slug'	=> 'theme-general-settings',
	));

}

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
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
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

//Remove Gutenberg Block Library CSS from loading on the frontend
function smartwp_remove_wp_block_library_css()
{
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
}
add_action( 'wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css' );

add_action('wp_enqueue_scripts', function(){
	if (!is_admin()) {
		wp_deregister_script('jquery');
		wp_deregister_script('wp-embed');
		wp_deregister_script('wp-emoji');
	}
});

remove_action( 'wp_head', 'print_emoji_detection_script', 7 ); 
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' ); 
remove_action( 'wp_print_styles', 'print_emoji_styles' ); 
remove_action( 'admin_print_styles', 'print_emoji_styles' );

function my_mce4_options($init) {
    $custom_colours = '
        "183153", "Navy blue",
        "94D4E9", "Sea blue",
        "EC6278", "Pink",
        "FDD963", "Yellow",
        "E3E0E5", "Grey",
        "000000", "Black",
        "FFFFFF", "White"
    ';
    // build colour grid default+custom colors
    $init['textcolor_map'] = '['.$custom_colours.']';
    // change the number of rows in the grid if the number of colors changes
    // 8 swatches per row
    $init['textcolor_rows'] = 1;
    return $init;
}
add_filter('tiny_mce_before_init', 'my_mce4_options');

/**
 * Enqueue scripts and styles.
 */
function sative_scripts() {
    // load bootstrap css
	wp_enqueue_style( 'sative-bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css' );
	wp_enqueue_style( 'sative-gfonts', 'https://fonts.googleapis.com/css?family=Roboto:300,300i,500,500i&display=swap' );

	wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js', array(), '', false );
	wp_enqueue_script('sative-popper', 'https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js', array(), '', true );
	wp_enqueue_script('sative-bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js', array(), '', true );
	wp_enqueue_script('sative-app', get_template_directory_uri() . '/assets/js/main.min.js', array(), '', true );
	// Internet Explorer HTML5 support
    wp_enqueue_script( 'html5hiv',get_template_directory_uri().'/inc/assets/js/html5.js', array(), '3.7.0', false );
    wp_script_add_data( 'html5hiv', 'conditional', 'lt IE 9' );
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

require_once get_template_directory() . '/inc/custom_post_types.php';

if( function_exists( 'pll_get_post' ) ) {
function getTplPageURL() 
{
    global $jobtpl;
    wp_reset_query();
    wp_reset_postdata();
    $args_tpl = [
        'post_type' => 'page',
        'fields' => 'ids',
        'nopaging' => true,
        'meta_key' => '_wp_page_template',
        'meta_value' => 'tpl_job-list.php'
    ];
    $pages_tpl = get_posts( $args_tpl );
    //var_dump($pages);
    // cycle through $pages here and either grab the URL
    // from the results or do get_page_link($id) with 
    // the id of the page you want 
    $jobtpl = null;
    if(isset($pages_tpl[0])) {
        $jobtpl= get_page_link( pll_get_post( $pages_tpl[0] ) );
    }
    return $jobtpl;
    wp_reset_postdata();
}
}

if( function_exists( 'pll_get_post' ) ) {
    function getTplPageKnowledgeURL() 
    {
        global $jobtpl;
        wp_reset_query();
        wp_reset_postdata();
        $args_tpl = [
            'post_type' => 'page',
            'fields' => 'ids',
            'nopaging' => true,
            'meta_key' => '_wp_page_template',
            'meta_value' => 'tpl_knowledge.php'
        ];
        $pages_tpl = get_posts( $args_tpl );
        //var_dump($pages);
        // cycle through $pages here and either grab the URL
        // from the results or do get_page_link($id) with 
        // the id of the page you want 
        $jobtpl = null;
        if(isset($pages_tpl[0])) {
            $jobtpl= get_page_link( pll_get_post( $pages_tpl[0] ) );
        }
        return $jobtpl;
        wp_reset_postdata();
    }
    }

function term_has_parent($termid, $tax){
    $term = get_term($termid, $tax);
    if ($term->parent > 0){
        return $term->parent;
    }
    return false;
}

/**
 * Getting top level job categories and job types
 *
 * @return array
 */
function jobDisplayHelper()
{
    $helper = array(
        'supCatName' => '',
        'type' => '',
        'industry' => '',
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

    $industries = get_the_terms(get_the_ID(), 'job-industry');
    if(is_array($industries)) {
        foreach($industries as $industry) {
            if($industry->parent == 0) {
                $helper['industry'] = $industry->name;
                break;
            }
        }
    }

    return $helper;
}

function hierarchical_tax_tree( $cat, $tax, $active = [] ) {
    $next = get_categories('taxonomy=' . $tax . '&orderby=count&order=DESC&hide_empty=false&parent=' . $cat);
    if( $next ) :    
        echo '<ul>';
        foreach( $next as $cat ) :
            if(get_query_var('term') == $cat->category_nicename || in_array($cat->term_id, $active)) {
                echo '<li class="active">';
                $checked = 'checked';
            } else {
                echo '<li>';
                $checked = null;
            }
            echo '<a href="' . get_category_link( $cat->term_id ) . '">' . $cat->name . '&nbsp;<small>('. $cat->count . ')</small><i class="far fa-times"></i></a>'; 
            hierarchical_tax_tree( $cat->term_id, $tax, $active = [] );
            echo '</li>';
        endforeach;   
        echo '</ul>'; 
    endif;    
}  


function hierarchical_tax_tree_filter( $cat, $tax, $active) {
    $next = get_categories('taxonomy=' . $tax . '&orderby=count&order=DESC&hide_empty=false&parent=' . $cat);
    if( $next ) :    
        echo '<ul>';
        foreach( $next as $cat ) :
            if($active === null) {
                $active = [];
            }
            if(get_query_var('term') == $cat->category_nicename || in_array($cat->term_id, $active)) {
                echo '<li class="active">';
                $checked = 'checked';
            } else {
                echo '<li>';
                $checked = null;
            }
            echo '<span>' . $cat->name . '&nbsp;<small>('. $cat->count . ')</small><i class="far fa-times"></i><input type="checkbox" data-name="'.$cat->category_nicename.'" ' . $checked . ' name="' . $tax . '[]" value="' . $cat->term_id . '"></span>'; 
            hierarchical_tax_tree_filter( $cat->term_id, $tax, $active);
            echo '</li>';
        endforeach;   
        echo '</ul>'; 
    endif;    
}


// add_action('wp_ajax_myfilter', 'jobs_filter_function'); // wp_ajax_{ACTION HERE} 
// add_action('wp_ajax_nopriv_myfilter', 'jobs_filter_function');
 

function filterHelper($els, $tax)
{
    $arr = $els;
    foreach($arr as $el) {
        if(term_has_parent($el, $tax) !== false) {
            $needle = term_has_parent($el, $tax);
            $stack = array_search($needle, $arr);
            if($stack !== false) {
                array_splice($arr, $stack, 1);
            }
        }
    }
    return $arr;
}

add_action( 'admin_menu', 'my_remove_admin_menus' );
function my_remove_admin_menus() {
    remove_menu_page( 'edit-comments.php' );
}

function resources_cpt_generating_rule($wp_rewrite) 
{
    $rules = array();
    $terms = get_terms( array(
        'taxonomy' => 'job-category',
        'hide_empty' => false,
    ) );
    $post_type = 'jobs';
    foreach ($terms as $term) {    
        $rules['vacatures/' . $term->slug . '/([^/]*)$'] = 'index.php?post_type=' . $post_type. '&job-category=$matches[1]&name=$matches[1]';
        $rules['nl/vacatures/' . $term->slug . '/([^/]*)$'] = 'index.php?post_type=' . $post_type. '&job-category=$matches[1]&name=$matches[1]';
        $rules['jobs/' . $term->slug . '/([^/]*)$'] = 'index.php?post_type=' . $post_type. '&job-category=$matches[1]&name=$matches[1]';
        $rules['en/jobs/' . $term->slug . '/([^/]*)$'] = 'index.php?post_type=' . $post_type. '&job-category=$matches[1]&name=$matches[1]';
    }
    // merge with global rules
    $wp_rewrite->rules = $rules + $wp_rewrite->rules;
}
add_filter('generate_rewrite_rules', 'resources_cpt_generating_rule');

if( function_exists( 'pll_current_language' ) ) {
function change_link( $permalink, $post ) 
{
    if( $post->post_type == 'jobs' ) {
        $resource_terms = get_the_terms( $post, 'job-category' );
        $term_slug = '';
        if( ! empty( $resource_terms ) ) {
            foreach ( $resource_terms as $term ) {
                // The featured resource will have another category which is the main one
                if( $term->slug == 'featured' ) {
                    continue;
                }
                $term_slug = $term->slug;
                break;
            }
        }
        if(pll_current_language() == 'nl') {
            $permalink = "/nl/vacatures/" . $term_slug . '/' . $post->post_name;
        } else {
            $permalink = "/en/jobs/" . $term_slug . '/' . $post->post_name;
        }
    }
    return $permalink;
}
add_filter('post_type_link', 'change_link', 10, 2);
}

$toTranslate = array(
    'From',
    'To',
    'Learn more',
    'Do you need my help?',
    'Contact',
    'Get in touch',
    'Share this content',
    'Filter jobs',
    'Categories',
    'Salary range',
    'Location',
    'Industry type',
    'Job type',
    'Hot skills',
    'Open in maps',
    'Filter',
    'open jobs',
    'open job',
    'jobs found',
    'job found',
    'jobs showing',
    'job showing',
    'More info',
    'Read more',
    'Topics',
    'Contact us!',
    'Apply here',
    'Apply with:',
    'Male',
    'Female',
    'Name',
    'Email',
    'Phone',
    'Date of birth',
    'City',
    'CV',
    'Upload',
    'Upload CV',
    'Motivation',
    'Send application',
    'I hereby agree with the',
    'Privacy Policy',
    'Back',
    'Executive search consultant',
    'Show all jobs',
    'Show all articles',
    'Search for a job!',
    'Show less',
    'No jobs found...',
    'Schedule a call or meeting',
    'Search our knowledge base. We have tons of useful articles for you!',
    'article found',
    'articles found',
    'Enter job title here',
    'Enter job location',
    'Filter by topic',
    'Contact',
    'Recent jobs',
    'Hot skills',
    'Subscribe to our newsletter',
    'Interested in instantly receiving the latest Search X Recruitment jobs within your area of expertise?',
    'Subscribe now',
    'Category',
    'Let me help you find the perfect job',
    "Let's find the perfect job for you",
    'Clear all',
    'Your file exceeds 5mb limit...',
    'Job application sucessful',
    'Search X Recruitment uses cookies to improve our website and your user experience. <br/>By clicking any link or continuing to browse you are giving your consent to our cookie policy.',
    'cookie policy',
    'Accept'
);

if( function_exists( 'pll_register_string' ) ) {
    foreach($toTranslate as $string) {
        pll_register_string('sative', $string);
    }
}

function siteURL()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domainName = $_SERVER['HTTP_HOST'].'/';
    return $protocol.$domainName;
}

require_once get_template_directory() . '/inc/application-form.php';

require_once get_template_directory() . '/inc/cronjob.php';

require_once get_template_directory() . '/inc/fetchdata.php';


function template_chooser($template)   
{    
  global $wp_query;   
  $post_type = get_query_var('post_type');   
  if( $wp_query->is_search && $post_type == 'jobs' )   
  {
    return locate_template('archive-jobs.php');  //  redirect to archive-search.php
  }   
  return $template;   
}
add_filter('template_include', 'template_chooser'); 