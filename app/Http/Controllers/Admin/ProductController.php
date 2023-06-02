<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $list     = Product::query()->with('stock');
        $per_page = $request->per_page ?? 10;
        $sorting  = $request->sorting ?? 'latest';
        if ($sorting === 'latest') {
            $list = $list->latest();
        } else if ($sorting === 'oldest') {
            $list = $list->oldest();
        }
        if ($request->select_day) {
            if ($request->select_day == 'today') {
                $list = $list->thisDay();

            } elseif ($request->select_day == 'thisWeek') {
                $list = $list->thisWeek();
            } elseif ($request->select_day == 'thisMonth') {
                $list = $list->thisMonth();
            } elseif ($request->select_day == 'thisYear') {
                $list = $list->thisYear();
            }
        }

        if ($request->search) {
            $list = $list->fullSearch(trim($request->search));
        }
        $list          = $list->paginate($per_page)->withQueryString();
        $searched_data = [
            'per_page'   => $per_page,
            'sorting'    => $sorting,
            'select_day' => $request->select_day,
        ];
        return view('backend.product.index', compact('list', 'searched_data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.product.create');
    }

    /**
     * @param string $string
     * @return string
     * @throws \Exception
     */
    private function generateUniqueSkuCode(string $string): string
    {
        $storeName = explode(" ", $string);
        $prefix    = "";
        foreach ($storeName as $value) {
            $prefix .= $value[0];
        }
        return strtoupper($prefix) . '-' . strtoupper(Str::random(4)) . '-' . random_int(100000, 999999);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $model          = new Product();
            $requested_data = $request->all();
            $requested_data = Arr::set($requested_data, 'slug', preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name));
            $requested_data = Arr::set($requested_data, 'sku', $this->generateUniqueSkuCode($request->name));
            $requested_data = Arr::set($requested_data, 'sold_qty', 0);
            if ($request->hasFile('image')) {
                $upload = 'uploads/product/' . 'product_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                Image::make($request->file('image'))->save($upload);
                $requested_data = Arr::set($requested_data, 'image', $upload);
            }
            $model->fill($requested_data)->save();
            Alert::success('Success', 'Product Created Successfully !');
            return redirect()->back();
        } catch (\Exception $exp) {
            Alert::error('Error', "System Error !");
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
