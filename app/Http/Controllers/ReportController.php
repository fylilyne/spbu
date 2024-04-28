<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\Device;
use App\Models\LogSensor;
use App\Models\ReportBroadcast;
use Carbon\Carbon;
use Excel;
use Auth;
use PDF;
use File;
use Response;
use Str;
use Illuminate\Support\Facades\Hash;
use \Yajra\Datatables\Datatables;



class ReportController extends Controller
{
/*
    public function __construct(){
    $this->middleware('auth');
    $this->middleware('leveldevice:administrator');
}
*/
    public function profilspbu(Request $request)
    {
        $title = 'REPORT PROFIL SPBU';
        $label_page = 'REPORT PROFIL SPBU';


        return view('report.profilspbu', compact('title', 'label_page'));
    }

    public function voltage(Request $request)
    {
        $title = 'REPORT VOLTAGE';
        $label_page = 'REPORT VOLTAGE';

        $devices = Device::get();

        return view('report.voltage', compact('title', 'label_page', 'devices'));
    }

    public function broadcast(Request $request)
    {
        $title = 'REPORT BROADCAST';
        $label_page = 'REPORT BROADCAST';

        $devices = Device::get();

        return view('report.broadcast', compact('title', 'label_page', 'devices'));
    }
    public function apiVoltage(Request $request)
    {
        $query = LogSensor::with('device')->whereNotNull('log_sensor.voltage');
    
        // Filter berdasarkan tanggal (hari yang dipilih)
        if ($request->has('selected_date')) {
            $selectedDate = Carbon::parse($request->input('selected_date'))->toDateString();
            $query->whereDate('timestamp', $selectedDate);
        }
    
        // Filter berdasarkan nama (device yang dipilih)
        if ($request->get('nama')) {
            $query->whereHas('device', function ($query) use ($request) {
                $query->where('device_key', $request->input('nama'));
            });
        }
    
        $data = $query->get();
    
        $formattedData = $data->map(function ($item) {
            return [
                'nama' => $item->device->nama, 
                'voltage' => $item->voltage,
                'status_led' => $item->status_led,
                'timestamp' => date('H:i:s', strtotime($item->timestamp)), 
            ];
        });
    
        return DataTables::of($formattedData)->make(true);
    }
    
    public function apiBroadcast(Request $request)
    {
        $query = ReportBroadcast::with('device');
    
        // Filter berdasarkan tanggal (hari yang dipilih)
        if ($request->has('selected_date')) {
            $selectedDate = Carbon::parse($request->input('selected_date'))->toDateString();
            $query->whereDate('broadcast_at', $selectedDate);
        }
    
        // Filter berdasarkan nama (device yang dipilih)
        if ($request->get('nama')) {
            $query->whereHas('device', function ($query) use ($request) {
                $query->where('device_key', $request->input('nama'));
            });
        }
    
        $data = $query->get();
    
        $formattedData = $data->map(function ($item) {
            return [
                'nama' => $item->device->nama, 
                'witel' => $item->device->witel, 
                'status_voltage' => $item->status_voltage,
                'broadcast_total' => $item->broadcast_total,
                'broadcast_at' => date('Y-m-d H:i:s', strtotime($item->broadcast_at)), 
            ];
        });
    
        return DataTables::of($formattedData)->make(true);
    }

}