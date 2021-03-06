@extends('layouts.master')

@section('title') Sales @endsection

@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection
@section('header') Invoice @endsection
@section('description') This page is about invoices for all your Sales(POS) @endsection

@section('top')
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> Invoice</li>
</ol>
@endsection

@section('content')
    <div class="box">

        <div class="box-header">
            <h3 class="box-title">Invoice</h3>
        </div>

        <div class="box-header">
            <a onclick="addForm()" class="btn btn-primary btn-lg" >Create Invoice</a>
            <a href="{{ route('exportPDF.salesAll1') }}" class="btn btn-danger btn-lg">Export PDF</a>
            <a href="{{ route('exportExcel.salesAll1') }}" class="btn btn-success btn-lg">Export Excel</a>
        </div>

        <div>
            <form id="filter-form">
                
                <div class="row input-daterange" style="margin-left:5px;">
                    <div class="col-md-3">
                        <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" readonly />
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly />
                    </div>
                    <div class="col-md-3">
                        <button type="submit" value="Submit" name="filter" id="filter" class="btn btn-primary">Filter</button>
                        <button type="button" name="refresh" id="refresh" class="btn btn-default">Refresh</button>
                    </div>
                    <b> Subtotal </b>
                    <div id="subtotal" class="col-md-3">
                        
                    </div>
                </div>
            </form>
          </div>
        <br />

        <!-- /.box-header -->
        <div class="box-body">
            <table id="sales-table" class="table table-striped">
                <thead>
                <tr>
                    {{-- <th>ID</th> --}}
                    <th>Date</th>
                    <th>PO No.</th>
                    <th>Total Amount</th>
                    <th>Customer Name</th>
                    <th>Mobile</th>
                    {{-- <th>Date</th> --}}
                    <th>Refund Status</th>
                    <th>Cashier</th>
                    <th>ID</th>
                    <th>Actions</th>
                    
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>

    {{-- @include('sales1.form_import') --}}

    @include('sales1.form')

@endsection

@section('bot')

<script src=" {{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }} "></script>
<script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }} "></script>


<!-- InputMask -->
<script src="{{ asset('assets/plugins/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('assets/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
<script src="{{ asset('assets/plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>
<!-- date-range-picker -->
<script src="{{ asset('assets/bower_components/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('assets/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<!-- bootstrap datepicker -->
<script src="{{ asset('assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<!-- bootstrap color picker -->
<script src="{{ asset('assets/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
<!-- bootstrap time picker -->
<script src="{{ asset('assets/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
{{-- Validator --}}
<script src="{{ asset('assets/validator/validator.min.js') }}"></script>


    <script>
        $(function () {

            //Date picker
            $('#date').datepicker({
                autoclose: true,
                //  format: 'dd-mm-yy',
                 
            })
            $('#date').datepicker('setDate', new Date());


            //Colorpicker
            $('.my-colorpicker1').colorpicker()
            //color picker with addon
            $('.my-colorpicker2').colorpicker()

            //Timepicker
            $('.timepicker').timepicker({
                showInputs: false
            })
        })
    </script>

    <script type="text/javascript">
        var url = '{{ route('api.sales1') }}';
        var table = $('#sales-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
            url: "{{ route('api.sales1') }}",
            type: "GET", // or 'GET' if you prefer
            data: function (data) {
             data.from_date = $('#from_date').val();
             data.to_date = $('#to_date').val();
                }
                },
            columns: [
                // {data: 'id', name: 'id'},
                {data: 'date', name: 'date'},
                {data: 'po_no', name: 'po_no'},
                {data: 'total_amount', name: 'total_amount'},
                {data: 'customer_name', name: 'customer_name'},
                {data: 'mob_no', name: 'mob_no'},
                {data: 'refund_status', name: 'refund_status'},
                {data: 'cashier', name: 'cashier'},
                {data: 'id', name: 'id'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order: [ [0, 'desc'] ]

        });
        $( "#filter-form" ).submit(function( event ) {
          event.preventDefault();
          table.ajax.url( url ).load();
          getSubtotalSum($('#from_date').val(),$('#to_date').val());
});

