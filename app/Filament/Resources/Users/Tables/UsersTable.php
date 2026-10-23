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
                TextColumn::make('name')->label(__('Họ và tên'))->searchable()->sortable(),
                TextColumn::make('email')->label(__('Email'))->searchable()->sortable(),
                TextColumn::make('phone')->label(__('Số điện thoại'))->searchable(),
                TextColumn::make('role.name')->label(__('Vai trò'))->placeholder(__('Chưa gán'))->sortable(),
                IconColumn::make('is_locked')->label(__('Đã khóa'))->boolean(),
                TextColumn::make('bookings_count')->label(__('Số lượt đặt bàn'))->counts('bookings')->sortable(),
                TextColumn::make('created_at')->label(__('Ngày tạo'))->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_locked')->label(__('Tài khoản bị khóa')),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([]);
    }
}
