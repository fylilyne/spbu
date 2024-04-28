@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Function to update data
    function updateData() {
        // Ambil data dari controller
        $.ajax({
            url: '{{route("get_donut_chart")}}',
            method: 'GET',
            success: function (data) {
                // Set default values if data is not available
                data.online = data.online || 0;
                data.offline = data.offline || 0;
                data.warning = data.warning || 0;
                data.totalAlat = data.totalAlat || 0;

                // Tampilkan informasi total alat, persentase online, offline, dan warning
                $('#totalOnline').text(data.online);
                $('#totalOffline').text(data.offline);
                $('#totalWarning').text(data.warning);
                $('#totalAlat').text(data.totalAlat);
            },
            error: function (error) {
                console.error(error);
            }
        });

        $.ajax({
        url: '{{route("update_device_status")}}',
        method: 'GET',
        success: function (response) {
            console.log(response);
        },
        error: function (error) {
            console.error(error);
        }
    });
    }

    // Panggil updateData pertama kali
    updateData();

    // Panggil updateData setiap 5000 milidetik (5 detik)
    setInterval(updateData, 5000);

    $('.circle').on('click', function() {
    // Dapatkan status dari class lingkaran yang diklik
    var status = $(this).hasClass('online') ? 'online' :
                 $(this).hasClass('offline') ? 'offline' :
                 $(this).hasClass('warning') ? 'warning' : '';

    // Jika status tidak kosong, arahkan ke halaman Monitoring Index dengan filter status
    if (status) {
        window.location.href = '{{ route("monitoring.index") }}?status=' + status;
    }
});
</script>
@stop
@extends('layouts.app')

@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Dashboard</h3>
                <p class="text-subtitle text-muted">Selamat datang dihalaman Monitoring Smart Lock Voltage.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
            </div>
        </div>
    </div>
    <section class="section">
        <div class="col-12 col-lg-12">
            <div class="row">
                <!-- Online Status -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body  text-center">
                            <div class="circle online bg-success text-white mb-4">
                                <p class="total" id="totalOnline">{{ $data['online'] ?? 0 }}</p>
                            </div>
                            <h4>Online</h4>
                        </div>
                    </div>
                </div>

                <!-- Offline Status -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="circle offline bg-danger text-white mb-4">
                                <p class="total" id="totalOffline">{{ $data['offline'] ?? 0 }}</p>
                            </div>
                            <h4>Offline</h4>
                        </div>
                    </div>
                </div>

                <!-- Warning Status -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body  text-center">
                            <div class="circle warning bg-warning text-white mb-4">
                                <p class="total" id="totalWarning">{{ $data['warning'] ?? 0 }}</p>
                            </div>
                            <h4>Warning</h4>
                        </div>
                    </div>
                </div>

                {{-- <!-- Total Alat -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="btn total-alat bg-dark text-white">
                                <p class="total" id="totalAlat">{{ $data['totalAlat'] ?? 0 }}</p>
                                <p class="label">Total Alat</p>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </section>
</div>

<style>
    .circle {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        margin: auto;
    }

    .total-alat {
        width: 200px;
        height: 100px;
        border-radius: 5px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        margin: auto;
    }

    .total {
        margin: 0;
        font-size: 32px;
    }

    .label {
        font-size: 18px;
    }
</style>

@endsection

