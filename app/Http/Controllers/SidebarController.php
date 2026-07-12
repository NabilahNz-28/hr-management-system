<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SidebarController extends Controller
{
    public function absen()
    {
        $sidebar = [
            'logo' => 'AK',
            'brand' => 'Absensi Karyawan',
            'sections' => [
                [
                    'label' => 'DASHBOARD',
                    'items' => [
                        [
                            'text' => 'Dashboard',
                            'route' => 'dashboard.absensi',
                            'routeIs' => 'dashboard.absensi',
                            'icon' => 'dashboard',
                        ],
                    ],
                ],
                [
                    'label' => 'ABSENSI',
                    'items' => [
                        [
                            'text' => 'Absensi Masuk',
                            'route' => 'absensi.absensi.absen.masuk',
                            'routeIs' => 'absensi.masuk',
                            'icon' => 'clock',
                        ],
                        [
                            'text' => 'Absensi Pulang',
                            'route' => 'absensi.absensi.absen.pulang',
                            'routeIs' => 'absensi.pulang',
                            'icon' => 'clock-out',
                        ],
                        [
                            'text' => 'Pengajuan Izin',
                            'route' => 'absensi.absensi.pengajuan-izin',
                            'routeIs' => 'absensi.pengajuan-izin',
                            'icon' => 'calendar',
                        ],
                        [
                            'text' => 'Pengajuan Cuti',
                            'route' => 'absensi.absensi.pengajuan-cuti',
                            'routeIs' => 'absensi.pengajuan-cuti',
                            'icon' => 'plus',
                        ],
                    ],
                ],
                [
                    'label' => 'MONITORING',
                    'items' => [
                        [
                            'text' => 'Rekap Harian',
                            'route' => 'absensi.monitoring.rekap-harian',
                            'routeIs' => 'monitoring.rekap-harian',
                            'icon' => 'calendar',
                        ],
                        [
                            'text' => 'Rekap Bulanan',
                            'route' => 'absensi.monitoring.rekap-bulanan',
                            'routeIs' => 'monitoring.rekap-bulanan',
                            'icon' => 'list',
                        ],
                    ],
                ],
                [
                    'label' => 'LAPORAN',
                    'items' => [
                        [
                            'text' => 'Laporan Absensi',
                            'route' => 'absensi.laporan.laporan-absensi',
                            'routeIs' => 'laporan.laporan.absensi',
                            'icon' => 'file',
                        ],
                    ],
                ],
                [
                    'label' => 'PENGATURAN',
                    'items' => [
                        [
                            'text' => 'Lokasi Kantor',
                            'route' => '#',
                            'routeIs' => 'pengaturan.lokasi',
                            'icon' => 'map',
                        ],
                        [
                            'text' => 'Jam Kerja',
                            'route' => '#',
                            'routeIs' => 'pengaturan.jam-kerja',
                            'icon' => 'clock',
                        ],
                        [
                            'text' => 'Profile',
                            'route' => '#',
                            'routeIs' => 'profile',
                            'icon' => 'user',
                        ],
                    ],
                ],
            ],
        ];

        return view('layouts.sidebar-absen', compact('sidebar'));
    }

    public function pic()
    {
        $sidebar = [
            'logo' => 'PIC',
            'brand' => 'Dashboard PIC',
            'sections' => [
                [
                    'label' => 'DASHBOARD',
                    'items' => [
                        [
                            'text' => 'Dashboard',
                            'route' => 'dashboard.pic',
                            'routeIs' => 'dashboard.pic',
                            'icon' => 'grid',
                        ],
                    ],
                ],
                [
                    'label' => 'INVENTORY',
                    'items' => [
                        [
                            'text' => 'Stock Opname',
                            'route' => 'inventories.inventory.stock-opname',
                            'routeIs' => 'stock-opname',
                            'icon' => 'clipboard',
                        ],
                        [
                            'text' => 'Transfer Stock',
                            'route' => 'inventories.inventory.transfer-stock',
                            'routeIs' => 'transfer-stock',
                            'icon' => 'transfer',
                        ],
                    ],
                ],
                [
                    'label' => 'LAPORAN',
                    'items' => [
                        [
                            'text' => 'Laporan Opname',
                            'route' => 'inventories.inventory.laporan-opname',
                            'routeIs' => 'laporan.opname',
                            'icon' => 'file',
                        ],
                        [
                            'text' => 'Laporan Transfer',
                            'route' => 'inventories.inventory.laporan-transfer',
                            'routeIs' => 'laporan-transfer',
                            'icon' => 'history',
                        ],
                    ],
                ],
            ],
        ];

        return view('layouts.sidebar-pic', compact('sidebar'));    }

    public function superadmin()
    {
        $sidebar = [
            'logo' => 'SA',
            'brand' => 'Super Admin',
            'sections' => [
                [
                    'label' => 'DASHBOARD',
                    'items' => [
                        [
                            'text' => 'Dashboard',
                            'route' => '#',
                            'routeIs' => 'superadmin.dashboard',
                            'icon' => 'dashboard',
                        ],
                    ],
                ],
                [
                    'label' => 'MENU',
                    'items' => [
                        [
                            'text' => 'Menu 1',
                            'route' => '#',
                            'routeIs' => 'superadmin.menu1',
                            'icon' => 'menu',
                        ],
                        [
                            'text' => 'Menu 2',
                            'route' => '#',
                            'routeIs' => 'superadmin.menu2',
                            'icon' => 'menu',
                        ],
                        [
                            'text' => 'Menu 3',
                            'route' => '#',
                            'routeIs' => 'superadmin.menu3',
                            'icon' => 'menu',
                        ],
                        [
                            'text' => 'Menu 4',
                            'route' => '#',
                            'routeIs' => 'superadmin.menu4',
                            'icon' => 'menu',
                        ],
                    ],
                ],
            ],
        ];

            return view('layouts.sidebar-superadmin', compact('sidebar'));
    }
}