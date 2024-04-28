@section('css')

@stop
@section('js')


@stop

@extends('layouts.app')

@section('content')

<div class="page-heading">
    <h3>{{isset($label_page) ? $label_page : 'Label Not Found'}}</h3>
</div>

           <div class="page-content">
            <div class="row">    
              <div class="col-12">      
              <form action="{{ route('contact.update', $data->id) }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('put') }}
                     <div class="row">
              <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                  <div class="card-body">

                  <div class="form-group">
                    <label>Nama</label>
                    <input type="text" class="form-control" name="nama" required="" value="{{$data->nama}}" autofocus >
                </div>
             
                <div class="form-group">
                    <label>Chat ID Telegram</label>
                    <input type="text"  class="form-control" name="chat_id" required=""value="{{$data->chat_id}}" >
                </div>

               

                    

                  
                 </div>
                 <div class="card-footer">
                    <button class="btn btn-primary">Simpan</button>
                    <a href="{{ route('contact.index') }}" class="btn btn-light float-xl-end">Kembali</a>
                  </div>
                 </div>
  
                 </div>
                 </div>
                </form>
      
          </div>
          </div>
          </div>
 @endsection