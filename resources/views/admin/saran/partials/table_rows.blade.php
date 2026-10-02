@php
    $no = ($saran->currentPage() - 1) * $saran->perPage() + 1;
@endphp

@forelse($saran as $item)
<tr class="hover:bg-gray-50 transition {{ $item->status == 'baru' ? 'bg-yellow-50' : '' }}">
    <td class="px-4 py-3 text-sm text-gray-500 text-center">{{ $no++ }}</td>
    <td class="px-4 py-3 text-sm text-gray-700 font-medium">{{ $item->nama ?? '-' }}</td>
    <td class="px-4 py-3 text-sm text-gray-500">{{ $item->email ?? '-' }}</td>
    <td class="px-4 py-3">
        <div class="flex items-center gap-2">
            <span class="pesan-preview">{{ Str::limit($item->pesan, 50) }}</span>
            <button onclick="lihatPesan({{ $item->id }}, '{{ addslashes($item->pesan) }}', '{{ addslashes($item->nama) }}', '{{ $item->email ?? '-' }}', '{{ $item->created_at->translatedFormat('d/m/Y H:i') }}')" 
                    class="text-xs text-blue-600 hover:text-blue-800 font-medium whitespace-nowrap">
                Lihat Selengkapnya
            </button>
        </div>
    </td>
    <td class="px-4 py-3">
        @if($item->status == 'baru')
            <span class="badge-status belum-dibaca">
                <span class="dot"></span>
                <span class="icon"><i class="fas fa-envelope"></i></span>
                Belum Dibaca
            </span>
        @else
            <span class="badge-status sudah-dibaca">
                <span class="dot"></span>
                <span class="icon"><i class="fas fa-check-circle"></i></span>
                Sudah Dibaca
            </span>
        @endif
    </td>
    <td class="px-4 py-3 text-sm text-gray-500">
        {{ $item->created_at->translatedFormat('d/m/Y H:i') }} WIB
    </td>
    <td class="px-4 py-3">
        <div class="action-container">
            @if($item->status == 'baru')
                <button onclick="tandaiDibaca({{ $item->id }})" 
                        class="btn-action btn-read" 
                        title="Tandai sudah dibaca">
                    <i class="fas fa-check"></i>
                </button>
            @endif
            <button onclick="confirmHapus({{ $item->id }})" 
                    class="btn-action btn-delete" 
                    title="Hapus saran">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
        <i class="fas fa-inbox text-3xl text-gray-300 block mb-2"></i>
        Belum ada saran yang masuk
    </td>
</tr>
@endforelse