<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MediaController extends Controller
{
    public function index()
    {
        $media = Media::latest()->get();
        return response()->json($media);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10240', // max 10MB
        ]);

        if (! $request->hasFile('image') || ! $request->file('image')->isValid()) {
            Log::error('Media upload failed: no valid image file present', [
                'has_file' => $request->hasFile('image'),
                'file' => $request->file('image'),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Geçerli bir resim dosyası yüklenmedi.'
            ], 422);
        }

        $file = $request->file('image');
        Log::debug('Media upload file info', [
            'client_original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'path' => $file->getPathname(),
            'error' => $file->getError(),
        ]);

        $originalName = $file->getClientOriginalName();
        $mimeType = $file->getMimeType();
        $size = $file->getSize();

        // Generate a unique filename
        $timestamp = time();
        $filename = $timestamp . '_' . pathinfo($originalName, PATHINFO_FILENAME);
        
        try {
            // Frontend'den gelen saf orijinal resmi yakala, yoksa mevcuttaki kırpılmışı kullan
            $originalFile = $request->file('original_image') ?? $file;

            // 1. Orijinal resmi original_img klasörüne kaydet
            $originalPath = 'media/original_img/' . $filename . '.' . $originalFile->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('media/original_img', $originalFile->getRealPath(), $filename . '.' . $originalFile->getClientOriginalExtension());

            // 2. Resmi yükle ve işle
            $manager = new ImageManager(new Driver());
            $image = $manager->decodePath($file->getPathname());
            
            // 3. Genişlik 1200px'den büyükse 1200px'e kırp (en-boy oranı korunur)
            if ($image->width() > 1200) {
                $image->scale(width: 1200);
            }

            // 4. WebP formatına dönüştür ve ayarlardaki kalite ile kaydet
            $webpQuality = (int) \App\Models\Setting::getVal('webp_quality', 80);
            $webpFilename = $filename . '.webp';
            $webpPath = 'media/gallery/' . $webpFilename;
            
            $webpContent = $image->encodeUsingFileExtension('webp', quality: $webpQuality)->toString();
            Storage::disk('public')->put($webpPath, $webpContent);

            // 5. Veritabanına kaydet
            // ÇAKIŞMAYI ÖNLEMEK İÇİN: Sadece 'media/gallery/...' şeklinde saf yolları kaydediyoruz.
            $media = Media::create([
                'name' => $originalName,
                'file_path' => $webpPath,
                'webp_path' => $webpPath,
                'original_path' => $originalPath,
                'mime_type' => 'image/webp',
                'size' => Storage::disk('public')->size($webpPath),
                'width' => $image->width(),
                'height' => $image->height(),
                'format' => 'WebP'
            ]);

            return response()->json([
                'success' => true,
                'media' => $media
            ]);
        } catch (\Throwable $e) {
            Log::error('Media upload processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Resim işleme başarısız: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Media $media)
    {
        // Delete WebP file from storage
        if ($media->webp_path && Storage::disk('public')->exists($media->webp_path)) {
            Storage::disk('public')->delete($media->webp_path);
        }

        // Delete from database
        $media->delete();

        return response()->json(['success' => true]);
    }

    
}