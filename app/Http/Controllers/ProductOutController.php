<?php

namespace App\Http\Controllers;

use App\Category;
use App\Customer;
use App\Exports\ExportProductOut;
use App\Product;
use App\Product_Out;
use App\Company;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use PDF;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Auth;
use App\User;
use App\Refund;

class ProductOutController extends Controller
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


        $customers = Customer::orderBy('name','ASC')
            ->get()
            ->pluck('name','id');


        $invoice_data = Product_Out::all();
        return view('product_out.index', compact('products','customers', 'invoice_data'));
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
           'customer_id'    => 'required',
           'qty'            => 'required',
           'date'           => 'required'
        ]);
        $barcode = \DB::select(\DB::raw("select id from barcodes where name = '$request->product_id'"));//product_id is the barcode name
        
       $barcode_id = $barcode[0]->id;
        $find_product = \DB::select(\DB::raw("select id, name from products where barcode_id = $barcode_id"));
        //$Product_Out->update($request->all());
        // $request->product_id = $find_product[0]->name;
        $product_id = $find_product[0]->id;
        // dd($product_id);
        // $price = $find_product[0]->price;
        $price = $request->price;
      
        // $price = \DB::table('products')->select('price')->where('id', $request['product_id'] )->get();
        $subtotal = $price * $request->qty ;
        if($request->discount > 0)
        $subtotal = $subtotal - ($subtotal* ($request->discount/100));
        Product_Out::create(array_merge($request->all(), ['product_id' =>$product_id , 'po_no' => rand(1, 99999), 'price' => $price, 'refund_status' => 0, 'subtotal' => $subtotal, 'cashier' => Auth::user()->name]));
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
        $Product_Out = Product_Out::find($id);
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
            'customer_id'    => 'required',
            'price'    => 'required',
            'qty'            => 'required',
            'date'           => 'required',
            
        ]);

        $Product_Out = Product_Out::findOrFail($id);
        $subtotal =  $request->price * $request->qty ;

          if($request->discount > 0)
            $subtotal = $subtotal - ($subtotal* ($request->discount/100));
        
        $Product_Out->update(array_merge($request->all(), ['subtotal' => $subtotal, 'cashier' => Auth::user()->name]));

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
        

        $Product_Out = Product_Out::findOrFail($id);
         $refund_status = "Refund of ". $Product_Out->subtotal . "KWD  "   ." Qty x Price " . $Product_Out->qty. " x ". $Product_Out->price . " on ". date("Y/m/d"). "by ". $Product_Out->cashier;
        // Refund::create(['product_out_id' => $id, 'po_no' => $Product_Out->po_no, 'refund_date' =>  date("Y/m/d"), 'refund_amount' => $Product_Out->price,  'cashier' => Auth::user()->name]);
        $product = Product::findOrFail($Product_Out->product_id);
        $product->qty += $Product_Out->qty;
        $product->update();

        $Product_Out->price = 0;
        $Product_Out->qty = 0;
        $subtotal = $Product_Out->price * $Product_Out->qty ; //in case refund amount is included, 

          if($Product_Out->discount > 0)
            $subtotal = $subtotal - ($subtotal* ($Product_Out->discount/100));
        
        $Product_Out->update(['subtotal' => $subtotal, 'cashier' => Auth::user()->name, 'refund_status' => $refund_status]);



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
        Product_Out::destroy($id);

        return response()->json([
            'success'    => true,
            'message'    => 'Products Delete Deleted'
        ]);
    }



    public function apiProductsOut(){
        $product = Product_Out::all();
        // $refund = Refund::all();
// dd($product);
        return Datatables::of($product)
            ->addColumn('products_name', function ($product){
                return $product->product->name;
            })
            ->addColumn('price', function ($product){
                return $product->price;
            })
            ->addColumn('po_no', function ($product){
                return $product->po_no;
            })

            ->addColumn('refund_status', function ($product){
                if($product->refund_status == "0")
                    return "No";
                 else
                 return $product->refund_status;

                //     return "Refund of ". $product->subtotal . "KWD  "  . $product->product->name ." - " . $product->qty. " by ". $product->customer->name . " on ". date("Y/m/d");
            })
            ->addColumn('discount', function ($product){

                if($product->discount == NULL || $product->discount == 0)
                return 0;
                else
                return $product->discount . "%";
            })
            ->addColumn('customer_name', function ($product){
                return $product->customer->name;
            })
            ->addColumn('cashier', function ($product){
                return Auth::user()->name;
            })

            ->addColumn('multiple_export', function ($product){
                return '<input type="checkbox" name="exportpdf[]" class="checkbox" value="'. $product->id .'">';
            })
            ->addColumn('action', function($product){
                if (Auth::user()->role == "admin" )
                {

                if($product->refund_status == 0)
                {
                return '<a onclick="editForm('. $product->id .')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
                    '<a onclick="deleteData('. $product->id .')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a> '.
                    '<a onclick="refund('. $product->id .')" class="btn btn-success btn-xs"><i class="glyphicon glyphicon-repeat"></i> Refund</a> ';
                }
                else
                 {
                    return '<a onclick="editForm('. $product->id .')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
                    '<a onclick="deleteData('. $product->id .')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a> ';
                 }
                }
                else{

                    if($product->refund_status == 0)
                    {
                    return '<a onclick="editForm('. $product->id .')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
                        '<a onclick="refund('. $product->id .')" class="btn btn-success btn-xs"><i class="glyphicon glyphicon-repeat"></i> Refund</a> ';
                    }
                    else
                     {
                        return '<a onclick="editForm('. $product->id .')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' ;
                     }

                }

            })
            ->rawColumns(['multiple_export','products_name','price', 'po_no', 'refund_status', 'discount','customer_name','cashier', 'action'])->make(true);

    }

    public function exportProductOutAll()
    {
        $Product_Out = Product_Out::all();
        $pdf = PDF::loadView('product_out.productOutAllPDF',compact('Product_Out'));
        return $pdf->download('product_out.pdf');
    }

    public function exportProductOut(Request $request)
    {
        $idst = explode(",",$request->exportpdf);
        $idst1 = array_values($idst);
        // dd($idst1);
        
        // $Product_Out = Product_Out::find($idst);
        $Product_Out = \DB::table('product_out')
        ->join('products', 'products.id', '=', 'product_out.product_id')
        ->join('barcodes', 'barcodes.id', '=', 'products.barcode_id')
        ->join('customers', 'customers.id', '=', 'product_out.customer_id')
        ->select('products.name as product_name', 'customers.name as customer_name', 'barcodes.name as barcode_name', 'product_out.subtotal', 'product_out.qty', 'product_out.po_no', 'product_out.date', 'customers.address', 'customers.email')
        ->whereIn('product_out.id', $idst1)
        ->get();
        // dd($Product_Out);

        $companyInfo = Company::find(1);
       $view = view('product_out.productOutPDF', compact('Product_Out', 'companyInfo'))->render();
//  dd($Product_Out);
// 
// return view('sales.productOutPDF', compact('Product_Out', 'companyInfo'))->render();    
return response()->json([
    'success'    => true,
    'message'    => 'Order Completed',
    'data'      => $view
]);


    }

    public function exportExcel()
    {
        return (new ExportProductOut)->download('product_out.xlsx');
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
