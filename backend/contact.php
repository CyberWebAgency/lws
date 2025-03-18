<?php
header('Content-Type: application/json');
require_once 'conn.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

function validateInput($data) {
    return trim(htmlspecialchars(strip_tags($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Debug: Log the raw POST data
        error_log("Raw POST data: " . print_r($_POST, true));
        
        // Validate and sanitize input
        $errors = [];
        
        // Required fields
        $required_fields = ['full_name', 'email', 'phone', 'service_required', 'message'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                $errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required";
            }
        }

        // Only validate email and phone if they are set
        if (isset($_POST['email']) && !empty(trim($_POST['email']))) {
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format";
            }
        }

        if (isset($_POST['phone']) && !empty(trim($_POST['phone']))) {
            if (!preg_match("/^[0-9]{10}$/", $_POST['phone'])) {
                $errors[] = "Phone number must be 10 digits";
            }
        }

        if (!empty($errors)) {
            echo json_encode([
                "success" => false,
                "message" => "Validation errors",
                "errors" => $errors,
                "debug" => ["post_data" => $_POST] // Include POST data in response for debugging
            ]);
            exit;
        }

        // Sanitize and get form data
        $full_name = mysqli_real_escape_string($conn, validateInput($_POST['full_name']));
        $email = mysqli_real_escape_string($conn, validateInput($_POST['email']));
        $phone = mysqli_real_escape_string($conn, validateInput($_POST['phone']));
        $service_required = mysqli_real_escape_string($conn, validateInput($_POST['service_required']));
        $message = mysqli_real_escape_string($conn, validateInput($_POST['message']));
        $consent = isset($_POST['consent']) ? 1 : 0;

        // Debug: Log the sanitized data
        error_log("Sanitized data: " . print_r([
            'full_name' => $full_name,
            'email' => $email,
            'phone' => $phone,
            'service_required' => $service_required,
            'message' => $message,
            'consent' => $consent
        ], true));

        // Insert into database
        $sql = "INSERT INTO contacts (full_name, email, phone, service_required, message, consent, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $full_name, $email, $phone, $service_required, $message, $consent);
        
        if ($stmt->execute()) {
            // Send email notification
            $to = "your-email@lawgicalstation.com"; // Replace with your email
            $subject = "New Contact Form Submission";
            $email_message = "New contact form submission:\n\n";
            $email_message .= "Name: $full_name\n";
            $email_message .= "Email: $email\n";
            $email_message .= "Phone: $phone\n";
            $email_message .= "Service: $service_required\n";
            $email_message .= "Message: $message\n";
            
            $headers = "From: $email\r\n";
            $headers .= "Reply-To: $email\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();
            
            mail($to, $subject, $email_message, $headers);
            
            echo json_encode([
                "success" => true, 
                "message" => "Thank you for contacting us. We will get back to you soon!"
            ]);
        } else {
            throw new Exception($stmt->error);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        error_log("Contact form error: " . $e->getMessage());
        echo json_encode([
            "success" => false, 
            "message" => "An error occurred while processing your request. Please try again later.",
            "debug" => ["error" => $e->getMessage()] // Include error message in response for debugging
        ]);
    }
} else {
    echo json_encode([
        "success" => false, 
        "message" => "Invalid request method"
    ]);
}
?> 