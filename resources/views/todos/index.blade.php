<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>FlowTask - My To-Do List</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "error": "#ba1a1a",
                        "primary": "#674bb5",
                        "primary-container": "#a78bfa",
                        "primary-fixed": "#e8ddff",
                        "surface": "#f8f9ff",
                        "surface-container-lowest": "#ffffff",
                        "on-surface": "#121c2a",
                        "on-surface-variant": "#494552",
                        "on-primary": "#ffffff",
                        "on-primary-container": "#3c1989",
                        "outline": "#7a7583",
                        "outline-variant": "#cac4d4",
                    },
                    "borderRadius": {
                        "xl": "0.75rem",
                        "2xl": "1rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "stack-gap": "12px",
                        "container-max": "800px",
                        "margin-desktop": "40px",
                        "margin-mobile": "20px"
                    }
                }
            }
        }
    </script>
    <style>
        .custom-shadow {
            box-shadow: 0 10px 25px -5px rgba(167, 139, 250, 0.1);
        }
    </style>
</head>
<body class="bg-surface text-on-surface min-h-screen font-['Inter'] flex flex-col items-center">

<!-- Header -->
<header class="w-full bg-primary-fixed text-on-primary-container py-8 px-margin-mobile md:px-margin-desktop mb-8 text-center flex flex-col items-center justify-center">
    <h1 class="text-3xl md:text-4xl text-primary font-bold">My To-Do List</h1>
</header>

<!-- Main Content Canvas -->
<main class="w-full max-w-container-max px-margin-mobile md:px-margin-desktop flex flex-col gap-8 flex-grow">

    <!-- Notifikasi Sukses -->
    @if(session('success'))
        <div class="w-full p-4 bg-emerald-100 text-emerald-800 rounded-xl font-medium text-sm flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-emerald-800 font-bold">&times;</button>
        </div>
    @endif

    <!-- Search Bar -->
    <div class="relative w-full">
        <span class="material-symbols-outlined absolute left-4 top-1/2 transform -translate-y-1/2 text-outline">search</span>
        <input id="searchInput" onkeyup="filterTasks()" class="w-full pl-12 pr-4 py-3 bg-surface-container-lowest border-2 border-transparent focus:border-primary-container rounded-full custom-shadow outline-none text-base transition-colors" placeholder="Search tasks..." type="text"/>
    </div>

    <!-- Task Creation Form -->
    <form action="{{ route('todos.store') }}" method="POST" class="bg-surface-container-lowest rounded-2xl p-6 custom-shadow flex flex-col gap-stack-gap">
        @csrf
        <input name="title" required class="w-full p-4 bg-surface-container-lowest border border-gray-100 focus:border-primary-container rounded-xl text-base outline-none transition-colors" placeholder="Task Title" type="text"/>
        
        <textarea name="description" required class="w-full p-4 bg-surface-container-lowest border border-gray-100 focus:border-primary-container rounded-xl text-sm outline-none transition-colors resize-none" placeholder="Task Description" rows="3"></textarea>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-between items-center">
            <div class="relative w-full sm:w-auto flex-grow max-w-xs">
                <span class="material-symbols-outlined absolute left-3 top-1/2 transform -translate-y-1/2 text-outline">calendar_today</span>
                <input name="due_date" class="w-full pl-10 pr-4 py-3 bg-surface-container-lowest border border-gray-100 focus:border-primary-container rounded-xl text-sm outline-none transition-colors text-on-surface-variant" type="datetime-local"/>
            </div>
            
            <button type="submit" class="w-full sm:w-auto bg-primary-container hover:bg-primary text-on-primary font-semibold text-lg py-3 px-8 rounded-xl transition-colors duration-200">
                Add Task
            </button>
        </div>
    </form>

    <!-- Task List -->
    <div id="taskList" class="flex flex-col gap-stack-gap w-full pb-16">
        @forelse ($todos as $todo)
            <div class="task-card bg-surface-container-lowest rounded-2xl p-4 flex items-start gap-4 custom-shadow hover:shadow-md transition-all group {{ $todo->is_completed ? 'opacity-60' : 'hover:bg-[#EDE9FE]' }}">
                
                <!-- Toggle Form Checkbox -->
                <form action="{{ route('todos.toggle', $todo->id) }}" method="POST" class="mt-1">
                    @csrf
                    @method('PATCH')
                    <input type="checkbox" onchange="this.form.submit()" {{ $todo->is_completed ? 'checked' : '' }} class="w-6 h-6 rounded-full border-2 border-outline-variant text-primary-container focus:ring-primary-container checked:bg-primary-container cursor-pointer transition-colors"/>
                </form>

                <!-- Task Details -->
                <div class="flex-grow flex flex-col gap-1">
                    <h3 class="task-title font-semibold text-lg text-on-surface {{ $todo->is_completed ? 'line-through text-on-surface-variant' : '' }}">
                        {{ $todo->title }}
                    </h3>
                    
                    <p class="task-desc text-sm text-on-surface-variant whitespace-pre-line {{ $todo->is_completed ? 'line-through' : '' }}">
                        {{ $todo->description }}
                    </p>

                    <!-- Due Date Badge -->
                    @if($todo->due_date)
                        <div class="flex items-center mt-2">
                            <span class="bg-primary-fixed text-primary text-xs font-semibold px-3 py-1 rounded-full">
                                ⏱️ {{ $todo->due_date->format('d M Y, H:i') }}
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons: Edit & Delete -->
                <div class="flex items-center gap-1">
                    <!-- Tombol Edit (Pensil) -->
                    <button type="button" onclick="openEditModal({{ $todo->id }}, '{{ addslashes($todo->title) }}', '{{ addslashes($todo->description) }}', '{{ $todo->due_date ? $todo->due_date->format('Y-m-d\TH:i') : '' }}')" class="text-outline hover:text-primary transition-colors p-2">
                        <span class="material-symbols-outlined">edit</span>
                    </button>

                    <!-- Tombol Hapus -->
                    <form action="{{ route('todos.destroy', $todo->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Hapus tugas ini?')" class="text-outline hover:text-error transition-colors p-2">
                            <span class="material-symbols-outlined">delete</span>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-surface-container-lowest rounded-2xl p-8 text-center text-on-surface-variant custom-shadow">
                Belum ada tugas. Yuk tambah tugas pertamamu di atas!
            </div>
        @endforelse
    </div>
