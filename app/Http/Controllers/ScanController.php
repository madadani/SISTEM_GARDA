<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ScanController extends Controller
{
    /**
     * Display a listing of pending scans
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['driver', 'patient']);

        // Filter by status
        $status = $request->get('status', 'ALL');
        if ($status && $status !== 'ALL') {
            $query->where('status', $status);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('driver', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('driver_id_card', 'like', "%{$search}%");
            });
        }

        $transactions = $query->orderBy('scan_time', 'desc')->paginate(10)->withQueryString();
        
        // Count pending untuk notifikasi
        $pendingCount = Transaction::pending()->count();

        return view('scan.index', compact('transactions', 'pendingCount', 'status'));
    }

    /**
     * Simulasi scan QR oleh driver (untuk testing)
     */
    public function simulateScan(Request $request)
    {
        $validated = $request->validate([
            'driver_id' => 'required|exists:drivers,id',
        ]);

        $transaction = Transaction::create([
            'transaction_id' => 'TRX-' . strtoupper(Str::random(10)),
            'driver_id' => $validated['driver_id'],
            'status' => 'PENDING',
            'scan_time' => now(),
            'points_awarded' => 1,
        ]);

        return redirect()->route('scan.index')->with('success', 'Scan berhasil! Menunggu konfirmasi admin.');
    }

    /**
     * Show confirmation form
     */
    public function confirm($id)
    {
        $transaction = Transaction::with('driver')->findOrFail($id);
        
        if ($transaction->status !== 'PENDING') {
            return redirect()->route('scan.index')->with('error', 'Transaksi sudah dikonfirmasi atau ditolak.');
        }

        return view('scan.confirm', compact('transaction'));
    }

    /**
     * Process confirmation and add patient data
     */
    public function processConfirm(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        
        if ($transaction->status !== 'PENDING') {
            return redirect()->route('scan.index')->with('error', 'Transaksi sudah dikonfirmasi atau ditolak.');
        }

        $validated = $request->validate([
            'patient_name' => 'nullable|string|max:255',
            'patient_condition' => 'nullable|string',
            'destination' => 'required|in:IGD,Ponek',
        ], [
            'destination.required' => 'Tujuan pasien wajib dipilih.',
            'destination.in' => 'Tujuan hanya boleh IGD atau Ponek.',
        ]);

        // Update transaction status
        $transaction->update([
            'status' => 'CONFIRMED',
            'confirmed_by_admin_id' => auth()->id(),
        ]);

        // Create patient record
        $transaction->patient()->create([
            'patient_name' => $validated['patient_name'],
            'patient_condition' => $validated['patient_condition'],
            'destination' => $validated['destination'],
            'arrival_time' => now(),
        ]);

        // Add points to driver
        $driver = $transaction->driver;
        $driver->increment('total_points', $transaction->points_awarded);

        return redirect()->route('scan.index')->with('success', 'Scan berhasil dikonfirmasi! Driver mendapat +' . $transaction->points_awarded . ' poin.');
    }

    /**
     * Reject scan
     */
    public function reject(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        
        if ($transaction->status !== 'PENDING') {
            return redirect()->route('scan.index')->with('error', 'Transaksi sudah dikonfirmasi atau ditolak.');
        }

        $transaction->update([
            'status' => 'REJECTED',
            'confirmed_by_admin_id' => auth()->id(),
        ]);

        return redirect()->route('scan.index')->with('warning', 'Scan ditolak. Tidak ada poin yang diberikan.');
    }

    /**
     * Get pending count for notification badge
     */
    public function getPendingCount()
    {
        return response()->json([
            'count' => Transaction::pending()->count()
        ]);
    }
}
