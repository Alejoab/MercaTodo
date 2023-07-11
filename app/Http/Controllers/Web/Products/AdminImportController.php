<?php

namespace App\Http\Controllers\Web\Products;

use App\Console\Jobs\ProductsImport;
use App\Domain\Products\Models\ExportImport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use App\Support\Enums\JobsByUserStatus;
use App\Support\Enums\JobsByUserType;
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
            'type' => JobsByUserType::IMPORT,
        ]);

        if ($import->status === JobsByUserStatus::PENDING) {
            return response()->json(['error' => 'Import is already in progress.'], 400);
        }

        $import->status = JobsByUserStatus::PENDING;
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
            JobsByUserStatus::PENDING => response()->json(['status' => JobsByUserStatus::PENDING, 'data' => $import->getAttribute('errors'),]),
            JobsByUserStatus::COMPLETED => response()->json(['status' => JobsByUserStatus::COMPLETED, 'data' => $import->getAttribute('errors'),]),
            JobsByUserStatus::FAILED => response()->json(['status' => JobsByUserStatus::FAILED,]),
            default => response()->json(),
        };
    }
}
