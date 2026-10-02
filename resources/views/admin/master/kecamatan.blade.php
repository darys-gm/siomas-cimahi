@extends('layouts.admin')

@section('title', 'Master Kecamatan')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Master Kecamatan</h1>
        <button onclick="showTambahForm()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus"></i> Tambah
        </button>
    </div>

    <div id="tambahForm" class="hidden mb-4 p-4 bg-gray-50 rounded-lg border">
        <form action="{{ route('admin.master.kecamatan') }}" method="POST">
            @csrf
            <div class="flex gap-4">
                <input type="text" name="nama" placeholder="Nama Kecamatan" class="flex-1 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Simpan</button>
                <button type="button" onclick="hideTambahForm()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Batal</button>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Nama Kecamatan</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Jumlah ORMAS</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data ?? [] as $item)
                <tr>
                    <td class="px-6 py-4 text-sm">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 text-sm">{{ $item->nama }}</td>
                    <td class="px-6 py-4 text-sm">{{ $item->ormas_count ?? 0 }}</td>
                    <td class="px-6 py-4 text-sm">
                        <button onclick="editKecamatan({{ $item->id }}, '{{ $item->nama }}')" class="text-blue-600 hover:text-blue-900">Edit</button>
                        <form action="{{ route('admin.master.kecamatan', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada data kecamatan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-96">
        <h3 class="text-lg font-semibold mb-4">Edit Kecamatan</h3>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <input type="text" id="editNama" name="nama" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <div class="flex justify-end mt-4 gap-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
function showTambahForm() {
    document.getElementById('tambahForm').classList.remove('hidden');
}
function hideTambahForm() {
    document.getElementById('tambahForm').classList.add('hidden');
}
function editKecamatan(id, nama) {
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editNama').value = nama;
    document.getElementById('editForm').action = "{{ route('admin.master.kecamatan', '') }}/" + id;
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
@endsection