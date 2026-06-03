<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\DoctorsByDepartmentChart;
use App\Filament\Widgets\StatsOverviewWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->authGuard('admin')
            ->colors([
                'primary' => Color::Teal,
                'secondary' => Color::Indigo,
                'gray' => Color::Slate,
            ])
            ->brandName('Arogio Admin')
            ->brandLogo(fn() => new HtmlString('<div style="font-weight:bold;font-size:1.5rem;letter-spacing:-0.025em;"><span class="text-white">Swasthya<span style="color:#14b8a6;">Search</span></span></div>'))
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Overview')
                    ->icon('heroicon-o-home')
                    ->collapsed(false),
                NavigationGroup::make()
                    ->label('Directory Management')
                    ->icon('heroicon-o-building-office-2')
                    ->collapsed(false),
                NavigationGroup::make()
                    ->label('Taxonomy & AI Matching')
                    ->icon('heroicon-o-tag')
                    ->collapsed(false),
                NavigationGroup::make()
                    ->label('Content Management')
                    ->icon('heroicon-o-document-text')
                    ->collapsed(false),
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): HtmlString => new HtmlString('
                    <style>
                        aside.fi-sidebar {
                            background-color: #0F172B !important;
                            border-right: 1px solid rgba(255, 255, 255, 0.1) !important;
                        }
                        aside.fi-sidebar .fi-sidebar-header {
                            background-color: #0F172B !important;
                            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
                        }
                        aside.fi-sidebar .fi-sidebar-header * {
                            color: #ffffff !important;
                        }
                        aside.fi-sidebar .fi-sidebar-group-title {
                            color: #94a3b8 !important; /* slate-400 */
                            font-weight: 600 !important;
                        }
                        aside.fi-sidebar .fi-sidebar-item-button {
                            color: #cbd5e1 !important; /* slate-300 */
                        }
                        aside.fi-sidebar .fi-sidebar-item-icon {
                            color: #94a3b8 !important; /* slate-400 */
                        }
                        aside.fi-sidebar .fi-sidebar-item-button:hover {
                            background-color: rgba(255, 255, 255, 0.05) !important;
                            color: #ffffff !important;
                        }
                        aside.fi-sidebar .fi-sidebar-item-button:hover .fi-sidebar-item-icon {
                            color: #14b8a6 !important; /* Teal */
                        }
                        aside.fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-button {
                            background-color: #14b8a6 !important;
                            color: #ffffff !important;
                        }
                        aside.fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-icon {
                            color: #ffffff !important;
                        }
                        aside.fi-sidebar .fi-sidebar-sub-group-items {
                            background-color: rgba(0, 0, 0, 0.2) !important;
                        }
                    </style>
                ')
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                StatsOverviewWidget::class,
                DoctorsByDepartmentChart::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
