<?php

namespace App\Http\Controllers\Web\Products;

use App\Domain\Products\Jobs\ProductsExport;
use App\Http\Controllers\Controller;
use App\Support\Enums\JobsByUserStatus;
use App\Support\Enums\JobsByUserType;
use App\Support\Exceptions\ApplicationException;
use App\Support\Exceptions\JobsByUserException;
use App\Support\Jobs\CompleteJobsByUser;
use App\Support\Models\JobsByUser;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class AdminExportController extends Controller
{
    /**
     * @throws JobsByUserException
     * @throws ApplicationException
     */
    public function export(Request $request): void
    {
        $userId = auth()->user()->getAuthIdentifier();
        $search = $request->input('search');
        $category = $request->input('category');
        $brand = $request->input('brand');

        /**
         * @var JobsByUser $export
         */
        $export = JobsByUser::query()->firstOrCreate([
            'user_id' => $userId,
            'type' => JobsByUserType::EXPORT,
        ]);

        if ($export->status === JobsByUserStatus::PENDING) {
            throw JobsByUserException::exportActive();
        }

        $fileName = "products_export_$userId.xlsx";
        $export->status = JobsByUserStatus::PENDING;
        $export->file_name = $fileName;
        $export->save();

        try {
            Excel::queue(new ProductsExport($export, $search, $category, $brand), $fileName, 'exports')
                ->chain([
                    new CompleteJobsByUser($export),
                ]);
        } catch (Throwable $e) {
            $export->status = JobsByUserStatus::FAILED;
            $export->save();
            throw new ApplicationException($e, []);
        }

    }

    public function checkExport(): JsonResponse
    {
        $userId = auth()->user()->getAuthIdentifier();

        $export = JobsByUser::query()
            ->fromUser($userId)
            ->getExports()
            ->latest()
            ->first();

        return match ($export?->getAttribute('status')) {
            JobsByUserStatus::PENDING => response()->json(['status' => JobsByUserStatus::PENDING,]),
            JobsByUserStatus::COMPLETED => response()->json(['status' => JobsByUserStatus::COMPLETED,]),
            JobsByUserStatus::FAILED => response()->json(['status' => JobsByUserStatus::FAILED]),
            default => response()->json(),
        };
    }

    public function download(): StreamedResponse
    {
        $userId = auth()->user()->getAuthIdentifier();
        $fileName = JobsByUser::query()->fromUser($userId)->getExports()->latest()->first()?->getAttribute('file_name');

        /**
         * @var FilesystemAdapter $disk
         */
        $disk = Storage::disk('exports');

        if ($fileName === null || !$disk->exists($fileName)) {
            abort(404);
        }

        return $disk->download($fileName);
    }
}
