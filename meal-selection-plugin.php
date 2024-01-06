<?php
/*
Plugin Name: Meal Selection Form
Description: A custom plugin for handling meal selection form.
Version: 1.0
Author: Frankson IT Services
*/

// Enqueue scripts and styles
function meal_selection_enqueue_scripts() {
    wp_enqueue_style('meal-selection-style', plugins_url('/style.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'meal_selection_enqueue_scripts');

// Shortcode for displaying the form
function meal_selection_form_shortcode() {
    ob_start();
    include plugin_dir_path(__FILE__) . 'meal-selection-form.html';
    return ob_get_clean();
}
add_shortcode('meal_selection_form', 'meal_selection_form_shortcode');

// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure essential fields are not empty
    if (empty($_POST["customerName"]) || empty($_POST["customerEmail"]) || empty($_POST["customerPhone"])) {
        // Handle the case when essential fields are empty
        // You may display an error message or redirect the user back to the form
    } else {
        // Customize this section to process form data and send email
        $customerName = sanitize_text_field($_POST["customerName"]);
        $customerEmail = sanitize_email($_POST["customerEmail"]);
        $customerPhone = sanitize_text_field($_POST["customerPhone"]);
        $shippingAddress = sanitize_text_field($_POST["shippingAddress"]);
        $selectedMeals = isset($_POST["selectedMeals"]) ? array_map('sanitize_text_field', $_POST["selectedMeals"]) : array();
        $selectedDates = isset($_POST["selectedDates"]) ? array_map('sanitize_text_field', $_POST["selectedDates"]) : array();

        $to = "samuelfrank017@gmail.com"; // Replace with your email address
        $subject = "New Meal Order";

        // Construct the message
        $message = "Order Details:\n\n";
        $message .= "Customer Name: $customerName\n";
        $message .= "Email: $customerEmail\n";
        $message .= "Phone Number: $customerPhone\n";
        $message .= "Shipping Address: $shippingAddress\n";

        // Loop through selected dates
        for ($i = 0; $i < count($selectedDates); $i++) {
            $message .= "\nDay " . ($i + 1) . ":\n";

            // Check if the index exists in the selectedMeals array
            if (isset($selectedMeals[$i])) {
                $message .= "Selected Meals: " . $selectedMeals[$i] . "\n";
            } else {
                $message .= "No meal selected for this day\n";
            }

            $message .= "Selected Date: " . $selectedDates[$i] . "\n";
        }

        // Uncomment the following line to send the email (make sure to configure your server for sending emails)
        mail($to, $subject, $message);
    }
}
?>
