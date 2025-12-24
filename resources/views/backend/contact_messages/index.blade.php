@extends('backend.master')

@section('content')
<div class="container-fluid py-5">

    {{-- Page Header --}}
    <div class="mb-5 text-center fade-in">
        <h3 class="fw-bold text-primary">📩 Contact Messages</h3>
        <p class="text-secondary fs-6">All messages sent via the contact form are listed below.</p>
    </div>

    {{-- Card Container --}}
    <div class="card shadow-lg border-0 rounded-4 fade-in" style="animation-delay: 0.2s;">
        <div class="card-body p-4">

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
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
                        <tr class="fade-in" style="animation-delay: {{ 0.1 * $key }}s;">
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
                            <td colspan="7" class="text-center text-danger fw-semibold">
                                No contact messages found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3 fade-in" style="animation-delay: 0.5s;">
                {{ $messages->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>

</div>

{{-- Custom CSS --}}

<style>
    /* Fade-in animation */
    .fade-in {
        opacity: 0;
        transform: translateY(-20px);
        animation: fadeIn 0.8s forwards;
    }

    @keyframes fadeIn {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Card styling */
    .card {
        background: linear-gradient(145deg, #ffffff, #f2f8ff);
        border-left: 6px solid #0d6efd;
    }

    /* Table Styling */
    table th {
        font-weight: 600;
        text-align: center;
        background-color: #0d6efd !important;
        color: #fff;
        border: none;
    }

    table td {
        text-align: center;
        vertical-align: middle;
        color: #495057;
        border-top: 1px solid #dee2e6;
        transition: background 0.3s, color 0.3s;
    }

    table tbody tr:hover td {
        background-color: #e7f1ff;
        color: #0d6efd;
        cursor: pointer;
    }

    /* Striped row customization */
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f0f8ff;
    }

    /* Pagination Styling */
    .pagination .page-link {
        color: #0d6efd;
        border-radius: 50% !important;
        padding: 0.5rem 0.75rem;
        margin: 0 2px;
        transition: all 0.3s;
    }

    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
    }

    .pagination .page-link:hover {
        background-color: #0b5ed7;
        color: #fff;
    }

    /* Header Styling */
    h3 {
        font-size: 1.8rem;
    }

    p {
        font-size: 0.95rem;
    }

    /* Responsive table for mobile */
    @media (max-width: 768px) {
        table th, table td {
            font-size: 0.85rem;
            padding: 0.5rem;
        }
    }
</style>


{{-- JS for fade-in cascade effect --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const fadeElems = document.querySelectorAll('.fade-in');
        fadeElems.forEach((el, i) => {
            el.style.animationDelay = `${i * 0.9}s`;
            el.classList.add('fade-in');
        });
    });
</script>
@endsection
