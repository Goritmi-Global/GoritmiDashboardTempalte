<?php

use Illuminate\Support\Facades\Storage;
use App\Models\Upload;

if (!function_exists('store_base64_image')) {
     
    function store_base64_image($base64)
    {
        try {
            $data = explode(',', $base64);
            $imageData = base64_decode($data[1]);
            $filename = uniqid() . '.webp';
            $path = 'uploads/' . $filename;

            Storage::disk('public')->put($path, $imageData);

            $upload = Upload::create([
                'file_original_name' => $filename,
                'file_name' => $filename,
                'file_size' => strlen($imageData),
                'extension' => 'webp',
                'type' => 'image',
            ]);

            return $upload->id;
        } catch (\Exception $e) {
            \Log::error('Image upload failed: ' . $e->getMessage());
            return null;
        }
    }
}


if (!function_exists('get_upload_url')) {
    function get_upload_url($uploadId)
{
    $upload = Upload::find($uploadId);
    return $upload ? Storage::url('uploads/' . $upload->file_name) : null;
}

}
