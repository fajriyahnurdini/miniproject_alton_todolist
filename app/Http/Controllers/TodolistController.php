<?php

namespace App\Http\Controllers;

use App\Models\Todolist;
use Illuminate\Http\Request;

class TodolistController extends Controller
{
    // READ: Menampilkan semua daftar tugas
    public function index()
    {
        $todos = Todolist::latest()->get();
        return view('todos.index', compact('todos'));
    }

    // CREATE: Menyimpan tugas baru ke MySQL
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|max:255',
            'description' => 'required',
            'due_date'    => 'nullable|date',
        ]);

        Todolist::create([
            'title'        => $request->title,
            'description'  => $request->description,
            'due_date'     => $request->due_date,
            'is_completed' => false,
        ]);

        return redirect()->back()->with('success', 'Tugas berhasil ditambahkan!');
    }

    // UPDATE: Mengubah status is_completed (Selesai / Belum)
    public function toggleComplete($id)
    {
        $todo = Todolist::findOrFail($id);
        
        $todo->update([
            'is_completed' => !$todo->is_completed
        ]);

        return redirect()->back();
    }

    // DELETE: Menghapus tugas dari database
    public function destroy($id)
    {
        $todo = Todolist::findOrFail($id);
        $todo->delete();

        return redirect()->back()->with('success', 'Tugas berhasil dihapus!');
    }
}