$('#refresh').click(function(){
  $('#from_date').val('');
  $('#to_date').val('');
//   $('#orders-table').DataTable().destroy();
  table.ajax.url( url ).load();
  
//   $('#subtotal').text(table.column( 6 ).data().sum());

 });     
function getSubtotalSum(from_date, to_date) {
            $.ajax({
                url: "{{ url('getSubtotalSumInvoice') }}" + '/' + from_date + '/' + to_date ,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#subtotal').text(data.data);
                    console.log(data);
                    // $('#productName').text(data.name);
                    // $('#price').val(data.price);

                }
            });
        }

        function addForm() {
            save_method = "add";
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('#modal-form form')[0].reset();
            $('.modal-title').text('Add Sales');
        }

        $('.input-daterange').datepicker({
         todayBtn:'linked',
         format:'yyyy-mm-dd',
         autoclose:true
         });


        function refund(id) {
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Refund !'
            }).then(function () {
                $.ajax({
                    url : "{{ url('salerefund') }}" + '/' + id,
                    type : "GET",
                    success : function(data) {
                        table.ajax.reload();
                        swal({
                            title: 'Success!',
                            text: data.message,
                            type: 'success',
                            timer: '1500'
                        })
                    },
                    error : function () {
                        swal({
                            title: 'Oops...',
                            text: data.message,
                            type: 'error',
                            timer: '1500'
                        })
                    }
                });
            });
        }

        function generateInvoice(id) {

            $.ajax({
                url: "{{ url('generateInvoice') }}" + '/' + id ,
                type: "GET",
                dataType: "JSON",
                    success: function(response) {
        // alert(response.data);
                    w = window.open(window.location.href,"_blank");
                    w.document.open();
                    w.document.write(response.data);
                    w.document.close();
                    w.window.print();
                    $('#sales-table').DataTable().ajax.reload();

                },
            
                error : function() {
                    alert("Nothing Data");
                }
            });
        }


        function editForm(id) {
            save_method = 'edit';
            $('input[name=_method]').val('PATCH');
            $('#modal-form form')[0].reset();
            $.ajax({
                url: "{{ url('sales1') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modal-form').modal('show');
                    $('.modal-title').text('Edit Sales');

                    $('#id').val(data.id);
                    $('#customer_name').val(data.customer_name);
                     $('#total_amount').val(data.total_amount);
                    $('#date').val(data.date);
                },
                error : function() {
                    alert("Nothing Data");
                }
            });
        }

        function deleteData(id){
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then(function () {
                $.ajax({
                    url : "{{ url('sales1') }}" + '/' + id,
                    type : "POST",
                    data : {'_method' : 'DELETE', '_token' : csrf_token},
                    success : function(data) {
                        table.ajax.reload();
                        swal({
                            title: 'Success!',
                            text: data.message,
                            type: 'success',
                            timer: '1500'
                        })
                    },
                    error : function () {
                        swal({
                            title: 'Oops...',
                            text: data.message,
                            type: 'error',
                            timer: '1500'
                        })
                    }
                });
            });
        }

        $(function(){
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('sales1') }}";
                    else url = "{{ url('sales1') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",

                        data: new FormData($("#modal-form form")[0]),
                        contentType: false,
                        processData: false,
                        success : function(data) {
                            $('#modal-form').modal('hide');
                            table.ajax.reload();
                            swal({
                                title: 'Success!',
                                text: data.message,
                                type: 'success',
                                timer: '1500'
                            })
                        },
                        error : function(data){
                            swal({
                                title: 'Oops...',
                                text: data.message,
                                type: 'error',
                                timer: '1500'
                            })
                        }
                    });
                    return false;
                }
            });
        });
    </script>

    

@endsection
