<?php

namespace App\Http\Controllers\Web\Products;

use App\Domain\Products\Jobs\ProductsImport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use App\Support\Contracts\CreateJobsByUser;
use App\Support\Enums\JobsByUserStatus;
use App\Support\Enums\JobsByUserType;
use App\Support\Exceptions\ApplicationException;
use App\Support\Exceptions\CustomException;
use App\Support\Jobs\CompleteJobsByUser;
use App\Support\Models\JobsByUser;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class AdminImportController extends Controller
{
    /**
     * @throws CustomException
     */
    public function import(ImportRequest $request, CreateJobsByUser $createJobAction): void
    {
        $userId = auth()->user()->getAuthIdentifier();

        $import = $createJobAction->execute($userId, JobsByUserType::IMPORT);

        try {
            Excel::queueImport(new ProductsImport($import), $request->file('file'))
                ->chain([
                    new CompleteJobsByUser($import),
                ]);
        } catch (Throwable $e) {
            $import->status = JobsByUserStatus::FAILED;
            $import->save();
            throw new ApplicationException($e, []);
        }
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
