<?php
namespace App\Http\Traits;


use App\Models\Attachment;
use App\Models\Photo;
use Intervention\Image\Facades\Image;

trait HelperTrait
{
 public function saveImage($pass,$file){

     $fileName   = date('YmdHi').$file->getClientOriginalName();
     $img        = Image::make($file)->resize(440,140);
     $img->save('uploads/media/'.$fileName, 60);
     $rel_data = ['path'=>$fileName];
     $pass->photos()->create($rel_data);
 }
 public function saveAttachment($file){

     $fileName   = date('YmdHi').$file->getClientOriginalName();
     $img        = Image::make($file)->resize(440,140);
     $disk=$img->save('uploads/media/rooms/'.$fileName, 60);
     $attachment =Attachment::create(['name'=>$fileName,'original_name'=>$file->getClientOriginalName(),"size"=>$img->filesize(),
         'mime'=>$file->getMimeType(),'extension'=>$file->extension(),
         'path'=>$file->path(),'alt'=>$file->getClientOriginalName(),
         'hash'=>$file->hashName(),'disk'=>$disk->basePath(),'user_id'=>auth()->id()]);
     return $attachment->id;

 }

}
