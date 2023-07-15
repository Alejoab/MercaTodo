<?php

namespace App\Http\Controllers\Web\Reports;

use App\Domain\Reports\Enums\ReportType;
use App\Domain\Reports\Jobs\ReportExport;
use App\Domain\Reports\Jobs\SalesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Http\Requests\SalesRequest;
use App\Support\Enums\JobsByUserStatus;
use App\Support\Enums\JobsByUserType;
use App\Support\Exceptions\ApplicationException;
use App\Support\Exceptions\JobsByUserException;
use App\Support\Jobs\CompleteJobsByUser;
use App\Support\Models\JobsByUser;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class AdminReportController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Administrator/Reports/Index', [
            'reportTypes' => ReportType::cases(),
        ]);
    }

    /**
     * @throws JobsByUserException
     * @throws ApplicationException
     */
    public function generate(ReportRequest $request): void
    {
        $userId = auth()->user()->getAuthIdentifier();

        /**
         * @var JobsByUser $report
         */
        $report = JobsByUser::query()->firstOrCreate([
            'user_id' => $userId,
            'type' => JobsByUserType::REPORT,
        ]);

        if ($report->status === JobsByUserStatus::PENDING) {
            throw JobsByUserException::reportActive();
        }

        $fileName = "report_$userId.xlsx";
        $report->status = JobsByUserStatus::PENDING;
        $report->file_name = $fileName;
        $report->save();

        try {
            Excel::queue(new ReportExport($report, $request->input('reports'), $request->date('from'), $request->date('to')), $fileName, 'exports')
                ->chain([
                    new CompleteJobsByUser($report),
                ]);
        } catch (Throwable $e) {
            $report->status = JobsByUserStatus::FAILED;
            $report->save();
            throw new ApplicationException($e, []);
        }
    }

    public function checkReport(): JsonResponse
    {
        $userId = auth()->user()->getAuthIdentifier();

        $report = JobsByUser::query()
            ->fromUser($userId)
            ->getReports()
            ->latest()
            ->first();

        return match ($report?->getAttribute('status')) {
            JobsByUserStatus::PENDING => response()->json(['status' => JobsByUserStatus::PENDING,]),
            JobsByUserStatus::COMPLETED => response()->json(['status' => JobsByUserStatus::COMPLETED,]),
            JobsByUserStatus::FAILED => response()->json(['status' => JobsByUserStatus::FAILED,]),
            default => response()->json(),
        };
    }

    public function download(): StreamedResponse
    {
        $userId = auth()->user()->getAuthIdentifier();
        $fileName = JobsByUser::query()->fromUser($userId)->getReports()->latest()->first()?->getAttribute('file_name');

        /**
         * @var FilesystemAdapter $disk
         */
        $disk = Storage::disk('exports');

        if ($fileName === null || !$disk->exists($fileName)) {
            abort(404);
        }

        return $disk->download($fileName);
    }

    /**
     * @throws JobsByUserException
     * @throws ApplicationException
     */
    public function generateSales(SalesRequest $request): void
    {
        $userId = auth()->user()->getAuthIdentifier();

        /**
         * @var JobsByUser $sales
         */
        $sales = JobsByUser::query()->firstOrCreate([
            'user_id' => $userId,
            'type' => JobsByUserType::SALES,
        ]);

        if ($sales->status === JobsByUserStatus::PENDING) {
            throw JobsByUserException::salesActive();
        }

        $fileName = "sales_$userId.xlsx";
        $sales->status = JobsByUserStatus::PENDING;
        $sales->file_name = $fileName;
        $sales->save();

        try {
            Excel::queue(new SalesExport($sales, $request->date('from'), $request->date('to')), $fileName, 'exports')
                ->chain([
                    new CompleteJobsByUser($sales),
                ]);
        } catch (Throwable $e) {
            $sales->status = JobsByUserStatus::FAILED;
            $sales->save();
            throw new ApplicationException($e, []);
        }
    }

    public function checkSales(): JsonResponse
    {
        $userId = auth()->user()->getAuthIdentifier();

        $sales = JobsByUser::query()
            ->fromUser($userId)
            ->getSales()
            ->latest()
            ->first();

        return match ($sales?->getAttribute('status')) {
            JobsByUserStatus::PENDING => response()->json(['status' => JobsByUserStatus::PENDING,]),
            JobsByUserStatus::COMPLETED => response()->json(['status' => JobsByUserStatus::COMPLETED,]),
            JobsByUserStatus::FAILED => response()->json(['status' => JobsByUserStatus::FAILED,]),
            default => response()->json(),
        };
    }

    public function downloadSales(): StreamedResponse
    {
        $userId = auth()->user()->getAuthIdentifier();
        $fileName = JobsByUser::query()->fromUser($userId)->getSales()->latest()->first()->getAttribute('file_name');

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
