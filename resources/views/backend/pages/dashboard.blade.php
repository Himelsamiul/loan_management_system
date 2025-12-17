@extends('backend.master')

@section('content')

<style>
/* =========================
   Dashboard Card Styles
=========================== */
.dashboard-card {
    border-radius: 12px;
    padding: 20px;
    color: #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: 0.3s;
    height: 100%;
}
.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.3);
}
.card-icon {
    font-size: 40px;
    opacity: 0.3;
}
.card-title {
    font-size: 16px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 2px;
}
.card-value {
    font-size: 28px;
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

/* Background Colors */
.bg-primary { background-color: #007bff !important; }
.bg-success { background-color: #28a745 !important; }
.bg-danger { background-color: #dc3545 !important; }
.bg-warning { background-color: #ffc107 !important; color: #343a40 !important; }
.bg-info { background-color: #33165b71 !important; }
.bg-dark { background-color: #450529ff !important; }
.bg-secondary { background-color: #358d5fff !important; }

/* Pie Chart Container */
.pie-chart-container {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 30px;
}
.chart-box {
    flex: 1;
    min-width: 300px;
    max-width: 500px;
    background: #f8f9fa;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
</style>

<div class="container-fluid py-4">
    <h1 class="mb-5 display-5 text-dark font-weight-bold">📊 Admin Dashboard</h1>

    {{-- Application Summary --}}
    <h3 class="section-title">Application Summary</h3>
    <div class="row g-3">
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card bg-primary d-flex justify-content-between align-items-center">
                <div>
                    <div class="card-title">Total Applications</div>
                    <div class="card-value count-up" data-target="{{ $totalApplications }}">0</div>
                </div>
                <div class="card-icon"><i class="fas fa-file-alt"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card bg-warning d-flex justify-content-between align-items-center">
                <div>
                    <div class="card-title">Pending</div>
                    <div class="card-value count-up" data-target="{{ $totalPending }}">0</div>
                </div>
                <div class="card-icon"><i class="fas fa-clock"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card bg-success d-flex justify-content-between align-items-center">
                <div>
                    <div class="card-title">Approved</div>
                    <div class="card-value count-up" data-target="{{ $totalApproved }}">0</div>
                </div>
                <div class="card-icon"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card bg-danger d-flex justify-content-between align-items-center">
                <div>
                    <div class="card-title">Rejected</div>
                    <div class="card-value count-up" data-target="{{ $totalRejected }}">0</div>
                </div>
                <div class="card-icon"><i class="fas fa-times-circle"></i></div>
            </div>
        </div>
    </div>

    {{-- Loan Status --}}
    <h3 class="section-title">Loan Status</h3>
    <div class="row g-3">
        <div class="col-lg-4 col-md-6">
            <div class="dashboard-card bg-info d-flex justify-content-between align-items-center">
                <div>
                    <div class="card-title">Loan Given</div>
                    <div class="card-value count-up" data-target="{{ $totalLoanGiven }}">0</div>
                </div>
                <div class="card-icon"><i class="fas fa-hand-holding-usd"></i></div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="dashboard-card bg-dark d-flex justify-content-between align-items-center">
                <div>
                    <div class="card-title">Closed Loans</div>
                    <div class="card-value count-up" data-target="{{ $totalClosed }}">0</div>
                </div>
                <div class="card-icon"><i class="fas fa-lock"></i></div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="dashboard-card bg-secondary d-flex justify-content-between align-items-center">
                <div>
                    <div class="card-title">Ongoing Loans</div>
                    <div class="card-value count-up" data-target="{{ $totalOngoing }}">0</div>
                </div>
                <div class="card-icon"><i class="fas fa-spinner"></i></div>
            </div>
        </div>
    </div>

    {{-- Financial Summary --}}
    <h3 class="section-title">Financial Summary</h3>
    <div class="row g-3">
        <div class="col-lg-6 col-md-6">
            <div class="dashboard-card bg-primary d-flex justify-content-between align-items-center">
                <div>
                    <div class="card-title">Total Given Loan</div>
                    <div class="card-value count-up-currency" data-target="{{ $totalGivenAmount }}">0৳</div>
                </div>
                <div class="card-icon"><i class="fas fa-donate"></i></div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="dashboard-card bg-success d-flex justify-content-between align-items-center">
                <div>
                    <div class="card-title">Total Collection</div>
                    <div class="card-value count-up-currency" data-target="{{ $totalCollection }}">0৳</div>
                </div>
                <div class="card-icon"><i class="fas fa-wallet"></i></div>
            </div>
        </div>
    </div>

    {{-- System Overview --}}
    <h3 class="section-title">System Overview</h3>
    <div class="row g-3">
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card bg-info d-flex justify-content-between align-items-center">
                <div>
                    <div class="card-title">Loan Types</div>
                    <div class="card-value count-up" data-target="{{ $totalLoanTypes }}">0</div>
                </div>
                <div class="card-icon"><i class="fas fa-layer-group"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card bg-primary d-flex justify-content-between align-items-center">
                <div>
                    <div class="card-title">Loan Names</div>
                    <div class="card-value count-up" data-target="{{ $totalLoanNames }}">0</div>
                </div>
                <div class="card-icon"><i class="fas fa-list"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card bg-success d-flex justify-content-between align-items-center">
                <div>
                    <div class="card-title">Clients</div>
                    <div class="card-value count-up" data-target="{{ $registrations }}">0</div>
                </div>
                <div class="card-icon"><i class="fas fa-users"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card bg-secondary d-flex justify-content-between align-items-center">
                <div>
                    <div class="card-title">Users</div>
                    <div class="card-value count-up" data-target="{{ $totalStaff ?? 0 }}">0</div>
                </div>
                <div class="card-icon"><i class="fas fa-user-tie"></i></div>
            </div>
        </div>
    </div>

    {{-- Pie Charts --}}
    <h3 class="section-title">Charts Overview</h3>
    <div class="pie-chart-container">
        <div class="chart-box">
            <canvas id="applicationStatusChart"></canvas>
        </div>
        <div class="chart-box">
            <canvas id="loanStatusChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    // Counter Animations
    function animateCounter(element, target, duration = 1500) {
        let startTime = null;
        const step = (timestamp) => {
            if (!startTime) startTime = timestamp;
            const progress = timestamp - startTime;
            const percentage = Math.min(progress / duration, 1);
            const value = Math.floor(percentage * target);
            element.textContent = value;
            if (percentage < 1) requestAnimationFrame(step);
            else element.textContent = target;
        };
        requestAnimationFrame(step);
    }
    document.querySelectorAll('.count-up').forEach(el => {
        const target = parseInt(el.getAttribute('data-target'));
        animateCounter(el, target);
    });

    // Currency Counters
    function animateCurrencyCounter(el, target, duration = 1500) {
        let startTime = null;
        const numericTarget = parseFloat(String(target).replace(/,/g,''));
        const step = (timestamp) => {
            if (!startTime) startTime = timestamp;
            const progress = timestamp - startTime;
            const percentage = Math.min(progress / duration, 1);
            const value = (percentage * numericTarget).toFixed(2);
            el.textContent = Number(value).toLocaleString() + '৳';
            if (percentage < 1) requestAnimationFrame(step);
            else el.textContent = Number(numericTarget).toLocaleString() + '৳';
        };
        requestAnimationFrame(step);
    }
    document.querySelectorAll('.count-up-currency').forEach(el => {
        const target = el.getAttribute('data-target');
        animateCurrencyCounter(el, target);
    });

    // Pie Charts
    const appCtx = document.getElementById('applicationStatusChart').getContext('2d');
    const loanCtx = document.getElementById('loanStatusChart').getContext('2d');

    const applicationStatusChart = new Chart(appCtx, {
        type: 'pie',
        data: {
            labels: ['Pending', 'Approved', 'Rejected'],
            datasets: [{
                label: 'Application Status',
                data: [{{ $totalPending }}, {{ $totalApproved }}, {{ $totalRejected }}],
                backgroundColor: ['#ffc107','#28a745','#dc3545'],
            }]
        },
        options: {
            plugins: {
                legend: { display: true, position: 'bottom' }
            }
        }
    });

    const loanStatusChart = new Chart(loanCtx, {
        type: 'pie',
        data: {
            labels: ['Loan Given', 'Ongoing Loan', 'Closed Loan'],
            datasets: [{
                label: 'Loan Status',
                data: [{{ $totalLoanGiven }}, {{ $totalOngoing }}, {{ $totalClosed }}],
                backgroundColor: ['#17a2b8','#06360aff','#f62323ff'],
            }]
        },
        options: {
            plugins: {
                legend: { display: true, position: 'bottom' }
            }
        }
    });

});
</script>

@endsection
