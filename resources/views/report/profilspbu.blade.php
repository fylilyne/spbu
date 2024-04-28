@section('css')

@stop

@section('js')

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
            }
        ]
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
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-striped table-dark mb-0" id="example2">
                            <thead>
                              <tr>
                                <th nowrap>SPBU</th>
                                <th nowrap>Witel</th>
                                <th nowrap>Network</th>
                                <th nowrap>Tipe Dispenser</th>
                                <th nowrap>Total Dispenser</th>
                                <th nowrap>Dispenser Integrasi</th>
                                <th nowrap>Dispenser Tidak Terintegrasi</th>
                                <th nowrap>Lock Voltage</th>
                            </tr>
                          </thead>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
             </div>
 @endsection

