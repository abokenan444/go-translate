<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FileTranslation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileTranslationController extends Controller
{
    /**
     * عرض صفحة ترجمة الملفات
     */
    public function index()
    {
        $user = auth()->user();
        
        // الملفات الأخيرة
        $recentTranslations = FileTranslation::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
        
        // الإحصائيات
        $stats = [
            'total_files' => FileTranslation::where('user_id', $user->id)->count(),
            'completed' => FileTranslation::where('user_id', $user->id)
                ->where('status', 'completed')->count(),
            'processing' => FileTranslation::where('user_id', $user->id)
                ->where('status', 'processing')->count(),
            'failed' => FileTranslation::where('user_id', $user->id)
                ->where('status', 'failed')->count(),
        ];
        
        return view('dashboard.file-translation.index', compact('recentTranslations', 'stats'));
    }
    
    /**
     * رفع ملف للترجمة
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,docx|max:10240', // 10MB
            'source_lang' => 'required|string|max:10',
            'target_lang' => 'required|string|max:10',
            'preserve_layout' => 'boolean',
            'cultural_adaptation' => 'boolean',
        ]);
        
        $user = auth()->user();
        $file = $request->file('file');
        
        // حفظ الملف
        $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('file-translations', $filename, 'public');
        
        // إنشاء سجل الترجمة
        $translation = FileTranslation::create([
            'user_id' => $user->id,
            'original_filename' => $file->getClientOriginalName(),
            'file_type' => $file->getClientOriginalExtension(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'source_language' => $request->source_lang,
            'target_language' => $request->target_lang,
            'preserve_layout' => $request->preserve_layout ?? true,
            'cultural_adaptation' => $request->cultural_adaptation ?? true,
            'status' => 'pending',
        ]);
        
        // تقدير عدد الصفحات والكلمات والتكلفة
        $estimatedPages = 1; // افتراضي
        $estimatedWords = 500; // افتراضي
        
        // محاولة تقدير أفضل بناءً على حجم الملف
        $fileSizeKB = $file->getSize() / 1024;
        if ($file->getClientOriginalExtension() === 'pdf') {
            $estimatedPages = max(1, round($fileSizeKB / 50)); // تقريباً 50KB لكل صفحة
            $estimatedWords = $estimatedPages * 500; // تقريباً 500 كلمة لكل صفحة
        } else {
            $estimatedWords = max(100, round($fileSizeKB * 10)); // تقدير للصور والوثائق
            $estimatedPages = max(1, round($estimatedWords / 500));
        }
        
        // حساب التكلفة (0.05$ لكل كلمة)
        $totalCost = $estimatedWords * 0.05;
        
        // معالجة الملف (سيتم تطبيقها لاحقاً عبر Queue)
        // dispatch(new ProcessFileTranslation($translation));
        
        return response()->json([
            'success' => true,
            'message' => 'تم رفع الملف بنجاح! جاري المعالجة...',
            'file_path' => $path,
            'estimated_pages' => $estimatedPages,
            'estimated_words' => $estimatedWords,
            'total_cost' => $totalCost,
            'translation' => $translation,
        ]);
    }
    
    /**
     * تحميل الملف المترجم
     */
    public function download($id)
    {
        $user = auth()->user();
        $translation = FileTranslation::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();
        
        if ($translation->status != 'completed' || !$translation->translated_file_path) {
            return response()->json([
                'success' => false,
                'message' => 'الملف المترجم غير جاهز بعد!',
            ], 400);
        }
        
        return Storage::disk('public')->download($translation->translated_file_path);
    }
    
    /**
     * حذف ملف
     */
    public function delete($id)
    {
        $user = auth()->user();
        $translation = FileTranslation::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();
        
        // حذف الملفات
        if ($translation->file_path) {
            Storage::disk('public')->delete($translation->file_path);
        }
        if ($translation->translated_file_path) {
            Storage::disk('public')->delete($translation->translated_file_path);
        }
        
        $translation->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'تم حذف الملف بنجاح!',
        ]);
    }
}
