<?php

namespace App\Http\Controllers;

use App\Charts\ProductChart;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $product = Product::all();
        if($request->ajax()){
            return datatables()->of($product)
            ->addColumn('action', function($data){
                $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Edit" class="edit btn btn-info btn-sm edit-post"><i class="far fa-edit"></i> Edit</a> ';
                $button .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger btn-sm"><i class="far fa-trash-alt"></i> Delete</button>';     
                return $button;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        
        return view('home');
    }
    
    public function store(Request $request)
    {
        $id = $request->id;
        
        $data   =   Product::updateOrCreate(['id' => $id],
        [
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
        ]); 
        
        return response()->json($data);
    }
    
    public function edit($id)
    {
        $where = array('id' => $id);
        $data  = Product::where($where)->first();
        
        return response()->json($data);
    }
    
    public function destroy($id)
    {
        $data = Product::where('id',$id)->delete();
        
        return response()->json($data);
    }

    
    public function chart()
    {
        $month = ['June', 'July', 'August'];

        $product = [];

        foreach ($month as $key => $value) {
            $product[] = Product::where(DB::raw("DATE_FORMAT(created_at, '%M')"),$value)->count();
        }

        $productChart = new ProductChart;
        $productChart->labels($month);
        $productChart->dataset('Product', 'bar', $product);

        return view('chart', [ 'productChart' => $productChart ] );
    }
}
