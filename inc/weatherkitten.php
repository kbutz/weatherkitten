<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // the form has fields for q and units, temporarily passing "error" for error handling
    $q = htmlspecialchars($_GET["q"]);
    $units = htmlspecialchars($_GET["units"]);
    $error = htmlspecialchars($_GET["error"]);
    $img = htmlspecialchars($_GET["img"]);
}

/* geolocating for more reliable location info
 * geocoding help from http://www.andrew-kirkpatrick.com/2011/10/google-geocoding-api-with-php/
 * Google's geocode api returns an array of possible cities, with the the top hit being the first at [0]
 * Returns array with a portion of the data supplied by Google Maps
 */
function geocode($string){
 
    $string = str_replace (" ", "+", urlencode($string));
    $details_url = "https://maps.googleapis.com/maps/api/geocode/json?address=".$string."&key=". GEOCODE_API_KEY;
 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $details_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = json_decode(curl_exec($ch), true);
 
    // If Status Code is ZERO_RESULTS, OVER_QUERY_LIMIT, REQUEST_DENIED or INVALID_REQUEST
    if ($response['status'] != 'OK') {
    	return null;
    }
    $geometry = $response['results'][0]['geometry'];
    $formatted_address = $response['results'][0]['formatted_address'];
 
    $array = array(
        'latitude' => $geometry['location']['lat'],
        'longitude' => $geometry['location']['lng'],
        'location_type' => $geometry['location_type'],
        'address' => $formatted_address,
    );
    return $array;
}

// Retrieves json object as string, decoding to associative array
function get_weather($lat, $lng, $units) {
	$request = 'http://api.openweathermap.org/data/2.5/weather?lat=' . $lat . '&lon=' . $lng . '&units=' . $units . '&APPID=' . OWM_APP_ID . '&mode=json';
	$response  = file_get_contents($request);
	$weather_data  = json_decode($response, true);
	return $weather_data;
}

function print_city($data) {
	$city_data = '<h3> '. $data['address'] . '</h3>';
	return $city_data;
}

function print_temperature($data, $units) {
	if ($units == "imperial"){
 		$temp = '<p><span class="conditionTitle">Current temperature:</span><span class="condition"> ' . $data["main"]["temp"] . '&#176;F</span><span></p>';
	} else {
 		$temp = '<p><span class="conditionTitle">Current temperature:</span><span class="condition"> ' . $data["main"]["temp"] . '&#176;C</span><span></p>';
	}
	return $temp;
}

function print_weather_description($data) {
	$desc = '<p>Weather description: ' . $data["weather"][0]["description"] . '.</p>';
	return $desc;
}

function process_weather_category($data) {
	// set catgif;
	$catgif;
	//Check weather code for general description of weather using OpenWeatherMap's weather codes.
	// One of the JSON sections is an array, thus using array notation for weather[]
	// Blue skies, cat from http://giphy.com/gifs/26yiIkCrJxAqY
	if ($data["weather"][0]["id"] === 800 || ($data["weather"][0]["id"] <= 955 && $data["weather"][0]["id"] >=951)){
	 $catgif = 800; 	//'suncat.gif';
	}
	// light clouds, cat from: http://giphy.com/gifs/cH5VGNHfv46vS
	else if ($data["weather"][0]["id"] <= 804 && $data["weather"][0]["id"] >= 801){
	 $catgif = 801;		//'cloudcat.gif';
	}
	//severe or strange atmospheric conditions, cats from: http://giphy.com/gifs/halloween-betty-boop-boops-party-vXPvgpgPoKTRu
	else if ($data["weather"][0]["id"] <= 781 && $data["weather"][0]["id"] >= 730){
	 $catgif = 730;	//'strangecat.gif';
	}
	//mist or haze
	else if ($data["weather"][0]["id"] == 701 || $data["weather"][0]["id"] == 721 || $data["weather"][0]["id"] == 711){
	 $catgif = 701;	//'fogcat.jpg';
	}
	// snow, cat from http://giphy.com/gifs/wUgWRubJHS7Ac
	else if ($data["weather"][0]["id"] <= 622 && $data["weather"][0]["id"] >= 600){
	 $catgif = 600; //'snowcat.gif';
	}
	// rain, cat from http://giphy.com/gifs/cat-animal-weather-h5Bgk3GwjdDUc
	else if ($data["weather"][0]["id"] <= 531 && $data["weather"][0]["id"] >= 500){
	 $catgif = 500; //'raincat.gif';
	}
	// drizzle, cat from: http://theverybesttop10.com/cats-avoiding-the-rain/raincat
	else if ($data["weather"][0]["id"] <= 321 && $data["weather"][0]["id"] >= 300){
	 $catgif = 300; //'drizzlecat.jpg';
	}
	// thunderstorm
	else {
	 $catgif = 900; //'wetcat.jpg';
	}
	// Prints $catgif
	//echo '<div class="resultcat"><img src="http://www.kylebutz.com/weathercat/' . $catgif . '" class="resultcat"/></div>'; 
	return $catgif;
}

/*
 * Returns a random row matching the weather_code
 */

function get_weather_cat_img($weather_code) {
    require(BASE_URL . "inc/database.php");
    try {
        $results = $db->prepare("
            SELECT * 
            FROM cat_gifs
            WHERE weather_code = ?
            ORDER BY RAND()
            LIMIT 1
            ");
        $results->bindParam(1,$weather_code);
        $results->execute();
    } catch (Exception $e) {
        echo "Problem fetching weather cat";
        exit;
    }
    $fetch_cat_array = $results->fetchAll(PDO::FETCH_ASSOC);
    return $fetch_cat_array;
}

function get_all_cat_imgs() {
	require(BASE_URL . "inc/database.php");
	try {
        $results = $db->query("
            SELECT * 
            FROM cat_gifs
            ORDER BY weather_code
            ");
    } catch (Exception $e) {
        echo "Problem fetching weather cat";
        exit;
    }
    $fetch_cat_array = $results->fetchAll(PDO::FETCH_ASSOC);
    return $fetch_cat_array;
}

function get_single_cat_img($img_id) {
    require(BASE_URL . "inc/database.php");
    try {
    	$img_id = intval($img_id);
        $results = $db->prepare("
            SELECT * 
            FROM cat_gifs
            WHERE id = ?
            ");
        $results->bindParam(1,$img_id);
        $results->execute();
    } catch (Exception $e) {
        echo "Problem fetching weather cat";
        exit;
    }
    $fetch_cat_array = $results->fetchAll(PDO::FETCH_ASSOC);
    return $fetch_cat_array;
}
