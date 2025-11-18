<?php
session_start();
require_once 'api_config.php';

// Enable CORS and set content type
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'User not logged in. Please login to search hospitals.']);
    exit;
}

// Database connection
require_once 'database.php';
try {
    $conn = getDatabaseConnection();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$userId = $_SESSION['user_id'];

// Get user's address
$stmt = $conn->prepare("SELECT address FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

if (!$userData || empty($userData['address'])) {
    echo json_encode(['success' => false, 'message' => 'User address not found']);
    exit;
}

$userAddress = $userData['address'];

// Function to get coordinates from address using Google Geocoding API
function getCoordinatesFromAddress($address) {
    if (GOOGLE_GEOCODING_API_KEY === 'YOUR_GOOGLE_GEOCODING_API_KEY_HERE') {
        // Fallback: return approximate coordinates for demo
        return ['lat' => 19.0760, 'lng' => 72.8777]; // Mumbai coordinates
    }
    
    $url = GOOGLE_GEOCODING_URL . '?' . http_build_query([
        'address' => $address,
        'key' => GOOGLE_GEOCODING_API_KEY
    ]);
    
    $response = @file_get_contents($url);
    if ($response === false) {
        return ['lat' => 19.0760, 'lng' => 72.8777]; // Mumbai fallback
    }
    
    $data = json_decode($response, true);
    
    if ($data && $data['status'] === 'OK' && !empty($data['results'])) {
        $location = $data['results'][0]['geometry']['location'];
        return ['lat' => $location['lat'], 'lng' => $location['lng']];
    }
    
    return null;
}

// Function to search hospitals using Google Places API
function searchHospitalsGoogle($lat, $lng, $radius = DEFAULT_SEARCH_RADIUS) {
    if (GOOGLE_PLACES_API_KEY === 'YOUR_GOOGLE_PLACES_API_KEY_HERE') {
        return null; // API key not configured
    }
    
    $url = GOOGLE_PLACES_NEARBY_URL . '?' . http_build_query([
        'location' => $lat . ',' . $lng,
        'radius' => $radius,
        'type' => 'hospital',
        'key' => GOOGLE_PLACES_API_KEY
    ]);
    
    $response = @file_get_contents($url);
    if ($response === false) {
        return null;
    }
    
    $data = json_decode($response, true);
    
    if ($data && $data['status'] === 'OK') {
        $hospitals = [];
        foreach ($data['results'] as $place) {
            $hospitalLat = $place['geometry']['location']['lat'];
            $hospitalLng = $place['geometry']['location']['lng'];
            $hospitalName = $place['name'];
            $hospitalAddress = $place['vicinity'] ?? $place['formatted_address'] ?? 'Address not available';
            
            // Determine if it's a government hospital
            $isGovernment = (
                stripos($hospitalName, 'government') !== false ||
                stripos($hospitalName, 'govt') !== false ||
                stripos($hospitalName, 'public') !== false ||
                stripos($hospitalName, 'municipal') !== false ||
                stripos($hospitalName, 'district') !== false ||
                stripos($hospitalName, 'primary health') !== false ||
                stripos($hospitalName, 'community health') !== false
            );
            
            $hospitals[] = [
                'name' => $hospitalName,
                'address' => $hospitalAddress,
                'rating' => $place['rating'] ?? 'N/A',
                'type' => $isGovernment ? 'Government' : 'Private',
                'distance' => calculateDistance($lat, $lng, $hospitalLat, $hospitalLng),
                'phone' => 'Contact via Google',
                'services' => 'General Healthcare, Emergency Services',
                'lat' => $hospitalLat,
                'lng' => $hospitalLng,
                'place_id' => $place['place_id'] ?? null,
                'google_maps_url' => "https://www.google.com/maps/search/?api=1&query=" . urlencode($hospitalName . " " . $hospitalAddress)
            ];
        }
        return $hospitals;
    }
    
    return null;
}

// Function to search hospitals using OpenStreetMap Overpass API (Free alternative)
function searchHospitalsOSM($lat, $lng, $radius = 5000) {
    $radiusKm = $radius / 1000;
    
    $query = "[out:json][timeout:25];
    (
      node[\"amenity\"=\"hospital\"](around:$radius,$lat,$lng);
      node[\"amenity\"=\"clinic\"](around:$radius,$lat,$lng);
      node[\"amenity\"=\"doctors\"](around:$radius,$lat,$lng);
    );
    out body;";
    
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => 'data=' . urlencode($query)
        ]
    ]);
    
    $response = file_get_contents(OVERPASS_API_URL, false, $context);
    
    if ($response === false) {
        return null;
    }
    
    $data = json_decode($response, true);
    
    if (isset($data['elements'])) {
        $hospitals = [];
        foreach ($data['elements'] as $element) {
            if (isset($element['tags']['name'])) {
                $hospitals[] = [
                    'name' => $element['tags']['name'],
                    'address' => $element['tags']['addr:full'] ?? 'Address not available',
                    'type' => ucfirst($element['tags']['amenity'] ?? 'Healthcare'),
                    'distance' => calculateDistance($lat, $lng, $element['lat'], $element['lon']),
                    'phone' => $element['tags']['phone'] ?? 'Not available',
                    'services' => $element['tags']['healthcare'] ?? 'General Healthcare'
                ];
            }
        }
        return $hospitals;
    }
    
    return null;
}

// Function to calculate distance between two coordinates
function calculateDistance($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371; // Earth's radius in kilometers
    
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    
    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    
    $distance = $earthRadius * $c;
    return round($distance, 1) . ' km';
}

// Function to get fallback hospitals (when APIs fail)
function getFallbackHospitals($city = 'Mumbai') {
    $hospitals = [
        [
            'name' => 'Government General Hospital',
            'type' => 'Government',
            'address' => 'Main Road, ' . $city,
            'phone' => '+91-XXXXXXXXXX',
            'services' => 'General Medicine, Gynecology, Emergency',
            'distance' => '2.3 km'
        ],
        [
            'name' => 'Primary Health Center',
            'type' => 'Government',
            'address' => 'Health Center Road, ' . $city,
            'phone' => '+91-XXXXXXXXXX',
            'services' => 'Basic Healthcare, Vaccination, Maternal Care',
            'distance' => '1.8 km'
        ],
        [
            'name' => 'Community Health Center',
            'type' => 'Community',
            'address' => 'Community Area, ' . $city,
            'phone' => '+91-XXXXXXXXXX',
            'services' => 'Family Planning, Prenatal Care',
            'distance' => '3.5 km'
        ],
        [
            'name' => 'District Hospital',
            'type' => 'Government',
            'address' => 'Hospital Road, ' . $city,
            'phone' => '+91-XXXXXXXXXX',
            'services' => 'Specialized Care, Surgery, Maternity Ward',
            'distance' => '5.2 km'
        ]
    ];
    
    // Add Google Maps URLs to fallback hospitals
    foreach ($hospitals as &$hospital) {
        $hospital['google_maps_url'] = "https://www.google.com/maps/search/?api=1&query=" . urlencode($hospital['name'] . " " . $hospital['address']);
    }
    
    return $hospitals;
}

// Main execution
try {
    // Get coordinates from user address
    $coordinates = getCoordinatesFromAddress($userAddress);
    
    if (!$coordinates) {
        throw new Exception('Could not geocode address');
    }
    
    $lat = $coordinates['lat'];
    $lng = $coordinates['lng'];
    
    // Try Google Places API first
    $hospitals = searchHospitalsGoogle($lat, $lng);
    
    // If Google API fails, try OpenStreetMap
    if (!$hospitals) {
        $hospitals = searchHospitalsOSM($lat, $lng);
    }
    
    // If both APIs fail, use fallback data
    if (!$hospitals || empty($hospitals)) {
        $addressParts = explode(',', $userAddress);
        $city = trim(end($addressParts));
        $hospitals = getFallbackHospitals($city);
    }
    
    // Sort hospitals: Government first, then by distance
    usort($hospitals, function($a, $b) {
        // First priority: Government hospitals
        if ($a['type'] === 'Government' && $b['type'] !== 'Government') {
            return -1;
        }
        if ($b['type'] === 'Government' && $a['type'] !== 'Government') {
            return 1;
        }
        
        // Second priority: Distance (extract numeric value)
        $distanceA = (float) str_replace(' km', '', $a['distance']);
        $distanceB = (float) str_replace(' km', '', $b['distance']);
        
        return $distanceA <=> $distanceB;
    });
    
    // Limit results but keep all for "view more" functionality
    $allHospitals = $hospitals;
    $hospitals = array_slice($hospitals, 0, MAX_RESULTS);
    
    echo json_encode([
        'success' => true,
        'hospitals' => $hospitals,
        'user_location' => ['lat' => $lat, 'lng' => $lng],
        'search_address' => $userAddress
    ]);
    
} catch (Exception $e) {
    // Return fallback data on any error
    $addressParts = explode(',', $userAddress);
    $city = trim(end($addressParts));
    $hospitals = getFallbackHospitals($city);
    
    echo json_encode([
        'success' => true,
        'hospitals' => $hospitals,
        'user_location' => null,
        'search_address' => $userAddress,
        'note' => 'Using fallback data due to API limitations'
    ]);
}

$conn->close();
?>