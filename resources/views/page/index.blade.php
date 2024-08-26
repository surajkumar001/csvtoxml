@extends('layouts.user_type.auth')

@section('content')

<?php 
$base_url = config('custom.page_base_url');
?>
<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">All Pages</h5>
                        </div>
                        <a href="{{route('addpage')}}" class="btn bg-gradient-primary btn-sm mb-0" type="button">+&nbsp; Add Page</a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        ID
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        URL
                                    </th>
                                     <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Category
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        File
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Creation Date
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pages as $key => $page)
                                <tr>
                                    <td class="ps-4">
                                        <p class="text-xs font-weight-bold mb-0">{{$page->id}}</p>
                                    </td>
                                    <td class="text-center ps-2">
                                        <a href="{{$base_url}}{{$page->url}}" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit user" target="_blank">
                                        <p class="text-xs font-weight-bold mb-0">{{$page->url}}</p>
                                        </a>
                                    </td>
                                    <td class="text-center ps-2">
                                        <p class="text-xs font-weight-bold mb-0">{{isset($page->category) ? $page->category :''}}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{$page->file_name}}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{$page->created_at}}</p>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{route('editpage',[$page->id])}}" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit user">
                                            <i class="fas fa-edit text-secondary btn btn-sm"></i>
                                        </a>
                                        <span>
                                        <!-- Delete File Form -->
                                        <form action="{{ route('pagedelete') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $page->id }}">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this file?');"><i class='fas fa-trash' style='font-size:11px'></i></button>
                                        </form>
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Display pagination links -->
                        <div>
                            {{ $pages->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection