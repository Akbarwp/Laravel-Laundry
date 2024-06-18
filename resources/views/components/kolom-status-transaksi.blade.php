@props(['value'])

@if ($value == "Baru")
    <span class="badge badge-info">{{ $value }}</span>
@elseif ($value == "Proses")
    <span class="badge badge-warning">{{ $value }}</span>
@elseif ($value == "Siap Ambil")
    <span class="badge badge-primary">{{ $value }}</span>
@elseif ($value == "Antar")
    <span class="badge badge-secondary">{{ $value }}</span>
@elseif ($value == "Selesai")
    <span class="badge badge-success">{{ $value }}</span>
@elseif ($value == "Batal")
    <span class="badge badge-error">{{ $value }}</span>
@elseif ($value == "Lunas")
    <span class="badge badge-success bg-green-300">{{ $value }}</span>
@elseif ($value == "DP")
    <span class="badge badge-warning bg-yellow-300">{{ $value }}</span>
@endif
