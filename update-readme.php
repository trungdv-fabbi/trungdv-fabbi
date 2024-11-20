<?php
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

$city = 'HaNoi';
// URL API của wttr.in
$apiUrl = "https://wttr.in/$city?format=%C+%t+%h+%w&m";
$quoteApi = "https://zenquotes.io/api/random";
$quoteResponse = @file_get_contents($quoteApi);
$quoteData = json_decode($quoteResponse, true);
$quoteSection = '';
$textWeather = "## 🌤️ Weather conditions today in Ha Noi:";
$textQuote = "## 🌟 Quote of the day:";
if (isset($quoteData[0]['q'], $quoteData[0]['a'])) {
    $quote = $quoteData[0]['q'];
    $author = $quoteData[0]['a'];
    $quoteSection = $textQuote . PHP_EOL . $quote . PHP_EOL . "-" . $author;
}

// Gửi yêu cầu đến wttr.in
$response = @file_get_contents($apiUrl);

if ($response === false) {
    echo "Get infomation weather failed! .\n";
    exit(1);
}

// Phân tích thông tin trả về
// Ví dụ kết quả: "Trời quang +27°C 60% 3km/h"
$weatherInfo = $textWeather . PHP_EOL . "- " . $response;

foreach ($lines as $key => $line) {
    if (str_contains($line, $textWeather) !== false) {
        $lines[$key + 1] = '';
        $lines[$key] = $weatherInfo . PHP_EOL;
    } else if (str_contains($line, 'Good')) {
        $lines[$key] = $newLine;
    } else if (str_contains($line, $textQuote)) {
        $lines[$key + 1] = '';
        $lines[$key + 2] = '';
        $lines[$key] = $quoteSection;
    }
}

foreach ($lines as $key => $line) {
    if (str_contains($line, 'Good')) {
        $lines[$key] = $newLine;
        break;
    }
}
file_put_contents($filePath, implode('', $lines));

echo "✅ [SUCCESS]: README.md has been updated. \n";

