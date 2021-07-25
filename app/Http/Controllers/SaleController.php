<?php

namespace App\Http\Controllers;

use App\Category;
use App\Customer;
use App\Exports\ExportProductOut;
use App\Product;
use App\Product_Out;
use App\Temp_Sale;
use App\Company;
use App\Sale_New;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use PDF;
use Auth;
// use Barryvdh\DomPDF\Facade as PDF;
use App\Barcode;
use Illuminate\Support\Facades\Response;



class SaleController extends Controller
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
        return view('sales.index', compact('products','customers', 'invoice_data'));
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
     * 
     * 
     */
    //code to scan barcode is written here
    public function store(Request $request)
    {
         $i = 1;
        // $this->validate($request, [
        //    'product_id'     => 'required',
        //    'customer_id'    => 'required',
        //    'qty'            => 'required',
        //    'date'           => 'required'
        // ]);

        // Product_Out::create($request->all());

       
        // return response()->json([
        //     'success'    => true,
        //     'message'    => 'Products Out Created'
        // ]);
        
        $this->validate($request, [
            'barcode_name'     => 'required',
            // 'customer_id'    => 'required',
            // 'qty'            => 'required',
            // 'date'           => 'required'
        ]);

       // $barcode = Barcode::findOrFail($request->barcode_name);
       // var_dump($barcode);
       $barcode = \DB::select(\DB::raw("select id from barcodes where name = '$request->barcode_name'"));
        
       $barcode_id = $barcode[0]->id;
        $find_product = \DB::select(\DB::raw("select id, price from products where barcode_id = $barcode_id"));
       // $Product_Out = Product_Out::findOrFail($find_product->id);
        //$Product_Out->update($request->all());
        $input['product_id'] = $find_product[0]->id;
        $input['price'] = $find_product[0]->price;
        $input['customer_id'] = 3;
        $input['po_no'] = $i++;
        $input['qty'] = 1;
        $input['discount'] = 0;
        $input['subtotal'] = $find_product[0]->price * 1;
        $input['date'] = date("Y/m/d");
       
        // Product_Out::create($input);
        Temp_Sale::create($input);


        $product = Product::findOrFail($input['product_id']);
        $product->qty -= $request->qty;
        $product->update();

        return response()->json([
            'success'    => true,
            'message'    => ' '
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
        $Product_Out = Temp_Sale::find($id);
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
            'price'          => 'required',
            'discount'            => 'required',
            'qty'            => 'required',
            'date'           => 'required'
        ]);

        $Temp_Sale = Temp_Sale::findOrFail($id);
       
          $subtotal = $request->price * $request->qty ;
          if($request->discount > 0)
            $subtotal = $subtotal - ($subtotal* ($request->discount/100));
      
        $Temp_Sale->update(array_merge($request->all(), ['subtotal' => $subtotal]));

        $product = Product::findOrFail($request->product_id);
        $product->qty -= $request->qty;
        $product->update();

        return response()->json([
            'success'    => true,
            'message'    => 'Changed Successfully'
        ]);
    }

    public function barcodescan(Request $request)
    {
        $this->validate($request, [
            'barcode_name'     => 'required',
            // 'customer_id'    => 'required',
            // 'qty'            => 'required',
            // 'date'           => 'required'
        ]);

        $barcode = barcodes::findOrFail($barcode_name);
        $find_product = Product::findOrFail($barcode->id);
       // $Product_Out = Product_Out::findOrFail($find_product->id);
        //$Product_Out->update($request->all());
        $input['product_id'] = $find_product->id;
        $input['customer_id'] = "";
        $input['qty'] = 1;
        $input['date'] = date("Y/m/d");
        $input['discount'] = 0;
        $input['subtotal'] = $find_product[0]->price * 1;
        // Product_Out::create($request->all());

        $product = Product::findOrFail($request->product_id);
        $product->qty -= $request->qty;
        $product->update();

        return response()->json([
            'success'    => true,
            'message'    => 'Product Out Updated'
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
        Temp_Sale::destroy($id);

        return response()->json([
            'success'    => true,
            'message'    => 'Products Delete Deleted'
        ]);
    }



    public function apiProductsOut(){
        $product = Temp_Sale::all();
        
        return Datatables::of($product)
            ->addColumn('products_name', function ($product){
                return $product->product->name;
            })
            ->addColumn('price', function ($product){
                return $product->price;
            })
            // ->addColumn('multiple_export', function ($product){
            //     return '<input type="checkbox" name="exportpdf[]" class="checkbox" value="'. $product->id .'">';
            // })
            ->addColumn('action', function($product){
                if (Auth::user()->role == "admin" )
                {
                return '<a onclick="editForm('. $product->id .')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
                    '<a onclick="deleteData('. $product->id .')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a> ';
                }
                else{
                    return '<a onclick="editForm('. $product->id .')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' ;

                }
            })
            ->rawColumns(['products_name','price','action'])->make(true);

    }

    public function exportProductOutAll()
    {
        $Product_Out = Temp_Sale::all();
        $companyInfo = Company::find(1);
        $pdf = PDF::loadView('sales.productOutAllPDF',compact('Product_Out', 'companyInfo'));
        return $pdf->download('sales.pdf');
    }

    public function exportProductOut(Request $request)
    {
        $idst = explode(",",$request->exportpdf);
        $Product_Out = Product_Out::find($idst);
        $companyInfo = Company::find(1);

        $pdf = PDF::setOptions([
            'images' => true,
            'isHtml5ParserEnabled' => true, 
            'isRemoteEnabled' => true
        ])->loadView('sales.productOutPDF', compact('Product_Out', 'companyInfo'))->setPaper('a4', 'portrait')->stream();
        return $pdf->download(date("Y-m-d H:i:s",time()).'_Product_Out.pdf');
    }

    public function exportExcel()
    {
        return (new ExportProductOut)->download('sales.xlsx');
    }

    public function checkAvailable($id)
    {
        $Product = Product::findOrFail($id);
        return $Product;
    }

    public function order_complete()
    {
        $i = 0;
        $temp_sales = Temp_Sale::all();
        $total_amount = \DB::select(\DB::raw("select SUM((price - (price * (discount/100))) * qty) as sum from temp_sales "));
        //  dd($total_amount[0]->sum);// foreach($temp_sales as $object));
        
            $input['po_no'] = rand(1, 99999);
            $input['total_amount'] = $total_amount[0]->sum;
            $input['date'] = date("Y/m/d");
            $input['customer_id'] = 3;
            $input['cashier'] = Auth::user()->name;
            $input['refund_status'] = 0;


        // 'invoice_link' => "/sales"
    // ]);
        Sale_New::create($input);
        // $this->exportProductOutAll();
        // foreach($temp_sales as $object)
        // {
            
            $arrays[] = $temp_sales->toArray();
            // print_r($arrays);
            foreach($arrays[0] as $item)
            {
                // dd($item);
          
                $test['po_no'] = $input['po_no'];
                $test['product_id'] = $item['product_id'];
                
                $test['price'] = $item['price'];
                
                
                $test['qty'] = $item['qty'];
                $test['date'] = $item['date'];
                $test['refund_status'] = 0;
                if($item['discount']>0)    
                {
                $test['subtotal'] =  ($item['price'] - ( $item['price']* ( $item['discount']/100))) * $item['qty']; 
                $test['discount'] = $item['discount'];    
                }
                else{
                    $test['subtotal'] = $item['price'];
                    $test['discount'] = 0;
                    }
                $test['cashier'] = Auth::user()->name;
                $test['customer_id'] = 3;



                
               Product_Out::insert($test);
               $product = Product::findOrFail($item['product_id']);
               $product->qty -= $item['qty'];
               $product->save();

                // print_r($test);


            // }


           }


        $delete_temp_sales = \DB::select(\DB::raw("truncate table temp_sales"));
        // $Product_Out = \DB::select(\DB::raw('select * from product_out where po_no =' . $input['po_no']));
        $Product_Out = \DB::table('product_out')
            ->join('products', 'products.id', '=', 'product_out.product_id')
            ->join('barcodes', 'barcodes.id', '=', 'products.barcode_id')
            ->select('products.name as product_name', 'barcodes.name as barcode_name', 'product_out.price', 'product_out.subtotal', 'product_out.qty', 'product_out.po_no', 'product_out.date')
            ->where('po_no', $input['po_no'] )
            ->get();
        
        $companyInfo = Company::find(1);

    //    dd($Product_Out);

        // $pdf = PDF::setOptions([
        //     'images' => true,
        //     'isHtml5ParserEnabled' => true, 
        //     'isRemoteEnabled' => true
        // ])->loadView('sales.productOutPDF', compact('Product_Out', 'companyInfo'))->setPaper('a4', 'portrait')->stream('test.pdf');
        // // $pdf->download(date("Y-m-d H:i:s",time()).'_Product_Out.pdf');
        // $pdf = PDF::loadView('sales.productOutPDF', compact('Product_Out', 'companyInfo'));
        // $pdf->stream('my.pdf',array('Attachment'=>0));
        $view = view('sales.productOutPDF', compact('Product_Out', 'companyInfo'))->render();

        // return view('sales.productOutPDF', compact('Product_Out', 'companyInfo'))->render();    
        return response()->json([
            'success'    => true,
            'message'    => 'Order Completed',
            'data'      => $view
        ]);
    }


}

