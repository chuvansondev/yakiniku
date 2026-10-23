<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Role;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Password;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('Thông tin tài khoản'))
                ->schema([
                    TextInput::make('name')
                        ->label(__('Họ và tên'))
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->label(__('Email'))
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),
                    TextInput::make('phone')
                        ->label(__('Số điện thoại'))
                        ->tel()
                        ->maxLength(30),
                    TextInput::make('password')
                        ->label(__('Mật khẩu'))
                        ->password()
                        ->revealable()
                        ->rules(fn (?User $record): array => $record ? ['nullable', Password::min(8)] : ['required', Password::min(8)])
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->helperText(__('Để trống khi sửa nếu không muốn đổi mật khẩu.')),
                    Select::make('role_id')
                        ->label(__('Vai trò'))
                        ->options(fn () => Role::query()->orderBy('name')->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->nullable(),
                    Toggle::make('is_locked')
                        ->label(__('Khóa tài khoản'))
                        ->default(false),
                    DateTimePicker::make('email_verified_at')
                        ->label(__('Email đã xác minh lúc')),
                ])
                ->columns(2),
        ]);
    }
}
