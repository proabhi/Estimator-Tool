<?php
/*
Plugin Name:  Basement Estimator Tool
Plugin URI:   https://swarnatek.com/
Description:  A tool for esitmating your basment creation budget.
Version:      1.0
Author:       Swarnatek
Author URI:   https://www.swarnatek.com
License:      GPL2
License URI:  https://www.swarnatek.com
*/


(defined('ABSPATH') || exit);

// Act on plugin activation
register_activation_hook(__FILE__, "activate_myplugin");

// Act on plugin de-activation
register_deactivation_hook(__FILE__, "deactivate_myplugin");

// Activate Plugin
function activate_myplugin()
{
    // Execute tasks on Plugin activation
    // Insert DB Tables
    init_db_myplugin();
}

// De-activate Plugin
function deactivate_myplugin()
{
    // Execute tasks on Plugin de-activation
}

// Initialize DB Tables
function init_db_myplugin()
{
    // WP Globals
    global $table_prefix, $wpdb;

    // Customer Table
    $estimation_records = $table_prefix . 'estimation_records';

    // Create Customer Table if not exist
    if ($wpdb->get_var("SHOW TABLES LIKE '$estimation_records'") != $estimation_records) {
        $charset_collate = $wpdb->get_charset_collate();

        // Query - Create Table
        $sql = "CREATE TABLE `$estimation_records` (
            `id` int(11) NOT NULL auto_increment,
            `name` varchar(500) NOT NULL,
            `email` varchar(500) NOT NULL,
            `phone` varchar(500) NOT NULL,
            `cost_as_per_sqt` decimal(10,2) NOT NULL,
            `cost_per_ceiling_height` decimal(10,2) NOT NULL,
            `cost_as_per_bathroom_preference` decimal(10,2) NOT NULL,
            `bathroom_drain_cost` decimal(10,2) NOT NULL,
            `cost_as_per_kitchenette_preference` decimal(10,2) NOT NULL,
            `kitchenette_drain_cost` decimal(10,2) NOT NULL,
            `ceiling_type_cost` decimal(10,2) NOT NULL,
            `total_cost` decimal(10,2) NOT NULL,
            PRIMARY KEY (`id`)
        ) $charset_collate;";

        // Include Upgrade Script
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // Create Table
        dbDelta($sql);
    }
}


function myincludes()
{
    wp_register_style('myincludes', plugins_url('/css/style.css?' . date("h:i:sa"), __FILE__), false, '1.0', 'all'); // Inside a plugin
    wp_enqueue_style('myincludes');
    wp_register_script('myincludes', plugins_url('/js/script.js?' . date("h:i:sa"), __FILE__), array('jquery'), true);
    wp_enqueue_script('myincludes');
}

function adminIncludes()
{
    wp_register_script('adminIncludes', plugins_url('/js/adminScript.js?' . date("h:i:sa"), __FILE__), array('jquery'), true);
    wp_enqueue_script('adminIncludes');
}

add_action('init', 'myincludes');
add_action('admin_init', 'adminIncludes');

function basementEstimatorMenu()
{
    add_menu_page(
        'Basement Estimator',
        'Basement Estimator Tool',
        'edit_plugins',
        'basement-estimator',
        '_custom_menu_page',
        'dashicons-media-spreadsheet',
        '36',
    );
}

add_action('admin_menu', 'basementEstimatorMenu');



