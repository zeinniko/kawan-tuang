<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use App\Notifications\UserPushNotification;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected string $view = 'filament.admin.resources.user-resource.pages.view-user';

    public function mount(int | string $record): void
    {
        parent::mount($record);

        // Eager load relasi untuk dipanggil di Blade
        $this->record->load([
            'ktpVerification',
            'addresses',
            'orders' => fn ($query) => $query->latest()->limit(10),
        ]);
    }

    /**
     * Membuat Tombol Custom Notification di Header Halaman Detail
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('sendPushNotification')
                ->label('Kirim Push Notif')
                ->icon('heroicon-o-paper-airplane')
                ->color('info')
                ->modalHeading('Kirim Push Notifikasi ke User')
                ->modalDescription(fn () => "Pesan akan dikirim langsung ke HP " . ($this->record->full_name ?? $this->record->name ?? 'User'))
                ->modalSubmitActionLabel('Kirim Notifikasi')
                ->form([
                    TextInput::make('title')
                        ->label('Judul Notifikasi')
                        ->placeholder('Contoh: Promo Voucher Khusus!')
                        ->required()
                        ->maxLength(255),
                    Textarea::make('body')
                        ->label('Pesan Notifikasi')
                        ->placeholder('Tulis pesan notifikasi yang akan muncul di HP user...')
                        ->required()
                        ->rows(3),
                ])
                ->action(function (array $data): void {
                    $user = $this->record;

                    // Validasi jika user belum punya FCM Token
                    if (! $user->fcm_token) {
                        FilamentNotification::make()
                            ->title('Gagal Mengirim Notifikasi')
                            ->body('User ini belum memiliki FCM Token (belum pernah login di aplikasi HP).')
                            ->warning()
                            ->send();

                        return;
                    }

                    // Kirim Push Notification via FCM Class
                    $user->notify(new UserPushNotification(
                        title: $data['title'],
                        body: $data['body'],
                        data: [
                            'type' => 'custom_admin_push',
                            'user_id' => (string) $user->id,
                        ]
                    ));

                    // Toast sukses di Admin Filament
                    FilamentNotification::make()
                        ->title('Notifikasi Terkirim!')
                        ->body('Push notification telah dikirim ke perangkat user.')
                        ->success()
                        ->send();
                }),
        ];
    }
}