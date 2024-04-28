<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Exception;
use Illuminate\Http\Request;

use App\Models\LogSensor;
use App\Models\ReportBroadcast;
use App\Models\Device;
use App\Models\Contact;
use Carbon\Carbon;
use Excel;
use Auth;
use PDF;
use File;
use Response;
use Str;
use DB;
use Illuminate\Support\Facades\Hash;
use \Yajra\Datatables\Datatables;



class SensorController extends Controller
{
    private $token;

    public function __construct()
    {
        $this->token = "6964182663:AAHzkueEdHyy6utgpnUOLO6jElMFt1uaM0k";
    }

    public function index(Request $request)
    {
        $title = 'Monitoring Smart Lock Voltage';
        $label_page = 'Monitoring Smart Lock Voltage';
        $device_key = $request->device_key;

        $device = Device::where('device_key', $device_key)->first();


        //$max_voltage = $request->max_voltage;

        return view('sensor.index', compact('title', 'label_page', 'device', 'device_key'));
    }
    // public function updateDeviceStatus()
    // {
    //     $devices = Device::all();

    //     foreach ($devices as $device) {
    //         $lastLog = LogSensor::where('device_key', $device->device_key)
    //             ->orderBy('timestamp', 'desc')
    //             ->first();

    //         if (!$lastLog) {
    //             $device->update(['status' => 'offline']);
    //         }
    //     }

    //     return response()->json(['message' => 'Device status updated successfully']);
    // }

    public function updateDeviceStatus()
    {
        // Ambil semua perangkat
        $devices = Device::all();

        foreach ($devices as $device) {
            // Ambil log sensor terakhir untuk perangkat ini
            $lastLog = LogSensor::where('device_key', $device->device_key)
                ->orderBy('timestamp', 'desc')
                ->first();

            // Tentukan waktu maksimum untuk dianggap offline (5 menit)
            $offlineThreshold = Carbon::now()->subMinutes(5);

            // Periksa apakah perangkat memiliki log sensor terakhir
            if ($lastLog) {
                // Perangkat online jika log sensor terakhir lebih baru dari batas waktu offline
                $status = ($lastLog->timestamp > $offlineThreshold) ? 'online' : 'offline';
            } else {
                // Jika tidak ada log sensor, tandai sebagai offline
                $status = 'offline';
            }

            // Update status perangkat
            $device->update(['status' => $status]);
        }

        return response()->json(['message' => 'Device status updated successfully']);
    }
    public function sendTelegramMessageToContacts($message)
    {
        // Mendapatkan semua chat_id dari tabel contact
        $chatIds = Contact::pluck('chat_id')->toArray();

        // Mengirim pesan ke setiap chat_id
        foreach ($chatIds as $chatId) {
            sendTelegramMessage($this->token, $chatId, $message);
        }
    }
    public function getCountContact()
    {
        $countContact = Contact::count();

        return $countContact;
    }
    public function store(Request $request)
    {
        try {
            
        //     return response()->json([
        //     'success' => true,
        //     'message' => 'CEK DATA',
        //     'data' => $request->all()
        // ]);
        
            $validated = $request->validate([
                'voltage' => 'required',
                'device_key' => 'required',
            ]);

            $voltage = $request->voltage;
            $deviceKey = $request->device_key;
            $status_led = $request->status_led;
            $timestamp = now(); // Mendapatkan waktu saat ini
            // $this->updateOtherDevicesStatus();
            $device = Device::where('device_key', $deviceKey)->first();
            $nilai_input = 0;

            if ($device) {
                // Menyimpan data ke dalam tabel log_sensor jika voltage lebih besar dari 0
                if ($voltage > $device->min_voltage) {

                    LogSensor::create([
                        'voltage' => $voltage,
                        'device_key' => $deviceKey,
                        'timestamp' => $timestamp,
                        'status_led' => $status_led,
                    ]);

                    $nilai_input = 1;
                }

                if ($status_led == '1') {
                    Device::where('device_key', $deviceKey)->update(['status' => 'warning']);
                } elseif ($status_led == '0') {
                    Device::where('device_key', $deviceKey)->update(['status' => 'online']);
                }
                // Informasi untuk pengiriman pesan Telegram

                if (($voltage > $device->min_voltage) && ($device->telegram == 1)) {
                    // Mendapatkan data terbaru dari tabel log_sensor
                    $latestData = LogSensor::where('device_key', $deviceKey)
                        ->orderBy('timestamp', 'desc')
                        ->first();

                    if ($latestData) {
                        $latestLed = $latestData->status_led;
                        $latestVoltage = $latestData->voltage;
                        $latestTimestamp = strtotime($latestData->timestamp);

                        // Mendapatkan data notifikasi terakhir
                        $lastNotification = LogSensor::select('id', 'status', 'device_key')
                            ->selectRaw('MAX(last_notification_timestamp) AS last_notification_time')
                            ->where('device_key', $deviceKey)
                            ->groupBy('id', 'status', 'device_key')
                            ->where('last_notification_timestamp', function ($query) use ($deviceKey) {
                                $query->select(DB::raw('MAX(last_notification_timestamp)'))
                                    ->from('log_sensor')
                                    ->where('device_key', $deviceKey);
                            })
                            ->first();


                        if ($lastNotification) {
                            $lastNotificationTime = strtotime($lastNotification->last_notification_time);

                            $currentTime = time();

                            // Mengecek kondisi untuk mengirim pesan Telegram
                            if ($latestLed == '1' && ($currentTime - $lastNotificationTime) > 300) {
                                $device_name = $device->site;
                                $message = "Voltage Site $device_name naik senilai $latestVoltage pada " . date('Y-m-d H:i:s', $latestTimestamp);
                                $this->sendTelegramMessageToContacts($message);

                                // Update data sensor untuk mencatat waktu notifikasi terakhir dan status
                                LogSensor::create([
                                    'device_key' => $deviceKey,
                                    'last_notification_timestamp' => now(),
                                    'status' => 'high',
                                ]);

                                ReportBroadcast::create([
                                    'device_key' => $deviceKey,
                                    'status_voltage' => 'high',
                                    'broadcast_at' => now(),
                                    'broadcast_total' => $this->getCountContact()
                                ]);
                            }

                            if ($latestLed == '0' && ($currentTime - $lastNotificationTime) > 300) {
                                if ($lastNotification->status == 'high') {
                                    $device_name = $device->site;
                                    $message = "Voltage Site $device_name sudah normal $latestVoltage pada " . date('Y-m-d H:i:s', $latestTimestamp);

                                    $this->sendTelegramMessageToContacts($message);

                                    $id = $lastNotification->id;

                                    // Update data sensor untuk mengubah status menjadi 'normal'
                                    LogSensor::where('id', $id)->update([
                                        'status' => 'normal',
                                    ]);

                                    Device::where('device_key', $lastNotification->device_key)->update(['status' => 'online']);

                                    ReportBroadcast::create([
                                        'device_key' => $lastNotification->device_key,
                                        'status_voltage' => 'normal',
                                        'broadcast_at' => now(),
                                        'broadcast_total' => $this->getCountContact()
                                    ]);
                                }
                            }
                        } else {
                            if ($latestLed == '1') {

                                $device_name = $device->site;
                                $message = "Voltage Site $device_name naik senilai $latestVoltage pada " . date('Y-m-d H:i:s', $latestTimestamp);
                                $this->sendTelegramMessageToContacts($message);

                                // Update data sensor untuk mencatat waktu notifikasi terakhir dan status
                                LogSensor::create([
                                    'device_key' => $deviceKey,
                                    'last_notification_timestamp' => now(),
                                    'status' => 'high',
                                ]);

                                ReportBroadcast::create([
                                    'device_key' => $deviceKey,
                                    'status_voltage' => 'high',
                                    'broadcast_at' => now(),
                                    'broadcast_total' => $this->getCountContact()
                                ]);
                            }
                        }
                    }
                }
                if ($nilai_input == 1) {
                    $responseData = [
                        'message' => 'Data has been processed successfully.',
                        'device_key' => $deviceKey,
                        'status' => 'success',
                    ];
                } else {
                    $responseData = [
                        'message' => 'Data Voltage tidak ada!',
                        'device_key' => $deviceKey,
                        'status' => 'error',
                    ];
                }

                // Mengirim respons dalam bentuk JSON
                return response()->json($responseData);
            }
        } catch (Exception $e) {
            // Menangkap kesalahan dan memberikan respons yang sesuai
            $errorResponse = [
                'message' => 'An error occurred: ' . $e->getMessage(),
                'status' => 'error',
            ];

            return response()->json($errorResponse);
        }
    }
    // private function updateOtherDevicesStatus()
    // {
    //     $fiveMinutesAgo = now()->subMinutes(5);

