<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    // Hapus keyword 'static' di properti $view
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
}