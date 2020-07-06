<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


function postRequest($request, $api_key, $api_secret, $json)
{
    $hash = bin2hex(hash_hmac('sha1', $request.'/'.$api_key, $api_secret, true));

    $ch = curl_init('https://api.searchsoftware.nl/'.$request.'?api_key='.$api_key.'&signature='.$hash);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $response = json_decode($response);

    return $response;
}

function add_to_queue(){

    $api_key = 'XoslTEyE';
    $api_secret = 'ZZXRgDovPQvPfLjklPLBoTAl';

    $application_data = array(

        'name'          => isset( $_POST['app-name'] ) ? $_POST['app-name'] : '',
        'email'         => isset( $_POST['app-email'] ) ? $_POST['app-email'] : '',
        'date_of_birth' => isset( $_POST['app-dob'] ) ? $_POST['app-dob'] : '',
        'gender'        => isset( $_POST['app-gender'] ) ? $_POST['app-gender'] : '',
        'phone'         => isset( $_POST['app-phone'] ) ? $_POST['app-phone'] : '',

        'location' => array(
            'line1'   => '',
            'line2'   => '',
            'line3'   => '',
            'zip'     => '',
            'city'    => isset( $_POST['app-city'] ) ? $_POST['app-city'] : '',
            'state'   => '',
            'country' => '',
        ),

        'sources' => array(
            array(
                'parent_source_id' => 'Website SIR', // Example: Website
                'name' => 'Applicant' // Example: Applicant
            ),
        ),

        'note' => array(
            'text' => isset( $_POST['app-motivation'] ) ? $_POST['app-motivation'] : '',
        ),

        'job' => array(
            'id' => isset( $_POST['app-jobid'] ) ? $_POST['app-jobid'] : 188,
        ),

        //'urls' => array('https://www.example.com/some/url/123'),

    );

    $data['json'] = json_encode($application_data);

    if( isset( $_FILES['app-cv'] ) && $_FILES['app-cv'] ) {

        // if( $_FILES['app-cv']['size'] <= 5248000 ) {

        $uploaded_cv = realpath( $_FILES['app-cv']['tmp_name'] );
        $cv_ext = $_FILES['app-cv']['type'];
        $cv_name = basename( $_FILES['app-cv']['name'] );
        $data['cv'] = curl_file_create($uploaded_cv, $cv_ext, $cv_name);

    }

    $person_response = postRequest('people/add_to_queue', $api_key, $api_secret, $data);

    // echo '<pre>';
    // echo var_dump($person_response);
    // echo '</pre>';
    // var_dump($person_response->status);

    if( $person_response->status === 'ok' ) {
        sendEmail();
    } else {
        $message = 'failed';
    }

    return $message;

}

function sendEmail()
{
    function wpdocs_set_html_mail_content_type() {
        return 'text/html';
    }
    add_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );
    
    $to = $_POST['app-email'];
    $subject = pll__('Job application sucessful');
    $body = appEmailTemplate();
    $headers = array('Content-Type: text/html; charset=UTF-8');

    $email = wp_mail( $to, $subject, $body, $headers  );

    if($email) {
        $message = 'success';
    } else {
        $message = 'failed';
    }

    remove_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );

    return $message;
}

