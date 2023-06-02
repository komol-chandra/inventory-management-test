<?php



if (!function_exists('productInfo')) {
    function productInfo($id)
    {
        return \App\Models\Product::select(['name','sku'])->where('id',$id)->first();
    }
}

