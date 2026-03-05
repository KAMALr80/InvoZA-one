<?php

namespace App\Http\Controllers;

use App\Services\AdvancedSalesForecastService;
use Illuminate\Http\Request;

class ForecastController extends Controller
{
    protected $forecastService;

    public function __construct(AdvancedSalesForecastService $forecastService)
    {
        $this->forecastService = $forecastService;
    }

    /**
     * Show advanced forecast analysis page
     */
    public function index()
    {
        $forecast = $this->forecastService->generateForecast(30, 60);

        return view('forecast.advanced', compact('forecast'));
    }

    /**
     * Show forecast comparison view
     */
    public function comparison()
    {
        $forecast = $this->forecastService->generateForecast(30, 60);

        return view('forecast.comparison', compact('forecast'));
    }

    /**
     * Export forecast data as CSV
     */
    public function export()
    {
        $forecast = $this->forecastService->generateForecast(30, 60);

        $filename = 'sales_forecast_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $items = [];
        $items[] = ['Day', 'Date', 'Forecast', 'Upper Bound (95%)', 'Lower Bound (95%)'];

        foreach ($forecast['forecast'] as $day => $value) {
            $date = \Carbon\Carbon::now()->addDays($day);
            $items[] = [
                $day,
                $date->format('Y-m-d'),
                round($value, 2),
                round($forecast['confidence_intervals']['upper_bound'][$day], 2),
                round($forecast['confidence_intervals']['lower_bound'][$day], 2)
            ];
        }

        $callback = function() use ($items) {
            $file = fopen('php://output', 'w');
            foreach ($items as $item) {
                fputcsv($file, $item);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
