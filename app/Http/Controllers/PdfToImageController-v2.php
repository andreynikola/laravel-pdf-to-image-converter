<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;

use Imagick;
use ZipArchive;

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

            session_start();

            $validatedData = $request->validate([
                'file' => 'required|mimes:pdf|max:50000'
            ]);

            $folder = uniqid();
            $directory = public_path('/storage/images/'.$folder."/");

            foreach ($request->file() as $file) {
                $fileName = time().'_'.$file->getClientOriginalName();
                $file->move(public_path('/storage/images/'.$folder), $fileName);
            }

            $_SESSION["progress"] = 0;
            $_SESSION["folder"] = $folder;
            $_SESSION["fileName"] = $fileName;
            $_SESSION["total_pages"] = 0;

            return response()->json([ 'Action' => 'End file uploading' ]);

        }

        if ( $request->action == 'convert' ) {

            session_start();

            // Формируем изображения из pdf файла
            $im = new imagick(public_path('/storage/images/'.$_SESSION["folder"]).'/'.$_SESSION["fileName"]);

            /*if ( $im->getNumberImages() > 500 ) {

                $error = array(
                    'message' => 'Файл должен содержать не более 500 страниц'
                );

                return response()->json($error, 422);

            }*/

            $_SESSION["total_pages"] = $im->getNumberImages();

            for ($i=0; $i < $im->getNumberImages(); $i++) {

                $im->setIteratorIndex($i);
                $im->setImageFormat('jpg');
                $im->writeImage( public_path('/storage/images/'.$_SESSION["folder"]).'/'.$i.".jpg" );

            }

            return response()->json([ 'Action' => 'Сonversion complete. Total '.$_SESSION['total_pages'].' pages' ]);

        }

        if ( $request->action == 'show_progress' ) {

            for($i=1; $i<=$_SESSION["total_pages"]; $i++){
                session_start();
                $_SESSION["progress"] = ( $_SESSION["progress"] < 100 ? round( $i / $_SESSION["total_pages"] * 100 ) : 100 );
                session_write_close();
                sleep(1);
            }

            return response()->json([ 'Progress' => $_SESSION["progress"] ]);

        }

        if ( $request->action == 'get_progress' ) {

            session_start();
            return response()->json([ 'progress' => $_SESSION["progress"] ]);

        }


        return response()->json([ 'progress' => "out of wrapper" ]); 


        // // Формируем архив изображений
        // $images = glob("" . $directory . "*.jpg");

        // $zip = new ZipArchive;
        // $zip->open($directory.'archive.zip', ZIPARCHIVE::CREATE);

        // foreach($images as $image) {
        //     $zip->addFile($image, basename($image));
        // }  

        // $zip->close();
   


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