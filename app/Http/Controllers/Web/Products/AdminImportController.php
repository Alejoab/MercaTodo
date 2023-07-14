<?php

namespace App\Http\Controllers\Web\Products;

use App\Domain\Products\Jobs\ProductsImport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use App\Support\Enums\JobsByUserStatus;
use App\Support\Enums\JobsByUserType;
use App\Support\Exceptions\JobsByUserException;
use App\Support\Jobs\CompleteJobsByUser;
use App\Support\Models\JobsByUser;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;

class AdminImportController extends Controller
{
    /**
     * @throws JobsByUserException
     */
    public function import(ImportRequest $request): void
    {
        $userId = auth()->user()->getAuthIdentifier();

        /**
         * @var JobsByUser $import
         */
        $import = JobsByUser::query()->firstOrCreate([
            'user_id' => $userId,
            'type' => JobsByUserType::IMPORT,
        ]);

        if ($import->status === JobsByUserStatus::PENDING) {
            throw JobsByUserException::importActive();
        }

        $import->status = JobsByUserStatus::PENDING;
        $import->errors = [];
        $import->save();

        Excel::queueImport(new ProductsImport($import), $request->file('file'))
            ->chain([
                new CompleteJobsByUser($import),
            ]);
    }

    public function checkImport(): JsonResponse
    {
        $userId = auth()->user()->getAuthIdentifier();

        $import = JobsByUser::query()
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
