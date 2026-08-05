<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Todolist;

class TodolistController extends Controller
{
    // READ: Menampilkan task HANYA milik user yang sedang login
   public function index(Request $request)
{
    $query = Todolist::where('user_id', Auth::id());

    // Jika user mengisi kolom pencarian
    if ($request->has('search') && $request->search != '') {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    $todos = $query->latest()->get();

    return view('todos.index', compact('todos'));
}

    

    // CREATE: Menyimpan task baru beserta user_id pembuatnya
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|max:255',
            'description' => 'required',
            'due_date'    => 'nullable|date',
        ]);

        Todolist::create([
            'user_id'     => Auth::id(),
            'title'       => $request->title,
            'description' => $request->description,
            'due_date'    => $request->due_date,
        ]);

        return redirect()->back()->with('success', 'Tugas berhasil ditambahkan!');
    }

    // UPDATE STATUS CHECKBOX
    public function toggleComplete($id)
    {
        $todo = Todolist::where('user_id', Auth::id())->findOrFail($id);
        $todo->update(['is_completed' => !$todo->is_completed]);

        return redirect()->back();
    }

    // UPDATE FULL (EDIT TASK)
    public function update(Request $request, $id)
    {
        // 1. Validasi input (description dibuat nullable agar lebih fleksibel)
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date'    => 'nullable|date',
        ]);

        // 2. Cari tugas berdasarkan ID dan pastikan milik user yang sedang login
        $todo = Todolist::where('user_id', Auth::id())->findOrFail($id);

        // 3. Update data menggunakan variabel $validated yang sudah aman
        $todo->update([
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
            'due_date'    => $validated['due_date'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Tugas berhasil diperbarui!');
    }

    // DELETE
    public function destroy($id)
    {
        $todo = Todolist::where('user_id', Auth::id())->findOrFail($id);
        $todo->delete();

        return redirect()->back()->with('success', 'Tugas berhasil dihapus!');
    }
}
