@extends('backend.master')

@section('content')

<style>
    /* Original CSS from the user's request (retained for consistency) */
    .dashboard-card {
        border-radius: 10px;
        padding: 20px;
        color: #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: 0.3s;
        background-color: #343a40;
        height: 100%;
    }
    .dashboard-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }
    .card-icon {
        font-size: 40px;
        opacity: 0.5;
        align-self: center;
    }
    .card-title {
        font-size: 16px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }
    .card-value {
        font-size: 30px;
        font-weight: 800;
        margin-top: 5px;
    }
    .section-title {
        font-size: 24px;
        font-weight: 700;
        margin-top: 40px;
        margin-bottom: 20px;
        color: #343a40;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 5px;
    }

    /* Custom Color Enhancements */
    .bg-primary { background-color: #007bff !important; }
    .bg-warning { background-color: #ffc107 !important; color: #343a40 !important; }
    .bg-success { background-color: #28a745 !important; }
    .bg-danger { background-color: #dc3545 !important; }
    .bg-info { background-color: #17a2b8 !important; }
    .bg-dark { background-color: #343a40 !important; }
    .bg-secondary { background-color: #6c757d !important; }
    .bg-warning .card-icon {
        opacity: 0.3;
        color: #343a40;
    }
</style>

<div class="container-fluid py-4">

    <h1 class="mb-5 display-5 text-dark font-weight-bold">📊 Admin Dashboard</h1>

    <h3 class="section-title">Application Summary</h3>
    <div class="row">

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="dashboard-card bg-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title">Total Applications</div>
                        {{-- Added data-target attribute for JS --}}
                        <div class="card-value count-up" data-target="{{ $totalApplications }}">0</div>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="dashboard-card bg-warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title">Pending</div>
                        {{-- Added data-target attribute for JS --}}
                        <div class="card-value count-up" data-target="{{ $totalPending }}">0</div>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="dashboard-card bg-success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title">Approved</div>
                        {{-- Added data-target attribute for JS --}}
                        <div class="card-value count-up" data-target="{{ $totalApproved }}">0</div>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="dashboard-card bg-danger">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title">Rejected</div>
                        {{-- Added data-target attribute for JS --}}
                        <div class="card-value count-up" data-target="{{ $totalRejected }}">0</div>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <h3 class="section-title">Loan Status</h3>

    <div class="row">

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="dashboard-card bg-info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title">Loan Given</div>
                        {{-- Added data-target attribute for JS --}}
                        <div class="card-value count-up" data-target="{{ $totalLoanGiven }}">0</div>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="dashboard-card bg-dark">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title">Closed Loans</div>
                        {{-- Added data-target attribute for JS --}}
                        <div class="card-value count-up" data-target="{{ $totalClosed }}">0</div>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="dashboard-card bg-secondary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title">Ongoing Loans</div>
                        {{-- Added data-target attribute for JS --}}
                        <div class="card-value count-up" data-target="{{ $totalOngoing }}">0</div>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-spinner"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <h3 class="section-title">Financial Summary</h3>

    <div class="row">

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="dashboard-card bg-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title">Total Given Loan</div>
                        {{-- This is a currency field, we'll use a slightly different class/logic --}}
                        <div class="card-value count-up-currency" data-target="{{ $totalGivenAmount }}">0৳</div>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-donate"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="dashboard-card bg-success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title">Total Collection</div>
                        {{-- This is a currency field, we'll use a slightly different class/logic --}}
                        <div class="card-value count-up-currency" data-target="{{ $totalCollection }}">0৳</div>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <h3 class="section-title">System Overview</h3>

    <div class="row">

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="dashboard-card bg-info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title">Loan Types</div>
                        {{-- Added data-target attribute for JS --}}
                        <div class="card-value count-up" data-target="{{ $totalLoanTypes }}">0</div>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-layer-group"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="dashboard-card bg-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title">Loan Names</div>
                        {{-- Added data-target attribute for JS --}}
                        <div class="card-value count-up" data-target="{{ $totalLoanNames }}">0</div>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-list"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="dashboard-card bg-success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title">Registered Users</div>
                        {{-- Added data-target attribute for JS --}}
                        <div class="card-value count-up" data-target="{{ $totalUsers }}">0</div>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

{{-- =================== --}}
{{-- JAVASCRIPT FOR DYNAMICS --}}
{{-- =================== --}}

<script>
document.addEventListener('DOMContentLoaded', () => {

    // Helper function for the animated counter
    function animateCounter(element, target, duration = 1500) {
        const start = 0;
        let startTime = null;

        const step = (timestamp) => {
            if (!startTime) startTime = timestamp;
            const progress = timestamp - startTime;
            const percentage = Math.min(progress / duration, 1);
            const value = Math.floor(percentage * target);

            element.textContent = value;

            if (percentage < 1) {
                window.requestAnimationFrame(step);
            } else {
                element.textContent = target; // Ensure it hits the exact target value
            }
        };

        window.requestAnimationFrame(step);
    }

    // 1. Animate all simple integer counters
    document.querySelectorAll('.count-up').forEach(element => {
        const target = parseInt(element.getAttribute('data-target'));
        animateCounter(element, target);
    });

    // 2. Animate currency counters with formatting
    function animateCurrencyCounter(element, target, duration = 1500) {
        const start = 0;
        let startTime = null;
        // The Laravel variable is formatted like '1,234.56', so we strip non-numeric characters except for the decimal point
        const numericTarget = parseFloat(String(target).replace(/,/g, ''));

        const step = (timestamp) => {
            if (!startTime) startTime = timestamp;
            const progress = timestamp - startTime;
            const percentage = Math.min(progress / duration, 1);
            const currentValue = percentage * numericTarget;

            // Use toLocaleString for number formatting (thousands separator)
            const formattedValue = currentValue.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            element.textContent = formattedValue + '৳';

            if (percentage < 1) {
                window.requestAnimationFrame(step);
            } else {
                // Ensure the final value is exactly the target with proper formatting
                const finalFormattedValue = numericTarget.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                element.textContent = finalFormattedValue + '৳';
            }
        };

        window.requestAnimationFrame(step);
    }

    document.querySelectorAll('.count-up-currency').forEach(element => {
        const target = element.getAttribute('data-target');
        animateCurrencyCounter(element, target);
    });
});
</script>


@endsection