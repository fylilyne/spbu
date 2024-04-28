@section('css')
  <style>
/* #realTimeChartContainer {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
} */

#lastVoltageBadge {
    margin-right: 10px !important;
}
  </style>
@stop

@section('js')
  
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
  async function fetchData() {
      try {
        const response = await fetch('{{route("get_data_log_sensor")}}?device_key={{$device_key}}');
        const data = await response.json();
        return data.reverse().slice(0, 10);
      } catch (error) {
          console.error('Error fetching data:', error);
          return [];
      }
  }

  function updateChart(chart) {
      fetchData().then(newData => {
          const timestamps = newData.map(data => data.timestamp);
          const voltages = newData.map(data => data.voltage);

          // Check if voltage exceeds 10 and set borderColor accordingly
          const borderColor = voltages.map(value => (value > {{$device->max_voltage}} ? 'black' : 'black'));

          chart.data.labels = timestamps.map(time => new Date(time).toLocaleTimeString());
          chart.data.datasets[0].data = voltages;
          chart.data.datasets[0].borderColor = borderColor;
          chart.data.datasets[0].backgroundColor = 'rgba(0, 0, 0, 0.2)';

          chart.update();
      });
  }

  function initChart() {
    const ctx = document.getElementById('realTimeChart').getContext('2d');

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'TEGANGAN VOLTAGE SLV',
                borderColor: 'black',
                backgroundColor: 'rgba(0, 0, 0, 0.2)',
                fill: true,
                lineTension: 0,
                data: [],
            }],
        },
        options: {
            scales: {
                x: [{
                    type: 'realtime',
                    realtime: {
                        refresh: 1000,
                        delay: 2000,
                    },
                }],
                y: [{
                    type: 'linear',
                    display: true,
                    position: 'left',
                }],
            },
            plugins: {
                datalabels: {
                    display: false,
                },
                drawHorizontalLine: {
                    y: 10,
                    borderColor: 'red',
                    borderWidth: 1,
                },
            },
            legend: {
                position: 'bottom',
                labels: {
                    filter: function (legendItem, chartData) {
                        return chartData.datasets[legendItem.datasetIndex].label !== 'Realtime Voltage';
                    },
                },
            },
        },
    });

    // Initial data load
    fetchData().then(initialData => {
            chart.data.labels = initialData.map(data => new Date(data.timestamp).toLocaleTimeString());
            chart.data.datasets[0].data = initialData.map(data => data.voltage);

            // Check if initial voltages exceed max_voltage and set borderColor accordingly
            const initialBorderColor = initialData.map(data => (data.voltage > {{$device->max_voltage}} ? 'black' : 'black'));
            chart.data.datasets[0].borderColor = initialBorderColor;

            chart.update();

            // Update the last voltage badge initially
            updateLastVoltageBadge(initialData);
        });

    // Periodically update the chart
    setInterval(() => {
            fetchData().then(newData => {
                updateChart(chart, newData);
                updateLastVoltageBadge(newData);
            });
        }, 3000); // Update every 3 seconds (adjust as needed)
}

function updateLastVoltageBadge(data) {
    const lastData = data.length > 0 ? data[data.length - 1] : null;

    // Check if lastData exists and has a valid numeric voltage
    if (lastData && typeof lastData.voltage === 'string' && !isNaN(parseFloat(lastData.voltage))) {
        const lastVoltage = parseFloat(lastData.voltage);
        const voltageBadge = document.getElementById('lastVoltageBadge');
        voltageBadge.textContent = `Voltage: ${lastVoltage.toFixed(2)}`;

        // Set badge background color based on max_voltage comparison
        voltageBadge.style.backgroundColor = lastVoltage > 10 ? 'red' : 'green';
    } else {
        console.error('Invalid lastData or voltage:', lastData);
    }
}



document.addEventListener('DOMContentLoaded', initChart);

</script>



@stop

@extends('layouts.app')

@section('content')

<div class="page-heading">
    <h3>{{isset($label_page) ? $label_page : 'Label Not Found'}}</h3>
</div>
         
<div class="page-content">
    <div class="row">
        <div class="col-12 col-md-8 col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="card-header-action">
                        <a href="{{ route('monitoring.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                    {{-- Display Online/Offline Status Badge --}}
                    <div class="mb-2 text-center">
                        @if($device->status == 'online')
                            <span class="badge bg-success btn-xl">Online</span>
                        @elseif($device->status == 'warning')
                            <span class="badge bg-warning btn-xl">Warning</span>
                        @else
                            <span class="badge bg-danger btn-xl">Offline</span>
                        @endif
                    </div>

                    {{-- Display Updated At --}}
                    <div class="mb-2 text-center">
                        
                        @if($device->status == 'online')
                            <span class="badge bg-success btn-xl">
                                Updated At: {{ \Carbon\Carbon::parse($device->updated_at)->format('d F Y, H:i:s T') }}</span>
                        @elseif($device->status == 'warning')
                            <span class="badge bg-warning btn-xl">
                                Updated At: {{ \Carbon\Carbon::parse($device->updated_at)->format('d F Y, H:i:s T') }}</span>
                        @else
                            <span class="badge bg-danger btn-xl">
                                Updated At: {{ \Carbon\Carbon::parse($device->updated_at)->format('d F Y, H:i:s T') }}</span>
                        @endif
                    </div>

                    {{-- Additional content goes here --}}
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card">
                <div class="card-body">
                    {{-- Display Device Details in a Table --}}
                    <table class="table table-bordered">
                        <tr>
                            <td>SPBU</td>
                            <td>{{ $device->nama }}</td>
                        </tr>
                        <tr>
                            <td>Witel</td>
                            <td>{{ $device->witel }}</td>
                        </tr>
                        <tr>
                            <td>Network</td>
                            <td>{{ $device->network }}</td>
                        </tr>
                        <tr>
                            <td>Tipe Dispenser</td>
                            <td>{{ $device->tipe_dispenser }}</td>
                        </tr>
                        <tr>
                            <td>Dispenser Integrasi</td>
                            <td>{{ $device->dispenser_integrasi }}</td>
                        </tr>
                        <tr>
                            <td>Dispenser Tidak Terintegrasi</td>
                            <td>{{ $device->dispenser_tidak_terintegrasi }}</td>
                        </tr>
                        <tr>
                            <td>Lock Voltage</td>
                            <td>{{ $device->lock_voltage }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
          		<div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                  <div class="card-header">
                    {{-- <h4>Data</h4> --}}
                    <div class="card-header-action">

                    </div>
                  </div>
                  <div class="card-body position-relative">
                    @if($device_key)
                    <div id="lastVoltageBadge" class="badge position-absolute top-0 end-0">Last Voltage: 123.45</div>
                    <div id="realTimeChartContainer" class="position-relative">
                        <canvas id="realTimeChart" width="400" height="200"></canvas>
                    </div>
                    
                    
                    
                  @else
                    <div class="btn btn-dark">Device tidak ditemukan</div>
                  @endif
                  </div>
                </div>
              </div>
             </div>


 @endsection

