<?php
header('Content-Type: application/json');
require_once 'conn.php';

function validateInput($data) {
    return trim(htmlspecialchars(strip_tags($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Validate and sanitize input
        $errors = [];
        
        // Required fields
        $required_fields = ['first_name', 'last_name', 'email', 'phone', 'business_type'];
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

        // Only validate business type if it's set
        if (isset($_POST['business_type']) && !empty(trim($_POST['business_type']))) {
            $valid_business_types = ['startup', 'small', 'medium', 'large'];
            if (!in_array($_POST['business_type'], $valid_business_types)) {
                $errors[] = "Invalid business type selected";
            }
        }

        if (!empty($errors)) {
            echo json_encode([
                "success" => false,
                "message" => "Validation errors",
                "errors" => $errors
            ]);
            exit;
        }

        // Sanitize and get form data
        $first_name = mysqli_real_escape_string($conn, validateInput($_POST['first_name']));
        $last_name = mysqli_real_escape_string($conn, validateInput($_POST['last_name']));
        $email = mysqli_real_escape_string($conn, validateInput($_POST['email']));
        $phone = mysqli_real_escape_string($conn, validateInput($_POST['phone']));
        $business_type = mysqli_real_escape_string($conn, validateInput($_POST['business_type']));

        // Insert into database using prepared statement
        $sql = "INSERT INTO leads (first_name, last_name, email, phone, business_type, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $phone, $business_type);
        
        if ($stmt->execute()) {
            // Send email notification
            $to = "your-email@lawgicalstation.com"; // Replace with your email
            $subject = "New Lead Registration";
            $email_message = "New lead registration:\n\n";
            $email_message .= "Name: $first_name $last_name\n";
            $email_message .= "Email: $email\n";
            $email_message .= "Phone: $phone\n";
            $email_message .= "Business Type: $business_type\n";
            
            $headers = "From: $email\r\n";
            $headers .= "Reply-To: $email\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();
            
            mail($to, $subject, $email_message, $headers);
            
            echo json_encode([
                "success" => true,
                "message" => "Thank you for registering! We will contact you shortly with your free benefits package."
            ]);
        } else {
            throw new Exception($stmt->error);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        error_log("Lead registration error: " . $e->getMessage());
        echo json_encode([
            "success" => false,
            "message" => "An error occurred while processing your registration. Please try again later."
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method"
    ]);
}
?>
