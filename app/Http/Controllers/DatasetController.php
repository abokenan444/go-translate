<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Dataset\DatasetExportService;
use Illuminate\Support\Facades\Storage;

class DatasetController extends Controller
{
    public function export(Request $request)
    {
        $service = new DatasetExportService();
        $result = $service->exportJsonl();
        return response()->json(['success'=>true,'path'=>$result['path'],'count'=>$result['count']]);
    }

    public function download(Request $request)
    {
        $file = $request->query('file');
        if (!$file || !Storage::disk('local')->exists($file)) {
            return response()->json(['success'=>false,'message'=>'File not found'],404);
        }
        return response()->download(Storage::disk('local')->path($file));
    }
}
