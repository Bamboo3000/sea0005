<?php

/**
 * Adding/updating jobs from XML
 *
 * @return void
 */
function xmlRead()
{
    $postsArr = jobList();
    $job_ids = array();
    $xml = simplexml_load_file('https://jobs.searchsoftware.nl/searchit.xml') or die("Error: Cannot create object");

    foreach($xml->vacancy as $job) {

        if( !empty($job->id) ) {
            $jobID = intval($job->id);
        } else {
            $jobID = null;
        }

        array_push($job_ids, $jobID);

        $date = date( "Y-m-d H:i:s", strtotime($job->modify_date) );

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

        if( strval($job->contact_first_name) && strval($job->contact_last_name) ) {
            $recruiter = strval($job->contact_first_name).' '.strval($job->contact_last_name);
        } else if( $job->contact ) {
            $recruiter = strval($job->contact);
        }

        if($recruiter) {
            $recruiter_related = get_page_by_title($recruiter, OBJECT, 'team');
        }

        $job_categories = $job->categories->category;

        $jobArray = array(
            'post_type'     => 'jobs',
            'post_status'   => 'publish',
            'post_title'    => $job->title,
            'post_content'  => $job->description,
            'post_date'     => $date,
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

            if( isset( $_GET['force'] ) ) {
                $force = true;
            } else {
                $force = false;
            }

            if( strval($postDate) !== strval($date) || $force) {

                var_dump( strval($postDate) );
                var_dump( strval($date) );

                unset($jobArray['post_name']);
                unset($jobArray['post_type']);
                unset($jobArray['post_status']);
                $jobArray['ID'] = $postID;

                wp_update_post( $jobArray, true );

                update_field( 'job_id', $jobID, $postID );
                update_field( 'salary_min', $salary_min, $postID );
                update_field( 'salary_max', $salary_max, $postID );
                update_field( 'location', strval($job->address), $postID );
                update_field( 'latitude', floatval($job->lat), $postID );
                update_field( 'longitude', floatval($job->lng), $postID );
                update_field( 'recruiter', $recruiter, $postID );

                if( $recruiter_related !== null ) {
                    update_field( 'recruiter_related', $recruiter_related, $postID );   
                }
                
                if( $meta_title !== null ) {
                    update_field( 'meta_title', $meta_title, $postID );
                    if( get_post_meta( $postID, '_yoast_wpseo_title', true ) ) {
                        update_post_meta( $postID, '_yoast_wpseo_title', $meta_title);
                    } else {
                        add_post_meta( $postID, '_yoast_wpseo_title', $meta_title);
                    }
                }

                if( $meta_description !== null ) {
                    update_field( 'meta_description', $meta_description, $postID );
                    if( get_post_meta( $postID, '_yoast_wpseo_metadesc', true ) ) {
                        update_post_meta( $postID, '_yoast_wpseo_metadesc', $meta_description);
                    } else {
                        add_post_meta( $postID, '_yoast_wpseo_metadesc', $meta_description);
                    }
                }

                if( $meta_keywords !== null ) {
                    update_field( 'meta_keywords', $meta_keywords, $postID );
                    $meta_keywords = str_replace(',', '', $meta_keywords);
                    if( get_post_meta( $postID, '_yoast_wpseo_focuskw', true ) ) {
                        update_post_meta( $postID, '_yoast_wpseo_focuskw', $meta_keywords);
                    } else {
                        add_post_meta( $postID, '_yoast_wpseo_focuskw', $meta_keywords);
                    }
                }

            }

            /**
             * Categories insert
             */
            insertCategories($job_categories, $postID);

            /**
             * Location insert
             */
            insertLocation($job, $postID);

            wp_reset_query();

        } else {

            /**
             * General fields insert
             */
            $postID = wp_insert_post( $jobArray, true );

            update_field( 'job_id', $jobID, $postID );
            update_field( 'salary_min', $salary_min, $postID );
            update_field( 'salary_max', $salary_max, $postID );
            update_field( 'location', strval($job->address), $postID );
            update_field( 'latitude', floatval($job->lat), $postID );
            update_field( 'longitude', floatval($job->lng), $postID );
            update_field( 'recruiter', $recruiter, $postID );

            if( $recruiter_related !== null ) {
                update_field( 'recruiter_related', $recruiter_related, $postID );   
            }

            if( $meta_title !== null ) {
                update_field( 'meta_title', $meta_title, $postID );
                add_post_meta( $postID, '_yoast_wpseo_title', $meta_title);
            }

            if( $meta_description !== null ) {
                update_field( 'meta_description', $meta_description, $postID );
                add_post_meta( $postID, '_yoast_wpseo_metadesc', $meta_description);
            }

            if( $meta_keywords !== null ) {
                update_field( 'meta_keywords', $meta_keywords, $postID );
                $meta_keywords = str_replace(',', '', $meta_keywords);
                add_post_meta( $postID, '_yoast_wpseo_focuskw', $meta_keywords);
            }

            /**
             * Categories insert
             */
            insertCategories($job_categories, $postID);

            /**
             * Location insert
             */
            insertLocation($job, $postID);

        }

    }

    jobsFulfilled($job_ids);

}

