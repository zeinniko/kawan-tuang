<?php

namespace App\Filament\Admin\Pages;

use App\Enums\NavigationGroup;
use App\Models\Category;
use App\Models\Order;
use App\Models\Store;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class SalesReport extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected string $view = 'filament.admin.pages.sales-report';

    public static function getNavigationIcon(): ?string
    {
        return 'data:image/svg+xml;base64,' . base64_encode('
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 48" width="24" height="48">
                <line x1="12" y1="0" x2="12" y2="48" stroke="#9ca3af" stroke-width="1.5" />
                <circle cx="12" cy="24" r="8" fill="#9ca3af" />
            </svg>
        ');
    }

    protected static UnitEnum|string|null $navigationGroup = NavigationGroup::Marketing;
    protected static ?string $navigationLabel = 'Report Sales';
    protected static ?int $navigationSort = 1;

    // Form Filter State Array
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'start_date' => now()->startOfMonth()->toDateString(),
            'end_date' => now()->toDateString(),
            'store_id' => null,
            'category_id' => null,
        ]);
    }

    /**
     * Di Filament v4, parameter bertipe Schema / FormSchema
     */
    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                DatePicker::make('start_date')
                    ->label('Dari Tanggal')
                    ->live(),

                DatePicker::make('end_date')
                    ->label('Sampai Tanggal')
                    ->live(),

                Select::make('store_id')
                    ->label('Cabang Toko')
                    ->options(Store::pluck('name', 'id'))
                    ->placeholder('Semua Cabang')
                    ->live(),

                Select::make('category_id')
                    ->label('Kategori Produk')
                    ->options(Category::pluck('name', 'id'))
                    ->placeholder('Semua Kategori')
                    ->live(),
            ])
            ->statePath('data')
            ->columns(4);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $formData = $this->data ?? [];

                return Order::query()
                    ->with(['store'])
                    ->whereIn('status', [
                        Order::STATUS_PAID,
                        Order::STATUS_PROCESSING,
                        Order::STATUS_DELIVERING,
                        Order::STATUS_COMPLETED,
                    ])
                    ->when($formData['start_date'] ?? null, fn (Builder $q, $date) => $q->whereDate('created_at', '>=', $date))
                    ->when($formData['end_date'] ?? null, fn (Builder $q, $date) => $q->whereDate('created_at', '<=', $date))
                    ->when($formData['store_id'] ?? null, fn (Builder $q, $storeId) => $q->where('store_id', $storeId))
                    ->when($formData['category_id'] ?? null, function (Builder $q, $categoryId) {
                        $q->whereHas('items.product', function (Builder $pq) use ($categoryId) {
                            $pq->where('category_id', $categoryId);
                        });
                    });
            })
            ->columns([
                TextColumn::make('order_number')
                    ->label('No. Order')
                    ->fontFamily('mono')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tanggal & Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('store.name')
                    ->label('Cabang Toko')
                    ->badge()
                    ->color('amber')
                    ->default('-'),

                TextColumn::make('fulfillment_type')
                    ->label('Fulfillment')
                    ->formatStateUsing(fn ($state) => strtoupper((string) $state)),

                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR'),

                TextColumn::make('discount_amount')
                    ->label('Diskon')
                    ->money('IDR')
                    ->color('danger'),

                TextColumn::make('total_amount')
                    ->label('Total Akhir')
                    ->money('IDR')
                    ->weight('bold')
                    ->color('success'),
            ])
            ->headerActions([
                Action::make('export_csv')
                    ->label('Export CSV Report')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->color('success')
                    ->action(function () {
                        $formData = $this->data ?? [];

                        $orders = Order::query()
                            ->with(['store'])
                            ->whereIn('status', [
                                Order::STATUS_PAID,
                                Order::STATUS_PROCESSING,
                                Order::STATUS_DELIVERING,
                                Order::STATUS_COMPLETED,
                            ])
                            ->when($formData['start_date'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($formData['end_date'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '<=', $date))
                            ->when($formData['store_id'] ?? null, fn ($q, $storeId) => $q->where('store_id', $storeId))
                            ->when($formData['category_id'] ?? null, function ($q, $categoryId) {
                                $q->whereHas('items.product', fn ($pq) => $pq->where('category_id', $categoryId));
                            })
                            ->get();

                        $filename = 'sales-report-' . now()->format('Y-m-d-His') . '.csv';

                        return response()->streamDownload(function () use ($orders) {
                            $handle = fopen('php://output', 'w');
                            fputcsv($handle, ['No Order', 'Tanggal', 'Cabang', 'Tipe Fulfillment', 'Subtotal', 'Diskon', 'Total Akhir']);

                            foreach ($orders as $order) {
                                fputcsv($handle, [
                                    $order->order_number,
                                    $order->created_at->format('Y-m-d H:i:s'),
                                    $order->store->name ?? '-',
                                    strtoupper((string) $order->fulfillment_type),
                                    $order->subtotal,
                                    $order->discount_amount,
                                    $order->total_amount,
                                ]);
                            }

                            fclose($handle);
                        }, $filename, [
                            'Content-Type' => 'text/csv',
                        ]);
                    }),
            ]);
    }
}