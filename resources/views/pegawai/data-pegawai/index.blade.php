@extends('pegawai.layout')

@section('title', 'Data Pegawai')

@section('content')
<h1 class="text-2xl font-bold mb-4">Kelola Password Pegawai</h1>

{{-- Form Pencarian Pegawai --}}
<div class="relative w-[300px] mb-4">
    <input 
        type="text" 
        id="searchInput" 
        placeholder="Cari Nama Pegawai..." 
        class="w-full border border-gray-300 rounded-xl py-3 pl-12 pr-4 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
    />
    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 1 1 0-16 8 8 0 0 1 0 16z" />
        </svg>
    </div>
</div>

{{-- Notifikasi Success --}}
@if(session('success'))
    <div id="success-message" class="bg-green-500 text-white p-2 rounded my-2">{{ session('success') }}</div>
    <script>
        setTimeout(function() {
            document.getElementById('success-message').style.display = 'none';
        }, 5000); 
    </script>
@endif


{{-- Tabel Pegawai --}}
<table class="w-full mt-4 border">
    <thead>
        <tr class="bg-gray-200">
            <th class="border px-4 py-2">No</th>
            <th class="border px-4 py-2">Nama Pegawai</th>
            <th class="border px-4 py-2">Email</th>
            <th class="border px-4 py-2">Password</th>
            <th class="border px-4 py-2">Aksi</th>
        </tr>
    </thead>
    <tbody id="pegawaiBody">
        @foreach($pegawai as $index => $item)
        <tr>
            <td class="border px-4 py-2">{{ $index + 1 }}</td>
            <td class="border px-4 py-2">{{ $item->nama_pegawai }}</td>
            <td class="border px-4 py-2">{{ $item->email }}</td>
            <td class="border px-4 py-2">********</td> {{-- Hidden password for security --}}
            <td class="border px-4 py-2 text-center space-x-2">
            {{-- Edit Password --}}
            <button onclick='openEditModal(@json($item))' class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded">Ganti Password</button>
            {{-- Hapus Pegawai --}}
            <form action="{{ url('/pegawai/data-pegawai/' . $item->id_pegawai) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pegawai ini?')" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded">Hapus</button>
            </form>
        </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- Pagination --}}
<div class="mt-4">{{ $pegawai->links() }}</div>

{{-- Modal Edit Password Pegawai --}}
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50 p-4">
    <div class="bg-white rounded-lg w-full max-w-lg shadow-lg relative">
        <div class="p-4 border-b"><h2 class="text-xl text-center font-bold">Ganti Password Pegawai</h2></div>
        <form id="editForm" method="POST" class="p-6 space-y-4"> {{-- Action diisi via JS --}}
            @csrf
            @method('PUT')
            <div>
                <label for="editNama" class="block mb-1 font-semibold">Nama Pegawai</label>
                <input type="text" id="editNama" class="w-full border p-2 rounded bg-gray-100" readonly>
            </div>
            <div>
                <label for="editEmail" class="block mb-1 font-semibold">Email</label>
                <input type="email" id="editEmail" class="w-full border p-2 rounded bg-gray-100" readonly>
            </div>
            <div class="relative">
                <label for="editPassword" class="block mb-1 font-semibold">Password Baru</label>
                <input type="password" name="password" id="editPassword"
                    class="w-full border p-2 rounded-lg pl-4 pr-12 focus:outline-none focus:ring-2 focus:ring-[#C69C6D] focus:border-transparent"
                    placeholder="Password Baru" required>
                <button type="button" onclick="togglePassword('editPassword')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 mt-6">
                    <i id="eyeIcon" class="fas fa-eye"></i>
                </button>
            </div>
            <div class="relative">
                <label for="confirmPassword" class="block mb-1 font-semibold">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="confirmPassword"
                    class="w-full border p-2 rounded-lg pl-4 pr-12 focus:outline-none focus:ring-2 focus:ring-[#C69C6D] focus:border-transparent"
                    placeholder="Konfirmasi Password Baru" required>
                <button type="button" onclick="togglePassword('confirmPassword')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 mt-6">
                    <i id="eyeIconConfirm" class="fas fa-eye"></i>
                </button>
            </div>
            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="closeModal('editModal')" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Batal</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Update Password</button>
            </div>
        </form>
    </div>
</div>



<script>
// Fungsi Buka Modal
function openModal(id) {
    const modal = document.getElementById(id);
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal(id) {
    const modal = document.getElementById(id);
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Modal Edit
function openEditModal(data) {
    console.log('Data:', data);
    document.getElementById('editNama').value = data.nama_pegawai;
    document.getElementById('editEmail').value = data.email;
    document.getElementById('editPassword').value = '';
    document.getElementById('confirmPassword').value = '';

    const form = document.getElementById('editForm');
    form.action = `/pegawai/data-pegawai/${data.id_pegawai}`;

    openModal('editModal');
}

    // Fungsi untuk Modal Edit Password
    function openEditModal(data) {
        console.log('Open Modal Data:', data); // Debugging check

        // Set data nama dan email untuk dilihat
        document.getElementById('editNama').value = data.nama_pegawai;
        document.getElementById('editEmail').value = data.email;
        document.getElementById('editPassword').value = ''; // Kosongkan password

        // Set form action sesuai ID pegawai
        const form = document.getElementById('editForm');
        form.action = `/pegawai/data-pegawai/${data.id_pegawai}`; // Disesuaikan dengan route yang benar

        openModal('editModal');
    }

        // Fungsi Pencarian Nama Pegawai
        document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const rows = document.querySelectorAll('#pegawaiBody tr');

        searchInput.addEventListener('input', function () {
            const searchValue = searchInput.value.toLowerCase();

            rows.forEach(row => {
                const name = row.children[1].innerText.toLowerCase();
                if (name.includes(searchValue)) {
                    row.style.display = ''; // tampilkan
                } else {
                    row.style.display = 'none'; // sembunyikan
                }
            });
        });
    });

    function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') {
        input.type = 'text';
    } else {
        input.type = 'password';
    }
}

</script>
@endsection
