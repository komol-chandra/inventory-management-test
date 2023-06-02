@extends('layouts.backend.app')
@section('title','Product List')
@push('css')
@endpush
@section('content')
<div class="row">
    {{-- <h4 class="fw-bold py-3 mb-4">Product List</h4>--}}
    <div class="card">
        <div class="col-12 ">
            <h5 class="card-header">Product List <a href="{{route('admin.product.create')}}"
                    class="btn btn-outline-primary">Create New</a></h5>

        </div>
        <div class="table-responsive text-nowrap">
            <form action="{{route('admin.product.index')}}" method="get">
                <div class="row border border-1 mb-3 p-2 d-flex">
                    <div class="col-auto">
                        <select name="per_page" class="form-control select2" required>
                            <option value="10" {{$searched_data['per_page']==='10' ?'selected':''}}>10</option>
                            <option value="15" {{$searched_data['per_page']==='15' ?'selected':''}}>15</option>
                            <option value="50" {{$searched_data['per_page']==='50' ?'selected':''}}>50</option>
                            <option value="100" {{$searched_data['per_page']==='100' ?'selected':''}}>100</option>
                            <option value="500" {{$searched_data['per_page']==='500' ?'selected':''}}>500</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <select name="sorting" class="form-control select2" required>
                            <option>Sorting</option>
                            <option value="latest" {{$searched_data['sorting']==='latest' ?'selected':''}}>Latest
                            </option>
                            <option value="oldest" {{$searched_data['sorting']==='oldest' ?'selected':''}}>Oldest
                            </option>

                        </select>
                    </div>
                    <div class="col-auto">
                        <select class="form-control select2" name="select_day">
                            <option>Select Day</option>
                            <option value="today" {{$searched_data['select_day']=='today' ?'selected':''}}>
                                {{ __('Today') }}</option>
                            <option value="thisWeek" {{$searched_data['select_day']=='thisWeek' ?'selected':''}}>
                                {{ __('This Week') }}</option>
                            <option value="thisMonth" {{$searched_data['select_day']=='thisMonth' ?'selected':''}}>
                                {{ __('This Month') }}</option>
                            <option value="thisYear" {{$searched_data['select_day']=='thisYear' ?'selected':''}}>
                                {{ __('This Year') }}</option>
                        </select>
                    </div>

                    <div class="col-auto">
                        <input type="text" name="search" placeholder="Product name or SKU" class="form-control">
                    </div>
                    <div class="col-auto m-auto">
                        <button class="btn btn-sm btn-success">Submit</button>
                        <a href="{{route('admin.product.index')}}" class="btn btn-sm btn-dark">reset</a>
                    </div>
                </div>
            </form>
            <table class="table">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>SKU</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @php
                    $i = ($list->perPage() * ($list->currentPage() - 1)) + 1;
                    @endphp
                    @foreach ($list as $key =>$value)
                    <tr>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>{{$i++}}</strong></td>
                        <td> <img style="height: 50px; width: 50px" src="{{asset($value->image)}}" alt="Avatar"
                                class="rounded-circle"></td>
                        <td> {{$value->name}}</td>
                        <td> {{$value->sku}}</td>
                        <td> {{$value->stock->sum('available_qty')}}</td>
                        <td><span class="badge {{$value->is_active===1?" bg-label-primary":"bg-label-danger"}}
                                me-1">{{$value->is_active===1 ? "Active":"Inactive"}}</span></td>
                        {{-- <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu" style="">
                                    <a class="dropdown-item" href="javascript:void(0);"><i
                                            class="bx bx-edit-alt me-1"></i>
                                        Edit</a>
                                    <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i>
                                        Delete</a>
                                </div>
                            </div>
                        </td> --}}
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $list->links() }}
        </div>
    </div>
</div>
@endsection