<div class="modal fade" id="modal-form" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form  id="form-item" method="post" class="form-horizontal" data-toggle="validator" enctype="multipart/form-data" >
                {{ csrf_field() }} {{ method_field('POST') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title"></h3>
                </div>


                <div class="modal-body">
                    <input type="hidden" id="id" name="id">


                    <div class="box-body">
                    
                    <div class="form-group">
                            <label>Date</label>
                            <input data-date-format='yyyy-mm-dd' type="text" class="form-control" id="date" name="date"   required>
                            <span class="help-block with-errors"></span>
                        </div>

                        {{-- <div class="form-group">
                        <input name="barcode_name" onmouseover="this.focus();" type="text">
                        </div> --}}
                        

                        <div class="form-group">
                            <label>Products</label>
                            {!! Form::select('product_id', $products, null, ['class' => 'form-control select2', 'placeholder' => '-- Choose Product --', 'id' => 'product_id', 'required']) !!}
                            <span class="help-block with-errors"></span>
                        </div>

                        {{-- <div class="form-group">
                            <label>Customer</label>
                            {!! Form::select('customer_id', $customers, null, ['class' => 'form-control select2', 'placeholder' => '-- Choose Customer --', 'id' => 'customer_id', 'required']) !!}
                            <span class="help-block with-errors"></span>
                        </div> --}}

                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="text" class="form-control" id="qty" name="qty" value=1 required>
                            <span class="text-danger"><span id="productName"></span> currently number <span id="available">0</span>.</span>
                            <span class="help-block with-errors"></span>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" class="form-control" id="price" name="price"  required>
                        </div>
                        
                        <div class="form-group">
                            <label>Discount %</label>
                        <input type="number" value="0" min="0" max="100" id="discount" name="discount" step="1"/>
                        <span class="help-block with-errors"></span>
                        </div>


                        
                    </div>
                    <!-- /.box-body -->

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>

            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
