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
              <form action="{{ route('contact.store') }}" method="post" enctype="multipart/form-data">
                     {{ csrf_field() }}         
                     <div class="row">
          		<div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                  <div class="card-body">

                  <div class="form-group">
                      <label>Nama</label>
                      <input type="text" class="form-control" name="nama" required="" autofocus >
                  </div>
               
                  <div class="form-group">
                      <label>Chat ID Telegram</label>
                      <input type="text"  class="form-control" name="chat_id" required="" >
                  </div>


               
                 </div>
                 <div class="card-footer">
                    <button class="btn btn-primary" id="myBtn">Simpan</button>
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