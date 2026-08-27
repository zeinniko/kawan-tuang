<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Enums\NavigationGroup as EnumsNavigationGroup;
use App\Filament\Admin\Widgets\SalesChartWidget;
use App\Filament\Admin\Widgets\StatsOverviewWidget;
use App\Filament\Admin\Widgets\TopSellingProductsWidget;
use Filament\Navigation\NavigationGroup;
use Filament\Support\Icons\Heroicon;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->authGuard('web')
            ->login()
            ->sidebarWidth('17rem')
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => Blade::render("@vite(['resources/css/app.css', 'resources/js/app.js'])") . '
                <style>
                    /* Merapatkan container group */
                    ul.fi-sidebar-group-items,
                    .fi-sidebar-nav-groups {
                        row-gap: 0px !important;
                        gap: 0px !important;
                    }
                    .fi-sidebar-item {
                        margin: 0 !important;
                        padding: 0 !important;
                    }
                    .fi-sidebar-item-button, 
                    .fi-sidebar-item a {
                        padding-top: 2px !important;
                        padding-bottom: 2px !important;
                        padding-left: 8px !important;
                        padding-right: 8px !important;
                        margin-top: -2px !important;
                        margin-bottom: -2px !important;
                        display: flex;
                        align-items: center;
                        gap: 12px !important;
                    }
                    
                    .fi-sidebar-item-icon-container {
                        padding: 0 !important;
                        width: auto !important;
                    }
                    .fi-sidebar-nav::-webkit-scrollbar {
                        display: none !important;
                        width: 0 !important;
                        height: 0 !important;
                    }
                    .fi-sidebar-nav {
                        scrollbar-width: none !important;
                        -ms-overflow-style: none !important;
                    }
                    .fi-sidebar-item.fi-active > a,
                    .fi-sidebar-item.fi-active > button,
                    .fi-sidebar-group-items .fi-sidebar-item.fi-active > a,
                    .fi-sidebar-group-items .fi-sidebar-item.fi-active > button {
                        border-right: 2px solid #722f37 !important;
                        border-bottom: 2px solid #722f37 !important;
                        border-style: solid !important;
                    }
                </style>'
            )
            ->colors([
                'primary' => Color::Rose,
            ])
            ->discoverResources(
                in: app_path('Filament/Admin/Resources'),
                for: 'App\\Filament\\Admin\\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/Admin/Pages'),
                for: 'App\\Filament\\Admin\\Pages'
            )
            ->pages([
                Dashboard::class,
            ])

            ->discoverWidgets(
                in: app_path('Filament/Admin/Widgets'),
                for: 'App\\Filament\\Admin\\Widgets'
            )
            ->widgets([
                AccountWidget::class,
                SalesChartWidget::class,
                StatsOverviewWidget::class,
                TopSellingProductsWidget::class,
            ])
            ->navigationGroups([
                NavigationGroup::make(EnumsNavigationGroup::OrderFulfillment->getLabel()),
                NavigationGroup::make(EnumsNavigationGroup::Catalog->getLabel()),
                NavigationGroup::make(EnumsNavigationGroup::StoreInventory->getLabel()),
                NavigationGroup::make(EnumsNavigationGroup::Compliance->getLabel()),
                NavigationGroup::make(EnumsNavigationGroup::Marketing->getLabel()),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
