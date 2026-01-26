<?php

namespace App\Services\Meals ;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageServices
{
    public function upload($newImage)
    {
        $ImageName = Str::uuid() . "." . $newImage->getClientOriginalExtension() ;
        $newImage->storeAs('', $ImageName , 'products') ;
        return $ImageName ;
    }



    public function delete($image)
    {
        if(Storage::disk('products')->exists($image))
        {
            Storage::disk('products')->delete($image);
        }
    }

    public function update($oldImage , $newImage)
    {
        if ($newImage) {
            $this->delete($oldImage);
            return $this->upload($newImage);
        }
        return $oldImage;
    }
}


