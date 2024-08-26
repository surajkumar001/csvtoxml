@extends('layouts.user_type.auth')

@section('content')
<?php 

$id = $page->id ?? '';
$category = $page->category ?? '';
$fileName = $page->file_name ?? '';
$filePath = $page->file_path ?? '';
$jsonData = $page->json_data ?? '';
$contentData = $jsonData ? json_decode($jsonData) : '';
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
                    <a href="{{route('page')}}" class="btn bg-gradient-dark btn-sm mb-0" type="button">Back</a>
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
                                        <option value="category_1" {{($category == 'category_1' ? 'selected' : '')}}>Category 1</option>
                                        <option value="category_2" {{($category == 'category_2' ? 'selected' : '')}}>Category 2</option>
                                        <option value="category_3" {{($category == 'category_3' ? 'selected' : '')}}>Category 3</option>
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
                                    &nbsp<a href="{{url('storage',$filePath)}}" class="btn btn-success mt-3" download>Download </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="about">{{ 'XML FILE' }}</label>
                                <div class="@error('user.about')border border-danger rounded-3 @enderror">
                                    @if (isset($page->xml_url) && $page->xml_url)
                                        &nbsp<a href="{{url($page->xml_url)}}" class="btn btn-success mt-3" target="_blank">File</a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($contentData) 
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <tbody id="show">
                                        <?php  foreach ($contentData as $tr => $rows) { ?>
                                           <!-- Show row -->
                                           <tr id="show_{{$tr}}">
                                              <?php foreach ($rows as $td => $row) { ?>
                                                <td>
                                                    {{$row}}
                                                </td>
                                                <?php } ?>
                                                <td>
                                                    <i class="btn btn-sm mb-0" onclick="edit('{{$tr}}')">Edit</i>
                                                </td>
                                            </tr> 
                                            <!-- Edit row -->
                                            <tr id="edit_{{$tr}}" style="display:none;">
                                                <?php foreach ($rows as $td => $row) { ?>
                                                    <td>
                                                        <textarea type="text" rows="4" name="json_data[{{$tr}}][{{$td}}]" >{{$row}}</textarea>
                                                    </td>
                                                <?php } ?>
                                                <td>
                                                    <i class="btn btn-success" onclick="show('{{$tr}}')">view</i>
                                                </td>
                                            </tr>  
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">
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
   var edit = document.getElementById("edit_"+e);       
   var show = document.getElementById("show_"+e);       
   edit.style.display = "block";
   show.style.display = "none";
} 

function show(e){
    var edit = document.getElementById("edit_"+e);       
    var show = document.getElementById("show_"+e);       
    edit.style.display = "none";
    show.style.display = "block";
}
</script>


@endsection