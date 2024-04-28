@section('css')

@stop

@section('js')
  <script type="text/javascript">
    $(document).ready(function() {
        $('#example2').DataTable({
            "processing": true,
            "serverSide": true,
            "pageLength": 50,
            "ajax": "{{route('api_contact')}}",
            
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
                    data: 'chat_id',
                    name: 'chat_id',
                    orderable: true,
                    searchable: true
                }],
                "columnDefs": [{
                  "targets": 0,
                   "width": "20%",
                  "render": function(data, type, full) {
                    return '<span style="white-space: nowrap !important;"> <span><a href="contact/'+full['id']+'/edit" class="btn btn-sm btn-warning float-left mr-1">Edit</a></span><span data-toggle="tooltip" data-placement="top" title="Delete"> <button class="btn btn-danger btn-sm float-left" data-id_data='+full['id']+' data-bs-toggle="modal" data-bs-target="#delete">Delete</button></span></span>';
                 },
                }]
            });
        });
        /*
      <script>
        $(function () {
          $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "pageLength": 10,
            "autoWidth": true,
          });
        });*/


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
                        <a href="{{route('contact.create')}}" class="btn btn-primary">Tambah Data</a>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-striped table-dark mb-0" id="example2">
                            <thead>
                                <tr>
                                  <th nowrap=""></th>
                                  <th nowrap="">Nama</th>
                                  <th nowrap="">Chat ID</th>
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
            <form action="{{ route('contact.destroy', '/') }}" method="post" id="formDelete">
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

