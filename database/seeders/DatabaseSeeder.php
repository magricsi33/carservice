<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Car;
use App\Models\ServiceLog;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    public function run()
    {

        if (Client::count() === 0) {
            try {
                $clients = json_decode(file_get_contents(database_path('seeders/data/clients.json')), true);
                foreach ($clients as $client) {
                    try {
                        Client::create($client); // `insert` helyett `create`, így kezeli az egyedi kulcsokat
                    } catch (\Exception $e) {
                        Log::error("Hiba az ügyfél beszúrásakor: " . $e->getMessage(), ['data' => $client]);
                    }
                }
            } catch (\Exception $e) {
                Log::error("Hiba a clients.json beolvasásakor: " . $e->getMessage());
            }
        }

        if (Car::count() === 0) {
            $cars = json_decode(file_get_contents(database_path('seeders/data/cars.json')), true);
            foreach ($cars as $car) {
                Car::create($car);
            }
        }

        if (ServiceLog::count() === 0) {
            try {
                $logs = json_decode(file_get_contents(database_path('seeders/data/services.json')), true);
                foreach ($logs as $log) {
                    try {
                        ServiceLog::create($log);
                    } catch (\Exception $e) {
                        Log::error("Hiba a szerviznapló beszúrásakor: " . $e->getMessage(), ['data' => $log]);
                    }
                }
            } catch (\Exception $e) {
                Log::error("Hiba a service_logs.json beolvasásakor: " . $e->getMessage());
            }
        }
        
    }
}
