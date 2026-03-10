<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $query = Barang::with('kategori');

        // search & filter
        if($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
        if($request->filled('kategori_id')){
            $query->where('kategori_id', $request->kategori_id);
        }

        // Summary (filtered, before pagination)
        $allFiltered = (clone $query)->get();
        $summary = [
            'totalBarang' => $allFiltered->count(),
            'totalStok'   => (int) $allFiltered->sum('stok'),
            'totalNilai'  => (float) $allFiltered->sum(fn($item) => $item->harga * $item->stok),
        ];

        $barangs = $query->orderBy('nama')->paginate(10)->withQueryString();
        $kategoris = Kategori::orderBy('nama')->get(['id', 'nama']);

        return Inertia::render('barang/index', [
            'barangs'   => $barangs,
            'kategoris' => $kategoris,
            'filters'   => $request->only(['search', 'kategori_id']),
            'summary'   => $summary,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        $kategoris = Kategori::orderBy('nama')-> get(['id', 'nama']);
        return Inertia::render('barang/create', ['kategoris' => $kategoris]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'tanggal_pembelian' => 'required|date',

        ]);

        Barang::create($request ->only([
            'kategori_id','nama',  'deskripsi',  'harga', 'stok', 'tanggal_pembelian'
        ]));

        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Barang $barang)
    {
        //


    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang)
    {
        //

        $kategoris = Kategori::orderBy('nama')->get(['id', 'nama']);

        return Inertia::render('barang/edit', [
            'barang' => $barang->load('kategori'),
            'kategoris' => $kategoris,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang)
    {
        //

        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'tanggal_pembelian' => 'required|date',
        ]);

        $barang->update($request->only([
             'kategori_id','nama',  'deskripsi',  'harga', 'stok', 'tanggal_pembelian'
        ]));

        return redirect()->route('barang.index')
                        ->with('success', 'Barang berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang)
    {
        //

        $barang->delete();

        return redirect()->route('barang.index')
                ->with('success', 'Barang berhasil dihapus');

    }
}
