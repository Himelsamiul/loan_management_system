@extends('backend.master')

@section('content')

<style>
    .dashboard-card {
        border-radius: 10px;
        padding: 20px;
        color: #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: 0.3s;
    }
    .dashboard-card:hover {
        transform: translateY(-4px);
    }
    .card-icon {
        font-size: 40px;
        opacity: 0.4;
    }
    .card-title {
        font-size: 18px;
        font-weight: 600;
    }
    .card-value {
        font-size: 26px;
        font-weight: 700;
        margin-top: 5px;
    }
    .section-title {
        font-size: 22px;
        font-weight: 700;
        margin-top: 25px;
        margin-bottom: 15px;
    }
</style>

<div class="container-fluid py-3">

    <h2 class="mb-4">Admin Dashboard</h2>

    {{-- =================== --}}
    {{-- TOP SUMMARY CARDS --}}
    {{-- =================== --}}
    <div class="row">

        <div class="col-md-3 mb-3">
            <div class="dashboard-card bg-primary">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="card-title">Total Applications</div>
                        <div class="card-value">{{ $totalApplications }}</div>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="dashboard-card bg-warning">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="card-title">Pending</div>
                        <div class="card-value">{{ $totalPending }}</div>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="dashboard-card bg-success">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="card-title">Approved</div>
                        <div class="card-value">{{ $totalApproved }}</div>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="dashboard-card bg-danger">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="card-title">Rejected</div>
                        <div class="card-value">{{ $totalRejected }}</div>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- =================== --}}
    {{-- LOAN STATUS CARDS --}}
    {{-- =================== --}}
    <h3 class="section-title">Loan Status</h3>

    <div class="row">

        <div class="col-md-3 mb-3">
            <div class="dashboard-card bg-info">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="card-title">Loan Given</div>
                        <div class="card-value">{{ $totalLoanGiven }}</div>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="dashboard-card bg-dark">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="card-title">Closed Loans</div>
                        <div class="card-value">{{ $totalClosed }}</div>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="dashboard-card bg-secondary">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="card-title">Ongoing Loans</div>
                        <div class="card-value">{{ $totalOngoing }}</div>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-spinner"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- =============== --}}
    {{-- MONEY SUMMARY --}}
    {{-- =============== --}}
    <h3 class="section-title">Financial Summary</h3>

    <div class="row">

        <div class="col-md-3 mb-3">
            <div class="dashboard-card bg-primary">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="card-title">Total Given Loan</div>
                        <div class="card-value">{{ number_format($totalGivenAmount, 2) }}৳</div>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-donate"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="dashboard-card bg-success">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="card-title">Total Collection</div>
                        <div class="card-value">{{ number_format($totalCollection, 2) }}৳</div>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>
        </div>


    </div>


    {{-- =============== --}}
    {{-- SYSTEM COUNTS --}}
    {{-- =============== --}}
    <h3 class="section-title">System Overview</h3>

    <div class="row">

        <div class="col-md-3 mb-3">
            <div class="dashboard-card bg-info">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="card-title">Loan Types</div>
                        <div class="card-value">{{ $totalLoanTypes }}</div>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-layer-group"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="dashboard-card bg-primary">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="card-title">Loan Names</div>
                        <div class="card-value">{{ $totalLoanNames }}</div>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-list"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="dashboard-card bg-success">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="card-title">Registered Users</div>
                        <div class="card-value">{{ $totalUsers }}</div>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
