<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        return view('items.index', compact('items'));
    }
    // digunakan untuk menampilkan data item yang ada di database

    public function create()
    {
        return view('items.create');
    }
    // digunakan untuk menampilkan form untuk menambahkan item baru

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        // Item::create($request->all());
        // return redirect()->route('items.index')

        // Hanya masukkan atribut yang diizinkan
        Item::create($request->only(['name', 'description']));
        return redirect()->route('items.index');
    }
    // digunakan untuk menyimpan data item baru ke database

    public function show(Item $items)
    {
        return view('items.show', compact('items'));
    }
    // digunakan untuk menampilkan detail item

    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }
    // digunakan untuk menampilkan form untuk mengedit item

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        // $item->update($request->all());
        // return redirect()->route('items.index');

        // Hanya masukkan atribut yang diizinkan
        $item->update($request->only(['name', 'description']));
        return redirect()->route('items.index')->with('success', 'Item updated successfully');
    }
    // digunakan untuk menyimpan data item yang telah diubah ke database

    public function destroy(Item $item)
    {
        // return redirect()->route('items.index');
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted successfully');
    }
    // digunakan untuk menghapus item dari database
}
