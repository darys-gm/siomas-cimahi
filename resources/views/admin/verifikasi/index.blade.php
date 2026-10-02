@extends('layouts.admin')

@section('title', 'Verifikasi ORMAS')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Verifikasi ORMAS</h1>
        <div class="flex gap-2">
            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium">
                Total: {{ $ormas->total() ?? 0 }}
            </span>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Sumber</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ormas ?? [] as $item)
                <tr>
                    <td class="px-6 py-4 text-sm">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 text-sm font-medium">{{ $item->nama }}</td>
                    <td class="px-6 py-4 text-sm">{{ $item->jenisOrmas->nama ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $item->status_badge ?? 'bg-gray-500' }} text-white">
                            {{ $item->status_text ?? $item->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @if($item->user_id)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">User</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">Publik</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $item->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('admin.verifikasi.show', $item->id) }}" class="text-blue-600 hover:text-blue-900">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada pengajuan verifikasi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($ormas))
    <div class="mt-4">
        {{ $ormas->links() }}
    </div>
    @endif
</div>
@endsection