<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use App\Models\Media;

class MediaController extends Controller
{

    public function index()
    {
        $media = Media::orderBy('id','desc')->paginate(10);
        return view('media/index',compact('media'));
    }

    public function addMedia(Request $request)
    {
        $id = $request->id ? $request->id : '' ;
        $media = Media::find($id);    
        return view('media/add-media',compact('media'));
    }

    public function store(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png,bmp,gif,svg,webp|max:2048', // Adjust file type and size as needed
        ]);
        // Store the file
        $name = $request->file('file')->getClientOriginalName();
        $fileName = time() .'_'. $name;
        $filePath = $request->file('file')->storeAs('media', $fileName, 'public');
        $data = array(
                    'file_name' =>  $name ?? '',
                    'file_path' =>  $filePath ?? '',
                    'alt_tag' =>  $request->alt_tag ?? '',
                    'status' =>  $request->status ?? '',
                );

        // print_r($data);
        // exit;

        $result = Media::create($data);
        if ($result) {
            return back()->with('success', 'File uploaded successfully.');
        }else{
            return back()->with('error', 'File not uploaded.');
        }
    }

    public function update(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'mimes:jpg,jpeg,png,bmp,gif,svg,webp', // Adjust file type and size as needed
        ]);

        $id = $request->id;
        $media = Media::find($id);

        if($request->file){
            // Store the file
            $name = $request->file('file')->getClientOriginalName();
            $fileName = time() .'_'. $name;
            $filePath = $request->file('file')->storeAs('media', $fileName, 'public');
        }else{
            $name = $media->file_name;
            $filePath = $media->file_path;
        }
      
        $data = array(
                    'file_name' =>  $name ?? '',
                    'file_path' =>  $filePath ?? '',
                    'alt_tag' =>  $request->alt_tag ?? '',
                    'status' =>  $request->status ?? '',
                    );

        $result = Media::where('id',$id)->update($data);
        if ($result) {
            return back()->with('success', 'File uploaded successfully.');
        }else{
            return back()->with('error', 'File not uploaded.');
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');
        $media = Media::find($id);
        $filePath = $media->file_path;
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);

            $media->delete();
            return back()->with('success', 'File deleted successfully.');
        }else{
            return back()->with('error', 'File not found.');
        }
    }


}
