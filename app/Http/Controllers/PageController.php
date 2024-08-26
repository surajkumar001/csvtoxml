<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use App\Models\Page;
use League\Csv\Reader;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PageController extends Controller
{
 
    public function index()
    {
        $pages = Page::orderBy('id','desc')->paginate(10);
        return view('page/index',compact('pages'));
    }
    public function addPage(Request $request)
    {
        $id = $request->id ? $request->id : '' ;
        $page = Page::find($id);
        return view('page/add-page', compact('page'));
    }
  public function editPage(Request $request)
    {
        $id = $request->id ? $request->id : '' ;
        $page = Page::find($id);
        return view('page/edit-page', compact('page'));
    }

    public function store(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:csv,xlsx', // Adjust file type and size as needed
        ]);

        // Store the file
        $name =  $request->file('file')->getClientOriginalName();
        $fileName = time() .'_'.$name;
        $filePath = $request->file('file')->storeAs('pages', $fileName, 'public');
        $fullPath = Storage::disk('public')->path($filePath);

        if ($request->file('file')->getClientMimeType() === 'text/csv') {
            // Read CSV file using League\Csv
            $csv = Reader::createFromPath($fullPath, 'r');
            $csv->setHeaderOffset(0); // Set the first row as header
            $fileData = $csv->getRecords(); // Get the data as an array of records

        } elseif ($request->file('file')->getClientMimeType() === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
            // Read XLSX file using PhpSpreadsheet
            $spreadsheet = IOFactory::load($fullPath);
            $sheet = $spreadsheet->getActiveSheet();
            $fileData = $sheet->toArray(null, true, true, true); // Convert the sheet to an array
        }

        // Filter out blank rows
        $filteredData = array_filter($fileData, function ($row) {
            // Check if all values in the row are empty
            return array_filter($row); // Returns false if the row is empty
        });

        $xmlFileUrl = $this->convertXmlFile($fileData);

        //Saved Header, Footer, Body in DataBase

        $json_data = array(); 

        foreach ($filteredData as $key => $row) {

            $url = isset($row['A']) ? $row['A'] : '';
            $header = isset($row['B']) ? $row['B'] : '';
            $footer = isset($row['C']) ? $row['C'] : '';
            $body = isset($row['D']) ? $row['D'] : '';
            $body_2 = isset($row['E']) ? $row['E'] : '';

            if($url && $url != 'URL'){

                $json_row = array(
                    'A' => $url,
                    // 'B' => $header,
                    // 'C' => $footer,
                    // 'D' => $body,
                    // 'E' => $body_2,
                    'F' => isset($row['F']) ? $row['F'] : '',
                    'G' => isset($row['G']) ? $row['G'] : '',
                    'H' => isset($row['H']) ? $row['H'] : '',
                );

                $json_data[] = $json_row;

            }

        }
            $data = array(
                        'category' =>  $request->category ?? '',
                        'file_name' => $name ?? '',
                        'file_path' => $filePath ?? '',
                        'json_data' => json_encode($json_data) ?? '',
                        'xml_url' =>  $xmlFileUrl ?? '',
                        'url' =>  $url ?? '',
                        'header' =>  $header ?? '',
                        'footer' =>  $footer ?? '',
                        'body' =>  $body ?? '',
                        'body_2' =>  $body_2 ?? '',
                    );

            $result = Page::create($data);
        
        if ($result) {
            return back()->with('success', 'Page saved successfully.');
        }else{
            return back()->with('errors', 'Page not saved.');
        }
    }

    public function update(Request $request)
    {
        $id = $request->id ?? '';
        $json_data = $request->json_data ? ($request->json_data) : '';

        $request->validate([
            'file' => 'mimes:csv,xlsx', // Adjust file type and size as needed
        ]);

        $page = Page::find($id);

        if($request->file){
            // Store the file
            $name =  $request->file('file')->getClientOriginalName();
            $fileName = time() .'_'.$name;
            $filePath = $request->file('file')->storeAs('pages', $fileName, 'public');
            $fullPath = Storage::disk('public')->path($filePath);

            if ($request->file('file')->getClientMimeType() === 'text/csv') {
                // Read CSV file using League\Csv
                $csv = Reader::createFromPath($fullPath, 'r');
                $csv->setHeaderOffset(0); // Set the first row as header
                $fileData = $csv->getRecords(); // Get the data as an array of records

            } elseif ($request->file('file')->getClientMimeType() === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                // Read XLSX file using PhpSpreadsheet
                $spreadsheet = IOFactory::load($fullPath);
                $sheet = $spreadsheet->getActiveSheet();
                $fileData = $sheet->toArray(null, true, true, true); // Convert the sheet to an array
            }

            // Filter out blank rows
            $filteredData = array_filter($fileData, function ($row) {
                // Check if all values in the row are empty
                return array_filter($row); // Returns false if the row is empty
            });

            $json_data = $filteredData;

            foreach ($filteredData as $key => $row) {

                $url = isset($row['A']) ? $row['A'] : '';
                $header = isset($row['B']) ? $row['B'] : '';
                $footer = isset($row['C']) ? $row['C'] : '';
                $body = isset($row['D']) ? $row['D'] : '';
                $body_2 = isset($row['E']) ? $row['E'] : '';
            }

        }else{

            $name = $page->file_name;
            $filePath = $page->file_path;
            $json_data = $request->json_data ? ($request->json_data) : '';

            $header = $request->header ?? '';
            $footer = $request->footer ?? '';
            $body = $request->body ?? '';
            $body_2 = $request->body_2 ?? '';
        }

        $xmlFileUrl = $this->convertXmlFile($json_data);

        //Saved Header, Footer, Body in DataBase 

        $json = [];

        foreach ($json_data as $row) {

            $url = isset($row['A']) ? $row['A'] : '';
          
            if($url && $url != 'URL'){

                $json_row = array(
                    'A' => $url,
                    // 'B' => $header,
                    // 'C' => $footer,
                    // 'D' => $body,
                    // 'E' => $body_2,
                    'F' => isset($row['F']) ? $row['F'] : '',
                    'G' => isset($row['G']) ? $row['G'] : '',
                    'H' => isset($row['H']) ? $row['H'] : '',
                );

                $json[] = $json_row;

            }

        }
                $data = array(
                            'category' =>  $request->category ?? '',
                            'file_name' => $name ?? '',
                            'file_path' => $filePath ?? '',
                            'json_data' => json_encode($json) ?? '',
                            'xml_url' =>  $xmlFileUrl ?? '',
                            'url' =>  $url ?? '',
                            'header' =>  $header ?? '',
                            'footer' =>  $footer ?? '',
                            'body' =>  $body ?? '',
                            'body_2' =>  $body_2 ?? '',
                            );

                $result = Page::where('id',$id)->update($data);
        
        if ($result) {
            return back()->with('success', 'Page updated successfully.');
        }else{
            return back()->with('errors', 'Page not updated.');
        }
    }

    public function convertXmlFile($data){

        // Create the root XML element
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');
        // Loop through the Excel data and add to XML
        foreach ($data as $row) {

            $url = $xml->addChild('url');
            if (isset($row['A'])) {
                $base_url = config('custom.page_base_url');
                $link = $base_url.$row['A'];
                $url->addChild('loc', htmlspecialchars($link));
            }
            if (isset($row['F'])) {
                $url->addChild('lastmod', htmlspecialchars($row['F']));
            }
            if (isset($row['G'])) {
                $url->addChild('changefreq', htmlspecialchars($row['G']));
            }
            if (isset($row['H'])) {
                $url->addChild('priority', htmlspecialchars($row['H']));
            }
        }
        // Save the XML content to a file in storage
        $xmlFileName = 'sitemap_' . time() . '.xml';
        $xmlFilePath = 'public/xml/' . $xmlFileName;
        Storage::put($xmlFilePath, $xml->asXML());
        // Get the file URL
        return $xmlFileUrl = Storage::url($xmlFilePath);
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');
        $page = Page::find($id);
        $filePath = $page->file_path;
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);

            $page->delete();
            return back()->with('success', 'File deleted successfully.');

        }else{
            return back()->with('error', 'File not found.');
        }
    }

}
