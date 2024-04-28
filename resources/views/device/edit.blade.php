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
              <form action="{{ route('device.update', $data->id) }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('put') }}
                     <div class="row">
              <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                  <div class="card-body">

                  <div class="form-group">
                    <label>Device Key</label>
                    <input type="text" class="form-control" value="{{$data->device_key}}" disabled >
                </div>

                  <div class="form-group">
                    <label>Nama</label>
                    <input type="text" class="form-control" name="nama" required="" value="{{$data->nama}}" autofocus >
                </div>
             
                <div class="form-group">
                  <label>Site</label>
                  <input type="text" class="form-control" name="site" required="" value="{{$data->site}}" >
              </div>

              <div class="form-group">
                  <label>SNSLV</label>
                  <input type="text" class="form-control" name="snslv" required="" value="{{$data->snslv}}" >
              </div>

              <div class="form-group">
                  <label>Witel</label>
                  <input type="text" class="form-control" name="witel" required="" value="{{$data->witel}}" >
              </div>

              <div class="form-group">
                  <label>Network</label>
                  <input type="text" class="form-control" name="network" required="" value="{{$data->network}}" >
              </div>

              <div class="form-group">
                  <label>Tipe Dispenser</label>
                  <input type="text" class="form-control" name="tipe_dispenser" required="" value="{{$data->tipe_dispenser}}" >
              </div>

              <div class="form-group">
                  <label>Total Dispenser</label>
                  <input type="number" class="form-control" name="total_dispenser" required="" value="{{$data->total_dispenser}}" >
              </div>

              <div class="form-group">
                  <label>Dispenser Integrasi</label>
                  <input type="number" class="form-control" name="dispenser_integrasi" required="" value="{{$data->dispenser_integrasi}}" >
              </div>

              <div class="form-group">
                  <label>Dispenser Tidak Terintegrasi</label>
                  <input type="number" class="form-control" name="dispenser_tidak_terintegrasi" required="" value="{{$data->dispenser_tidak_terintegrasi}}" >
              </div>

              <div class="form-group">
                  <label>Lock Voltage</label>
                  <input type="text" class="form-control" name="lock_voltage" required="" value="{{$data->lock_voltage}}" >
              </div>

              <div class="form-group">
                  <label>Min Voltage</label>
                  <input type="text" class="form-control" name="min_voltage" required="" value="{{$data->min_voltage}}" >
              </div>

              <div class="form-group">
                  <label>Max Voltage</label>
                  <input type="text" class="form-control" name="max_voltage" required="" value="{{$data->max_voltage}}" >
              </div>

              <div class="form-group">
                  <label>Broadcast Telegram</label>
                  <input type="radio" class="btn-check" name="telegram" id="success-outlined" autocomplete="off" value="1" {{($data->telegram == '1' ? 'checked' : '')}} >
                  <label class="btn btn-outline-success" for="success-outlined">Aktif</label>

                  <input type="radio" class="btn-check" name="telegram" id="danger-outlined" autocomplete="off" value="0" {{($data->telegram == '0' ? 'checked' : '')}}>
                  <label class="btn btn-outline-danger" for="danger-outlined">Tidak Aktif</label>
              </div>

              <div class="form-group">
                  <label>Status</label>
                  <input type="radio" class="btn-check" name="status" autocomplete="off" value="online" {{($data->status == 'online' ? 'checked' : '')}}  id="status-online">
                  <label class="btn btn-outline-success" for="status-online">Online</label>

                  <input type="radio" class="btn-check" name="status" autocomplete="off" value="offline" {{($data->status == 'offline' ? 'checked' : '')}}  id="status-offline">
                  <label class="btn btn-outline-danger"  for="status-offline">Offline</label>

                  <input type="radio" class="btn-check" name="status" autocomplete="off" value="warning" {{($data->status == 'warning' ? 'checked' : '')}}  id="status-warning">
                  <label class="btn btn-outline-warning"  for="status-warning">Warning</label>
              </div>

                    

                  
                 </div>
                 <div class="card-footer">
                    <button class="btn btn-primary">Simpan</button>
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