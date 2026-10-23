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
                Section::make(__('Header'))
                    ->description(__('Tên thương hiệu, logo và nhãn các liên kết trên thanh đầu trang.'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('header_brand_name')->label(__('Tên thương hiệu Header (Tiếng Việt)')),
                        TextInput::make('header_brand_name_en')->label(__('Header brand name (English)')),
                        FileUpload::make('header_logo_url')
                            ->label(__('Logo Header'))
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120)
                            ->fetchFileInformation(false)
                            ->disk('public')
                            ->directory('header-logos')
                            ->visibility('public')
                            ->hintAction(\Filament\Actions\Action::make('clearHeaderLogo')
                                ->label(__('Xóa ảnh logo hiện tại'))
                                ->icon('heroicon-m-trash')
                                ->color('danger')
                                ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get): bool => filled($get('header_logo_url')))
                                ->action(fn (\Filament\Schemas\Components\Utilities\Set $set) => $set('header_logo_url', null)))
                            ->helperText(__('Chọn ảnh logo. Để trống sẽ dùng logo mặc định hoặc tên thương hiệu.')),
                        TextInput::make('header_home_label')->label(__('Nhãn 1')),
                        TextInput::make('header_home_label_en')->label(__('Label 1')),
                        TextInput::make('header_menu_label')->label(__('Nhãn 2')),
                        TextInput::make('header_menu_label_en')->label(__('Label 2')),
                        TextInput::make('header_promotions_label')->label(__('Nhãn 3')),
                        TextInput::make('header_promotions_label_en')->label(__('Label 3')),
                        TextInput::make('header_secret_label')->label(__('Nhãn 4')),
                        TextInput::make('header_secret_label_en')->label(__('Label 4')),
                        TextInput::make('header_about_label')->label(__('Nhãn 5')),
                        TextInput::make('header_about_label_en')->label(__('Label 5')),
                        Repeater::make('header_submenus')
                            ->label(__('Nhãn con trong Header'))
                            ->schema([
                                Select::make('parent')->label(__('Nhãn cha'))->options([
                                    'header_home_label' => __('Nhãn 1'),
                                    'header_menu_label' => __('Nhãn 2'),
                                    'header_promotions_label' => __('Nhãn 3'),
                                    'header_secret_label' => __('Nhãn 4'),
                                    'header_about_label' => __('Nhãn 5'),
                                ])->required(),
                                Repeater::make('children')
                                    ->label(__('Danh sách nhãn con'))
                                    ->schema([
                                        TextInput::make('label')->label(__('Tên nhãn con'))->required(),
                                        TextInput::make('label_en')->label(__('Child label (English)')),
                                        TextInput::make('url')->label(__('Đường dẫn'))->required(),
                                    ])
                                    ->itemLabel(fn (array $state): ?string => filled($state['label'] ?? null) ? $state['label'] : __('Nhãn con mới'))
                                    ->collapsible()
                                    ->collapsed()
                                    ->columns(1)
                                    ->defaultItems(0)
                                    ->addActionLabel(__('Thêm nhãn con')),
                            ])
                            ->grid([
                                'default' => 1,
                                'md' => 2,
                                'xl' => 3,
                            ])
                            ->defaultItems(0)
                            ->addActionLabel(__('Thêm nhóm nhãn con'))
                            ->columnSpanFull()
                            ->helperText(__('Chọn nhãn cha rồi thêm các nhãn con và đường dẫn tương ứng.')),
                    ]),
                Section::make(__('Footer · Thông tin nhà hàng'))
                    ->description(__('Mô tả, địa chỉ, giờ hoạt động và các kênh liên hệ.'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('footer_brand_name')->label(__('Tên thương hiệu Footer (Tiếng Việt)')),
                        TextInput::make('footer_brand_name_en')->label(__('Footer brand name (English)')),
                        FileUpload::make('footer_logo_url')
                            ->label(__('Logo Footer'))
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120)
                            ->fetchFileInformation(false)
                            ->disk('public')
                            ->directory('footer-logos')
                            ->visibility('public')
                            ->hintAction(\Filament\Actions\Action::make('clearFooterLogo')
                                ->label(__('Xóa ảnh logo Footer'))
                                ->icon('heroicon-m-trash')
                                ->color('danger')
                                ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get): bool => filled($get('footer_logo_url')))
                                ->action(fn (\Filament\Schemas\Components\Utilities\Set $set) => $set('footer_logo_url', null)))
                            ->helperText(__('Chọn ảnh logo hiển thị dưới tên thương hiệu trong Footer.')),
                        Textarea::make('footer_description')->label(__('Mô tả (Tiếng Việt)')),
                        Textarea::make('footer_description_en')->label(__('Description (English)')),
                        Textarea::make('footer_address')->label(__('Địa chỉ (Tiếng Việt)')),
                        Textarea::make('footer_address_en')->label(__('Address (English)')),
                        TextInput::make('footer_hours')->label(__('Giờ hoạt động (Tiếng Việt)')),
                        TextInput::make('footer_hours_en')->label(__('Opening hours (English)')),
                        TextInput::make('hotline')->label(__('Hotline')),
                        TextInput::make('hotline_2')->label(__('Số điện thoại thứ hai')),
                        TextInput::make('email')->label(__('Email'))->email(),
                        TextInput::make('facebook_url')->label(__('Facebook URL'))->url(),
                        TextInput::make('youtube_url')->label(__('YouTube URL'))->url(),
                        TextInput::make('zalo_url')->label(__('Zalo URL'))->url(),
                        TextInput::make('tripadvisor_url')->label(__('Tripadvisor URL'))->url(),
                        TextInput::make('google_business_url')->label(__('Google Search URL'))->url(),
                    ]),
                Section::make(__('Footer · Thông tin doanh nghiệp'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('footer_company_name')->label(__('Tên doanh nghiệp (Tiếng Việt)')),
                        TextInput::make('footer_company_name_en')->label(__('Company name (English)')),
                        Textarea::make('footer_company_address')->label(__('Địa chỉ doanh nghiệp (Tiếng Việt)')),
                        Textarea::make('footer_company_address_en')->label(__('Company address (English)')),
                    ]),
                Section::make(__('Footer · Ưu đãi và chủ đề'))
                    ->description(__('Nhập mỗi chủ đề nổi bật trên một dòng.'))
                    ->columns(2)
                    ->schema([
                        Textarea::make('footer_subscribe_text')->label(__('Lời mời đăng ký (Tiếng Việt)')),
                        Textarea::make('footer_subscribe_text_en')->label(__('Subscribe message (English)')),
                        Textarea::make('footer_topics')->label(__('Chủ đề nổi bật (Tiếng Việt)'))->helperText(__('Mỗi dòng một chủ đề. Có thể thêm URL sau dấu |, ví dụ: Ẩm thực Nhật Bản | https://ussinavietnam.vn/tag/am-thuc-nhat-ban/')),
                        Textarea::make('footer_topics_en')->label(__('Popular topics (English)'))->helperText(__('One topic per line. Optionally add its URL after |.')),
                    ]),
            ]);
    }
}

