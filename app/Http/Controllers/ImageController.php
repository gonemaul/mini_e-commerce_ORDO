<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function uploadImage(Request $request){
         // Validasi file
         $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048' // Sesuaikan validasi sesuai kebutuhan
        ]);

        // Simpan file
        $imageName = time() . '.' . $request->image->extension();
        $imagePath = $request->image->store('product_image', 'public');

        // Simpan path gambar ke database atau lakukan tindakan lain sesuai kebutuhan
        ProductImage::create([
            'image' => $imagePath,
        ]);
        return response()->json(['success' => true, 'message' => 'Gambar berhasil diunggah', 'path' => $imagePath, 'status' => 'Complete', 'color' => '#00B125'],200);
    }

    public function deleteImage(Request $request){
        $path = ProductImage::where('image', $request->image)->first();
        $path->delete();
        Storage::delete('public/'.$request->image);
        return response()->json(['success' => true, 'message' => 'Gambar berhasil dihapus', 'path' => $request->image, 'status' => 'Removed', 'color' => '#515151'],200);
    }
}
