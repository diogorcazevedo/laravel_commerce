<?php

namespace App\Http\Controllers;


use App\Category;
use App\Http\Requests;
use App\Http\Requests\ProductRequest;
use App\Product;
use App\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProductImageRequest;

class ProductsController extends Controller
{

    /**
     * @var Product
     */
    private $productModel;
    /**
     * @var Category
     */
    private $category;


    public function __construct(Product $productModel, Category $category)
    {


        $this->productModel = $productModel;
        $this->category = $category;
    }


    public function index()
    {
        $products = $this->productModel->paginate(5);

        $products->setPath('products');

        return view('/products/index', compact('products'));

    }

    public function create()
    {
        $categories = $this->category->lists('name', 'id');

        return view('products/create', compact('categories'));

    }

    public function store(ProductRequest $request)
    {
        $data = $request->all();
        $this->productModel->create($data);

        return redirect()->route('products.index');
    }

    public function edit($id)
    {
        $product = $this->productModel->find($id);
        $categories = $this->category->lists('name', 'id');

        return view('products/edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, $id)
    {
        //  $data = $request->all();
        //  $data = $request->except('_token');
        //  $this->productModel->update($data,$id);

        $data = $request->all();
        $this->productModel->find($id)->update($data);

        return redirect()->route('products.index');
    }

    public function destroy($id)
    {

        $this->productModel->find($id)->delete();

        return redirect()->route('products.index');
    }

    /*
     *
     *
     *
     * IMAGE
     *
     *
     */


    public function images($id)
    {

        $product = $this->productModel->find($id);

        return view('products/images', compact('product'));
    }

    public function createImage($id)
    {

        $product = $this->productModel->find($id);

        return view('products/create_image', compact('product'));
    }


    public function storeImage(ProductImageRequest $request, $id, ProductImage $productImage)
    {
        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();

        $image = $productImage::create(['product_id' => $id, 'extension' => $extension]);

        Storage::disk('public_local')->put($image->id . '.' . $extension, File::get($file));

        return redirect()->route('products.images', ['id' => $id]);
    }

    public function destroyImage(ProductImage $productImage, $id)
    {

        $image = $productImage->find($id);

        if(file_exists(public_path().'/uploads/'.$image->id . '.' . $image->extension)){
            Storage::disk('public_local')->delete($image->id . '.' . $image->extension);
        }

        $product = $image->product;
        $image->delete();

        return redirect()->route('products.images', ['id' => $product->id]);
    }
}
