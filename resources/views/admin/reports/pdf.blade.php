<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penggunaan Ruangan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        h2 { text-align: center; margin-bottom: 5px; }
        p.period { text-align: center; color: #555; margin-top: 0; }
    </style>
</head>
<body>
    <h2>Laporan Penggunaan Ruangan</h2>
    @if(request('start_date') || request('end_date'))
        <p class="period">
            Periode: {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->translatedFormat('d F Y') : 'Awal' }} 
            s/d {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->translatedFormat('d F Y') : 'Sekarang' }}
        </p>
    @endif

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Ruangan</th>
                <th>Kegiatan</th>
                <th>Peserta</th>
                <th>Waktu Penggunaan</th>
                <th>Status</th>
                <th>Verifikator</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $index => $booking)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $booking->user->name }}</td>
                <td>{{ $booking->room->name }}</td>
                <td>{{ $booking->activity_name }}</td>
                <td>{{ $booking->participant_count }}</td>
                <td>
                    {{ $booking->start_time->format('d/m/Y') }}<br>
                    {{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}
                </td>
                <td>{{ ucfirst($booking->status) }}</td>
                <td>
                    {{ $booking->verifier ? $booking->verifier->name : '-' }}<br>
                    <small>{{ $booking->verified_at ? $booking->verified_at->format('d/m/Y') : '' }}</small>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
