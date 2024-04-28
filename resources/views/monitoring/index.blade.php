@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.dataTables.min.css">
@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
    var dataTable = $('#example2').DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 50,
        "ajax": "{{ route('api_device') }}",
        "autoWidth": true,
        "ordering": true,
        "buttons": [
            'excelHtml5'
        ],
        "columns": [
            {
                data: 'site',
                name: 'site',
                orderable: true,
                searchable: true,
                render: function(data, type, row) {
                    if (type === 'display' || type === 'filter') {
                        // Generate hyperlink with URL parameters
                        var url = "{{ url('realtime-voltage') }}?device_key=" + row.device_key;
                        return '<a style="text-decoration: underline;" target="_blank" href="' + url + '">' + data + '</a>';
                    }
                    return data;
                }
            },
            {
                data: 'snslv',
                name: 'snslv',
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
                data: 'status',
                name: 'status',
                orderable: true,
                searchable: true,
            },
            {
                data: 'updated_at',
                name: 'updated_at',
                orderable: true,
                searchable: true,
                render: function(data, type, row) {
                    if (type === 'display' || type === 'filter') {
                        var date = new Date(data);
                        return moment(date).format('DD MMMM YYYY, HH:mm:ss') + ' WITA';
                    }
                    return data;
                }
            }
        ],"createdRow": function(row, data, dataIndex) {
    var statusCell = $(row).find('td:eq(3)');
    if (data.status === 'online') {
        statusCell.html('<span class="badge bg-success">Online</span>');
    } else if (data.status === 'offline') {
        statusCell.html('<span class="badge bg-danger">Offline</span>');
    } else if (data.status === 'warning') {
        statusCell.html('<span class="badge bg-warning text-dark">Warning</span>');
    }
},
    });
    var statusParam = new URLSearchParams(window.location.search).get('status');

// Setel nilai filter status pada dropdown jika parameter status ada
if (statusParam) {
    $('#status-filter').val(statusParam).trigger('change');
    
    dataTable.column('status:name').search(statusParam).draw();
}
    // Add status filter
    $('#status-filter').on('change', function() {
        var status = $(this).val();
        dataTable.column('status:name').search(status).draw();
    });

    // Add export to Excel button
    $('#export-to-excel').on('click', function() {
        dataTable.button('.buttons-excel').trigger();
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
                <div class="card-header">
                    <h4>Data</h4>
                    <div class="card-header-action">
                        <div class="form-group">
                            <label for="status-filter">Filter Status:</label>
                            <select id="status-filter" class="form-control">
                                <option value="">All</option>
                                <option value="online">Online</option>
                                <option value="offline">Offline</option>
                                <option value="warning">Warning</option>
                            </select>
                        </div>
                        <button id="export-to-excel" class="btn btn-success">Export to Excel</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-dark mb-0" id="example2">
                            <thead>
                                <tr>
                                    <th nowrap>Site</th>
                                    <th nowrap>SNSLV</th>
                                    <th nowrap>Witel</th>
                                    <th nowrap>Status</th>
                                    <th nowrap>Updated At</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
