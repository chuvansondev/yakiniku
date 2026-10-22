<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Header')
                    ->description('Tên thương hiệu, logo và nhãn các liên kết trên thanh đầu trang.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('header_brand_name')->label('Tên thương hiệu Header (Tiếng Việt)'),
                        TextInput::make('header_brand_name_en')->label('Header brand name (English)'),
                        FileUpload::make('header_logo_url')
                            ->label('Logo Header')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120)
                            ->fetchFileInformation(false)
                            ->disk('public')
                            ->directory('header-logos')
                            ->visibility('public')
                            ->hintAction(\Filament\Actions\Action::make('clearHeaderLogo')
                                ->label('Xóa ảnh logo hiện tại')
                                ->icon('heroicon-m-trash')
                                ->color('danger')
                                ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get): bool => filled($get('header_logo_url')))
                                ->action(fn (\Filament\Schemas\Components\Utilities\Set $set) => $set('header_logo_url', null)))
                            ->helperText('Chọn ảnh logo. Để trống sẽ dùng logo mặc định hoặc tên thương hiệu.'),
                        TextInput::make('header_home_label')->label('Nhãn 1'),
                        TextInput::make('header_home_label_en')->label('Label 1'),
                        TextInput::make('header_menu_label')->label('Nhãn 2'),
                        TextInput::make('header_menu_label_en')->label('Label 2'),
                        TextInput::make('header_promotions_label')->label('Nhãn 3'),
                        TextInput::make('header_promotions_label_en')->label('Label 3'),
                        TextInput::make('header_secret_label')->label('Nhãn 4'),
                        TextInput::make('header_secret_label_en')->label('Label 4'),
                        TextInput::make('header_about_label')->label('Nhãn 5'),
                        TextInput::make('header_about_label_en')->label('Label 5'),
                        Repeater::make('header_submenus')
                            ->label('Nhãn con trong Header')
                            ->schema([
                                Select::make('parent')->label('Nhãn cha')->options([
                                    'header_home_label' => 'Nhãn 1',
                                    'header_menu_label' => 'Nhãn 2',
                                    'header_promotions_label' => 'Nhãn 3',
                                    'header_secret_label' => 'Nhãn 4',
                                    'header_about_label' => 'Nhãn 5',
                                ])->required(),
                                Repeater::make('children')
                                    ->label('Danh sách nhãn con')
                                    ->schema([
                                        TextInput::make('label')->label('Tên nhãn con')->required(),
                                        TextInput::make('label_en')->label('Child label (English)'),
                                        TextInput::make('url')->label('Đường dẫn')->required(),
                                    ])
                                    ->itemLabel(fn (array $state): ?string => filled($state['label'] ?? null) ? $state['label'] : 'Nhãn con mới')
                                    ->collapsible()
                                    ->collapsed()
                                    ->columns(1)
                                    ->defaultItems(0)
                                    ->addActionLabel('Thêm nhãn con'),
                            ])
                            ->grid([
                                'default' => 1,
                                'md' => 2,
                                'xl' => 3,
                            ])
                            ->defaultItems(0)
                            ->addActionLabel('Thêm nhóm nhãn con')
                            ->columnSpanFull()
                            ->helperText('Chọn nhãn cha rồi thêm các nhãn con và đường dẫn tương ứng.'),
                    ]),
                Section::make('Footer · Thông tin nhà hàng')
                    ->description('Mô tả, địa chỉ, giờ hoạt động và các kênh liên hệ.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('footer_brand_name')->label('Tên thương hiệu Footer (Tiếng Việt)'),
                        TextInput::make('footer_brand_name_en')->label('Footer brand name (English)'),
                        FileUpload::make('footer_logo_url')
                            ->label('Logo Footer')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120)
                            ->fetchFileInformation(false)
                            ->disk('public')
                            ->directory('footer-logos')
                            ->visibility('public')
                            ->hintAction(\Filament\Actions\Action::make('clearFooterLogo')
                                ->label('Xóa ảnh logo Footer')
                                ->icon('heroicon-m-trash')
                                ->color('danger')
                                ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get): bool => filled($get('footer_logo_url')))
                                ->action(fn (\Filament\Schemas\Components\Utilities\Set $set) => $set('footer_logo_url', null)))
                            ->helperText('Chọn ảnh logo hiển thị dưới tên thương hiệu trong Footer.'),
                        Textarea::make('footer_description')->label('Mô tả (Tiếng Việt)'),
                        Textarea::make('footer_description_en')->label('Description (English)'),
                        Textarea::make('footer_address')->label('Địa chỉ (Tiếng Việt)'),
                        Textarea::make('footer_address_en')->label('Address (English)'),
                        TextInput::make('footer_hours')->label('Giờ hoạt động (Tiếng Việt)'),
                        TextInput::make('footer_hours_en')->label('Opening hours (English)'),
                        TextInput::make('hotline')->label('Hotline'),
                        TextInput::make('hotline_2')->label('Số điện thoại thứ hai'),
                        TextInput::make('email')->label('Email')->email(),
                        TextInput::make('facebook_url')->label('Facebook URL')->url(),
                        TextInput::make('youtube_url')->label('YouTube URL')->url(),
                        TextInput::make('zalo_url')->label('Zalo URL')->url(),
                        TextInput::make('tripadvisor_url')->label('Tripadvisor URL')->url(),
                        TextInput::make('google_business_url')->label('Google Search URL')->url(),
                    ]),
                Section::make('Footer · Thông tin doanh nghiệp')
                    ->columns(2)
                    ->schema([
                        TextInput::make('footer_company_name')->label('Tên doanh nghiệp (Tiếng Việt)'),
                        TextInput::make('footer_company_name_en')->label('Company name (English)'),
                        Textarea::make('footer_company_address')->label('Địa chỉ doanh nghiệp (Tiếng Việt)'),
                        Textarea::make('footer_company_address_en')->label('Company address (English)'),
                    ]),
                Section::make('Footer · Ưu đãi và chủ đề')
                    ->description('Nhập mỗi chủ đề nổi bật trên một dòng.')
                    ->columns(2)
                    ->schema([
                        Textarea::make('footer_subscribe_text')->label('Lời mời đăng ký (Tiếng Việt)'),
                        Textarea::make('footer_subscribe_text_en')->label('Subscribe message (English)'),
                        Textarea::make('footer_topics')->label('Chủ đề nổi bật (Tiếng Việt)')->helperText('Mỗi dòng một chủ đề. Có thể thêm URL sau dấu |, ví dụ: Ẩm thực Nhật Bản | https://ussinavietnam.vn/tag/am-thuc-nhat-ban/'),
                        Textarea::make('footer_topics_en')->label('Popular topics (English)')->helperText('One topic per line. Optionally add its URL after |.'),
                    ]),
            ]);
    }
}

