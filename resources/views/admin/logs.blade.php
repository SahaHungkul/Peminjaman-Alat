@extends('layouts.app')

@section('content')
    <h3>Log Aktivitas Sistem</h3>
    <div class="mb-3">
        <div class="card">
            <div class="card-body">
                <table class="table table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu</th>
                            <th>User</th>
                            <th>Aksi</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentLog as $log)
                            <tr>
                                <td class="">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>
                                    <span class="">{{ $log->user->name }}</span>
                                    <span class="">({{ ucfirst($log->user->role) }})</span>
                                </td>
                                <td>{{ $log->action }}</td>
                                <td>{{ Str::limit($log->deskripsi, 50) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-3">Belum Ada Aktivitas Tercatat</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">{{ $recentLog->links('pagination::bootstrap-5') }}</div>
            </div>
        </div>
    </div>
@endsection
