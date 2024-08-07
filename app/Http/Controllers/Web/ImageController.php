<?php

namespace App\Http\Controllers\Web;

use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class ImageController extends Controller
{
    public function uploadImage(Request $request){
         // Validasi file
         $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048' // Sesuaikan validasi sesuai kebutuhan
        ]);

        $imagePath = $request->image->store('product_image', 'public');

        // Simpan path gambar ke database atau lakukan tindakan lain sesuai kebutuhan
        ProductImage::create([
            'path' => $imagePath,
        ]);
        return response()->json(['success' => true, 'message' => 'Gambar berhasil diunggah', 'path' => $imagePath, 'status' => 'Complete', 'color' => '#00B125'],200);
    }

    public function deleteImage(Request $request){
        $path = ProductImage::where('path', $request->image)->first();
        $path->delete();
        Storage::delete($request->image);
        return response()->json(['success' => true, 'message' => 'Gambar berhasil dihapus', 'path' => $request->image, 'status' => 'Removed', 'color' => '#515151'],200);
    }
}
