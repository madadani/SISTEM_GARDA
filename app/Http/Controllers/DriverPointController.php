<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DriverPointController extends Controller
{
    /**
     * Show driver points by ID card (public access, no auth)
     * This is accessed when driver scans QR code for viewing points only
     * Does NOT create transaction
     */
    public function showPoints($driverIdCard)
    {
        // Find driver by ID card
        $driver = Driver::where('driver_id_card', $driverIdCard)->firstOrFail();
        
        // Get latest transaction
        $latestTransaction = $driver->transactions()->latest('scan_time')->first();
        
        return view('driver.point', compact('driver', 'latestTransaction'));
    }

    /**
     * Scan barcode to record transaction when driver brings patient to hospital
     * This creates a new transaction automatically
     */
    public function scanTransaction($driverIdCard)
    {
        // Find driver by ID card
        $driver = Driver::where('driver_id_card', $driverIdCard)->firstOrFail();
        
        // Get latest transaction
        $latestTransaction = $driver->transactions()->latest('scan_time')->first();
        
        // Auto-create new transaction when barcode is scanned
        if (!$latestTransaction || $latestTransaction->created_at->diffInMinutes(now()) > 5) {
            // Create new transaction (only if last scan was more than 5 minutes ago to prevent spam)
            $transaction = Transaction::create([
                'transaction_id' => 'TRX-' . strtoupper(Str::random(10)),
                'driver_id' => $driver->id,
                'status' => 'PENDING',
                'scan_time' => now(),
                'points_awarded' => 1,
            ]);
            
            $latestTransaction = $transaction;
        }
        
        return view('driver.scan-success', compact('driver', 'latestTransaction'));
    }
}
