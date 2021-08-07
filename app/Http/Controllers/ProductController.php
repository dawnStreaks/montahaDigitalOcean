<?php

namespace App\Http\Controllers;

use App\Category;
use App\Barcode;
use App\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Auth;
class ProductController extends Controller
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
        $category = Category::orderBy('name','ASC')
            ->get()
            ->pluck('name','id');
// dd($category);
        $producs = Product::all();
        return view('products.index', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request , [
            'name'          => 'required|string',
            'price'         => 'required',
            'qty'           => 'required',
            // 'barcode'       => 'required',
            'category_id'   => 'required',
        ]);

        $input = $request->all();

        $barcode = new Barcode;
        // if($input['barcode'])
        $barcode->name = $input['name'];
        $barcode->save();
        

        $input['image'] = null;

        if ($request->hasFile('image')){
            $input['image'] = '/upload/products/'.str_slug($input['name'], '-').'.'.$request->image->getClientOriginalExtension();
            $request->image->move(public_path('/upload/products/'), $input['image']);
        }
    // if($input['barcode'])
        $input['barcode_id'] = $barcode->id;
        Product::create($input);
        $bc = \DB::table('products')->select('id')->where('barcode_id', $input['barcode_id'] )->get();
        // dd($bc[0]->id);
        $product = Product::findOrFail($bc[0]->id);
        $input['barcode'] = $product->id. '-' .$barcode->name;
        $bcode = Barcode::findOrFail($input['barcode_id']);
        $bcode->name = $input['barcode'];
        $bcode->save();
        

        return response()->json([
            'success' => true,
            'message' => 'Products Created'
        ]);

    }

    public function checkAvailableName($id)
    {
        $bc = \DB::table('products')->select('name')->where('name', $id )->get();
        // dd(count($bc));
        if(count($bc) > 0)
        {
        return response()->json([
            'success' => true,
            'message' => 'Item exist',
            
        ]);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => '',
                
            ]);

        }
        }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::orderBy('name','ASC')
            ->get()
            ->pluck('name','id');
        $product = Product::find($id);
        $product['barcode'] = $product->barcode->name;
        
        return $product;
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
        $category = Category::orderBy('name','ASC')
            ->get()
            ->pluck('name','id');

        $this->validate($request , [
            'name'          => 'required|string',
            'price'         => 'required',
            'qty'           => 'required',
            // 'barcode'       => 'required',
            'category_id'   => 'required',
        ]);

        $input = $request->all();
        $produk = Product::findOrFail($id);

        $input['image'] = $produk->image;

        if ($request->hasFile('image')){
            if (!$produk->image == NULL){
                unlink(public_path($produk->image));
            }
            $input['image'] = '/upload/products/'.str_slug($input['name'], '-').'.'.$request->image->getClientOriginalExtension();
            $request->image->move(public_path('/upload/products/'), $input['image']);
        }
        $produk->update($input);

         $barcode = Barcode::findOrFail($produk->barcode_id);
         $barcode->name = $produk->id. '-' .$produk->name;
         $barcode->save();

        


        return response()->json([
            'success' => true,
            'message' => 'Products Update'
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
        $product = Product::findOrFail($id);

        if (!$product->image == NULL){
            unlink(public_path($product->image));
        }

        Product::destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'Products Deleted'
        ]);
    }

    public function apiProducts(){
        $product = Product::all();
        // var_dump($pbarcode);


        return Datatables::of($product)
            ->addColumn('category_name', function ($product){
                return $product->category->name;
            })
            ->addColumn('barcode_name', function ($product){
                return $product->barcode->name;
            })
            ->addColumn('barcode_image', function ($product){
                return '<a href="https://barcode.tec-it.com/barcode.ashx?data='.$product->barcode->name.'&code=Code128&dpi=96&imagetype=Png&download=true" style="margin: 0 auto;display: block;text-align:center;" title="Download Barcode" target="_blank" download><img class="img-responsive img-thumbnail" src="https://barcode.tec-it.com/barcode.ashx?data='.$product->barcode->name.'&code=Code128&dpi=96"><br>Download</a>';
                // return '<a id="printBarcode" href="https://barcode.tec-it.com/barcode.ashx?data='.$product->barcode->name.'&code=Code128&dpi=96&imagetype=Png&download=false" style="margin: 0 auto;display: block;text-align:center;" title="Print Barcode" target="_blank" onclick="printBarcode(this.id)" ><img class="img-responsive img-thumbnail" src="https://barcode.tec-it.com/barcode.ashx?data='.$product->barcode->name.'&code=Code128&dpi=96"></a>';

            })
            
            ->addColumn('show_photo', function($product){
                if ($product->image == NULL){
                    return 'No Image';
                }
                return '<img class="rounded-square" width="100" src="'. url($product->image) .'" alt="">';
            })
            ->addColumn('action', function($product){
                if (Auth::user()->role == "admin" )
                    {
                return '<a onclick="editForm('. $product->id .')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
                    '<a onclick="deleteData('. $product->id .')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
                    }
                    else
                    {
                        return '<a onclick="editForm('. $product->id .')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' ;
    
                    }
            })
            ->rawColumns(['barcode','barcode_image','category_name','show_photo','action'])->make(true);
    }
}
