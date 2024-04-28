@section('css')


    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/pikaday/1.8.0/css/pikaday.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.dataTables.min.css">
@stop

@section('js')
    <!-- Pikaday CSS -->
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js"></script>
    <!-- Moment.js (diperlukan oleh Pikaday) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <!-- Pikaday JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pikaday/1.8.0/pikaday.min.js"></script>


    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var picker = new Pikaday({
                field: document.getElementById('selected_date'),
                format: 'dddd, DD MMMM YYYY',
                showWeekday: true,
                isRTL: false,
                i18n: {
                    previousMonth: 'Bulan Sebelumnya',
                    nextMonth: 'Bulan Depan',
                    months: [
                        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ],
                    weekdays: [
                        'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
                    ],
                    weekdaysShort: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab']
                },
                defaultDate: new Date(),
                setDefaultDate: true,
                onSelect: function(date) {
                    // Memperbarui nilai langsung pada elemen input
                    var formattedDate = date.toLocaleString('id-ID', {
                        weekday: 'long',
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });
                    $('#selected_date').val(formattedDate);
                    $('#example2').DataTable().ajax.reload();
                }
            });

            // Set nilai awal pada elemen input saat halaman pertama kali dimuat
            var initialDate = new Date().toLocaleString('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            $('#selected_date').val(initialDate);

            moment.locale('id');
            var table = $('#example2').DataTable({
                "processing": true,
                "serverSide": true,
                "pageLength": 50,
                "buttons": [{
                    extend: 'excelHtml5',
                    text: 'Export to Excel',
                    titleAttr: 'Download Excel',
                    title: function() {
                        var selectedDate = picker.getMoment().locale('id').format(
                            'DD MMMM YYYY');
                        var selectedName = $('#nama_filter option:selected').text() ||
                            'Semua SPBU';

                        // Menggunakan moment.js untuk mendapatkan nama hari dan bulan dalam bahasa Indonesia
                        var selectedDay = picker.getMoment().locale('id').format('dddd');
                        var selectedMonth = picker.getMoment().locale('id').format('MMMM');

                        // Konversi hari dan bulan ke format kapital

                        // Mengganti nama hari dalam bahasa Indonesia jika memenuhi kondisi
                        if (selectedDay === 'Monday') {
                            selectedDay = 'Senin';
                        } else if (selectedDay === 'Tuesday') {
                            selectedDay = 'Selasa';
                        } else if (selectedDay === 'Wednesday') {
                            selectedDay = 'Rabu';
                        } else if (selectedDay === 'Thursday') {
                            selectedDay = 'Kamis';
                        } else if (selectedDay === 'Friday') {
                            selectedDay = 'Jumat';
                        } else if (selectedDay === 'Saturday') {
                            selectedDay = 'Sabtu';
                        } else if (selectedDay === 'Sunday') {
                            selectedDay = 'Minggu';
                        }

                        return 'Report Voltage - Tanggal: ' + selectedDay + ', ' +
                            selectedDate + ', SPBU: ' + selectedName;
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    }
                }],
                "ajax": {
                    "url": "{{ route('api_broadcast') }}",
                    "data": function(d) {
                        // Menggunakan metode getMoment().format()
                        d.selected_date = picker.getMoment().format('YYYY-MM-DD');
                        d.nama = $('#nama_filter').val() || $('#nama_filter option:first')
                    .val(); // Menggunakan nilai default
                    }
                },
                "order": [
                    [2, "asc"]
                ],

                "autoWidth": true,
                "ordering": true,
                "columns": [{
                        data: 'nama',
                        name: 'nama',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'witel',
                        name: 'witel',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'status_voltage',
                        name: 'status_voltage',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'broadcast_at',
                        name: 'broadcast_at',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'broadcast_total',
                        name: 'broadcast_total',
                        orderable: true,
                        searchable: true,
                        render: function(data, type, row) {
                            return data + ' Send OK';
                        }
                    }
                ]
            });

            var devices = @json($devices);
            devices.forEach(function(device) {
                $('#nama_filter').append('<option value="' + device.device_key + '">' + device.nama +
                    '</option>');
            });

            // Mengatur opsi default pada filter nama
            $('#nama_filter').on('change', function() {
                table.ajax.reload();
            });

            // Memuat data saat halaman pertama kali dibuka
            table.ajax.reload();


            // Add export to Excel button
            $('#export-to-excel').on('click', function() {
                // Menambahkan informasi filter ke opsi ekspor
                var exportOptions = {
                    modifier: {
                        page: 'all',
                        search: 'applied',
                        order: 'applied',
                    },
                    columns: [0, 1, 2, 3]
                };

                table.button('.buttons-excel').trigger('click', [exportOptions]);
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
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-12 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label for="selected_date">Pilih Tanggal:</label>
                                    <input type="text" id="selected_date" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="nama_filter">Filter SPBU:</label>
                                    <select id="nama_filter" class="form-control">
                                        <!-- Opsional: Tambahkan opsi default jika diperlukan -->
                                        <option value="">Semua SPBU</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-2 col-lg-2">
                                <div class="form-group">

                                    <label>Export to Excel</label>
                                    <button id="export-to-excel" class="btn btn-success">Download</button>
                                </div>
                            </div>
                        </div>

                        <table class="table table-striped table-dark mb-0" id="example2">
                            <thead>
                                <tr>
                                    <th nowrap>SPBU</th>
                                    <th nowrap>Witel</th>
                                    <th nowrap>Status Voltage</th>
                                    <th nowrap>Broadcast At</th>
                                    <th nowrap>Broadcast Total</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endsection
