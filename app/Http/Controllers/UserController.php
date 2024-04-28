<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\User;
use Carbon\Carbon;
use Excel;
use Auth;
use PDF;
use File;
use Response;
use Str;
use Illuminate\Support\Facades\Hash;
use \Yajra\Datatables\Datatables;



class UserController extends Controller
{
/*
    public function __construct(){
    $this->middleware('auth');
    $this->middleware('leveluser:administrator');
}
*/
       public function apiAll()
    {
        $model = new User();
        $query = $model->select('users.*')
        ->get();
        // dd($query);
       return datatables()->of($query) 
                 /*   ->editColumn('kode', '@if($lampiran != null)<a target="_blank" href="' . url('files', $dt->id) .'">{{$lampiran}}</a> @endif')*/
            ->toJson();
    }
    public function index(Request $request)
    {
        $title = 'Data - User';
        $label_page = 'Data - User';
        $q = $request->input('q');

    	$limit = 50;
    	$num = num_row($request->input('page'), $limit);


        return view('user.index', compact('num', 'q', 'title', 'label_page'));
    }


    public function create()
    {
        $title = 'Tambah - User';
        $label_page = 'Tambah - User';

        return view('user.create', compact('title', 'label_page'));
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
        $usercheck = User::where('username', $request->username)->count();

        if($usercheck > 0) {
              toastr()->info('Duplicate username!', 'Oopss');
            return back();
        } else {
             User::create([
            'name' => $request['name'],
            'username' => $request['username'],
            'email' => $request['email'],
            'level' => $request['level'],
            'password' => Hash::make($request['password']),
        ]);

        toastr()->success('Data has been saved successfully!', 'Congrats');
        return redirect()->route('user.index');
        }
             
    }
    public function checkUsername(Request $request)
    {
        $username = $request->input('username');
        $exists = User::where('username', $username)->exists();
        return response()->json(['exists' => $exists]);
    }
    
    public function destroy($id)
    {
        $data = User::findOrFail($id);
        $data->delete();

       
        toastr()->success('Berhasil dihapus!', 'Congrats');
        return back();
    }

    public function edit($id)
    {
        $title = 'Edit - User';
        $label_page = 'Edit - User';
        $data = User::where('id', $id)->first(); 
          
        return view('user.edit', compact('data', 'title', 'label_page'));
    }

    public function editprofile($id)
    {
        $title = 'Edit - Profile';
        $label_page = 'Edit - Profile';
        $data = User::where('id', $id)->first(); 
        if(Auth::user()->id != $id) {
            return back();
        }
        return view('user.editprofile', compact('data', 'title', 'label_page'));
    }


    public function print($id)
    {

       /* $data = SuratPeringatan::leftjoin('sip_karyawan', 'sip_karyawan.id', '=', 'sip_sp.karyawan_id')
            ->select('sip_sp.*', 'sip_karyawan.nama_kary')->where('sip_sp.id', $id)->first();    
        return view('sp.print', compact('data'));
*/
    }

    public function update(Request $request, $id)
    {
            /*Karyawan::where('jabatan', $request->input('jabatan'))
                    ->update(['jabatan' => $request->input('jabatan')]);*/

     $d = User::where('id', $id)->first();

    /*  $request->validate([
            'lampiran' => 'mimes:pdf,xlsx,xls,jpg,png,doc,docx|max:2048',
        ]);

        $slug =  Str::slug($request->get('judul').'-'.$request->get('tanggal'), '-');

        if($request->lampiran) {            
            $fileName = 'User-'.rand(11111, 99999) . '.' .$request->lampiran->extension(); 
            $request->lampiran->move('files', $fileName);
        } else {
            $fileName = $d->lampiran;
        }*/

        if($request->password) {            
            $password = Hash::make($request['password']);
        } else {
            $password = $d->password;
        }

        User::where('id', $id)->update([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'level' => $request->input('level'),
                    'password' => $password,
                ]);



        toastr()->success('Berhasil diubah.', 'Congrats');
        return redirect()->route('user.index'); 
    }

    public function updateprofile(Request $request, $id)
    {

     $d = User::where('id', $id)->first();
       if(Auth::user()->id != $id) {
            return back();
        }

        if($request->password) {            
            $password = Hash::make($request['password']);
        } else {
            $password = $d->password;
        }

        User::where('id', $id)->update([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'password' => $password,
                ]);



        toastr()->success('Berhasil diubah.', 'Congrats');
        return back(); 
    }

}