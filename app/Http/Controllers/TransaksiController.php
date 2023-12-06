<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TransaksiController extends Controller
{
    public function home()
    {
        $tahun = null;

        return view('transaksi', ['tahun' => $tahun]);
    }

    public function transaksi(Request $request)
    {
        // mengambil request tahun
        $tahun = $request->tahun;

        // mengambil data menu dan transaksi
        $getMenu = Http::get('https://tes-web.landa.id/intermediate/menu');
        $getTransaksi = Http::get('https://tes-web.landa.id/intermediate/transaksi?tahun=' . $tahun);

        // 
        $getMenuJson = json_decode($getMenu);
        $getTransaksiJson = json_decode($getTransaksi);

        // menampung array menu dan transaksi ke dalam Collection
        $menuCollections = collect($getMenuJson);
        $transaksiCollections = collect($getTransaksiJson);

        // 
        $makananCollections = $menuCollections->where('kategori', 'makanan');
        $minumanCollections = $menuCollections->where('kategori', 'minuman');

        // variabel untuk menampung total keseluruhan dalam setahun
        $total = 0;

        // kondisi jika variabel tahun telah diisi
        if ($tahun) {

            // start menghitung total menu perbulan
            foreach ($menuCollections as $menu1) {
                for ($i = 1; $i <= 12; $i++) {
                    $total_menu_perbulan[$menu1->menu][$i] = 0;
                }
            }

            foreach ($transaksiCollections as $transaksi1) {
                $bulan1 = date('n', strtotime($transaksi1->tanggal));
                $total_menu_perbulan[$transaksi1->menu][$bulan1] += $transaksi1->total;
            }
            // end menghitung total menu perbulan


            // start hitung total permenu dalam satu tahun
            foreach ($menuCollections as $menu2) {
                $total_permenu_setahun[$menu2->menu] = 0;
            }

            foreach ($transaksiCollections as $transaksi2) {
                $total_permenu_setahun[$transaksi2->menu] += $transaksi2->total;
            }
            // end hitung total permenu dalam satu tahun


            // start hitung semua menu dalam satu bulan
            foreach ($menuCollections as $menu3) {
                for ($i = 1; $i <= 12; $i++) {
                    $total_semua_menu_perbulan[$i] = 0;
                }
            }

            foreach ($transaksiCollections as $transaksi3) {
                $bulan2 = date('n', strtotime($transaksi3->tanggal));
                $total_semua_menu_perbulan[$bulan2] += $transaksi3->total;
            }
            // end hitung semua menu dalam satu bulan


            // start hitung total keseluruhan dalam satu tahun
            foreach ($transaksiCollections as $transaksi) {
                $total += $transaksi->total;
            }
            // end hitung total keseluruhan dalam satu tahun

            return view('transaksi', [
                'total_menu_perbulan' => $total_menu_perbulan,
                'total_permenu_setahun' => $total_permenu_setahun,
                'total_semua_menu_perbulan' => $total_semua_menu_perbulan,
                'total' => $total,
                'tahun' => $tahun,
                'makanans' => $makananCollections,
                'minumans' => $minumanCollections,
            ]);
        }

        return redirect()->route('home');
    }
}
