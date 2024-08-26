@extends('layouts.user_type.auth')

@section('content')

<?php 
$id = $media->id ?? '';
$alt_tag = $media->alt_tag ?? '';
?>
<div>
    <div class="container-fluid">
        <div class="container-fluid py-4">
            <div class="card">
                <div class="card-header pb-0 px-3">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">{{($id) ? 'Edit Media' : 'Add Media'}}</h5>
                        </div>
                        <a href="{{route('media')}}" class="btn bg-gradient-dark btn-sm mb-0" type="button"><i class="fa fa-arrow-circle-left" style="font-size:14px"></i> Back</a>
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
                    <form action="{{($id)? route('updatemedia') : route('storemedia')}}" method="POST" role="form text-left" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$id}}">
                        <div class="row">
                           <div class="col-md-4">
                            <div class="form-group">
                                <label for="about">{{ 'Media Upload' }}</label>
                                <div class="@error('user.about')border border-danger rounded-3 @enderror">
                                    <input type="file" class="form-control" name="file">
                                    <!-- Display Error Message Below File Input -->
                                    @if ($errors->has('file'))
                                    <div style="color: red; margin-top: 5px;">
                                        {{ $errors->first('file') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @if($media)
                            <img src="{{url('storage/',$media->file_path)}}" alt="{{$media->alt_tag}}" class="avatar">
                        @endif
                        </div>
                          <div class="col-md-4">
                            <div class="form-group">
                                <label for="about">{{ 'ALT Tage' }}</label>
                                <div class="@error('user.about')border border-danger rounded-3 @enderror">
                                    <input type="text" class="form-control" name="alt_tag" value="{{$alt_tag}}">
                                    </div>
                                </div>
                            </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="user-name" class="form-control-label">{{ __('Status') }}</label>
                                <div class="@error('user.name')border border-danger rounded-3 @enderror">
                                    <select class="form-control" name="status">
                                        <option>--Select--</option>
                                        <option value="1" {{($media && $media->status == 1) ? 'selected' : ''}}>{{ __('Active') }}</option>
                                        <option value="0" {{($media && $media->status == 0) ? 'selected' : ''}}>{{ __('Inactive') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">
                        <i class="fa fa-save" style="font-size:13px"></i>
                    {{ 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection