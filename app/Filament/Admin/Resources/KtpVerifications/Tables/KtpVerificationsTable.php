<?php

namespace App\Filament\Admin\Resources\KtpVerifications\Tables;

use App\Services\StorageService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class KtpVerificationsTable
{
    public static function configure(Table $table): Table
    {
        $storageService = app(StorageService::class);

        return $table
            ->columns([
                TextColumn::make('user.full_name')
                    ->label('Nama Pengguna')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->user?->email),

                TextColumn::make('user.birth_date')
                    ->label('Tgl Lahir')
                    ->date('d M Y')
                    ->sortable(),

                // FOTO KTP (Klik thumbnail di tabel untuk modal zoom)
                ImageColumn::make('ktp_image_url')
                    ->label('Foto KTP')
                    ->square()
                    ->getStateUsing(fn ($record) => $record->ktp_image_url 
                        ? app(StorageService::class)->getUrl($record->ktp_image_url, 'private', 60) 
                        : null
                    )
                    ->action(
                        Action::make('preview_ktp_col')
                            ->modalHeading(fn ($record) => 'Foto KTP - ' . $record->user?->full_name)
                            ->modalContent(fn ($record) => self::renderSingleZoomModal(
                                app(StorageService::class)->getUrl($record->ktp_image_url, 'private', 60),
                                'Foto KTP Asli'
                            ))
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Tutup')
                    ),

                // FOTO SELFIE (Klik thumbnail di tabel untuk modal zoom)
                ImageColumn::make('selfie_image_url')
                    ->label('Foto Selfie')
                    ->square()
                    ->getStateUsing(fn ($record) => $record->selfie_image_url 
                        ? app(StorageService::class)->getUrl($record->selfie_image_url, 'private', 60) 
                        : null
                    )
                    ->action(
                        Action::make('preview_selfie_col')
                            ->modalHeading(fn ($record) => 'Foto Selfie KTP - ' . $record->user?->full_name)
                            ->modalContent(fn ($record) => self::renderSingleZoomModal(
                                app(StorageService::class)->getUrl($record->selfie_image_url, 'private', 60),
                                'Foto Selfie Memegang KTP'
                            ))
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Tutup')
                    ),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    })
                    ->sortable(),

                TextColumn::make('verified_at')
                    ->label('Diverifikasi Pada')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending'  => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                // ACTION: TINJAU BEBERAPA DOKUMEN SIDE-BY-SIDE WITH ROTATE & MAGNIFIER
                Action::make('inspect_documents')
                    ->label('Tinjau Dokumen')
                    ->icon('heroicon-o-magnifying-glass-plus')
                    ->color('info')
                    ->modalHeading(fn ($record) => 'Tinjau Dokumen KYC: ' . $record->user?->full_name)
                    ->modalWidth('5xl')
                    ->modalContent(function ($record) use ($storageService) {
                        $ktpUrl = $storageService->getUrl($record->ktp_image_url, 'private', 60);
                        $selfieUrl = $storageService->getUrl($record->selfie_image_url, 'private', 60);

                        return new HtmlString("
                            <div class='grid grid-cols-1 md:grid-cols-2 gap-4 p-2'>
                                " . self::renderZoomBoxHtml($ktpUrl, '🎴 Foto KTP Asli') . "
                                " . self::renderZoomBoxHtml($selfieUrl, '🤳 Foto Selfie Memegang KTP') . "
                            </div>
                        ");
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),

                // ACTION: APPROVE
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status !== 'approved')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status'           => 'approved',
                            'verified_at'      => now(),
                            'rejection_reason' => null,
                        ]);

                        $record->user?->update([
                            'is_age_verified' => true,
                        ]);
                    }),

                // ACTION: REJECT
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status !== 'rejected')
                    ->form([
                        Textarea::make('rejection_reason')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->placeholder('Contoh: Foto KTP buram / NIK tidak sesuai'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status'           => 'rejected',
                            'verified_at'      => now(),
                            'rejection_reason' => $data['rejection_reason'],
                        ]);

                        $record->user?->update([
                            'is_age_verified' => false,
                        ]);
                    }),

                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Helper merender HTML Inspector Gambar dengan Smooth Hover Zoom + Rotate Button
     */
    protected static function renderZoomBoxHtml(?string $url, string $label): string
    {
        if (! $url) {
            return "
                <div class='border border-gray-200 dark:border-gray-800 rounded-2xl p-4 bg-gray-50 dark:bg-gray-900 text-center text-xs text-gray-400'>
                    {$label} Tidak Tersedia
                </div>
            ";
        }

        return "
            <div x-data=\"{ scale: 1, rotation: 0, x: 50, y: 50 }\" 
                 class='flex flex-col items-center border border-gray-200 dark:border-gray-800 rounded-2xl p-3 bg-gray-50 dark:bg-gray-900'>
                
                <!-- HEADER BAR & ACTION TOOLBAR -->
                <div class='w-full flex items-center justify-between mb-2 px-1'>
                    <span class='text-xs font-bold text-gray-700 dark:text-gray-300'>
                        {$label}
                    </span>
                    <div class='flex items-center gap-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-1 shadow-sm'>
                        <button type='button' @click='rotation = (rotation + 90) % 360' title='Putar Gambar' class='px-2 py-0.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded text-gray-700 dark:text-gray-200 text-[11px] font-semibold flex items-center gap-1 cursor-pointer'>
                            🔄 Putar
                        </button>
                        <button type='button' @click='scale = Math.min(scale + 0.5, 4)' title='Perbesar' class='px-2 py-0.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded text-gray-700 dark:text-gray-200 text-[11px] font-bold cursor-pointer'>
                            +
                        </button>
                        <button type='button' @click='scale = Math.max(scale - 0.5, 1)' title='Perkecil' class='px-2 py-0.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded text-gray-700 dark:text-gray-200 text-[11px] font-bold cursor-pointer'>
                            -
                        </button>
                        <button type='button' @click='scale = 1; rotation = 0; x = 50; y = 50;' title='Reset' class='px-2 py-0.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded text-gray-700 dark:text-gray-200 text-[11px] font-semibold cursor-pointer'>
                            Reset
                        </button>
                    </div>
                </div>

                <!-- IMAGE CANVAS WINDOW (SMOOTH NO-FLICKER) -->
                <div class='relative w-full h-[350px] overflow-hidden rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-950 flex items-center justify-center cursor-crosshair'
                     @mousemove='
                        if (scale > 1) {
                            const r = \$el.getBoundingClientRect();
                            x = (((\$event.clientX - r.left) / r.width) * 100);
                            y = (((\$event.clientY - r.top) / r.height) * 100);
                        }
                     '
                     @mouseenter='if (scale === 1) scale = 2.5;'
                     @mouseleave='scale = 1; x = 50; y = 50;'>
                    
                    <img src='{$url}'
                         :style=\"'transform: scale(' + scale + ') rotate(' + rotation + 'deg); transform-origin: ' + x + '% ' + y + '%;'\"
                         class='max-h-full max-w-full object-contain pointer-events-none transition-transform duration-75 ease-out' />
                </div>

                <span class='text-[10px] text-gray-400 mt-2 text-center'>
                    💡 Tempelkan kursor untuk zoom otomatis, atau gunakan tombol <strong>Putar / + / -</strong> di atas.
                </span>
            </div>
        ";
    }

    /**
     * Helper Modal Zoom Tunggal saat Gambar Thumbnail di Tabel Diklik
     */
    protected static function renderSingleZoomModal(?string $imageUrl, string $title): HtmlString
    {
        return new HtmlString("
            <div class='p-2'>
                " . self::renderZoomBoxHtml($imageUrl, $title) . "
            </div>
        ");
    }
}