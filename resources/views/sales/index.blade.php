@extends('layouts.master')

@section('title') Product Out @endsection

@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('header')
{{-- <img src="{{ asset('upload/logo/logo.jpeg') }}" alt="logo" style="width:100%; width:50px;/> --}}
  <h1>MONTAHA ALAJEEL</h1> @endsection
@section('description') Point of Sale @endsection

@section('top')
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> Sales</li>
</ol>
@endsection

@section('content')
    <div class="box">

        

        <div class="box-header">
            {{-- <a onclick="addForm()" class="btn btn-primary" >Add Products Out</a> --}}
            <a href="{{ route('exportPDF.productOutAll1') }}" class="btn btn-danger btn-lg">Export Data PDF</a>
            <a href="{{ route('exportExcel.productOutAll1') }}" class="btn btn-success btn-lg">Export Data Excel</a>
             <button id="order_completed" class="btn btn-primary btn-lg"> Completed</button>

            {{-- <button id="downloadPDF" class="btn btn-primary">Export Invoice PDF</button> --}}
        </div>

        <div class="box-header">
            <h3 class="box-title" style="margin-left:10px;"><b>Scan Barcode here</b></h3><br>
            <div  id="barcode-modal-form" style ="margin-left: 20px;" >
                <form  id="form-item1" method="post" class="form-horizontal" data-toggle="validator" enctype="multipart/form-data" >
                    {{ csrf_field() }} {{ method_field('POST') }}
    
                  <div class="form-group">
                    <input id="barcode_name" name="barcode_name" onmouseover="this.focus();" type="text" style="padding-left:10px;padding-right:30%;">
                  </div>
                {{-- <button type="submit" class="btn btn-primary">Submit</button> --}}
                </form>

            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table id="products-out-table" class="table table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Products</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Discount</th>
                    <th>Subtotal</th>
                    <th>Date</th>
                    <th></th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    @include('sales.form')
    {{-- @include('sales.barcodescanForm') --}}


@endsection

@section('bot')

    <!-- DataTables -->
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
        var table = $('#products-out-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('api.productsOut1') }}",
            columns: [
              //  {data: 'multiple_export', name: 'multiple_export'},
                {data: 'id', name: 'id'},
                {data: 'products_name', name: 'products_name'},
                {data: 'price', name: 'price'},
                {data: 'qty', name: 'qty'},
                {data: 'discount', name: 'discount'},
                {data: 'subtotal', name: 'subtotal'},
                {data: 'date', name: 'date'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        function addForm() {
            save_method = "add";
            $('input[name=_method]').val('POST');
            // $('#barcode-modal-form').modal('show');
            // $('#barcode-modal-form form')[0].reset();
            $('.modal-title').text('Add Products');
        }

        $(document).on("change","#product_id",function(){
            checkAvailable(this.value);
        });
        // $(document).on("change","#barcode_name",function(){
        //     $('#form-item1').submit();
        // });

        $(document).on("click","#order_completed",function(){
            $('#barcode_name').val("");


         
            $.ajax({
                url: "{{ url('order_complete') }}" ,
                type: "GET",
                dataType: "JSON",
                success: function(response) {
            w = window.open(window.location.href,"_blank");
            w.document.open();
            w.document.write(response.data);
            w.document.close();
            w.window.print();

        
                    
                    
                    $('#products-out-table').DataTable().ajax.reload();


                },
                error : function() {
                    alert("Nothing Data");
                }
            });
        });

        function editForm(id) {
            save_method = 'edit';
            // alert(id);
            $('input[name=_method]').val('PATCH');
             $('#modal-form').modal('show');

            $.ajax({
                url: "{{ url('sales') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modal-form').modal('show');
                    $('.modal-title').text('Edit Products');

                    $('#id').val(data.id);
                    $('#product_id').val(data.product_id).trigger('change');
                    // $('#customer_id').val(data.customer_id).trigger('change');
                    $('#qty').val(data.qty);
                    $('#price').val(data.price);
                    $('#date').val(data.date);
                    $('#customer_name').val(data.customer_name);
                    $('#mob_no').val(data.mob_no);

                },
                error : function() {
                    alert("Nothing Data");
                }
            });
        }

        // Check available items
        function checkAvailable(id) {
            $.ajax({
                url: "{{ url('checkAvailable') }}" + '/' + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#available').text(data.qty);
                    $('#productName').text(data.name);
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
                    url : "{{ url('sales') }}" + '/' + id,
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
                    // if (save_method == 'add') url = "{{ url('sales') }}";
                    // else
                     url = "{{ url('sales') . '/' }}" + id;

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

        $(function(){
            $('#barcode-modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    save_method = "add";

                    var id = $('#id').val();
                    // if (save_method == 'add')
                     url = "{{ url('sales') }}";
                     var textboxvalue = $('#barcode_name').val();
                    $.ajax({
                        url : url,
                        type : "POST",
                        data: new FormData($("#barcode-modal-form form")[0]),
                        contentType: false,
                        processData: false,
                        success : function(data) {
                            // $('#barcode-modal-form').modal('hide');
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



        // Disable button for first time
        $('#downloadPDF').prop('disabled', true);

        $(function(){
            // Check if any checkbox checked
            $(document).on("click","input[type=checkbox]",function() {
                var countCheckbox = $('input:checkbox:checked').length;
                if (countCheckbox == 0) {
                    $('#downloadPDF').prop('disabled', true);
                }else{
                    $('#downloadPDF').prop('disabled', false);
                }
            }); 

            // Download the PDF
            $('#downloadPDF').click(function(){

                var val = [];
                $(':checkbox:checked').each(function(i){
                    val[i] = $(this).val();
                });

                var exportUrl = "{{ route('exportPDF.productOut') }}";
                var blkstr = val.join(', ');
                console.log(blkstr);
                $.ajax({
                    url : exportUrl+'?exportpdf='+blkstr,
                    type : "GET",
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function (response, status, xhr) {
                        var filename = "";                   
                        var disposition = xhr.getResponseHeader('Content-Disposition');

                         if (disposition) {
                            var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                            var matches = filenameRegex.exec(disposition);
                            if (matches !== null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                        } 
                        var linkelem = document.createElement('a');
                        try {
                            var blob = new Blob([response], { type: 'application/octet-stream' });                        

                            if (typeof window.navigator.msSaveBlob !== 'undefined') {
                                //   IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                                window.navigator.msSaveBlob(blob, filename);
                            } else {
                                var URL = window.URL || window.webkitURL;
                                var downloadUrl = URL.createObjectURL(blob);

                                if (filename) { 
                                    // use HTML5 a[download] attribute to specify filename
                                    var a = document.createElement("a");

                                    // safari doesn't support this yet
                                    if (typeof a.download === 'undefined') {
                                        window.location = downloadUrl;
                                    } else {
                                        a.href = downloadUrl;
                                        a.download = filename;
                                        document.body.appendChild(a);
                                        a.target = "_blank";
                                        a.click();
                                    }
                                } else {
                                    window.location = downloadUrl;
                                }
                            }   
                        } catch (ex) {
                            console.log(ex);
                        } 
                    }
                });
            });
        });
    </script>
   <style>
    @media print {
        html, body {
         width: 80mm;
         height:100%;
         position:absolute;
        }
     }
     
     </style>

@endsection
