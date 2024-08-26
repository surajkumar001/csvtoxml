@extends('layouts.user_type.auth')

@section('content')
<?php 

$id = $page->id ?? '';
$category = $page->category ?? '';
$fileName = $page->file_name ?? '';
$filePath = $page->file_path ?? '';

$categories = config('custom.categories');

?>

<div>
    <div class="container-fluid">
<div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                 <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">{{($id) ? 'Edit Page' : 'Add Page'}}</h5>
                        </div>
                        <a href="{{route('page')}}" class="btn bg-gradient-dark btn-sm mb-0" type="button"><i class="fa fa-arrow-circle-left" style="font-size:14px"></i> Back</a>
                    </div>
            </div>
        
            <div class="card-body pt-4 p-3">
                <!-- Display Success Message -->
                @if (session('success'))
                    <p style="color: green;">{{ session('success') }}</p>
                @endif
                @if (session('errors'))
                    <p style="color: green;">{{ session('error') }}</p>
                @endif
                <form action="{{($id)? route('updatepage') : route('storepage')}}" method="POST" role="form text-left" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{$id}}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-name" class="form-control-label">{{ __('Category') }}</label>
                                <div class="@error('user.name')border border-danger rounded-3 @enderror">
                                    <select class="form-control" name="category">
                                        <option value="">--Select--</option>
                                        @foreach($categories as $key => $category_name)
                                        <option value="{{$category_name}}" {{($category == $category_name ? 'selected' : '')}}>{{$category_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    <div class="col-md-6">
                    <div class="form-group">
                        <label for="about">{{ 'File Upload' }}</label>
                        <div class="@error('user.about')border border-danger rounded-3 @enderror">
                            <input type="file" class="form-control" name="file">
                            <!-- Display Error Message Below File Input -->
                            @if ($errors->has('file'))
                                <div style="color: red; margin-top: 5px;">
                                    {{ $errors->first('file') }}
                                </div>
                            @endif
                            @if ($fileName && $filePath)
                            <span><b>File : </b>{{$fileName}}</span>

                            @endif

                        </div>
                    </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">
                        <i class="fa fa-save" style="font-size:13px"></i>
                        {{ ($id) ? 'Update' :' Save' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection