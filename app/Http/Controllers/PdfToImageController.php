<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;

use App\Convertation;

class PdfToImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if ($request->hasFile('file')) {

            $validatedData = $request->validate([
                'file' => 'required|mimes:pdf|max:50000'
            ]);

            $folder = uniqid();
            $directory = public_path('/storage/images/'.$folder."/");

            foreach ($request->file() as $file) {
                $fileName = time().'_'.$file->getClientOriginalName();
                $file->move(public_path('/storage/images/'.$folder), $fileName);
            }

            session(['directory' => $directory]);
            session(['folder' => $folder]);
            session(['fileName' => $fileName]);

            return response()->json([ 'Action' => 'Files successfully uploaded' ]);

        }

        if ( $request->action == 'convert' ) {

            Convertation::convert();

            return response()->json([ 'folder' => session('folder') ]);

        }

        return response()->json([ 'Action' => "Out of wrapper" ]); 

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $directory = public_path('/storage/images/'.$id."/");

        $images = glob("" . $directory . "*.jpg");

        foreach($images as $image){ 
            $image = explode('public', $image);
            $src[] = $image[1]; 
        }

        // dd($src);

        return view('store',['images' => $src, 'id' => $id]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}