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
                // ACTION: TINJAU DOKUMEN SIDE-BY-SIDE WITH ROTATE & MAGNIFIER
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
                                " . self::renderZoomBoxHtml($ktpUrl, 'Foto KTP Asli', 'identification') . "
                                " . self::renderZoomBoxHtml($selfieUrl, 'Foto Selfie Memegang KTP', 'camera') . "
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
    protected static function renderZoomBoxHtml(?string $url, string $label, string $iconType = 'identification'): string
    {
        if (! $url) {
            return "
                <div class='border border-gray-200 dark:border-gray-800 rounded-2xl p-4 bg-gray-50 dark:bg-gray-900 text-center text-xs text-gray-400'>
                    {$label} Tidak Tersedia
                </div>
            ";
        }

        // Definisi SVG Icon berdasarkan jenis dokumen
        $headerIconSvg = match ($iconType) {
            'camera' => '<svg class="w-4 h-4 text-indigo-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
            default => '<svg class="w-4 h-4 text-amber-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9h-3m2 3h-2m2 3h-2m-4-6h.01M9 12h.01M9 15h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>',
        };

        $rotateIconSvg = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>';
        $infoIconSvg = '<svg class="w-3.5 h-3.5 text-amber-500 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.516 0c.85.493 1.508 1.333 1.508 2.316V18"/></svg>';

        return "
            <div x-data=\"{ scale: 1, rotation: 0, x: 50, y: 50 }\" 
                 class='flex flex-col items-center border border-gray-200 dark:border-gray-800 rounded-2xl p-3 bg-gray-50 dark:bg-gray-900'>
                
                <!-- HEADER BAR & ACTION TOOLBAR -->
                <div class='w-full flex items-center justify-between mb-2 px-1'>
                    <span class='text-xs font-bold text-gray-700 dark:text-gray-300 flex items-center'>
                        {$headerIconSvg}
                        {$label}
                    </span>
                    <div class='flex items-center gap-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-1 shadow-sm'>
                        <button type='button' @click='rotation = (rotation + 90) % 360' title='Putar Gambar' class='px-2 py-0.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded text-gray-700 dark:text-gray-200 text-[11px] font-semibold flex items-center gap-1 cursor-pointer'>
                            {$rotateIconSvg}
                            Putar
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

                <span class='text-[10px] text-gray-400 mt-2 text-center flex items-center justify-center gap-1'>
                    {$infoIconSvg}
                    Tempelkan kursor untuk zoom otomatis, atau gunakan tombol <strong>Putar / + / -</strong> di atas.
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
                " . self::renderZoomBoxHtml($imageUrl, $title, 'identification') . "
            </div>
        ");
    }
}