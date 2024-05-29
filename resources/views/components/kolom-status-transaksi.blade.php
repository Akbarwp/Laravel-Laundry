@props(['value'])

@if ($value == "Baru")
    <span class="badge badge-info">{{ $value }}</span>
@elseif ($value == "Proses")
    <span class="badge badge-warning">{{ $value }}</span>
@elseif ($value == "Siap Diambil")
    <span class="badge badge-primary">{{ $value }}</span>
@elseif ($value == "Pengantaran")
    <span class="badge badge-secondary">{{ $value }}</span>
@elseif ($value == "Selesai")
    <span class="badge badge-success">{{ $value }}</span>
@elseif ($value == "Batal")
    <span class="badge badge-error">{{ $value }}</span>
@endif
