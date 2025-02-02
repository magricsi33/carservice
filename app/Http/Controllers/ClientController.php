<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return response()->json(Client::all());
    }

    public function getClientCars($client_id)
    {
        $cars = \App\Models\Car::where('client_id', $client_id)->orderBy('id', 'asc')->with('serviceLogs', 'client')->get();

        return response()->json($cars);
    }

    public function getCarServiceLogs($car_id)
    {
        return response()->json(
            \App\Models\ServiceLog::where('car_id', $car_id)
                ->orderBy('lognumber', 'desc')
                ->with('car')
                ->get()
        );
    }

    public function searchClient(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string',
            'idcard' => 'nullable|alpha_num',
        ]);

        if (!$request->name && !$request->idcard) {
            return response()->json(['error' => 'Legalább az egyik mezőt ki kell tölteni.'], 422);
        }

        if ($request->name && $request->idcard) {
            return response()->json(['error' => 'Csak egy mezőt tölthetsz ki.'], 422);
        }

        $query = Client::query();

        if ($request->idcard) {
            $query->where('idcard', $request->idcard);
        } else {
            $query->where('name', 'LIKE', "%{$request->name}%");
        }

        $clients = $query->get();

        if ($clients->count() > 1) {
            return response()->json(['error' => 'Túl sok találat. Pontosítsd a keresést.'], 422);
        } elseif ($clients->count() === 0) {
            return response()->json(['error' => 'Nincs találat.'], 404);
        }

        $client = $clients->first();
        $carCount = $client->cars()->count();
        $serviceCount = \App\Models\ServiceLog::whereIn('car_id', $client->cars()->pluck('id'))->count();

        return response()->json([
            'id' => $client->id,
            'name' => $client->name,
            'idcard' => $client->idcard,
            'car_count' => $carCount,
            'service_count' => $serviceCount,
        ]);
    }
}