function forms($video_url)
{
    $path = get_option('basement_estimator_video_url');
    $extracted_path = parse_url($path, PHP_URL_PATH);
    $extracted_path = $_SERVER['DOCUMENT_ROOT'].$extracted_path;
    if (file_exists($extracted_path) && $path != '') {
        $video_url = get_option('basement_estimator_video_url');
    } else {
        $video_url = site_url().'/wp-content/plugins/basement-estimator/uploads/AB9B73C1-2D09-436C-9B1E-42CA92096C8A.mov';
    }
    //$video_url = get_option('basement_estimator_video_url');
    $html = '<div class="estimation">
        <form id="preference" method="POST" action="#">
            <div class="main-section">
                <h2>Select Type of Basement</h2>
                <div class="details">

                    <label for="name">Name</label>
                    <input type="text"  required name="name" class="txt-underline" id="name" placeholder=" ">
					  
                       <div class="alert alert-danger" id="nameError" role="alert">
                        
                        </div>
                </div>
                <div class="details_half first">
                    <label for="email">Email</label>
                    <input type="email"  required name="email" class="txt-underline" id="email" placeholder=" ">
						
                         <div class="alert alert-danger" id="emailError" role="alert">
                        
                        </div>
                </div>
                <div class="details_half">
                    <label for="phone">Phone</label>
                    <input type="tel"  required class="txt-underline" name="phone"  id="phone" placeholder="" onkeypress="return event.charCode &gt;= 48 &amp;&amp; event.charCode &lt;= 57" required>
					 
					
                     <div class="alert alert-danger" id="phoneError" role="alert">
                        
                        </div>
                </div>
				   
				
				
                <div class="details_preference">
                    <label>Enter your Preference:</label>
                    <div class="radio-group">
                        <label><input type="radio" name="preference" value="standard"> Standard <span class="design"></span></label>
                        <label><input type="radio" name="preference" value="enhanced"> Enhanced <span class="design"></span></label>
                    </div>
                </div>
				<div id="myfrm">
                <div class="details_second">
                  
					 <label for="squareFeet">Approximately how many square feet?</label>
                    <input type="text" required class="txt-underline" name="sqft"  id="sqft"" placeholder="" onkeypress="return event.charCode &gt;= 48 &amp;&amp; event.charCode &lt;= 57" required>
					 

                     <div class="alert alert-danger" id="sqftError" role="alert">
                        
                        </div>
                </div>
              
                <div class="details_second_rec">
                    <label>Are your ceilings taller than 8 feet?</label>
                    <div class="radio-group-2">
                        <label><input type="radio"  required name="ceilings" value="yes"> Yes <span class="design"></span></label>
                        <label><input type="radio"  required name="ceilings" value="no"> No <span class="design"></span></label>
                    </div>
                    <div class="alert alert-danger" id="ceilingsError" role="alert">
                        
                        </div>
                </div>

                <div class="details_half first">
                    <label for="bathroom-choice">Do you want a bathroom?</label>
                    <select  id="bathroomPref" >
                        <option value="" selected disabled>Select an option</option>
                        <option value="fullBath">Yes, Full Bath</option>
                        <option value="halfBath">Yes, Half Bath</option>
                    </select>
                    <div class="alert alert-danger" id="bathprefError" role="alert">
                        
                        </div>
                </div>

                <div class="details_half">
                    <label for="bathroom-drainSelect">If yes bathroom: are drains already in place?</label>
                    <select id="bathroomDrains">
                        <option value="" selected disabled>Select an option</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                        <option value="idk">I dont know</option>
                       
                    </select>

                    <div class="alert alert-danger" id="bathdrainError" role="alert">
                        
                        </div>
                </div>
            
                <div class="details_half first">
                    <label for="kitchenette-choice">Do you want a kitchenette?</label>
                    <select id="kitchenettePref" >
                        <option value="" selected disabled>Select an option</option>
                        <option value="drybar">Yes, Dry Bar</option>
                        <option value="withSink">Yes, With sink</option>
                        <option value="no">No</option>
                    </select>
                    <div class="alert alert-danger" id="kitchenprefError" role="alert">
                        
                        </div>
                </div>

                <div class="details_half">
                    <label for="kitchenette-drainSelect">If yes kitchenette: are drains already in place?</label>
                    <select id="kitchenetteDrains" name="kitchenetteDrains">
                        <option value="" selected disabled>Select an option</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                        <option value="idk">I dont know</option>
                    </select>

                    <div class="alert alert-danger" id="kitchendrainError" role="alert">
                        
                        </div>
                </div>
				<div class="enhanced-field">
					<label>Do you prefer a drywall ceiling or a spray black ceiling?</label>
						<div class="enhanced-ceilingType-choice">
							 <label><input type="radio" name="enhanced-ceilingType" value="drywall">Drywall <span class="design"></span></label>
                        <label><input type="radio" name="enhanced-ceilingType" value="sprayblack">Spray Black <span class="design"></span></label>
						</div>

                        <div class="alert alert-danger" id="ceilingtypeError" role="alert">
                        
                        </div>
				</div>
                <div class="details_btn">
                    <button type="button" value="Submit" id="Submitfrm">Submit</button>
                </div>
				 </div>
            </div>
        </form>


       <div id="myModal" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
            <button type="button" id="close-btn" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">X</span>
                </button>
                <video id="myVideo">
                    <source src=' . $video_url . ' type="video/mp4">
                </video>
                <div id="loader-container">
                    Your Quote is Generating
					<div class="container-dot">
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    </div><br />
                    <div class="loader"></div>
                </div>
            </div>
        </div> 

        <div id="SuccessResponseModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close SuccessModalClose" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="msg"><p>Your estimate will be emailed to you shortly! If you do not see it, please check your junk folder.</p></div>
            </div>
            </div>
        </div>
        </div>
        
    </div>';
    return $html;
}
add_shortcode('forms', 'forms');
function _custom_menu_page()
{
    ?>
    <div class="wrap">
        <h1>Basement Estimator Tool Settings</h1>
        <form method="post" action="options.php">
            <?php
                // Output nonce, action, and option fields
                settings_fields('basement_estimator_settings_group');
    do_settings_sections('basement_estimator_settings');
    wp_enqueue_script('jquery');
    // This will enqueue the Media Uploader script
    wp_enqueue_media();
    ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Video URL</th>
                    <td>
                        <input type="text" id="testingVideo" name="basement_estimator_video_url" 
                            value="<?php echo get_option('basement_estimator_video_url'); ?>"
                            style="width:50%;" />
                             
                            
                            <input id="upload_image_button" type="button" value="Upload Video" />
                            <p class="description">Upload the video you want to display.</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Email</th>
                    <td>
                        <input type="email" name="basement_estimator_email"
                            value="<?php echo esc_attr(get_option('basement_estimator_email')); ?>"
                            style="width:50%;" />
                        <p class="description">Enter an email address to receive notifications or updates.</p>
                    </td>
                </tr>
            </table>
            <!-- <?php submit_button(); ?> -->
            <button type="submit" onclick="myFunction()" class="button button-primary basmentDetail">Save Changes</button>
        </form>
        <div class="alert alert-success BasementDetailSuccess" role="alert">
            <strong><i>Credentails saved.</i></strong>
        </div>
    </div>
<?php
}

// Register the settings field to store the video URL
function basement_estimator_register_settings()
{
    register_setting('basement_estimator_settings_group', 'basement_estimator_video_url');
    register_setting('basement_estimator_settings_group', 'basement_estimator_email');
}
add_action('admin_init', 'basement_estimator_register_settings');





// $path = get_option('basement_estimator_video_url');

// $static_filepath = $_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/basement-estimator/uploads/AB9B73C1-2D09-436C-9B1E-42CA92096C8A.mov';
// echo $static_filepath;
// $extracted_path = parse_url($path, PHP_URL_PATH);
// $extracted_path = $_SERVER['DOCUMENT_ROOT'].$extracted_path;
// if (file_exists($extracted_path)) {
//     echo 'file exists';
// } else {
//     echo '<p>Video not found</p>';
// }