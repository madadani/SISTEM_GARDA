<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewScan implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $scanData;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $scan;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Transaction $scan
     * @return void
     */
    public function __construct($scan)
    {
        $this->scan = $scan;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('scans');
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'new-scan';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        // Gunakan logic yang sama dengan ScanController untuk konsistensi
        $today = today();
        $yesterday = today()->subDay();
        
        $scansToday = \App\Models\Transaction::whereDate('scan_time', $today)
            ->where('status', 'CONFIRMED')
            ->whereHas('patient', function($q) {
                $q->whereNotNull('patient_name')
                  ->where('patient_name', '!=', '')
                  ->whereNotNull('patient_condition')
                  ->where('patient_condition', '!=', '');
            })->count();
            
        $scansYesterday = \App\Models\Transaction::whereDate('scan_time', $yesterday)
            ->where('status', 'CONFIRMED')
            ->whereHas('patient', function($q) {
                $q->whereNotNull('patient_name')
                  ->where('patient_name', '!=', '')
                  ->whereNotNull('patient_condition')
                  ->where('patient_condition', '!=', '');
            })->count();
        
        // Get recent activities data
        $recentActivities = $this->getRecentActivities();
        
        return [
            'scan' => [
                'id' => $this->scan->id,
                'driver_name' => $this->scan->driver->name,
                'scan_time' => $this->scan->scan_time->format('H:i'),
                'points' => $this->scan->points_awarded,
                'status' => $this->scan->status,
                'created_at' => $this->scan->created_at->toDateTimeString()
            ],
            'scans_today' => $scansToday,
            'scans_yesterday' => $scansYesterday,
            'increment' => 1,
            'recent_activities' => $recentActivities
        ];
    }
    
    /**
     * Get recent activities (same logic as DashboardController)
     */
    private function getRecentActivities()
    {
        $activities = [];
        
        // Ambil scan terbaru yang sudah memiliki pasien
        $recentScans = \App\Models\Transaction::with(['driver', 'patient'])
            ->where('status', 'CONFIRMED')
            ->whereHas('patient', function($q) {
                $q->whereNotNull('patient_name')
                  ->where('patient_name', '!=', '')
                  ->whereNotNull('patient_condition')
                  ->where('patient_condition', '!=', '');
            })
            ->latest()
            ->take(3)
            ->get();
            
        foreach ($recentScans as $scan) {
            $patientName = $scan->patient ? $scan->patient->patient_name : 'Pasien tidak ditemukan';
            $activities[] = [
                'title' => 'Scan berhasil',
                'subtitle' => ($scan->driver ? $scan->driver->name : 'Driver tidak ditemukan') . 
                            ' → ' . 
                            $patientName,
                'time' => $scan->created_at->diffForHumans(),
                'icon' => '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                'color' => 'bg-green-100',
                'timestamp' => $scan->created_at
            ];
        }
        
        // Ambil driver terbaru
        $recentDrivers = \App\Models\Driver::latest()->take(2)->get();
        foreach ($recentDrivers as $driver) {
            $activities[] = [
                'title' => 'Driver baru terdaftar',
                'subtitle' => $driver->name . ' • ID: ' . $driver->driver_id_card,
                'time' => $driver->created_at->diffForHumans(),
                'icon' => '<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>',
                'color' => 'bg-blue-100',
                'timestamp' => $driver->created_at
            ];
        }
        
        // Urutkan berdasarkan timestamp terbaru
        usort($activities, function($a, $b) {
            return $b['timestamp'] <=> $a['timestamp'];
        });
        
        // Ambil hanya 5 aktivitas terbaru
        return array_slice($activities, 0, 5);
    }
}
