@foreach ($produk as $p)
    <tr>
        <td>{{ $p->kode_produk }}</td>
        <td>
            {{ $p->nama_produk }}
            <span style="display:none;">
                @if ($p->variants->isNotEmpty())
                    @foreach ($p->variants as $variant)
                        {{ optional($variant->jenis)->jenis }}
                    @endforeach
                @endif
            </span>
        </td>
        <td>{{ $p->kategori->nama_kategori ?? 'N/A' }}</td>
        <td>
            @if ($p->variants->isNotEmpty())
                @foreach ($p->variants as $variant)
                    {{ $variant->stok ?? 0 }}@if (!$loop->last)
                        ,
                    @endif
                @endforeach
            @else
                0
            @endif
        </td>
        <td>
            <span class="badge {{ $p->status_aktif ? 'badge-light-success' : 'badge-light-danger' }}">
                {{ $p->status_aktif ? 'Aktif' : 'Non-Aktif' }}
            </span>
        </td>
        <td>
            <a href="{{ route('admin.produk.edit', $p->id) }}"
                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                <i class="ki-duotone ki-pencil fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </a>
            <form action="{{ route('admin.produk.destroy', $p->id) }}" method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm"
                    onclick="return confirm('Yakin ingin menghapus produk ini?')">
                    <i class="ki-duotone ki-trash fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                        <span class="path5"></span>
                    </i>
                </button>
            </form>
        </td>
    </tr>
@endforeach
