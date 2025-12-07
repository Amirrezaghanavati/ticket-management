<?php

namespace App\Enums;

enum TicketStatus: int
{
    case DRAFT = 0;
    case SUBMITTED = 1;
    case APPROVED_BY_ADMIN1 = 2;
    case APPROVED_BY_ADMIN2 = 3;
    case REJECTED_BY_ADMIN1 = 4;
    case REJECTED_BY_ADMIN2 = 5;
    case SENT_TO_WEBSERVICE = 6;

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => __('Draft'),
            self::SUBMITTED => __('Submitted'),
            self::APPROVED_BY_ADMIN1 => __('Approved by Admin 1'),
            self::APPROVED_BY_ADMIN2 => __('Approved by Admin 2'),
            self::REJECTED_BY_ADMIN1 => __('Rejected by Admin 1'),
            self::REJECTED_BY_ADMIN2 => __('Rejected by Admin 2'),
            self::SENT_TO_WEBSERVICE => __('Sent to Web Service'),
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::DRAFT, self::SUBMITTED => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20 dark:bg-amber-400/10 dark:text-amber-400 dark:ring-amber-400/20',
            self::APPROVED_BY_ADMIN1, self::APPROVED_BY_ADMIN2 => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-400/10 dark:text-emerald-400 dark:ring-emerald-400/20',
            self::REJECTED_BY_ADMIN1, self::REJECTED_BY_ADMIN2 => 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-600/20 dark:bg-rose-400/10 dark:text-rose-400 dark:ring-rose-400/20',
            self::SENT_TO_WEBSERVICE => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20 dark:bg-blue-400/10 dark:text-blue-400 dark:ring-blue-400/20',
        };
    }

    public function nextStatusForApprove(): ?TicketStatus
    {
        return match ($this) {
            self::SUBMITTED => self::APPROVED_BY_ADMIN1,
            self::APPROVED_BY_ADMIN1 => self::APPROVED_BY_ADMIN2,
            self::APPROVED_BY_ADMIN2 => self::SENT_TO_WEBSERVICE,
            default => null,
        };
    }

    public function nextStatusForReject(): ?TicketStatus
    {
        return match ($this) {
            self::SUBMITTED => self::REJECTED_BY_ADMIN1,
            self::APPROVED_BY_ADMIN1 => self::REJECTED_BY_ADMIN2,
            default => null,
        };
    }

    public function canBeApprovedByAdmin1(): bool
    {
        return $this === self::SUBMITTED;
    }

    public function canBeRejectedByAdmin1(): bool
    {
        return $this === self::SUBMITTED;
    }

    public function canBeApprovedByAdmin2(): bool
    {
        return $this === self::APPROVED_BY_ADMIN1;
    }

    public function canBeRejectedByAdmin2(): bool
    {
        return $this === self::APPROVED_BY_ADMIN1;
    }

    public function isPending(): bool
    {
        return \in_array($this, [
            self::DRAFT,
            self::SUBMITTED,
            self::APPROVED_BY_ADMIN1
        ], true);
    }

    public function isFinal(): bool
    {
        return \in_array($this, [
            self::REJECTED_BY_ADMIN1,
            self::REJECTED_BY_ADMIN2,
            self::SENT_TO_WEBSERVICE,
        ], true);
    }
}
