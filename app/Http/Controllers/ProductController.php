<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Transformers\ProductTransformer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
class ProductController extends Controller
{
    /**
     * @var Manager
     */
    private $fractal;

    /**
     * @var UserTransformer
     */
    private $productTransformer;

    function __construct(Manager $fractal, productTransformer $productTransformer)
    {
        $this->fractal = $fractal;
        $this->productTransformer = $productTransformer;
    }

    public function index()
    {
        $products = Product::all();
      $products = new Collection($products, $this->productTransformer); // Create a resource collection transformer
        $products = $this->fractal->createData($products); // Transform data

        return $products->toArray(); // Get transformed array of data
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'slug'=>'required',
            'price'=>'required',
        ]);
        return Product::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::where('id',$id)->get();
        $product = new Collection($product, $this->productTransformer); // Create a resource collection transformer
        $product = $this->fractal->createData($product); // Transform data

        return $product->toArray();
          
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
        $product = Product::find($id);
        $product->update($request->all());
        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       Product::destroy($id);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  string $name
     * @return \Illuminate\Http\Response
     */
    public function search($name)
    {
        return Product::where('name','LIKE',"%$name%")->get();
    }
}
