<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ForecastController extends Controller
{
    public function getForecast(Request $request): JsonResponse
    {
        $apiKey = config('services.tomorrow_io.key');
        $baseURL = 'https://api.tomorrow.io/v4/weather/forecast';
        $response = Http::get($baseURL, [
            'location' => $request->search_param,
            'timesteps' => '1d',
            'apikey' => $apiKey,
        ]);

        if (!$response->successful()) {
            return response()->json(['error' => 'Weather data fetch failed try again later.'], 400);
        }

        $responseData = json_decode($response->body(), true);
        $location = $responseData['location']['name'];
        if (!Str::contains($location, 'नेपाल')) {
            return response()->json(['error' => 'Provided location does not lie within Nepal.'], 404);
        }
        $weatherData = ['location' => $location];
        foreach ($responseData['timelines']['daily'] as $index => $data) {
            if ($index > 4) break;

            $date = Carbon::parse($data['time']);
            $dailyResponse = [
                'condition' => $this->getWeatherCondition($data['values']),
                'date' => [
                    'day' => $date->format('l'),
                    'date' => $date->format('j M'),
                ],
                'temperature' => [
                    'min' => $data['values']['temperatureMin'],
                    'max' => $data['values']['temperatureMax'],
                    'avg' => $data['values']['temperatureAvg']
                ],
                'humidity' => $data['values']['humidityAvg'],
                'wind' => $data['values']['windSpeedAvg']
            ];
            $weatherData['forecast'][] = $dailyResponse;
        }
        return response()->json([
            'message' => 'Weather data fetched successfully.',
            'data' => $weatherData
        ]);
    }

    public function getWeatherCondition($data): array
    {
        switch (true) {
            case $data['rainIntensityAvg'] > 0.1:
                $condition = 'Rainy';
                break;
            case $data['snowIntensityAvg'] > 0.1:
                $condition = 'Snowy';
                break;
            case $data['windSpeedAvg'] > 25:
                $condition = 'Windy';
                break;
            case $data['weatherCodeMax'] === 2000:
                $condition = 'Thunderstorm';
                break;
            default:
                $cloudCoverAvg = $data['cloudCoverAvg'];
                if ($cloudCoverAvg < 10) {
                    $condition = 'Clear Sky';
                } elseif ($cloudCoverAvg <= 50) {
                    $condition = 'Partly Cloudy';
                } else {
                    $condition = 'Cloudy';
                }
        }
        $url = file_exists(public_path("svg/{$condition}.svg")) ? url("svg/{$condition}.svg") : url("svg/default.svg");

        return [
            'image' => $url,
            'text' => $condition,
        ];
    }
}
