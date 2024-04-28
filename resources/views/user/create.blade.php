@section('css')
@stop
@section('js')
    <script type="text/javascript">
        $('#password, #confirm_password').on('keyup', function() {
            if ($('#password').val() == $('#confirm_password').val()) {
                document.getElementById("myBtn").disabled = false;
                $('#message').html('Matching').css('color', 'green');
            } else {
                $('#message').html('Not Matching').css('color', 'red');
                document.getElementById("myBtn").disabled = true;
            }
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#username').on('keyup', function() {
                var username = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('checkUsername') }}',
                    data: {
                        'username': username
                    },
                    success: function(data) {
                        if (data.exists) {
                            $('#message').html('Username sudah digunakan').css('color', 'red');
                            document.getElementById("myBtn").disabled = true;
                        } else {
                            $('#message').html('Username tersedia').css('color', 'green');
                            document.getElementById("myBtn").disabled = false;
                        }
                    }
                });
            });
        });
    </script>
@stop

@extends('layouts.app')

@section('content')


    <div class="page-heading">
        <h3>{{ isset($label_page) ? $label_page : 'Label Not Found' }}</h3>
    </div>


    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <form action="{{ route('user.store') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <div class="card-body">

                                    <div class="form-group">
                                        <label>Nama</label>
                                        <input type="text" class="form-control" name="name" required="" autofocus>
                                    </div>

                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" value="-" class="form-control" name="email"
                                            required="">
                                    </div>
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="username" class="form-control" name="username" id="username"
                                            required="">
                                        <span id='message'></span>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Level</label>
                                        <select class="form-control" name="level" required>
                                            <option value="admin">Admin</option>
                                            <option value="user">User</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="password"
                                            class="col-form-label text-md-right">{{ __('Password') }}</label>

                                        <input id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            required autocomplete="new-password">

                                    </div>

                                    <div class="form-group">
                                        <label for="confirm_password"
                                            class="col-form-label text-md-right">{{ __('Confirm Password') }}</label>
                                        <input id="confirm_password" type="password" class="form-control"
                                            name="password_confirmation" required autocomplete="new-password">

                                        <span id='message'></span>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-primary" id="myBtn">Simpan</button>
                                    <a href="{{ route('user.index') }}" class="btn btn-light float-xl-end">Kembali</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
