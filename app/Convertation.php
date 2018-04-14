<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

use Imagick;
use ZipArchive;

class Convertation extends Model
{
    //
    public static function uploadFile(){

    	$array = array('key' => 'value');
    	return $array;

    }

    public static function convert(){

        // Формируем изображения из pdf файла
        $im = new imagick(public_path('/storage/images/'.session('folder')).'/'.session('fileName'));

        if ( $im->getNumberImages() > 500 ) {

            $error = array(
                'message' => 'Файл должен содержать не более 500 страниц'
            );

            return response()->json($error, 422);

        }

        for ($i=0; $i < $im->getNumberImages(); $i++) {

            $im->setIteratorIndex($i);
            $im->setImageFormat('jpg');
            $im->writeImage( public_path('/storage/images/'.session('folder')).'/'.$i.".jpg" );

        }

        // Формируем архив изображений
        $images = glob("" . session('directory') . "*.jpg");

        $zip = new ZipArchive;
        $zip->open(session('directory').'archive.zip', ZIPARCHIVE::CREATE);

        foreach($images as $image) {
            $zip->addFile($image, basename($image));
        }  

        $zip->close();

    }

}