<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My To-Do List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f5f3ff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-custom {
            background-color: #e0d5ff;
            padding: 15px 30px;
        }

        .card-custom {
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .btn-purple {
            background-color: #9d72ff;
            color: white;
            border-radius: 10px;
        }

        .btn-purple:hover {
            background-color: #8352ff;
            color: white;
        }

        .completed-task {
            text-decoration: line-through;
            color: #8c8c8c;
        }
    </style>
</head>

<body>

    <!-- NAVBAR ATAS (FULL WIDTH) -->
    <nav class="navbar navbar-custom shadow-sm mb-4">
        <div class="container-fluid d-flex justify-content-between align-items-center px-4">
            <div>
                <h3 class="fw-bold mb-0" style="color: #6f42c1;">My To-Do List</h3>
                <small class="text-muted">Halo, <strong>{{ Auth::user()->name }}</strong> 👋</small>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold">
                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- KONTEN UTAMA -->
    <div class="container" style="max-width: 800px;">

        <!-- Alert Success -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Form Tambah Task -->
        <div class="card card-custom p-4 mb-4">
            <form action="{{ route('todos.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <input type="text" name="title" class="form-control form-control-lg border-0 bg-light"
                        placeholder="Judul Tugas..." required>
                </div>
                <div class="mb-3">
                    <textarea name="description" class="form-control border-0 bg-light" rows="3" placeholder="Deskripsi Tugas..."
                        required></textarea>
                </div>
                <div class="row g-2 align-items-center">
                    <div class="col-md-8">
                        <input type="datetime-local" name="due_date" class="form-control border-0 bg-light">
                    </div>
                    <div class="col-md-4 text-end">
                        <button type="submit" class="btn btn-purple w-100 py-2 fw-bold">
                            <i class="fas fa-plus me-1"></i> Add Task
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Daftar Task -->
        <div class="d-flex flex-column gap-3 mb-5">
            @forelse($todos as $todo)
                <div class="card card-custom p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <!-- Toggle Complete -->
                            <form action="{{ route('todos.toggle', $todo->id) }}" method="POST" class="m-0">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn p-0 border-0">
                                    @if ($todo->is_completed)
                                        <i class="fas fa-check-circle fa-2x text-success"></i>
                                    @else
                                        <i class="far fa-circle fa-2x text-muted"></i>
                                    @endif
                                </button>
                            </form>

                            <!-- Text Task -->
                            <div>
                                <h5 class="mb-1 fw-bold {{ $todo->is_completed ? 'completed-task' : '' }}">
                                    {{ $todo->title }}
                                </h5>
                                <p class="mb-1 text-muted {{ $todo->is_completed ? 'completed-task' : '' }}">
                                    {{ $todo->description }}
                                </p>
                                @if ($todo->due_date)
                                    <small class="text-secondary">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ \Carbon\Carbon::parse($todo->due_date)->format('d M Y, H:i') }}
                                    </small>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <!-- Delete -->
                            <form action="{{ route('todos.destroy', $todo->id) }}" method="POST" class="m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger p-0 ms-2"
                                    onclick="return confirm('Yakin ingin menghapus tugas ini?')">
                                    <i class="fas fa-trash-alt fa-lg"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <i class="far fa-folder-open fa-3x mb-3"></i>
                    <p class="mb-0">Belum ada tugas. Yuk, buat tugas pertama kamu!</p>
                </div>
            @endforelse
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
