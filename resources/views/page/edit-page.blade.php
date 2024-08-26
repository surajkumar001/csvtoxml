@extends('layouts.user_type.auth')

@section('content')
<?php 
$id        = $page->id ?? '';
$category  = $page->category ?? '';
$fileName  = $page->file_name ?? '';
$filePath  = $page->file_path ?? '';
$jsonData  = $page->json_data ?? '';
$header    = $page->header ?? '';
$footer    = $page->footer ?? '';
$body      = $page->body ?? '';
$contentData = $jsonData ? json_decode($jsonData) : '';
$categories = config('custom.categories');
?>
<style type="text/css">
    .jsonEdit_div_main{
        padding: 20px;
        width: 1080px;
        max-height: 600px;
        overflow: auto;
    }
    .jsonEdit_div{
        display: flex;
        align-items: center;
        justify-content: space-between;
        column-gap: 1rem;
    }
    .jsonEdit_div .editData{
        width: 100%;
      
        word-break: break-word;
    }    
    .jsonEdit_div .editData textarea{
        width: 100%;
    }
</style>
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
                <form action="{{($id) ? route('updatepage') : route('storepage')}}" method="POST" role="form text-left" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{$id}}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-name" class="form-control-label">{{ __('Category') }}</label>
                                    <select class="form-control" name="category">
                                        <option value="">--Select--</option>
                                         @foreach($categories as $key => $category_name)
                                        <option value="{{$category_name}}" {{($category == $category_name ? 'selected' : '')}}>{{$category_name}}</option>
                                        @endforeach
                                    </select>                        
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
                                    &nbsp<a href="{{url('storage',$filePath)}}" class="btn btn-success mt-3" download><i class="fa fa-download" style="font-size:14px"></i> Download </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-name" class="form-control-label">{{ __('Header') }}</label>
                                 <textarea type="text" name="header" rows="4" class="form-control"> {{$header}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-name" class="form-control-label">{{ __('Footer') }}</label>
                                 <textarea type="text" name="footer" rows="4" class="form-control">{{$footer}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="user-name" class="form-control-label">{{ __('Body') }}</label>
                                 <textarea type="text" name="body" rows="4" class="form-control">{{$body}}</textarea>
                            </div>
                        </div>
                        @if (isset($page->xml_url) && $page->xml_url)
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="about">{{ 'XML FILE' }}</label>
                                <div class="@error('user.about')border border-danger rounded-3 @enderror">
                                        &nbsp<a href="{{url($page->xml_url)}}" class="btn btn-success mt-3" target="_blank"><i class="fa fa-file" style="font-size:14px"></i> File</a>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($contentData) 
                        <div class="jsonEdit_div_main" >
                            <!-- Start contentData Foreach -->
                            @foreach ($contentData as $tr => $rows)
                                <!-- Show -->
                                <div class="jsonEdit_div" id="show_{{$tr}}">   
                                    @foreach ($rows as $td => $row)
                                        <div class="editData">
                                        {{htmlspecialchars($row)}}
                                        </div>
                                    @endforeach
                                    <div class="editData">
                                        <i class="fas fa-pencil-alt btn btn-sm mb-0" onclick="edit('{{$tr}}')"></i>
                                    </div>
                                </div>
                                <!-- Edit -->
                                <div class="jsonEdit_div" id="edit_{{$tr}}" style="display:none;">   
                                    @foreach ($rows as $td => $row)
                                        <div class="editData">
                                            <textarea type="text" rows="3" name="json_data[{{$tr}}][{{$td}}]" >{{$row}}</textarea>
                                        </div>
                                    @endforeach
                                    <div class="editData">
                                        <i class="fa fa-tasks btn btn-sm mb-0" onclick="show('{{$tr}}')"></i>
                                    </div>
                                </div>
                            @endforeach     
                            <!--End contentData Foreach -->
                        </div>          
                        @endif 
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">
                            <i class="fa fa-save" style="font-size:13px"></i>
                            {{ ($id) ? 'Update' :'Save' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">
    function editData() {
      var show = document.getElementById("show");
      var edit = document.getElementById("edit");
      var edit_btn = document.getElementById("edit_btn");
      if (edit.style.display === "none") {
        edit.style.display = "block";
        show.style.display = "none";
        edit_btn.style.display = "none";
    } else {
        edit.style.display = "none";
        show.style.display = "block";
        edit_btn.style.display = "block";
    }
}

function edit(e){
   $("#edit_"+e).show();
   $("#show_"+e).hide();
}
function show(e){
    $("#edit_"+e).hide();
   $("#show_"+e).show();
}
</script>

@endsection