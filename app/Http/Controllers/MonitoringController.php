<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\Device;
use Carbon\Carbon;
use Excel;
use Auth;
use PDF;
use File;
use Response;
use Str;
use Illuminate\Support\Facades\Hash;
use \Yajra\Datatables\Datatables;



class MonitoringController extends Controller
{
/*
    public function __construct(){
    $this->middleware('auth');
    $this->middleware('leveldevice:administrator');
}
*/
       public function apiAll()
    {
        $model = new Device();
        $query = $model->select('device.*')
        ->get();
        // dd($query);
       return datatables()->of($query) 
                 /*   ->editColumn('kode', '@if($lampiran != null)<a target="_blank" href="' . url('files', $dt->id) .'">{{$lampiran}}</a> @endif')*/
            ->toJson();
    }
    public function index(Request $request)
    {
        $title = 'Monitoring Smart Lock Voltage';
        $label_page = 'Monitoring Smart Lock Voltage';


        return view('monitoring.index', compact('title', 'label_page'));
    }


}