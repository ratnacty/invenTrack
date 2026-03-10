<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    //get data
    public function index(Request $request)
    {
        //
        $query = Kategori::withCount('barangs');

        // search
        if($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Summary (filtered, before pagination)
        $allFiltered = (clone $query)->get();
        $summary = [
            'totalKategori' => $allFiltered->count(),
            'totalBarang'   => $allFiltered->sum('barangs_count'),
        ];

        // order by & pagination
        $kategoris = $query->orderBy('nama')->paginate(10)->withQueryString();

        return Inertia::render('kategori/index', [
            'kategoris' => $kategoris,
            'filters'   => $request->only(['search']),
            'summary'   => $summary,
        ]);


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return Inertia::render('kategori/create');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $request->validate(([
            'nama' => 'required|string|max:255|unique:kategoris',
            'deskripsi' => 'nullable|string',
        ]));


        Kategori::create($request->only(['nama', 'deskripsi']));

        return redirect()->route('kategori.index')
                         ->with('success', 'Kategori berhasil ditambahkan.');


    }



    /**
     * Display the specified resource.
     */
    public function show(Kategori $kategori)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kategori $kategori)
    {
        //
        return Inertia::render('kategori/edit', ['kategori' => $kategori]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kategori $kategori)
    {
        //

         $request->validate(([
            'nama' => 'required|string|max:255|unique:kategoris,nama,' . $kategori->id,
            'deskripsi' => 'nullable|string',
        ]));

        $kategori->update($request->only(['nama', 'deskripsi']));

        return redirect()->route('kategori.index')
                         ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $kategori)
    {
        //

        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus');
    }
}
