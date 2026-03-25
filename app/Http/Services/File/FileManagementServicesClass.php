<?php


namespace App\Http\Services\File;


use App\Exceptions\ErrorMsgException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class FileManagementServicesClass{

    public static $acceptedExtensions = ['jpeg','jpg','png','heic','webp','pdf'];

    /**
     * @note store files from request
     */
    public static function storeFiles($media,$folderName,$mediaName='default'){
        $mediaName = pathinfo($media->getClientOriginalName(), PATHINFO_FILENAME);

        $folderName = str_replace(' ','-',$folderName);

        $folderName = $folderName.'/'.date('Y-m-d');
        
        $mediaName = Carbon::now()->timestamp . Carbon::now()->millisecond . '.' . $media->extension();
        // if(!in_array($media->extension(),static::$acceptedExtensions)){
        //     throw new ErrorMsgException(transMsg('invalid_file_type',['types'=>implode(',',static::$acceptedExtensions)]));
        // }
        $media->storeAs($folderName, $mediaName, 'public');
        $path = $folderName;

        $image = $path . '/' . $mediaName;
        return $image;
    }

    /**
     * store the base64 file and return the path in file storage
     * @var string $base64_file
     * @var string $folderName
     * @var string $mediaName
     * @return string
     */
    public static function storeBase64File($base64_file,$folderName,$mediaName='default'){

        $folderName = str_replace(' ','-',$folderName);
        $folderName = $folderName.'/'.date('Y-m-d');

        if(!str_contains($base64_file,';'))
            throw new ErrorMsgException('invalid file format');

        $explodedBase64 = explode(';', $base64_file);
        $type = $explodedBase64[0];
        $file_string = $explodedBase64[count($explodedBase64)-1];

        // explode the type string to get extension from it
        $typeElements = explode('/', $type);

        // extension will be the last item in the array
        $fileExtension = $typeElements[count($typeElements)-1];
        // if(!in_array($fileExtension,static::$acceptedExtensions)){
        //     throw new ErrorMsgException(transMsg('invalid_file_type',['types'=>implode(',',static::$acceptedExtensions)]));
        // }

        $mediaName = $mediaName.'-'.Carbon::now()->microsecond . '.' . $fileExtension;

        list(, $fileEncoded) = explode(',', $file_string);
        Storage::disk('public')->put($folderName.'/'.$mediaName, base64_decode($fileEncoded));

        $path = 'storage'.'/'.$folderName.'/'.$mediaName;

        return $path;
    }



    public static function storeManyFiles($medias,$path){
        $paths = [];
        foreach ($medias as $media){
            $paths[] = static::storeFiles(
                $media,$path
            );
        }
        return $paths;
    }

    /**
     * @param $value
     * @param string|null $default_image
     * @return string|null
     */
    public static function getFileAttribute($value, $default_image = null){
        
        return isset($value) && $value !== '' ? baseRoute() . $value : $default_image;
    }

    /**
     * Delete a file safely
     *
     * @param string|null $filePath
     * @return bool
     */
    public static function deleteFile(?string $filePath): bool
    {
        if (!$filePath) {
            return false;
        }

        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->delete($filePath);
        }

        return false;
    }
}
