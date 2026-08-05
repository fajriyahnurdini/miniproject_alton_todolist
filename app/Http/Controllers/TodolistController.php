<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Todolist;

class TodolistController extends Controller
{
    // READ: Menampilkan task HANYA milik user yang sedang login
    public function index()
    {
        $todos = Todolist::where('user_id', Auth::id())->latest()->get();
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
        $request->validate([
            'title'       => 'required|max:255',
            'description' => 'required',
            'due_date'    => 'nullable|date',
        ]);

        $todo = Todolist::where('user_id', Auth::id())->findOrFail($id);
        $todo->update([
            'title'       => $request->title,
            'description' => $request->description,
            'due_date'    => $request->due_date,
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
