<?php

include($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
include($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
define('WP_USE_THEMES', false);
global $wpdb;
add_action('wp_ajax_siteWideMessage', 'wpse_sendmail');
add_action('wp_ajax_nopriv_siteWideMessage', 'wpse_sendmail');

$plugin_dir = $_SERVER["DOCUMENT_ROOT"].'/wp-content/plugins/basement-estimator/fpdf/fpdf.php';

require($plugin_dir);

// Ensure this file is being accessed through WordPress
if (!defined('ABSPATH')) {
    exit; 
}

class PDF extends FPDF
{
    // Header method
    public function Header()
    {
        // Set the background color to black
        $this->SetFillColor(0, 0, 0); // RGB for black
        $this->Rect(0, 0, $this->GetPageWidth(), 40, 'F'); // Draw a filled rectangle for the header background

        // Add the image on the left side
        $imagePath = plugin_dir_path(__FILE__) . 'images/logo.png'; // Use plugin_dir_path for the image
        $this->Image($imagePath, 15, 15, 50); // Adjust the path and size as needed

        // Set font for the title
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(161, 204, 58); // Set text color to white
        $this->Cell(0, 5, '(614)-564-1700', 0, 1, 'R'); // Align right

        // Set font for the subtitle
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(255, 255, 255); // Set text color to white
        $this->Cell(0, 5, 'ReadyGo Remodeling', 0, 1, 'R'); // Align right

        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(255, 255, 255); // Set text color to white
        $this->Cell(0, 5, '5368 Central College Road', 0, 1, 'R'); // Align right

        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(255, 255, 255); // Set text color to white
        $this->Cell(0, 5, 'Westerville, OH 43081', 0, 1, 'R'); // Align right

        // Line break
        $this->Ln(22);
    }

    // Footer method
    public function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

/************************************ Function to generate PDF ***********************************************************/
function generate_pdf()
{
    // Check if the required POST data is set
    if (!isset($_POST['name']) || !isset($_POST['formType'])) {
        return; // Exit if required data is not present
    }

    // Create a new PDF instance
    $pdf = new PDF();
    $pdf->AddPage(); // Add the first page

    // Set font format and font-size for the body
    $pdf->SetFont('Arial', 'B', 20);
    $pdf->Cell(176, 5, 'Hello ' . htmlspecialchars($_POST['name']) . '!', 0, 0, 'C');
    $pdf->Ln(15); // Add space after "Welcome!"

    // Move the second text to the next line
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(176, 5, 'Here are your ' . htmlspecialchars($_POST['formType']) . ' basement features:', 0, 0, 'L');
    $pdf->Ln(8); // Add space after the title

    // Define features based on form type
    $features = [];
    if ($_POST['formType'] == 'standard') {
        $features = [
            'Spray black ceiling',
            'Board and batten walls',
            'Builder grade carpet (flooring credit available)',
            'Single switch control',
            '8-10 LED canned lights',
            '1 paint color',
            'Up to 3 White 6 panel doors and 3 trim'
        ];
    } else {
        $features = [
            'Drywall ceiling (black credit available)',
            'R 13 wall insulation',
            'Additional HVAC runs',
            'Unlimited switch zone controls',
            'Up to 16 LED canned lights',
            'One paint color, additional colors, optional',
            'Up to 5 doors Choice of styles',
            'Upgraded trim',
            'Upgraded carpet or LVP flooring'
        ];
    }

    // Print each feature
    $pdf->SetFont('Arial', '', 12);
    foreach ($features as $feature) {
        $pdf->Cell(0, 10, '* ' . $feature, 0, 1, 'L'); // Bullet point
    }

    // Add space before the next section
    $pdf->Ln(8);

    // Move the second text to the next line
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(176, 5, 'Here is your basement estimated cost', 0, 0, 'L');
    $pdf->Ln(10); // Add space after the second text

    // Set font format and font-size for the table
    $pdf->SetFont('Arial', 'B', 12);

    // Define the table header
    $header = ['Feature', 'Cost'];
    $data = [];

    // Prepare data based on form type
    if ($_POST['formType'] == 'standard') {
        $data = [
            ['Cost as per sqft', $_POST['squareftRate'] ? '$' . $_POST['squareftRate'] : 'NA'],
            ['Cost per ceiling height', $_POST['ceilingHeightRate'] ? '$' . $_POST['ceilingHeightRate'] : 'NA'],
            ['Cost as per bathroom preference', $_POST['bathroomTypeRate'] ? '$' . $_POST['bathroomTypeRate'] : 'NA'],
            ['Bathroom Drain Cost', $_POST['bathroomDrainsRate'] ? '$' . $_POST['bathroomDrainsRate'] : 'NA'],
            ['Cost as per kitchenette preference', $_POST['kitchenetteRate'] ? '$' . $_POST['kitchenetteRate'] : 'NA'],
            ['Kitchenette Drain Cost', $_POST['kitchenetteDrainsRate'] ? '$' . $_POST['kitchenetteDrainsRate'] : 'NA'],
            ['Total Cost', $_POST['totalRate'] ? '$' . $_POST['totalRate'] : 'NA'],
        ];
    } else {
        $data = [
            ['Cost as per sqft', $_POST['squareftRate'] ? '$' . $_POST['squareftRate'] : 'NA'],
            ['Cost per ceiling height', $_POST['ceilingHeightRate'] ? '$' . $_POST['ceilingHeightRate'] : 'NA'],
            ['Cost as per bathroom preference', $_POST['bathroomTypeRate'] ? '$' . $_POST['bathroomTypeRate'] : 'NA'],
            ['Bathroom Drain Cost', $_POST['bathroomDrainsRate'] ? '$' . $_POST['bathroomDrainsRate'] : 'NA'],
            ['Cost as per kitchenette preference', $_POST['kitchenetteRate'] ? '$' . $_POST['kitchenetteRate'] : 'NA'],
            ['Kitchenette Drain Cost', $_POST['kitchenetteDrainsRate'] ? '$' . $_POST['kitchenetteDrainsRate'] : 'NA'],
            ['Ceiling Type Cost', $_POST['ceilingTypeRate'] ? '$' . $_POST['ceilingTypeRate'] : 'NA'],
            ['Total Cost', $_POST['totalRate'] ? '$' . $_POST['totalRate'] : 'NA'],
        ];
    }

    // Set column widths
    $widths = [150, 40];

    // Create the table
    $pdf->SetFillColor(195, 195, 195);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetLineWidth(0.3);

    // Header
    foreach ($header as $i => $col) {
        $pdf->Cell($widths[$i], 10, $col, 1, 0, 'C', true);
    }
    $pdf->Ln();

    // Data
    $pdf->SetFont('Arial', '', 12);
    foreach ($data as $row) {
        foreach ($row as $i => $cell) {
            $pdf->Cell($widths[$i], 10, $cell, 1);
        }
        $pdf->Ln();
    }
    // Generate unique file name
    $bytes = random_bytes(5);
    $uniqueId = bin2hex($bytes);
    $fileName = 'mypdf_' . $uniqueId . '.pdf';
    $path = plugin_dir_path(__FILE__) . 'files/' . $fileName; // Save to the plugin's files directory

    // Close document and save to the server
    $pdf->Output($path, 'F');

    // Optionally, you can return the file path or URL for further use
    return $path; // Return the file path for reference
}

// Hook to handle the PDF generation
add_action('wp_ajax_generate_pdf', 'generate_pdf');

// Hook to enqueue scripts
add_action('wp_enqueue_scripts', 'enqueue_pdf_scripts');




$table_name = 'wp_estimation_records';

// Sanitize and validate input data
$name = sanitize_text_field($_POST['name']);
$email = sanitize_email($_POST['email']);
$phone = sanitize_text_field($_POST['phone']);
//$file_name = sanitize_file_name($fileName); // Ensure $fileName is defined
$squareftRate = absint($_POST['squareftRate']);
$ceilingHeightRate = absint($_POST['ceilingHeightRate']);
$bathroomTypeRate = absint($_POST['bathroomTypeRate']);
$bathroomDrainsRate = absint($_POST['bathroomDrainsRate']);
$kitchenetteRate = absint($_POST['kitchenetteRate']);
$kitchenetteDrainsRate = absint($_POST['kitchenetteDrainsRate']);
$ceilingTypeRate = absint($_POST['ceilingTypeRate']);
$totalRate = absint($_POST['totalRate']);

// Prepare the SQL statement
$result_check = $wpdb->query($wpdb->prepare(
    "INSERT INTO $table_name (name, email, phone, cost_as_per_sqt, cost_per_ceiling_height, cost_as_per_bathroom_preference, bathroom_drain_cost, cost_as_per_kitchenette_preference, kitchenette_drain_cost, ceiling_type_cost, total_cost) 
    VALUES (%s, %s, %s, %d, %d, %d, %d, %d, %d, %d, %d)",
    $name,
    $email,
    $phone,
    $squareftRate,
    $ceilingHeightRate,
    $bathroomTypeRate,
    $bathroomDrainsRate,
    $kitchenetteRate,
    $kitchenetteDrainsRate,
    $ceilingTypeRate,
    $totalRate
));

// Check for errors
if ($result_check === false) {
    echo $wpdb->last_error;
} else {
    echo "Record inserted successfully.";
}

// we have to verify that path exits or not if any error occur then we have to handle it
$attachmentFile = generate_pdf();
echo $attachmentFile;
if (file_exists($attachmentFile)) {
    echo "File path found successfully";

} else {
    echo "File path not found";
}

/************************************ Email Section ***********************************************************/

$admin_email = get_option('basement_estimator_email');

/*  email section  for user  */
send_custom_email($attachmentFile, $admin_email);

function send_custom_email($attachmentFile, $admin_email)
{
	
	
	
	
	$subject = "Quote for " .$_POST['name'];
    $body = "Hi " .$_POST['name']. ",<br><br>";
    $body .= 'Thank you for using the ReadyGo Remodeling Instant Estimator Tool! Attached, you’ll find your personalized estimate for your basement remodeling project based on the details you provided. <br><br>';
    $body .= 'This estimate is a great starting point, and we’d love to discuss your vision further to ensure everything is tailored to your needs. If you have any questions or would like to schedule a consultation give us a call at (614)-564-1700. <br><br>';
    $body .= 'We’re excited to help you transform your basement into a space you’ll love! <br><br>';
    $body .= 'Best regards, <br>';
    $body .= 'The ReadyGo Remodeling Team';
	
	

	$headers = array('Content-Type: text/html; charset=UTF-8');
	$headers .= 'From: '.$admin_email;
  	$headers .= "\r\nReply-To: ".$admin_email;
    $headers .= "\r\nContent-Type: text/html; charset=UTF-8";
    $headers .= "\r\nContent-Transfer-Encoding: 7bit";

    $email = $_POST['email'];

    // Send the email
    $result_check = wp_mail($email, $subject, $body, $headers, $attachmentFile);

    // Check the result and return a message
    if ($result_check) {
        echo "Email sent for user successfully";

    } else {
        echo "Something went wrong";
       // error_log("Failed to send email to $email"); // Log the error for debugging
    }
}

//  Call the function with the recipient's email



function send_custom_email_admin($attachmentFile, $admin_email)
{
    // Check if required POST data is set
    if (!isset($_POST['name'], $_POST['email'], $_POST['phone'])) {
        error_log("Required POST data is missing.");
        return;
    }

    $subject = "Quote for Admin";
		
    $body  = 'Hi Admin, <br><br>';
    $body .= 'The user ' . sanitize_text_field($_POST['name']) . ' recently requested a quote <br>';
    $body .= 'Below are the registered details of the user <br><br>';
    $body .= 'Name: ' . sanitize_text_field($_POST['name']) . "<br>";
    $body .= 'Email: ' . sanitize_email($_POST['email']) . "<br>";
    $body .= 'Phone no.: ' . sanitize_text_field($_POST['phone']) . "<br><br>";
    $body .= 'Kindly check the generated quote PDF attached. <br><br>';
    $body .= 'Best regards, <br>';
    $body .= 'The ReadyGo Remodeling Team';

    // Set up the headers
	$headers = array('Content-Type: text/html; charset=UTF-8');
    $headers .= 'From: ' . $admin_email;
	$headers .= "\r\nReply-To: " . sanitize_email($_POST['email']);
	$headers .= "\r\nContent-Type: text/html; charset=UTF-8";



    $to = $admin_email;

    // Send the email
    $result_check = wp_mail('ankush.swarnatek@gmail.com', $subject, $body, $headers, $attachmentFile);

    // Check the result and return a message
    if ($result_check) {
        echo "Email sent for admin successfully";
    } else {
        echo "Something went wrong";
        echo("Failed to send email to $to"); // Log the error for debugging
    }
}

//  Call the function with the recipient's email
send_custom_email_admin($attachmentFile, $admin_email);

/************************************ Attachment Deletion Section ***********************************************************/
function deleteAttachment($fileName){
    $status=unlink($fileName);
    if($status){
    echo "File deleted successfully";
    }else{
    echo "Sorry!";
    }
}

deleteAttachment($attachmentFile);