@extends('layouts.backend.app')
@section('title','Purchase View')
@push('css')
@endpush
@section('content')
    <div class="row">
        {{--       <h4 class="fw-bold py-3 mb-4">Product List</h4>--}}
        <div class="card">
            <div class="card-header">
                <h5 class="modal-title" id="exampleModalLabel1">Purchase Information</h5>
                <a href="{{route('admin.purchase.index')}}" class="btn btn-outline-primary">Back</a>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col mb-0">
                        <label for="nameBasic" class="form-label">Data</label>
                        <input type="text" id="nameBasic" class="form-control" disabled value="{{$purchase->date}}">
                    </div>
                    <div class="col mb-0">
                        <label for="emailBasic" class="form-label">Invoice Id</label>
                        <input type="text" id="emailBasic" class="form-control" disabled
                               value="{{$purchase->invoice_id}}">
                    </div>
                    <div class="col mb-0">
                        <label for="dobBasic" class="form-label">Supplier</label>
                        <input type="text" id="dobBasic" class="form-control" disabled value="{{$purchase->supplier}}">
                    </div>

                </div>
                <div class="row mt-2 mb-2" v-if="purchase.products.length > 0">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>SN</th>
                            <th>Product Name</th>
                            <th>Sku</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total Price</th>
                        </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                        @foreach($purchase->items as $key => $product)
                            <tr>
                                <td>{{ ++$key }}</td>

                                <td>{{ $product->product->name }}</td>
                                <td>{{ $product->product->sku }}</td>
                                <td>{{ $product->purchase_qty }}</td>
                                <td>{{ $product->purchase_price }}</td>
                                <td>{{ $product->purchase_price * $product->purchase_qty  }}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                    <div class="mb-3 col-md-9">
                        <label for="product_search" class="form-label">Note</label>
                        <p>{{$purchase->note??''}}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="grand_total" class="form-label">Grand Total </label>
                        <input class="form-control" type="text" id="grand_total" name="grand_total"
                               disabled value="{{$purchase->grand_total}}">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
