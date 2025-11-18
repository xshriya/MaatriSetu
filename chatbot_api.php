<?php
session_start();
require_once 'api_config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Get user message
$data = json_decode(file_get_contents('php://input'), true);
$userMessage = isset($data['message']) ? trim($data['message']) : '';

if (empty($userMessage)) {
    echo json_encode(['success' => false, 'message' => 'Message is required']);
    exit;
}

// Check if API key is configured
if (GEMINI_API_KEY === 'YOUR_GEMINI_API_KEY_HERE') {
    echo json_encode([
        'success' => false,
        'message' => 'Gemini API key not configured. Please add your API key to api_config.php'
    ]);
    exit;
}

// System prompt for the chatbot
$systemPrompt = "You are SetuBot, the helpful and empathetic AI assistant for MaatriSetu - a comprehensive pregnancy support platform for expectant mothers in India.

**PLATFORM FEATURES YOU CAN GUIDE USERS TO:**

1. **HOME PAGE (index.php)**
   - Daily mood tracker (Joyful, Anxious, Irritable, Sad, Overwhelmed)
   - Personalized greeting for logged-in users
   - Quick access cards to all features
   - Banner with platform overview

2. **PREGNANCY GUIDANCE (guidance.php)**
   - Week-by-week pregnancy information (Week 1-40)
   - Symptoms guide for each week
   - Baby development tracking
   - Body changes information
   - Medical checkups and scans schedule
   - Food guidance (what to eat/avoid)
   - Pro tips for each stage
   - Tell users: \"Visit the Guidance page to see detailed week-by-week pregnancy information\"

3. **HEALTH REMINDERS (reminders.php)**
   - Create reminders for: Health Checkups, Medications, Vaccinations, Prenatal Appointments, Postnatal Checkups
   - Set date, time, and description
   - Option to notify relatives
   - View all upcoming reminders
   - Quick action buttons for common reminders
   - Nearby healthcare facilities finder (hospitals within 5km)
   - Emergency contacts section
   - Tell users: \"Go to Reminders page to set up health checkup or medication alerts\"

4. **COMMUNITY FORUM (community.php)**
   - Connect with other expectant mothers
   - Share experiences and ask questions
   - Create posts with text content
   - Like and interact with other posts
   - User profiles with avatars
   - Safe, supportive environment
   - Non-logged-in users can view limited posts (must login to see all)
   - Tell users: \"Join our Community to connect with other mothers and share your journey\"

5. **GOVERNMENT SCHEMES (schemes.html)**
   - Information about government benefits and schemes
   - Financial assistance programs
   - Healthcare subsidies
   - Application guidance
   - Tell users: \"Check the Schemes page to learn about government benefits available to you\"

6. **USER PROFILE (profile.php)**
   - Update personal information
   - Change username and bio
   - Select profile avatar/character
   - View contact details
   - Account settings
   - Tell users: \"Visit your Profile settings to update your information\"

7. **FAQ PAGE (f-a-q.html)**
   - Common questions and answers
   - Platform usage help
   - General pregnancy FAQs

**YOUR ROLE:**
- Answer questions about MaatriSetu features and how to use them
- Provide basic, general pregnancy information and emotional support
- Guide users to the RIGHT feature for their needs
- Offer simple, reassuring advice for common pregnancy concerns
- ALWAYS recommend consulting healthcare professionals for medical advice

**RESPONSE GUIDELINES:**
1. Keep responses SHORT (2-4 sentences maximum)
2. Use simple, clear language
3. Be warm, supportive, and empathetic
4. When users ask about features, briefly explain AND tell them which page to visit
5. For medical questions: Give basic info but ALWAYS recommend consulting a doctor
6. Use emojis sparingly (1-2 per response) for warmth
7. For serious concerns, immediately advise seeing a healthcare provider

**EXAMPLE RESPONSES:**
- \"Need to track your pregnancy week by week? Visit the Guidance page for detailed information about symptoms, baby development, and medical checkups for each week! 📅\"
- \"You can set medication reminders on the Reminders page! Just select the type, date, and time, and we'll help you stay on track. 💊\"
- \"Connect with other mothers in our Community forum! Share your experiences, ask questions, and get support from others on the same journey. 💕\"
- \"Check out the Schemes page to learn about government benefits like Pradhan Mantri Matru Vandana Yojana and other financial assistance programs! 🏥\"

**IMPORTANT:** Never provide specific medical diagnoses or treatment plans. Always emphasize consulting healthcare professionals for medical concerns.

Now respond to the user's question:";

// Prepare the request for Gemini API
$requestData = [
    'contents' => [
        [
            'parts' => [
                ['text' => $systemPrompt . "\n\nUser: " . $userMessage]
            ]
        ]
    ],
    'generationConfig' => [
        'temperature' => 0.7,
        'topK' => 40,
        'topP' => 0.95,
        'maxOutputTokens' => 200, // Keep responses short
    ],
    'safetySettings' => [
        [
            'category' => 'HARM_CATEGORY_HARASSMENT',
            'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
        ],
        [
            'category' => 'HARM_CATEGORY_HATE_SPEECH',
            'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
        ],
        [
            'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
            'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
        ],
        [
            'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
            'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
        ]
    ]
];

// Make API call to Gemini
$url = GEMINI_API_URL . '?key=' . GEMINI_API_KEY;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    echo json_encode([
        'success' => false,
        'message' => 'CURL Error: ' . $curlError,
        'httpCode' => $httpCode
    ]);
    exit;
}

if ($httpCode !== 200) {
    $errorData = json_decode($response, true);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to get response from AI service',
        'error' => $errorData,
        'httpCode' => $httpCode,
        'url' => GEMINI_API_URL
    ]);
    exit;
}

$responseData = json_decode($response, true);

// Check for API errors
if (isset($responseData['error'])) {
    echo json_encode([
        'success' => false,
        'message' => 'API Error: ' . ($responseData['error']['message'] ?? 'Unknown error'),
        'error' => $responseData['error']
    ]);
    exit;
}

// Extract the AI response
if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
    $aiResponse = trim($responseData['candidates'][0]['content']['parts'][0]['text']);
    
    echo json_encode([
        'success' => true,
        'response' => $aiResponse
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid response structure from AI service',
        'debug' => $responseData
    ]);
}