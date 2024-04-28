<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\Contact;
use Carbon\Carbon;
use Excel;
use Auth;
use PDF;
use File;
use Response;
use Str;
use Illuminate\Support\Facades\Hash;
use \Yajra\Datatables\Datatables;



class ContactController extends Controller
{
/*
    public function __construct(){
    $this->middleware('auth');
    $this->middleware('levelcontact:administrator');
}
*/
       public function apiAll()
    {
        $model = new Contact();
        $query = $model->select('contacts.*')
        ->get();
        // dd($query);
       return datatables()->of($query) 
                 /*   ->editColumn('kode', '@if($lampiran != null)<a target="_blank" href="' . url('files', $dt->id) .'">{{$lampiran}}</a> @endif')*/
            ->toJson();
    }
    public function index(Request $request)
    {
        $title = 'Data - Contact';
        $label_page = 'Data - Contact';
        $q = $request->input('q');

    	$limit = 50;
    	$num = num_row($request->input('page'), $limit);


        return view('contact.index', compact('num', 'q', 'title', 'label_page'));
    }


    public function create()
    {
        $title = 'Tambah - Contact';
        $label_page = 'Tambah - Contact';

        return view('contact.create', compact('title', 'label_page'));
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
      
             Contact::create([
            'nama' => $request['nama'],
            'chat_id' => $request['chat_id']
        ]);

        toastr()->success('Data has been saved successfully!', 'Congrats');
        return redirect()->route('contact.index');
        
             
    }
    public function destroy($id)
    {
        $data = Contact::findOrFail($id);
        $data->delete();

       
        toastr()->success('Berhasil dihapus!', 'Congrats');
        return back();
    }

    public function edit($id)
    {
        $title = 'Edit - Contact';
        $label_page = 'Edit - Contact';
        $data = Contact::where('id', $id)->first(); 
          
        return view('contact.edit', compact('data', 'title', 'label_page'));
    }




    public function update(Request $request, $id)
    {
            /*Karyawan::where('jabatan', $request->input('jabatan'))
                    ->update(['jabatan' => $request->input('jabatan')]);*/

     $d = Contact::where('id', $id)->first();

    /*  $request->validate([
            'lampiran' => 'mimes:pdf,xlsx,xls,jpg,png,doc,docx|max:2048',
        ]);

        $slug =  Str::slug($request->get('judul').'-'.$request->get('tanggal'), '-');

        if($request->lampiran) {            
            $fileName = 'Contact-'.rand(11111, 99999) . '.' .$request->lampiran->extension(); 
            $request->lampiran->move('files', $fileName);
        } else {
            $fileName = $d->lampiran;
        }*/

        Contact::where('id', $id)->update([
                    'nama' => $request->input('nama'),
                    'chat_id' => $request->input('chat_id'),
                ]);



        toastr()->success('Berhasil diubah.', 'Congrats');
        return redirect()->route('contact.index'); 
    }

}