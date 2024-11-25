<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
function fetchWeatherData($location = "Hanoi") {
    $url = "https://wttr.in/{$location}?format=j1";
    $response = file_get_contents($url);
    return json_decode($response, true);
}

function getWeatherIcon($description) {
    $iconMap = [
        "Sunny" => "https://cdn.weatherapi.com/weather/64x64/day/113.png",
        "Clear" => "https://cdn.weatherapi.com/weather/64x64/day/113.png",
        "Partly cloudy" => "https://cdn.weatherapi.com/weather/64x64/day/116.png",
        "Cloudy" => "https://cdn.weatherapi.com/weather/64x64/day/119.png",
        "Overcast" => "https://cdn.weatherapi.com/weather/64x64/day/122.png",
        "Patchy rain nearby" => "https://cdn.weatherapi.com/weather/64x64/day/176.png",
        "Patchy snow nearby" => "https://cdn.weatherapi.com/weather/64x64/day/179.png",
        "Patchy sleet nearby" => "https://cdn.weatherapi.com/weather/64x64/day/182.png",
        "Patchy freezing drizzle nearby" => "https://cdn.weatherapi.com/weather/64x64/day/185.png",
        "Thundery outbreaks possible" => "https://cdn.weatherapi.com/weather/64x64/day/200.png",
        "Blowing snow" => "https://cdn.weatherapi.com/weather/64x64/day/227.png",
        "Blizzard" => "https://cdn.weatherapi.com/weather/64x64/day/230.png",
        "Fog" => "https://cdn.weatherapi.com/weather/64x64/day/248.png",
        "Freezing fog" => "https://cdn.weatherapi.com/weather/64x64/day/260.png",
        "Patchy light drizzle" => "https://cdn.weatherapi.com/weather/64x64/day/263.png",
        "Light drizzle" => "https://cdn.weatherapi.com/weather/64x64/day/266.png",
        "Freezing drizzle" => "https://cdn.weatherapi.com/weather/64x64/day/281.png",
        "Heavy freezing drizzle" => "https://cdn.weatherapi.com/weather/64x64/day/284.png",
        "Patchy light rain" => "https://cdn.weatherapi.com/weather/64x64/day/293.png",
        "Light rain" => "https://cdn.weatherapi.com/weather/64x64/day/296.png",
        "Moderate rain at times" => "https://cdn.weatherapi.com/weather/64x64/day/299.png",
        "Moderate rain" => "https://cdn.weatherapi.com/weather/64x64/day/302.png",
        "Heavy rain at times" => "https://cdn.weatherapi.com/weather/64x64/day/305.png",
        "Heavy rain" => "https://cdn.weatherapi.com/weather/64x64/day/308.png",
        "Light freezing rain" => "https://cdn.weatherapi.com/weather/64x64/day/311.png",
        "Moderate or heavy freezing rain" => "https://cdn.weatherapi.com/weather/64x64/day/314.png",
        "Light sleet" => "https://cdn.weatherapi.com/weather/64x64/day/317.png",
        "Moderate or heavy sleet" => "https://cdn.weatherapi.com/weather/64x64/day/320.png",
        "Patchy light snow" => "https://cdn.weatherapi.com/weather/64x64/day/323.png",
        "Light snow" => "https://cdn.weatherapi.com/weather/64x64/day/326.png",
        "Patchy moderate snow" => "https://cdn.weatherapi.com/weather/64x64/day/329.png",
        "Moderate snow" => "https://cdn.weatherapi.com/weather/64x64/day/332.png",
        "Patchy heavy snow" => "https://cdn.weatherapi.com/weather/64x64/day/335.png",
        "Heavy snow" => "https://cdn.weatherapi.com/weather/64x64/day/338.png",
        "Ice pellets" => "https://cdn.weatherapi.com/weather/64x64/day/350.png",
        "Light rain shower" => "https://cdn.weatherapi.com/weather/64x64/day/353.png",
        "Moderate or heavy rain shower" => "https://cdn.weatherapi.com/weather/64x64/day/356.png",
        "Torrential rain shower" => "https://cdn.weatherapi.com/weather/64x64/day/359.png",
        "Light sleet showers" => "https://cdn.weatherapi.com/weather/64x64/day/362.png",
        "Moderate or heavy sleet showers" => "https://cdn.weatherapi.com/weather/64x64/day/365.png",
        "Light snow showers" => "https://cdn.weatherapi.com/weather/64x64/day/368.png",
        "Moderate or heavy snow showers" => "https://cdn.weatherapi.com/weather/64x64/day/371.png",
        "Light showers of ice pellets" => "https://cdn.weatherapi.com/weather/64x64/day/374.png",
        "Moderate or heavy showers of ice pellets" => "https://cdn.weatherapi.com/weather/64x64/day/377.png",
        "Patchy light rain with thunder" => "https://cdn.weatherapi.com/weather/64x64/day/386.png",
        "Moderate or heavy rain with thunder" => "https://cdn.weatherapi.com/weather/64x64/day/389.png",
        "Patchy light snow with thunder" => "https://cdn.weatherapi.com/weather/64x64/day/392.png",
        "Moderate or heavy snow with thunder" => "https://cdn.weatherapi.com/weather/64x64/day/395.png",
        "Clear (Night)" => "https://cdn.weatherapi.com/weather/64x64/night/113.png",
        "Partly cloudy (Night)" => "https://cdn.weatherapi.com/weather/64x64/night/116.png",
        "Cloudy (Night)" => "https://cdn.weatherapi.com/weather/64x64/night/119.png",
        "Overcast (Night)" => "https://cdn.weatherapi.com/weather/64x64/night/122.png",
        "default" => "https://cdn-icons-png.flaticon.com/512/1116/1116453.png",
    ];


    return $iconMap[$description] ?? $iconMap['default'];
}

