<?php

namespace App\Http\Controllers\Administrator;

use App\Enums\ExportImportStatus;
use App\Enums\ExportImportType;
use App\Exports\ProductsExport;
use App\Http\Controllers\Controller;
use App\Models\ExportImport;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminExportController extends Controller
{
    public function export(Request $request): jsonResponse
    {
        $userId = auth()->user()->getAuthIdentifier();
        $search = $request->get('search');
        $category = $request->get('category');
        $brand = $request->get('brand');

        /**
         * @var ExportImport $export
         */
        $export = ExportImport::query()->firstOrCreate([
            'user_id' => $userId,
            'type' => ExportImportType::EXPORT,
        ]);

        if ($export->status === ExportImportStatus::PENDING) {
            return response()->json(['error' => 'Export is already in progress.'], 400);
        }

        $fileName = "products_export_$userId.xlsx";

        $export->status = ExportImportStatus::PENDING;
        $export->save();

        Excel::queue(new ProductsExport($export, $search, $category, $brand), $fileName, 'exports');

        return response()->json(['message' => 'Export started.']);
    }

    public function checkExport(): JsonResponse
    {
        $userId = auth()->user()->getAuthIdentifier();

        $export = ExportImport::query()
            ->fromUser($userId)
            ->getExports()
            ->latest()
            ->first();

        return match ($export?->getAttribute('status')) {
            ExportImportStatus::PENDING => response()->json(['status' => ExportImportStatus::PENDING,]),
            ExportImportStatus::COMPLETED => response()->json(['status' => ExportImportStatus::COMPLETED,]),
            ExportImportStatus::FAILED => response()->json(['status' => ExportImportStatus::FAILED]),
            default => response()->json(),
        };
    }

    public function download(): StreamedResponse
    {
        $userId = auth()->user()->getAuthIdentifier();
        $fileName = "products_export_$userId.xlsx";

        /**
         * @var FilesystemAdapter $disk
         */
        $disk = Storage::disk('exports');

        return $disk->download($fileName);
    }
}
