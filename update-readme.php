<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

function generateWeatherTable() {
    // API Key và URL
    $apiKey = getenv('API_WEATHER_KEY');
    $city = 'Hanoi';
    $units = 'metric';
    $apiUrl = "https://api.openweathermap.org/data/2.5/forecast?q=$city&units=$units&appid=$apiKey";

// Gửi yêu cầu API
    $response = file_get_contents($apiUrl);

    if ($response === false) {
        echo "Cannot get data in OpenWeatherMap.\n";
        exit(1);
    }

// Phân tích dữ liệu JSON
    $data = json_decode($response, true);

    if ($data['cod'] !== "200") {
        echo "Cannot get data in OpenWeatherMap.\n";
        exit(1);
    }

    $hours = [];
    $icons = [];
    $conditions = [];
    $temperatures = [];
    $humidities = [];
    $pressures = [];
    $winds = [];
    $rainChances = [];

    foreach ($data['list'] as $index => $entry) {
        if ($index >= 8) break;
        $hours[] = date('H:i', $entry['dt']);
        $icons[] = "https://openweathermap.org/img/wn/" . $entry['weather'][0]['icon'] . ".png";
        $conditions[] = $entry['weather'][0]['description'];
        $temperatures[] = round($entry['main']['temp']) . "°C";
        $humidities[] = $entry['main']['humidity'] . "%";
        $rainChance = isset($entry['pop']) ? ($entry['pop'] * 100) . "%" : "0%";
        $rainChances[] = $rainChance;
        $winds[] = $entry['wind']['speed'] . " kph";
    }

    $htmlTable = "<table border='1' style='border-collapse: collapse; width: 100%; text-align: center;'>\n";
    $htmlTable .= "<tr><th>Hour</th>";
    foreach ($hours as $hour) {
        $htmlTable .= "<td>$hour</td>";
    }
    $htmlTable .= "</tr>\n";

    $htmlTable .= "<tr><th>Weather</th>";
    foreach ($icons as $icon) {
        $htmlTable .= "<td><img src='$icon' alt='Weather icon' style='width: 50px; height: 50px;'></td>";
    }
    $htmlTable .= "</tr>\n";

    $htmlTable .= "<tr><th>Condition</th>";
    foreach ($conditions as $condition) {
        $htmlTable .= "<td>$condition</td>";
    }
    $htmlTable .= "</tr>\n";

    $htmlTable .= "<tr><th>Temperature</th>";
    foreach ($temperatures as $temp) {
        $htmlTable .= "<td>$temp</td>";
    }
    $htmlTable .= "</tr>\n";

    $htmlTable .= "<tr><th>Humidity</th>";
    foreach ($humidities as $humidity) {
        if ($humidity >= 40 && $humidity <= 70) {
            $humiditySafety = "Safe";
        } elseif ($humidity < 30) {
            $humiditySafety = "Too Dry";
        } else {
            $humiditySafety = "Too Humid";
        }
        $htmlTable .= "<td><p>$humidity</p><p>$humiditySafety</p></td>";
    }
    $htmlTable .= "</tr>\n";

    $htmlTable .= "<tr><th>Rain Probability</th>";
    foreach ($rainChances as $chance) $htmlTable .= "<td>$chance</td>";
    $htmlTable .= "</tr>\n";

    $htmlTable .= "<tr><th>Wind</th>";
    foreach ($winds as $wind) {
        $htmlTable .= "<td>$wind</td>";
    }
    $htmlTable .= "</tr>\n";

    $htmlTable .= "</table>";

    return $htmlTable;
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

$table = generateWeatherTable();

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

