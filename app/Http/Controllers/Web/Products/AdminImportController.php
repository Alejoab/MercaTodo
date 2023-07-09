<?php

namespace App\Http\Controllers\Web\Products;

use App\Console\Jobs\ProductsImport;
use App\Domain\Products\Enums\ExportImportStatus;
use App\Domain\Products\Enums\ExportImportType;
use App\Domain\Products\Models\ExportImport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;

class AdminImportController extends Controller
{
    public function import(ImportRequest $request): JsonResponse
    {
        $userId = auth()->user()->getAuthIdentifier();

        /**
         * @var ExportImport $import
         */
        $import = ExportImport::query()->firstOrCreate([
            'user_id' => $userId,
            'type' => ExportImportType::IMPORT,
        ]);

        if ($import->status === ExportImportStatus::PENDING) {
            return response()->json(['error' => 'Import is already in progress.'], 400);
        }

        $import->status = ExportImportStatus::PENDING;
        $import->errors = [];
        $import->save();

        Excel::queueImport(new ProductsImport($import), $request->file('file'));

        return response()->json(['message' => 'Import has been queued.']);
    }

    public function checkImport(): JsonResponse
    {
        $userId = auth()->user()->getAuthIdentifier();

        $import = ExportImport::query()
            ->fromUser($userId)
            ->getImports()
            ->latest()
            ->first();

        return match ($import?->getAttribute('status')) {
            ExportImportStatus::PENDING => response()->json(['status' => ExportImportStatus::PENDING, 'data' => $import->getAttribute('errors'),]),
            ExportImportStatus::COMPLETED => response()->json(['status' => ExportImportStatus::COMPLETED, 'data' => $import->getAttribute('errors'),]),
            ExportImportStatus::FAILED => response()->json(['status' => ExportImportStatus::FAILED,]),
            default => response()->json(),
        };
    }
}
