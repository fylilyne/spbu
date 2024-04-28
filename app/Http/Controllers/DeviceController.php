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



class DeviceController extends Controller
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
        $title = 'Data - Device';
        $label_page = 'Data - Device';
        $q = $request->input('q');

    	$limit = 50;
    	$num = num_row($request->input('page'), $limit);


        return view('device.index', compact('num', 'q', 'title', 'label_page'));
    }


    public function create()
    {
        $title = 'Tambah - Device';
        $label_page = 'Tambah - Device';

        return view('device.create', compact('title', 'label_page'));
    }

    public function store(Request $request)
    {       
       /* $request->validate([
            'lampiran' => 'mimes:pdf,xlsx,xls,jpg,png,doc,docx|max:2048',
        ]);

        $slug =  Str::slug($request->get('judul').'-'.$request->get('tanggal'), '-');

        if($request->lampiran) {            
            $fileName = 'pengumuman-'.rand(11111, 99999) . '.' .$request->lampiran->extension(); 
            $request->lampiran->move('files', $fileName);
        } else {
            $fileName = null;
        }*/
        $request->validate([
            // Add validation rules here based on your requirements
        ]);
    
        // Generate a random string with 8 characters (letters and numbers)
        $deviceKey = Str::random(10);
    
        // Add the device key to the request data
        $requestData = $request->all();
        $requestData['device_key'] = $deviceKey;
    
        // Create the device with the updated request data
        Device::create($requestData);

        toastr()->success('Data has been saved successfully!', 'Congrats');
        return redirect()->route('device.index');
        
             
    }
    public function destroy($id)
    {
        $data = Device::findOrFail($id);
        $data->delete();

       
        toastr()->success('Berhasil dihapus!', 'Congrats');
        return back();
    }

    public function edit($id)
    {
        $title = 'Edit - Device';
        $label_page = 'Edit - Device';
        $data = Device::where('id', $id)->first(); 
          
        return view('device.edit', compact('data', 'title', 'label_page'));
    }




    public function update(Request $request, Device $device)
    {
            /*Karyawan::where('jabatan', $request->input('jabatan'))
                    ->update(['jabatan' => $request->input('jabatan')]);*/

    //  $d = Device::where('id', $id)->first();

    /*  $request->validate([
            'lampiran' => 'mimes:pdf,xlsx,xls,jpg,png,doc,docx|max:2048',
        ]);

        $slug =  Str::slug($request->get('judul').'-'.$request->get('tanggal'), '-');

        if($request->lampiran) {            
            $fileName = 'Device-'.rand(11111, 99999) . '.' .$request->lampiran->extension(); 
            $request->lampiran->move('files', $fileName);
        } else {
            $fileName = $d->lampiran;
        }*/

    $requestData = $request->except(['_token', '_method']);

    $device->update($requestData);

       // Device::where('id', $id)->update($request->all());



        toastr()->success('Berhasil diubah.', 'Congrats');
        return redirect()->route('device.index'); 
    }

}