</main>

<!-- Modal Edit Task -->
<div id="editModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 hidden z-50">
    <div class="bg-surface-container-lowest rounded-2xl p-6 w-full max-w-lg custom-shadow flex flex-col gap-4">
        <div class="flex justify-between items-center border-b pb-3">
            <h2 class="text-xl font-bold text-primary">Edit Tugas</h2>
            <button onclick="closeEditModal()" class="text-outline hover:text-on-surface font-bold text-xl">&times;</button>
        </div>

        <form id="editForm" method="POST" class="flex flex-col gap-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-semibold mb-1">Judul Tugas</label>
                <input id="editTitle" name="title" required class="w-full p-3 bg-surface border border-gray-200 focus:border-primary-container rounded-xl outline-none" type="text"/>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Deskripsi Tugas</label>
                <textarea id="editDescription" name="description" required class="w-full p-3 bg-surface border border-gray-200 focus:border-primary-container rounded-xl outline-none resize-none" rows="3"></textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Tenggat Waktu</label>
                <input id="editDueDate" name="due_date" class="w-full p-3 bg-surface border border-gray-200 focus:border-primary-container rounded-xl outline-none text-on-surface-variant" type="datetime-local"/>
            </div>

            <div class="flex justify-end gap-3 mt-2">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 rounded-xl border border-gray-300 text-on-surface-variant font-medium hover:bg-gray-100 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-medium hover:bg-primary-container transition-colors">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Footer -->
<footer class="w-full max-w-container-max mx-auto px-margin-desktop flex flex-col items-center gap-4 py-8 bg-transparent">
    <span class="text-sm text-on-surface-variant">© FlowTask Minimalist Productivity</span>
</footer>

<!-- JavaScript Functions -->
<script>
    // Search Filter
    function filterTasks() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const tasks = document.querySelectorAll('.task-card');

        tasks.forEach(task => {
            const title = task.querySelector('.task-title').textContent.toLowerCase();
            const desc = task.querySelector('.task-desc').textContent.toLowerCase();

            if (title.includes(query) || desc.includes(query)) {
                task.style.display = 'flex';
            } else {
                task.style.display = 'none';
            }
        });
    }

    // Modal Edit Handler
    function openEditModal(id, title, description, dueDate) {
        const form = document.getElementById('editForm');
        form.action = `/todos/${id}`;

        document.getElementById('editTitle').value = title;
        document.getElementById('editDescription').value = description;
        document.getElementById('editDueDate').value = dueDate;

        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>

</body>
</html>