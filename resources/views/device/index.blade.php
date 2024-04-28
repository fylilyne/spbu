@section('css')

@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
 
  <script type="text/javascript">
    $(document).ready(function() {
        $('#example2').DataTable({
            "processing": true,
            "serverSide": true,
            "pageLength": 50,
            "ajax": "{{route('api_device')}}",
            
            "autoWidth": true,
            "ordering": true,
            "columns": [
            {
                data: 'action',
                name: 'action',
                className: "text-nowrap",
                orderable: false,
                searchable: false
            },
            {
                data: 'nama',
                name: 'nama',
                orderable: true,
                searchable: true
            },
            {
                data: 'site',
                name: 'site',
                orderable: true,
                searchable: true
            },
            {
                data: 'snslv',
                name: 'snslv',
                orderable: true,
                searchable: true
            },
            {
                data: 'status',
                name: 'status',
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
                data: 'network',
                name: 'network',
                orderable: true,
                searchable: true
            },
            {
                data: 'tipe_dispenser',
                name: 'tipe_dispenser',
                orderable: true,
                searchable: true
            },
            {
                data: 'total_dispenser',
                name: 'total_dispenser',
                orderable: true,
                searchable: true
            },
            {
                data: 'dispenser_integrasi',
                name: 'dispenser_integrasi',
                orderable: true,
                searchable: true
            },
            {
                data: 'dispenser_tidak_terintegrasi',
                name: 'dispenser_tidak_terintegrasi',
                orderable: true,
                searchable: true
            },
            {
                data: 'lock_voltage',
                name: 'lock_voltage',
                orderable: true,
                searchable: true
            },
            {
                data: 'min_voltage',
                name: 'min_voltage',
                orderable: true,
                searchable: true
            },
            {
                data: 'max_voltage',
                name: 'max_voltage',
                orderable: true,
                searchable: true
            },
            {
                data: 'device_key',
                name: 'device_key',
                orderable: true,
                searchable: true
            },
            {
                data: 'telegram',
                name: 'telegram',
                orderable: true,
                searchable: true
            }
        ],
        "columnDefs": [{
                  "targets": 0,
                   "width": "20%",
                  "render": function(data, type, full) {
                    return '<span style="white-space: nowrap !important;"> <span><a href="device/'+full['id']+'/edit" class="btn btn-sm btn-warning float-left mr-1">Edit</a></span><span data-toggle="tooltip" data-placement="top" title="Delete"> <button class="btn btn-danger btn-sm float-left" data-id_data='+full['id']+' data-bs-toggle="modal" data-bs-target="#delete">Delete</button></span> <span data-toggle="tooltip" data-placement="top" title="Copy Device Key" class="logo-key" data-device-key="' + full['device_key'] + '"><button class="btn btn-info btn-sm float-left ml-1"><i class="fas fa-key"></i></button></span></span>';
                 },
                }],"createdRow": function(row, data, dataIndex) {
            // Format Status column
            var statusCell = $(row).find('td:eq(4)'); // Assuming Status is the 5th column (index 4)
            if (data.status === 'online') {
                statusCell.html('<span class="badge bg-success">Online</span>');
            } else if (data.status === 'offline') {
                statusCell.html('<span class="badge bg-danger">Offline</span>');
    
            } else if (data.status === 'warning') {
                statusCell.html('<span class="badge bg-warning">Warning</span>');
            }

            // Format Telegram column
            var telegramCell = $(row).find('td:eq(15)'); // Assuming Telegram is the 16th column (index 15)
            if (data.telegram === '1') {
                telegramCell.html('<span class="badge bg-success">Yes</span>');
            } else if (data.telegram === '0') {
                telegramCell.html('<span class="badge bg-danger">No</span>');
            }
        }
            });
        
       // Define a variable to store the ClipboardJS instance
       var clipboard;

// Add click event for logo key button
$('#example2').on('click', '.logo-key', function() {
    var deviceKey = $(this).data('device-key');

    // Destroy previous ClipboardJS instance if exists
    if (clipboard) {
        clipboard.destroy();
    }

    // Create a new ClipboardJS instance
    clipboard = new ClipboardJS('.logo-key', {
        text: function() {
            return deviceKey;
        }
    });

    clipboard.on('success', function(e) {
        // Laravel's Toastr message
        toastr.success('Device Key copied to clipboard!', 'Success');
        e.clearSelection();
    });

    clipboard.on('error', function(e) {
        // Laravel's Toastr message
        toastr.error('Error copying Device Key to clipboard.', 'Error');
    });
});
});
</script>
      <script type="text/javascript">
/*        var myModal = document.getElementById('delete')
var myInput = document.getElementById('myInput')

myModal.addEventListener('shown.bs.modal', function () {
  myInput.focus()
})*/
$(window).on("load", function () {
    let action = '';
    $('#delete').on('show.bs.modal', function (event) {
    //alert('hi');
        let button = $(event.relatedTarget)
        let modal = $(this)
        action = $('#formDelete').attr('action');

        let id = button.data('id_data');
        
      // Jika id_data tidak ada pada tombol, coba cari pada parent element (mungkin baris tabel)
      if (typeof id === 'undefined') {
          id = button.closest('tr').data('id_data');
      }
       // let id = $(this).data("id_data"); 
        $('#formDelete').attr('action', action+'/'+id);
    });

    $('#delete').on('hidden.bs.modal', function () {
        $('#formDelete').attr('action', action);
    });
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
          		<div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Data</h4>
                    <div class="card-header-action">
                        <a href="{{route('device.create')}}" class="btn btn-primary">Tambah Data</a>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-striped table-dark mb-0" id="example2">
                            <thead>
                              <tr>
                                <th nowrap=""></th>
                                <th nowrap>Nama</th>
                                <th nowrap>Site</th>
                                <th nowrap>SNSLV</th>
                                <th nowrap>Status</th>
                                <th nowrap>Witel</th>
                                <th nowrap>Network</th>
                                <th nowrap>Tipe Dispenser</th>
                                <th nowrap>Total Dispenser</th>
                                <th nowrap>Dispenser Integrasi</th>
                                <th nowrap>Dispenser Tidak Terintegrasi</th>
                                <th nowrap>Lock Voltage</th>
                                <th nowrap>Min Voltage</th>
                                <th nowrap>Max Voltage</th>
                                <th nowrap>Device Key</th>
                                <th nowrap>Telegram</th>
                            </tr>
                          </thead>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
             </div>


 <!-- Modal delete-->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <form action="{{ route('device.destroy', '/') }}" method="post" id="formDelete">
                @csrf
                @method('delete')
                <div class="modal-body">
                    <!--<input type="text" name="id_barang" id="id_barang" value="">-->
                    <p class="text-center" style="font-size:1.3em; color:red;font-weight:800;">
                        Are you sure you want to delete this?
                    </p>
                    <input type="hidden" name="id_data" id="id_data" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">No, Cancel</button>
                    <button type="submit" class="btn btn-outline-danger">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
 @endsection