/**
 * Getting all the jobs
 *
 * @return array
 */
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
            //var_dump(get_post_field( 'post_name', get_the_ID()) );
            array_push($postsArr, get_post_field( 'post_name', get_the_ID() ));
        endwhile;
    endif;

    wp_reset_query();
    return $postsArr;
}

/**
 * Removing old jobs
 *
 * @return void
 */
function jobsFulfilled($job_ids)
{
    $inDB = jobList();

    foreach($inDB as $post_slug) {
        $args = array(
            'name'        => $post_slug,
            'post_type'   => 'jobs',
            'post_status' => 'publish',
            'numberposts' => 1
        );
        $my_posts = get_posts($args);

        if( $my_posts ) {
            $postID = $my_posts[0]->ID;
            $job_id = get_field('job_id', $postID);
            $post_type = get_post_type($postID);
        }
        if( !in_array( $job_id, $job_ids ) && $post_type !== 'jobs-fulfilled') {
            //var_dump($my_posts[0]->name);
            removeAllTerms($postID);
            $jobArray = array(
                'ID'            => $postID,
                'post_type'     => 'jobs-fulfilled',
            );
            wp_update_post( $jobArray, true );
        }
    }

}

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

function removeAllTerms($postID)
{
    $category = wp_get_post_terms($postID, 'job-category');
    $cats = array();
    foreach($category as $cat) {
        $cats[] = $cat->term_id;
    }
    $location = wp_get_post_terms($postID, 'job-location');
    $locs = array();
    foreach($location as $loc) {
        $locs[] = $loc->term_id;
    }
    $type = wp_get_post_terms($postID, 'job-type');
    $typs = array();
    foreach($type as $typ) {
        $typs[] = $typ->term_id;
    }
    wp_remove_object_terms($postID, $cats, 'job-category');
    wp_remove_object_terms($postID, $locs, 'job-location');
    wp_remove_object_terms($postID, $typs, 'job-type');
}

function insertCategories($job_categories, $postID) 
{

    $skillArr = array();

    foreach( $job_categories as $category ) {

        if($category['group'] == '#2 Skill Area') {

            $skillArr[] = strval($category);
            
            $term = get_term_by('name', strval($category), 'job-category');
            if(!$term) {
                wp_insert_term(strval($category), 'job-category');
                $term = get_term_by('name', strval($category), 'job-category');
            }
            $termID = $term->term_id;
            wp_set_post_terms($postID, $termID, 'job-category', true);

        } else if( $category['group'] == '#2.2 Skill Industry' ) {

            $term = get_term_by('name', strval($category), 'job-industry');
            if(!$term) {
                wp_insert_term(strval($category), 'job-industry');
                $term = get_term_by('name', strval($category), 'job-industry');
            }
            $termID = $term->term_id;
            wp_set_post_terms($postID, $termID, 'job-industry', true);

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

    if( !empty($skillArr) ) {
        insertCategoriesRec($job_categories, $postID, $skillArr);
    }

}

function insertCategoriesRec($job_categories, $postID, $skillArr)
{

    $skills = array();

    foreach( $skillArr as $skill ) {

        $skillGroup = 'Skill '.$skill;

        foreach( $job_categories as $category ) {

            if( strpos( $category['group'], $skillGroup ) != false ) {

                $skills[] = strval($category);

                $slug = slugify($skill);
                echo $slug.' - ';

                $parent = get_term_by('slug', $slug, 'job-category');
                $parentID = $parent->term_id;
                echo $parentID.' | ';
                $term = get_term_by('name', strval($category), 'job-category');
                if(!$term) {
                    wp_insert_term(strval($category), 'job-category', array('parent' => $parentID));
                    $term = get_term_by('name', strval($category), 'job-category');
                }
                $termID = $term->term_id;
                wp_set_post_terms($postID, $termID, 'job-category', true);

            }

        }

    }

    if( !empty($skills) ) {
        insertCategoriesRec($job_categories, $postID, $skills);
    }

}


function insertLocation($job, $postID)
{
    if( !empty($job->address_country) && !empty($job->address_city) ) {
        $country = strval( $job->address_country );
        $city = strval( $job->address_city );
    } else if(!empty($job->address)) {
        $split = explode( ",", strval($job->address) );
        if( count($split) > 1 ) {
            $city = $split[0];
            $country = $split[1];
        } else if( count($split) > 0 ) {
            $country = $split[0];
            $city = false;
        }
    } else {
        return false;
    }
    $term_type = 'job-location';

    if($country) {
        $term = get_term_by('name', $country, $term_type);
        if(!$term) {
            wp_insert_term($country, $term_type);
            $term = get_term_by('name', $country, $term_type);
        }
        $termID = $term->term_id;
        wp_set_post_terms($postID, $termID, $term_type, true);
    }
    if($city) {
        $parent = get_term_by('name', $country, $term_type);
        $parentID = $parent->term_id;
        $term = get_term_by('name', $city, $term_type);
        if(!$term) {
            wp_insert_term($city, $term_type, array('parent' => $parentID));
            $term = get_term_by('name', $city, $term_type);
        }
        $termID = $term->term_id;
        wp_set_post_terms($postID, $termID, $term_type, true);
    }
}