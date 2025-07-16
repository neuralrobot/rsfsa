<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

// Database configuration (update with your database details)
$db_config = [
    'host' => 'localhost',
    'dbname' => 'road_safety_db',
    'username' => 'your_username',
    'password' => 'your_password'
];

try {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    $required_fields = ['firstName', 'lastName', 'whatsappNumber', 'favoriteRoute', 'popiaConsent'];
    foreach ($required_fields as $field) {
        if (empty($input[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }
    
    // Validate POPIA consent
    if (!$input['popiaConsent']) {
        throw new Exception('POPIA consent is required');
    }
    
    // Validate phone number format
    if (!preg_match('/^\+27[0-9]{9,10}$/', $input['whatsappNumber'])) {
        throw new Exception('Invalid South African phone number format');
    }
    
    // Validate route selection
    $valid_routes = ['N1', 'N2', 'N3', 'N4', 'N12'];
    if (!in_array($input['favoriteRoute'], $valid_routes)) {
        throw new Exception('Invalid route selection');
    }
    
    // Connect to database
    $pdo = new PDO(
        "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset=utf8mb4",
        $db_config['username'],
        $db_config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
    
    // Check if phone number already exists
    $stmt = $pdo->prepare("SELECT id FROM whatsapp_subscriptions WHERE whatsapp_number = ?");
    $stmt->execute([$input['whatsappNumber']]);
    
    if ($stmt->fetch()) {
        // Update existing subscription
        $stmt = $pdo->prepare("
            UPDATE whatsapp_subscriptions 
            SET first_name = ?, last_name = ?, favorite_route = ?, 
                updated_at = NOW(), status = 'active'
            WHERE whatsapp_number = ?
        ");
        $stmt->execute([
            $input['firstName'],
            $input['lastName'],
            $input['favoriteRoute'],
            $input['whatsappNumber']
        ]);
        $subscription_id = $pdo->lastInsertId();
        $action = 'updated';
    } else {
        // Create new subscription
        $stmt = $pdo->prepare("
            INSERT INTO whatsapp_subscriptions 
            (first_name, last_name, whatsapp_number, favorite_route, popia_consent, 
             ip_address, user_agent, created_at, updated_at, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), 'active')
        ");
        $stmt->execute([
            $input['firstName'],
            $input['lastName'],
            $input['whatsappNumber'],
            $input['favoriteRoute'],
            1, // POPIA consent
            $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
            $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
        ]);
        $subscription_id = $pdo->lastInsertId();
        $action = 'created';
    }
    
    // Log the subscription for analytics
    $stmt = $pdo->prepare("
        INSERT INTO subscription_logs 
        (subscription_id, action, ip_address, user_agent, created_at) 
        VALUES (?, ?, ?, ?, NOW())
    ");
    $stmt->execute([
        $subscription_id,
        $action,
        $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
        $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
    ]);
    
    // Send WhatsApp confirmation (integrate with WhatsApp Business API)
    $whatsapp_result = sendWhatsAppConfirmation($input['whatsappNumber'], $input['firstName'], $input['favoriteRoute']);
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Subscription successful',
        'subscription_id' => $subscription_id,
        'action' => $action,
        'whatsapp_sent' => $whatsapp_result['success']
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

function sendWhatsAppConfirmation($phone_number, $first_name, $route) {
    // This function would integrate with WhatsApp Business API
    // For now, we'll simulate the confirmation
    
    $message = "Hi $first_name! 🚗\n\n";
    $message .= "Welcome to Road Safety Foundation WhatsApp alerts!\n\n";
    $message .= "You're now subscribed to receive traffic updates for the $route route.\n\n";
    $message .= "You'll receive:\n";
    $message .= "• Real-time traffic incidents\n";
    $message .= "• Road closures and diversions\n";
    $message .= "• Weather-related alerts\n";
    $message .= "• Safety tips and reminders\n\n";
    $message .= "Reply STOP to unsubscribe anytime.\n\n";
    $message .= "Drive safely! 🛡️";
    
    // In production, integrate with:
    // - WhatsApp Business API
    // - Twilio WhatsApp API
    // - 360Dialog
    // - Or other WhatsApp service providers
    
    // For demo purposes, log the message
    error_log("WhatsApp message to $phone_number: $message");
    
    return [
        'success' => true,
        'message_id' => 'MSG_' . time(),
        'phone_number' => $phone_number
    ];
}
?>
