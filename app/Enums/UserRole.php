<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN1 = 'admin1';
    case ADMIN2 = 'admin2';
    case USER = 'user';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ADMIN1 => __('Admin1'),
            self::ADMIN2 => __('Admin2'),
            self::USER => __('User'),
            default => '-'
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::ADMIN1 => 'info',
            self::ADMIN2 => 'info',
            self::USER => 'gray',
            default => 'gray'
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::ADMIN1 => 'heroicon-o-finger-print',
            self::ADMIN2 => 'heroicon-o-finger-print',
            self::USER => 'heroicon-o-user',
            default => null
        };
    }

    public function getDescription(): ?string
    {
        return match ($this) {
            self::ADMIN1 => __('User can login as admin1'),
            self::ADMIN2 => __('User can login as admin2'),
            self::USER => __('User does not have access to admin panel'),
            default => '-'
        };
    }
}
