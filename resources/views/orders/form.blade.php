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
                        {{-- <div class="form-group">
                            <label>Products</label>
                            {!! Form::select('product_id', $products, null, ['class' => 'form-control select2', 'placeholder' => '-- Choose Product --', 'id' => 'product_id', 'required']) !!}
                            <span class="help-block with-errors"></span>
                        </div> --}}
                        <div class="form-group">
                            <label>Products</label>
                            <input type="text" class="form-control" id="product_id" name="product_id"   required>
                            {{-- <span class="text-danger"><span id="productName"></span> currently number <span id="available">0</span>.</span> --}}
                            <span class="help-block with-errors"></span>
                        </div>
                        <table>
                            <tr>
                                <td>
                        <div class="form-group col">
                            <label>Customer Name </label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                            <span class="help-block with-errors"></span>
                        </div>
                                </td>
                                &nbsp;
                                <td>
                        <div class="form-group col">
                            <label>Mobile No</label>
                            <input type="text" class="form-control" id="mob_no" name="mob_no" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </td>
                    </tr>
                    </table>

                    <table>
                        <tr>
                           <td>
                        <div class="form-group">
                            <label>Shoulder</label>
                            <input type="text" class="form-control" id="shoulder" name="shoulder" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <label>Bust</label>
                            <input type="text" class="form-control" id="bust" name="bust" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <label>Sleeve Conference</label>
                            <input type="text" class="form-control" id="sleeve_conference" name="sleeve_conference" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <label>Arm Hole</label>
                            <input type="text" class="form-control" id="arm_hole" name="arm_hole" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </td>
                
                    <td>
                        <div class="form-group">
                            <label>SLDC</label>
                            <input type="text" class="form-control" id="sldc" name="sldc" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <label>Sleeve Length</label>
                            <input type="text" class="form-control" id="sleeve_length" name="sleeve_length" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <label>Waist Line</label>
                            <input type="text" class="form-control" id="waist_line" name="waist_line" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <label>Hips</label>
                            <input type="text" class="form-control" id="hips" name="hips" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <label>Length</label>
                            <input type="text" class="form-control" id="length" name="length" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </td>
                    </tr>
                    </table>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" class="form-control" id="price" name="price" required>
                            <span class="help-block with-errors"></span>
                        </div>
                        <div class="form-group">
                            <label>Paid Amount</label>
                            <input type="text" class="form-control" id="paid_amount" name="paid_amount" required>
                            <span class="help-block with-errors"></span>
                        </div>
                        <div class="form-group">
                            <label>Credit</label>
                            <input type="text" class="form-control" id="balance" name="balance" required>
                            <span class="help-block with-errors"></span>
                        </div>


                        <div class="form-group">
                            <label>Order Status</label>
                            {!! Form::select('order_status', array(0 => 'Payment Received', 1 => 'Payment Pending', 2 => 'Order Delivered'), 0, ['class' => 'form-control select2', 'placeholder' => '-- Choose Order Status --', 'id' => 'order_status', 'required']) !!}
                            <span class="help-block with-errors"></span>
                        </div>

                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="text" class="form-control" id="qty" name="qty"  value="1" required>
                            <span class="text-danger"><span id="productName"></span> currently number <span id="available">0</span>.</span>
                            <span class="help-block with-errors"></span>
                        </div>

                        <div class="form-group">
                            <label>Discount </label>
                        <input type="number" value="0" min="0" max="100" id="discount" name="discount" step="1"/>
                        <span class="help-block with-errors"></span>
                        </div>

                        

                        <div class="form-group">
                            <label>Date</label>
                            <input data-date-format='yyyy-mm-dd' type="text" class="form-control" id="date" name="date"   required>
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