function generateWeatherTable($data) {
    $hours = $data['weather'][0]['hourly'];
    $table = "Hanoi, Vietnam - " . date('Y-m-d') . PHP_EOL;
    $table .= "<table>\n";
    $table .= "    <tr><th>Hour</th>";
    foreach ($hours as $hour) {
        $time = sprintf("%02d:00", $hour['time'] / 100);
        $table .= "<td>" . $time . "</td>";
    }
    $table .= "</tr>\n";

    $table .= "    <tr><th>Weather</th>";
    foreach ($hours as $hour) {
        $description = $hour['weatherDesc'][0]['value'] ?? "";
        $icon = getWeatherIcon($description);
        $table .= "<td><img src='{$icon}' alt='{$description}'></td>";
    }
    $table .= "</tr>\n";

    $table .= "    <tr><th>Condition</th>";
    foreach ($hours as $hour) {
        $table .= "<td>" . $hour['weatherDesc'][0]['value'] . "</td>";
    }
    $table .= "</tr>\n";

    $table .= "    <tr><th>Temperature</th>";
    foreach ($hours as $hour) {
        $table .= "<td>" . $hour['tempC'] . "°C</td>";
    }
    $table .= "</tr>\n";

    $table .= "    <tr><th>Wind</th>";
    foreach ($hours as $hour) {
        $table .= "<td>" . $hour['windspeedKmph'] . " kph</td>";
    }
    $table .= "</tr>\n";
    $table .= "</table>";
    return $table;
}
function isWeekend() {
    $now = new DateTime();
    return (date('N', strtotime($now->format('Y-m-d H:i:s'))) >= 6);
}

function generateGreetings($time): string
{
    $emojis = ["🌟", "🌈", "🔥", "💡", "🎉", "💻", "🚀", "🎶", "📅", "🍀", "👋", "🏝", "😴"];
    $randomEmoji = $emojis[array_rand($emojis)];
    $textGoodMorning = "Good morning " . $randomEmoji . PHP_EOL;
    $textGoodAfternoon = "Good afternoon " . $randomEmoji . PHP_EOL;
    $textGoodEvening = "Good evening " . $randomEmoji . PHP_EOL;
    $textGoodNight = "Good night " . $randomEmoji . PHP_EOL;
    $textHappyWeekend = "Happy weekend 🏝" . $randomEmoji . PHP_EOL;

    if (isWeekend()) {
        return $textHappyWeekend;
    }
    if ($time >= 4 && $time < 11) {
        return $textGoodMorning;
    }
    if ($time >= 11 && $time < 16) {
        return $textGoodAfternoon;
    }
    if ($time >= 16 && $time < 23) {
        return $textGoodEvening;
    }

    return $textGoodNight;
}
$filePath = 'README.md';
if (!file_exists($filePath)) {
    echo "File README.md not exists.\n";
    exit(1);
}

$lines = file($filePath);
$hour = date('H');
$newLine = generateGreetings($hour);

$quoteApi = "https://zenquotes.io/api/random";
$quoteResponse = @file_get_contents($quoteApi);
$quoteData = json_decode($quoteResponse, true);
$quoteSection = '';
$textWeather = "## 🌤️ Today's Weather Forecast in My Hometown";
$textQuote = "## 🌟 Quote of the day:";
if (isset($quoteData[0]['q'], $quoteData[0]['a'])) {
    $quote = $quoteData[0]['q'];
    $author = $quoteData[0]['a'];
    $quoteSection = $textQuote . PHP_EOL . $quote . PHP_EOL . "-" . $author;
}

$data = fetchWeatherData();
$table = generateWeatherTable($data);

$weatherInfo = PHP_EOL . $textWeather . PHP_EOL . PHP_EOL . $table;
$tempWeather = null;
foreach ($lines as $key => $line) {
    if (str_contains($line, $textWeather) !== false) {
        $tempWeather = $key;
    } else if (str_contains($line, 'Good')) {
        $lines[$key] = $newLine;
    } else if (str_contains($line, $textQuote)) {
        $lines[$key + 1] = '';
        $lines[$key + 2] = '';
        $lines[$key] = $quoteSection;
    }
    if ($tempWeather !== null && $key >= $tempWeather) {
        $lines[$key] = '';
    }
}
$lines[$tempWeather] = $weatherInfo . PHP_EOL;

file_put_contents($filePath, implode('', $lines));

echo "✅ [SUCCESS]: README.md has been updated. \n";

