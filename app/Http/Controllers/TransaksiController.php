<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(){
        $hasilAgregasi = Transaksi::query()->whereBetween('tanggal_transaksi',
                        ['2026-01-01','2026-12-31'])
                        ->selectRaw('MONTH(tanggal_transaksi) as bulan, SUM(jumlah_penjualan) as total')
                        ->groupByRaw('MONTH(tanggal_transaksi)')->pluck('total','bulan');

        $namaBulan = [1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April',
                    5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September',
                    10=>'Oktober', 11=>'November', 12=>'Desember'];

        $agregasiBulanan = collect($namaBulan)->map(
            function($nama, $nomor) use ($hasilAgregasi){
                return [
                    'bulan' => $nama,
                    'total' => (int) ($hasilAgregasi[$nomor] ?? 0),
                ];
            }
        );

        $transaksi = Transaksi::query()->whereBetween('tanggal_transaksi',
                    ['2026-01-01','2026-12-31'])->orderByDesc('tanggal_transaksi')->orderByDesc('id')->get();
        
        return view('transaksi.index',compact('agregasiBulanan','transaksi'));
    }

    public function store(Request $request){
        $data = $request->validate(
            [
                'tanggal_transaksi'=>['required', 'date', 'after_or_equal:2026-01-01', 'before_or_equal:2026-12-31'],
                'jumlah_penjualan'=>['required','integer','min:1'],
            ],
            [
                'tanggal_transaksi.required'=>'Tanggal transaksi wajib diisi',
                'tanggal_transaksi.after_or_equal'=>'Tanggal transaksi minimal 1 Januari 2026',
                'tanggal_transaksi.before_or_equal'=>'Tanggal transaksi maksimal 31 Desember 2026',
                'jumlah_penjualan.required'=>'Jumlah penjualan wajib diisi',
                'jumlah_penjualan.integer'=>'Jumlah penjualan harus bilangan bulat',
                'jumlah_penjualan.min'=>'Jumlah penjualan minimal 1 buah jersey',
            ]
        );

        Transaksi::create($data);

        return redirect()
            ->route('transaksi.index')
            ->with('success','Transaksi berhasil disimpan');

    }
    
}