    //     $onlineDeviceKeys = LogSensor::where('timestamp', '>=', $fiveMinutesAgo)
    //         ->distinct()
    //         ->pluck('device_key');

    //     Device::whereNotIn('device_key', $onlineDeviceKeys)
    //         ->update(['status' => 'offline']);
    // }
    public function apiJson(Request $request)
    {
        $deviceKey = $request->input('device_key');

        $query = LogSensor::select('timestamp', 'voltage')
            ->whereNotNull('voltage')
            ->orderByDesc('timestamp');


        $query->where('device_key', $deviceKey);


        $data = $query->limit(10)->get()->toArray();

        // Kembalikan data dalam format JSON
        return response()->json($data);
    }

    public function apiDonuts(Request $request)
    {
        $onlineCount = Device::where('status', 'online')->count();
        $offlineCount = Device::where('status', 'offline')->count();
        $warningCount = Device::where('status', 'warning')->count();
        $totalAlat = Device::count();

        return response()->json([
            'totalAlat' => $totalAlat,
            'online' => $onlineCount,
            'offline' => $offlineCount,
            'warning' => $warningCount,
            'percentageOnline' => ($totalAlat > 0) ? (($onlineCount / $totalAlat) * 100) : 0,
            'percentageOffline' => ($totalAlat > 0) ? (($offlineCount / $totalAlat) * 100) : 0,
            'percentageWarning' => ($totalAlat > 0) ? (($warningCount / $totalAlat) * 100) : 0,
        ]);
    }
}
