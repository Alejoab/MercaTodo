<?php

namespace App\Support\Mails;

use App\Support\Enums\JobsByUserStatus;
use App\Support\Enums\JobsByUserType;
use App\Support\Models\JobsByUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobsByUserMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public JobsByUser $job)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->getSubject(),
        );
    }

    private function getSubject(): string
    {
        return "{$this->job->type->value} {$this->job->status->value}";
    }

    public function content(): Content
    {
        return new Content(
            markdown: $this->job->status !== JobsByUserStatus::FAILED ? 'mails.jobsbyuser.completed' : 'mails.jobsbyuser.failed',
            with: [
                'content' => $this->getContent(),
                'url' => $this->getLink(),
            ],
        );
    }

    private function getContent(): string
    {
        return match ($this->job->type) {
            JobsByUserType::SALES => 'Your sales export process has been successfully completed. Please go to the admin report panel to download the file.',
            JobsByUserType::IMPORT => 'Your products import process has been successfully completed. Please go to the admin products panel to see the imported products.',
            JobsByUserType::EXPORT => 'Your products export process has been successfully completed. Please go to the admin products panel to download the file.',
            JobsByUserType::REPORT => 'Your sales report process has been successfully completed. Please go to the admin report panel to download the file.',
        };
    }

    private function getLink(): string
    {
        return match ($this->job->type) {
            JobsByUserType::SALES, JobsByUserType::REPORT => url(route('admin.reports')),
            JobsByUserType::IMPORT, JobsByUserType::EXPORT => url(route('admin.products')),
        };
    }

}
