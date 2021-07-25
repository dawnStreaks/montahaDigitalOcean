<?php

namespace App\Http\Controllers;

use App\Category;
use App\Customer;
use App\Exports\ExportOrders;
use App\Product;
use App\Product_Out;
use App\Company;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use PDF;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Auth;
use App\User;
use App\Order;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,staff');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::orderBy('name','ASC')
            ->get()
            ->pluck('name','id');
        

        // $customers = Customer::orderBy('name','ASC')
        //     ->get()
        //     ->pluck('name','id');


        $invoice_data = Order::all();
        // return view('orders.index', compact('products','customers', 'invoice_data'));
         return view('orders.index', compact('products', 'invoice_data'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
           'product_id'     => 'required',
           'customer_name'    => 'required',
           'price'          => 'required',
           'qty'            => 'required',
           'order_status'   => 'required',
           'date'           => 'required'
        ]);
        // $price = \DB::table('products')->select('price')->where('id', $request['product_id'] )->get();
        $barcode = \DB::select(\DB::raw("select id from barcodes where name = '$request->product_id'"));//product_id is the barcode name
        
        $barcode_id = $barcode[0]->id;
         $find_product = \DB::select(\DB::raw("select id, name from products where barcode_id = $barcode_id"));
         //$Product_Out->update($request->all());
         // $request->product_id = $find_product[0]->name;
         $product_id = $find_product[0]->id;
 
        $subtotal = $request->price * $request->qty ;
        if($request->discount > 0)
        $subtotal = $subtotal - ($subtotal* ($request->discount/100));
        Order::create(array_merge($request->all(), ['product_id' => $product_id, 'po_no' => rand(1, 99999), 'price' => $request->price, 'refund_status' => 0, 'subtotal' => $subtotal, 'cashier' => Auth::user()->name]));
        $product = Product::findOrFail($request->product_id);
        $product->qty -= $request->qty;
        $product->save();

        return response()->json([
            'success'    => true,
            'message'    => 'Products Out Created'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Product_Out = Order::find($id);
        return $Product_Out;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'product_id'     => 'required',
            'customer_name'  => 'required',
            'price'          => 'required',
            'qty'            => 'required',
            'date'           => 'required',
            'order_status'   => 'required',
            'discount'       => 'required',

            
        ]);

        $order = Order::findOrFail($id);
        $subtotal = $request->price * $request->qty ;

          if($request->discount > 0)
            $subtotal = $subtotal - ($subtotal* ($request->discount/100));
        
        $order->update(array_merge($request->all(), ['subtotal' => $subtotal, 'cashier' => Auth::user()->name]));

        $product = Product::findOrFail($request->product_id);
        $product->qty -= $request->qty;
        $product->update();

        return response()->json([
            'success'    => true,
            'message'    => 'Product Out Updated'
        ]);
    }

    public function refund($id)
    {
        

        $Product_Out = Order::findOrFail($id);
         $refund_status = "Refund of ". $Product_Out->subtotal . "KWD  "   ." Qty x Price " . $Product_Out->qty. " x ". $Product_Out->price . " on ". date("Y/m/d"). "by ". $Product_Out->cashier;
        // Refund::create(['product_out_id' => $id, 'po_no' => $Product_Out->po_no, 'refund_date' =>  date("Y/m/d"), 'refund_amount' => $Product_Out->price,  'cashier' => Auth::user()->name]);
        $Product_Out->price = 0;
        $Product_Out->qty = 0;
        $subtotal = $Product_Out->price * $Product_Out->qty ; //in case refund amount is included, 

          if($Product_Out->discount > 0)
            $subtotal = $subtotal - ($subtotal* ($Product_Out->discount/100));
        
        $Product_Out->update(['subtotal' => $subtotal, 'cashier' => Auth::user()->name, 'refund_status' => $refund_status]);

        $product = Product::findOrFail($Product_Out->product_id);
        $product->qty += $Product_Out->qty;
        $product->update();


        return response()->json([
            'success'    => true,
            'message'    => 'Refund Succesfull'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Order::destroy($id);

        return response()->json([
            'success'    => true,
            'message'    => 'Products Delete Deleted'
        ]);
    }



    public function apiOrders(){
        $order = Order::all();

        return Datatables::of($order)
            ->addColumn('products_name', function ($order){
                return $order->product->name;
            })
            ->addColumn('price', function ($order){
                return $order->price;
            })
            ->addColumn('po_no', function ($order){
                return $order->po_no;
            })

            ->addColumn('order_status', function ($order){
                if($order->order_status == 0)
                    return "Payment Received";
                else if($order->order_status == 1)
                    return "Payment Pending";
                else
                    return " order Delivered & complete ". $order->subtotal . "KWD  "  . $order->product->name ." - " . $order->qty. " to ". $order->customer_name . " on ". date("Y/m/d");
            })
            ->addColumn('discount', function ($order){

                if($order->discount == NULL || $order->discount == 0)
                return 0;
                else
                return $order->discount . "%";
            })
            ->addColumn('customer_name', function ($order){
                return $order->customer_name;
            })
            ->addColumn('cashier', function ($order){
                return Auth::user()->name;
            })
            ->addColumn('refund_status', function ($order){
                if($order->refund_status == "0")
                return "No";
             else
             return $order->refund_status;
               
            })

            ->addColumn('multiple_export', function ($order){
                return '<input type="checkbox" name="exportpdf[]" class="checkbox" value="'. $order->id .'">';
            })
            ->addColumn('action', function($order){
                if (Auth::user()->role == "admin" )
                {

                if($order->refund_status == 0)
                {
                return '<a onclick="editForm('. $order->id .')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
                    '<a onclick="deleteData('. $order->id .')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a> '.
                    '<a onclick="refund('. $order->id .')" class="btn btn-success btn-xs"><i class="glyphicon glyphicon-repeat"></i> Refund</a> ';
                }
                else
                 {
                    return '<a onclick="editForm('. $order->id .')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
                    '<a onclick="deleteData('. $order->id .')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a> ';
                 }
                }
                else{

                    if($order->refund_status == 0)
                    {
                    return '<a onclick="editForm('. $order->id .')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
                        '<a onclick="refund('. $order->id .')" class="btn btn-success btn-xs"><i class="glyphicon glyphicon-repeat"></i> Refund</a> ';
                    }
                    else
                     {
                        return '<a onclick="editForm('. $order->id .')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' ;
                     }

                }


            })
            ->rawColumns(['multiple_export','products_name','price', 'po_no', 'order_status', 'discount','customer_name', 'refund_status','cashier', 'action'])->make(true);

    }

    public function exportProductOutAll()
    {
        $Product_Out = Order::all();
        $pdf = PDF::loadView('orders.productOutAllPDF',compact('Product_Out'));
        return $pdf->download('product_out.pdf');
    }

    public function exportProductOut(Request $request)
    {
        $idst = explode(",",$request->exportpdf);
        $idst1 = array_values($idst);
        // dd($idst1);
        
        // $Product_Out = Order::find($idst);
        $Product_Out = \DB::table('orders')
        ->join('products', 'products.id', '=', 'orders.product_id')
        ->join('barcodes', 'barcodes.id', '=', 'products.barcode_id')
        // ->join('customers', 'customers.id', '=', 'product_out.customer_id')
        ->select('products.name as product_name', 'barcodes.name as barcode_name', 'orders.subtotal', 'orders.qty', 'orders.po_no', 'orders.date', 'orders.customer_name')
        ->whereIn('orders.id', $idst1)
        ->get();
        // dd($Product_Out);

        $companyInfo = Company::find(1);
        $view = view('orders.productOutPDF', compact('Product_Out', 'companyInfo'))->render();
        //  dd($Product_Out);
        // 
        // return view('sales.productOutPDF', compact('Product_Out', 'companyInfo'))->render();    
        return response()->json([
            'success'    => true,
            'message'    => 'Order Completed',
            'data'      => $view
        ]);
//  dd($Product_Out);
        // $pdf = PDF::setOptions([
        //     'images' => true,
        //     'isHtml5ParserEnabled' => true, 
        //     'isRemoteEnabled' => true
        // ])->loadView('orders.productOutPDF', compact('Product_Out', 'companyInfo'));//->setPaper('a4', 'portrait')->stream();
        // // return $pdf->download('Product_Out.pdf');
        // return $pdf->download(date("Y-m-d H:i:s",time()).'_Product_Out.pdf');

    }

    public function exportExcel()
    {
        return (new ExportOrders)->download('product_out.xlsx');
    }

    public function checkAvailable($id)
    {
        
        $barcode = \DB::select(\DB::raw("select id from barcodes where name = '$id'"));//product_id is the barcode name
        
        $barcode_id = $barcode[0]->id;
        $find_product = \DB::select(\DB::raw("select id, name from products where barcode_id = $barcode_id"));
         //$Product_Out->update($request->all());
         // $request->product_id = $find_product[0]->name;
        $product_id = $find_product[0]->id; 
        $Product = Product::findOrFail($product_id);
        return $Product;
    }
}
