<div class="action-buttons">
    <a href="{{ url('/kategori/' . $kategori_id . '/edit') }}" class="btn btn-sm btn-primary">Edit</a>
    <form action="{{ url('/kategori/' . $kategori_id) }}" method="POST" style="display:inline;">
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">Delete</button>
    </form>
</div>