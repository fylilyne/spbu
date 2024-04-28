@section('css')

@stop
@section('js')

<script type="text/javascript">
    $('#password, #confirm_password').on('keyup', function () {
    if ($('#password').val() == $('#confirm_password').val()) {
         document.getElementById("myBtn").disabled = false;
        $('#message').html('Matching').css('color', 'green');
    } else {
        $('#message').html('Not Matching').css('color', 'red');
         document.getElementById("myBtn").disabled = true;
    }
});
</script>
@stop

@extends('layouts.app')

@section('content')

<div class="page-heading">
    <h3>{{isset($label_page) ? $label_page : 'Label Not Found'}}</h3>
</div>

           <div class="page-content">
            <div class="row">    
              <div class="col-12">      
              <form action="{{ url('updateprofile', $data->id) }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                     <div class="row">
              <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                  <div class="card-body">
                    
               <div class="form-group">
                      <label>Nama</label>
                      <input type="text" class="form-control" name="name" autofocus required="" value="{{$data->name}}">
                  </div>
               
                  <div class="form-group">
                      <label>Email</label>
                      <input type="text" class="form-control" name="email" required="" value="{{$data->email}}">
                  </div>
                  <div class="form-group">
                      <label>Username</label>
                      <input type="username" class="form-control" name="username" disabled="" value="{{$data->username}}">
                  </div>


                        <div class="form-group">
                            <label for="password" class="col-form-label text-md-right">{{ __('Password') }} (Abaikan jika tidak ganti password)</label>

                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">

                        </div>

                        <div class="form-group">
                            <label for="confirm_password" class="col-form-label text-md-right">{{ __('Confirm Password') }}</label>
                                <input id="confirm_password" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                         
                            <span id='message'></span>
                        </div>

                  
                 </div>
                 <div class="card-footer">
                    <button class="btn btn-primary">Simpan</button>
                  </div>
                 </div>
  
                 </div>
                 </div>
                </form>
      
          </div>
          </div>
          </div>
 @endsection