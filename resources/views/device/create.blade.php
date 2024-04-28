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
              <form action="{{ route('device.store') }}" method="post" enctype="multipart/form-data">
                     {{ csrf_field() }}         
                     <div class="row">
          		<div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" class="form-control" name="nama" required="" autofocus>
                        </div>
    
                        <div class="form-group">
                            <label>Site</label>
                            <input type="text" class="form-control" name="site" required="">
                        </div>
    
                        <div class="form-group">
                            <label>SNSLV</label>
                            <input type="text" class="form-control" name="snslv" required="">
                        </div>
    
                        <div class="form-group">
                            <label>Witel</label>
                            <input type="text" class="form-control" name="witel" required="">
                        </div>
    
                        <div class="form-group">
                            <label>Network</label>
                            <input type="text" class="form-control" name="network" required="">
                        </div>
    
                        <div class="form-group">
                            <label>Tipe Dispenser</label>
                            <input type="text" class="form-control" name="tipe_dispenser" required="">
                        </div>
    
                        <div class="form-group">
                            <label>Total Dispenser</label>
                            <input type="number" class="form-control" name="total_dispenser" required="">
                        </div>
    
                        <div class="form-group">
                            <label>Dispenser Integrasi</label>
                            <input type="number" class="form-control" name="dispenser_integrasi" required="">
                        </div>
    
                        <div class="form-group">
                            <label>Dispenser Tidak Terintegrasi</label>
                            <input type="number" class="form-control" name="dispenser_tidak_terintegrasi" required="">
                        </div>
    
                        <div class="form-group">
                            <label>Lock Voltage</label>
                            <input type="text" class="form-control" name="lock_voltage" required="">
                        </div>
    
                        <div class="form-group">
                            <label>Min Voltage</label>
                            <input type="text" class="form-control" name="min_voltage" required="">
                        </div>
    
                        <div class="form-group">
                            <label>Max Voltage</label>
                            <input type="text" class="form-control" name="max_voltage" required="">
                        </div>
    
                        <div class="form-group">
                            <label>Broadcast Telegram</label>
                            <input type="radio" class="btn-check" name="telegram" id="success-outlined" autocomplete="off" checked="" value="1" >
                            <label class="btn btn-outline-success" for="success-outlined">Aktif</label>
    
                            <input type="radio" class="btn-check" name="telegram" id="danger-outlined" autocomplete="off" value="0">
                            <label class="btn btn-outline-danger" for="danger-outlined">Tidak Aktif</label>
                        </div>
                        </div>
    
                    </div>
                 <div class="card-footer">
                    <button class="btn btn-primary" id="myBtn">Simpan</button>
                    <a href="{{ route('device.index') }}" class="btn btn-light float-xl-end">Kembali</a>
                  </div>
                 </div>
  
                 </div>
                 </div>
                </form>
      
          </div>
          </div>
          </div>
 @endsection