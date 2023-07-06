<?php

namespace App\Http\Controllers\Administrator;

use App\Enums\ExportImportStatus;
use App\Enums\ExportImportType;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use App\Imports\ProductsImport;
use App\Models\ExportImport;
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
        $import->save();

        Excel::queueImport(new ProductsImport($import), $request->file('file'));

        return response()->json(['message' => 'Import has been queued.']);
    }
}
