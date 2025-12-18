@extends('backend.master')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">📩 Contact Messages</h4>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $key => $msg)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $msg->name }}</td>
                            <td>{{ $msg->email }}</td>
                            <td>{{ $msg->phone }}</td>
                            <td>{{ $msg->subject ?? 'N/A' }}</td>
                            <td>{{ Str::limit($msg->message, 50) }}</td>
                            <td>{{ $msg->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                No contact messages found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $messages->links() }}
        </div>
    </div>
</div>
@endsection
