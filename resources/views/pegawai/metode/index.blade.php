@extends('pegawai.layout') {{-- Atau layout pegawai --}}
@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-4">Kelola Metode Pembayaran</h1>

    @if(session('success'))
        <div id="successMessage" class="bg-green-100 text-green-700 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tombol tambah --}}
    <button onclick="document.getElementById('modalTambah').showModal()" class="bg-blue-600 text-white px-4 py-2 rounded mb-4">
        + Tambah Metode
    </button>

    {{-- Tabel --}}
    <table class="w-full border">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 border">#</th>
                <th class="p-2 border">Nama Metode</th>
                <th class="p-2 border">Keterangan</th>
                <th class="p-2 border">Nomor Tujuan</th>
                <th class="p-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($metodes as $i => $m)
            <tr>
                <td class="p-2 border">{{ $i+1 }}</td>
                <td class="p-2 border">{{ $m->nama_metode }}</td>
                <td class="p-2 border">{{ $m->keterangan }}</td>
                <td class="p-2 border">{{ $m->nomor_tujuan }}</td>
                <td class="p-2 border space-x-2">
                    <button onclick="editMetode({{ $m->id_metode }}, '{{ $m->nama_metode }}', '{{ $m->keterangan }}', '{{ $m->nomor_tujuan }}')" class="bg-yellow-400 text-white px-2 py-1 rounded">Edit</button>
                    <form action="{{ route('pegawai.metode.destroy', $m->id_metode) }}" method="POST" class="inline" onsubmit="return confirm('Hapus metode ini?')">
                        @csrf @method('DELETE')
                        <button class="bg-red-600 text-white px-2 py-1 rounded">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Modal Tambah --}}
    <dialog id="modalTambah" class="modal">
        <form method="POST" action="{{ route('pegawai.metode.store') }}" class="p-4 bg-white rounded shadow">
            @csrf
            <h2 class="text-xl mb-2 font-bold">Tambah Metode Pembayaran</h2>
            <select name="nama_metode" class="border p-2 w-full mb-2" required onchange="toggleImage()">
                <option value="VA">VA</option>
                <option value="DANA">DANA</option>
                <option value="QRIS">QRIS</option>
            </select>

            <input type="text" name="keterangan" placeholder="Keterangan (optional)" class="border p-2 w-full mb-2">
            <small class="text-gray-500">Masukkan keterangan tambahan jika ada.</small>

            <input type="text" name="nomor_tujuan" placeholder="Nomor Tujuan (optional)" class="border p-2 w-full mb-2">
            <small class="text-gray-500">Masukkan nomor tujuan untuk pembayaran.</small>

            <div id="qrisImage" class="hidden my-4">
                <img src="path_to_qris_image.png" alt="QRIS" class="w-32 h-32 mx-auto">
            </div>

            <div class="flex justify-end gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                <button type="button" onclick="modalTambah.close()" class="bg-gray-300 px-4 py-2 rounded">Batal</button>
            </div>
        </form>
    </dialog>

    {{-- Modal Edit --}}
    <dialog id="modalEdit" class="modal">
        <form method="POST" id="formEdit" class="p-4 bg-white rounded shadow">
            @csrf @method('PUT')
            <h2 class="text-xl mb-2 font-bold">Edit Metode Pembayaran</h2>
            <select name="nama_metode" id="editNama" class="border p-2 w-full mb-2" required onchange="toggleEditImage()">
                <option value="VA">VA</option>
                <option value="DANA">DANA</option>
                <option value="QRIS">QRIS</option>
            </select>

            <input type="text" name="keterangan" id="editKeterangan" class="border p-2 w-full mb-2">
            <small class="text-gray-500">Masukkan keterangan tambahan jika ada.</small>

            <input type="text" name="nomor_tujuan" id="editNomorTujuan" class="border p-2 w-full mb-2">
            <small class="text-gray-500">Masukkan nomor tujuan untuk pembayaran.</small>

            <div id="editQrisImage" class="hidden my-4">
                <img src="path_to_qris_image.png" alt="QRIS" class="w-32 h-32 mx-auto">
            </div>

            <div class="flex justify-end gap-2">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Update</button>
                <button type="button" onclick="modalEdit.close()" class="bg-gray-300 px-4 py-2 rounded">Batal</button>
            </div>
        </form>
    </dialog>
</div>

<script>
    // Function to toggle QRIS image visibility based on selection
    function toggleImage() {
        const metode = document.querySelector('select[name="nama_metode"]').value;
        const qrisImage = document.getElementById('qrisImage');
        if (metode === 'QRIS') {
            qrisImage.classList.remove('hidden');
        } else {
            qrisImage.classList.add('hidden');
        }
    }

    // Function to toggle QRIS image visibility in edit modal
    function toggleEditImage() {
        const metode = document.getElementById('editNama').value;
        const qrisImage = document.getElementById('editQrisImage');
        if (metode === 'QRIS') {
            qrisImage.classList.remove('hidden');
        } else {
            qrisImage.classList.add('hidden');
        }
    }

    // Hide success message after 5 seconds
    setTimeout(function() {
        const successMessage = document.getElementById('successMessage');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 5000);

    function editMetode(id, nama, keterangan, nomor_tujuan) {
        document.getElementById('editNama').value = nama;
        document.getElementById('editKeterangan').value = keterangan;
        document.getElementById('editNomorTujuan').value = nomor_tujuan;
        document.getElementById('formEdit').action = `/pegawai/metode/${id}`;
        toggleEditImage();
        document.getElementById('modalEdit').showModal();
    }
</script>

@endsection