function appEmailTemplate()
{
    $job_link = 'https://' . $_SERVER['SERVER_NAME'] . $_POST['_wp_http_referer'];
    $job_title = get_the_title( $_POST['postid'] );
    $body = '<html>
                <head>
                    <style type="text/css" media="screen">
                        * {
                            font-family: Trebuchet MS!important;
                            font-size: 10pt;
                        }
                        td, tr, th, table {
                            padding: 0px;
                            margin: 0px;
                        }
                        p {
                            margin: 10px 0;
                        }
                    </style>
                </head>
                <body>
                    <table style="max-width: 100%; width: 100%; margin: 15px;" cellspacing="0" cellpadding="0" border="0">
                        <tbody>
                            <tr>
                                <td>
                                    <h1 style="font-size: 12pt!important;">
                                        Thank you for your application for the position of <a href="'. $job_link .'" style="color: #425CBB; font-size: 12pt!important;">'. $job_title .'</a>.<br/>
                                        By submitting your job application on our website, you agreed to and accepted the terms of our <a href="https://www.searchitrecruitment.com/en/privacy-policy" style="color: #425CBB; font-size: 12pt!important;">Privacy Policy</a>.
                                        <br/><br/>
                                        We will carefully consider your application and contact you if you are selected to continue in the recruitment process.
                                    </h1>'
                                    // <p style="font-size: 10pt!important;">
                                    //     <strong>Name:</strong> {{ name }}<br/>
                                    //     <strong>Email:</strong> {{ email }}<br/>
                                    //     {% if phone %}
                                    //     <strong>Phone: </strong> {{ phone }}<br/>
                                    //     {% endif %}
                                    //     {% if location['line1'] or location['city'] or location['zip'] or location['country'] %}
                                    //     <strong>Address:</strong><br/>
                                    //     <strong>Street: </strong> {{ location['line1'] }}<br/>
                                    //     <strong>City: </strong> {{ location['city'] }}<br/>
                                    //     <strong>Zip: </strong> {{ location['zip'] }}<br/>
                                    //     <strong>Country: </strong> {{ location['country'] }}<br/>
                                    //     {% endif %}
                                    //     {% if note['text'] %}
                                    //     <strong>Motivation and extra details: </strong><br/> 
                                    //     {{ note['text'] }}
                                    //     {% endif %}
                                    // </p>
                                    .'<p style="font-size: 10pt!important;">
                                        Job link: <a href="'. $job_link .'">'. $job_link .'</a>
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td style="padding-top: 40px;">
                                    <p style="font-size: 10pt!important;">
                                        Keep up-to-date with our latest jobs. Follow us on social media and sign up for our newsletter/job alert!
                                    </p>
                                    <p style="font-size: 10pt!important;">
                                        <strong>LinkedIn: </strong><a href="https://www.linkedin.com/company/3512705">https://www.linkedin.com/company/3512705</a><br/>
                                        <strong>Facebook: </strong><a href="https://www.facebook.com/searchitrecruitment">https://www.facebook.com/searchitrecruitment</a><br/>
                                        <strong>Twitter: </strong><a href="https://twitter.com/searchitjobs">https://twitter.com/searchitjobs</a><br/>
                                        <strong>Newsletter/job alert: </strong><a href="http://jobs.searchsoftware.nl/searchit/subscribe">http://jobs.searchsoftware.nl/searchit/subscribe</a> 
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <hr />
                    <p>This is an automatic message. Please do not reply to it.</p>
                    <table style="max-width: 320px; width: 100%; margin: 0 15px;" cellspacing="0" cellpadding="0" border="0">
                        <tbody>
                            <tr>
                                <td>
                                    <hr style="width: 190px; margin: 10px 0 2px 0;"/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="https://www.searchitrecruitment.com">
                                        <img id="TemplateLogo" data-class="external" src="https://www.searchitrecruitment.com/themes/searchit/assets/img/logo.png" alt="Search It Recruitment" style="display: block; margin-left: -15px; max-width: 220px;" width="220px">
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <hr style="width: 190px; margin: 2px 0 10px 0;"/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table cellspacing="0" cellpadding="0" border="0">
                                        <tbody>
                                            <tr>
                                                <td style="font-family: Trebuchet MS; font-size: 9pt; padding: 1px 0;" width="70px">
                                                    <font style="color: #333333; font-size: 9pt; font-family: Trebuchet MS">
                                                        <strong>Phone:</strong>
                                                    </font>
                                                </td>
                                                <td style="font-family: Trebuchet MS; font-size: 9pt;">
                                                    <font style="color: #333333; font-size: 9pt; font-family: Trebuchet MS">
                                                        <a href="tel:+31(0)207782393" style="color: #425CBB; text-decoration: none;">
                                                            +31 (0) 20 - 7782393
                                                        </a>
                                                    </font>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-family: Trebuchet MS; font-size: 9pt; padding: 1px 0;" width="70px">
                                                    <font style="color: #333333; font-size: 9pt; font-family: Trebuchet MS">
                                                        <strong>Email:</strong>
                                                    </font>
                                                </td>
                                                <td style="font-family: Trebuchet MS; font-size: 9pt;">
                                                    <font style="color: #333333; font-size: 9pt; font-family: Trebuchet MS">
                                                        <a href="mailto:info@searchitrecruitment.com" style="color: #425CBB; text-decoration: none;">
                                                            info@searchitrecruitment.com
                                                        </a>
                                                    </font>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-family: Trebuchet MS; font-size: 9pt; padding: 1px 0;" width="70px">
                                                    <font style="color: #333333; font-size: 9pt; font-family: Trebuchet MS">
                                                        <strong>Website:</strong>
                                                    </font>
                                                </td>
                                                <td style="font-family: Trebuchet MS; font-size: 9pt;">
                                                    <font style="color: #333333; font-size: 9pt; font-family: Trebuchet MS">
                                                        <a href="https://www.searchitrecruitment.com" style="color: #425CBB; text-decoration: none;">
                                                            www.searchitrecruitment.com
                                                        </a>
                                                    </font>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-family: Trebuchet MS; font-size: 9pt; padding: 10px 0 1px 0;" width="70px">
                                                    <font style="color: #333333; font-size: 9pt; font-family: Trebuchet MS">
                                                        <strong>Address:<br/><br/></strong>
                                                    </font>
                                                </td>
                                                <td style="font-family: Trebuchet MS; font-size: 9pt; padding: 10px 0 1px 0;">
                                                    <font style="color: #333333; font-size: 9pt; font-family: Trebuchet MS">
                                                        <a href="https://www.google.nl/maps/place/Search+It+Recruitment/@52.3214064,4.8767044,17z/data=!3m1!4b1!4m5!3m4!1s0x47c60a6c6573e861:0x4fb3a938d9044260!8m2!3d52.3214064!4d4.8788931" style="color: #425CBB; text-decoration: none;">
                                                            Laan van Kronenburg 14, 1183 AS, Amstelveen, Amsterdam Area, Netherlands
                                                        </a>
                                                    </font>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                </body>
            </html>';
    return $body;
}

function sative_application_form_submit() {

    //$message = add_to_queue();

    $message = sendEmail();

    $redirect = '/app-success?ref='.$_POST['_wp_http_referer'].'&message='.$message;
    header("Location: $redirect");

}
add_action( 'admin_post_nopriv_application_form', 'sative_application_form_submit' );
add_action( 'admin_post_application_form', 'sative_application_form_submit' );