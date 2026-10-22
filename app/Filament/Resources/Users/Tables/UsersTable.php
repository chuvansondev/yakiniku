<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Họ và tên')->searchable()->sortable(),
                TextColumn::make('email')->label('Email')->searchable()->sortable(),
                TextColumn::make('phone')->label('Số điện thoại')->searchable(),
                TextColumn::make('role.name')->label('Vai trò')->placeholder('Chưa gán')->sortable(),
                IconColumn::make('is_locked')->label('Đã khóa')->boolean(),
                TextColumn::make('bookings_count')->label('Số lượt đặt bàn')->counts('bookings')->sortable(),
                TextColumn::make('created_at')->label('Ngày tạo')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_locked')->label('Tài khoản bị khóa'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([]);
    }
}
