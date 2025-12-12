@extends('frontend.master')

@section('content')

<!-- Include AOS CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">

<style>
    body {
        background-color: #f5f7fa; /* Light soft background */
    }

    .card {
        background: #ffffff;
        border: none;
    }

    h2, h4 {
        color: #0d6efd; /* Bootstrap primary blue */
        font-weight: 600;
    }

    .form-label {
        font-weight: 500;
        color: #333;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25);
    }

    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004080;
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .btn-primary:focus {
        box-shadow: 0 0 0 0.25rem rgba(13,110,253,.5);
    }

    /* Section headers */
    .section-header {
        background-color: #e9f2ff;
        padding: 10px 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        color: #0d6efd;
        font-weight: 600;
    }

    /* Highlighted card shadow */
    .card-shadow {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }

    .card-shadow:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    }
</style>

<div class="container py-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-down">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card card-shadow rounded-4 p-4" data-aos="fade-up" data-aos-duration="1000">
        <h2 class="mb-4 text-center">Apply for: <span class="text-primary">{{ $loan->loan_name }}</span></h2>
        <p class="text-center"><strong>Loan Type:</strong> {{ $loan->loanType->loan_name }} | <strong>Interest:</strong> {{ $loan->interest }}%</p>
        <hr>

        <form action="{{ route('frontend.loan.apply.review') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="loan_name_id" value="{{ $loan->id }}">
            <input type="hidden" name="loan_type_id" value="{{ $loan->loan_type_id }}">

            @php $user = Auth::user(); @endphp

            <!-- ===================== PERSONAL INFORMATION ===================== -->
            <div class="section-header" data-aos="fade-right">Personal Details</div>
            <div class="row g-3">
                <div class="col-md-6" data-aos="fade-right">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control form-control-lg" value="{{ $user->name }} {{ $user->sure_name }}" readonly>
                </div>

                <div class="col-md-6" data-aos="fade-left">
                    <label class="form-label">Father’s Name *</label>
                    <input type="text" name="father_name" class="form-control form-control-lg" required>
                </div>

                <div class="col-md-6" data-aos="fade-right">
                    <label class="form-label">Mother’s Name *</label>
                    <input type="text" name="mother_name" class="form-control form-control-lg" required>
                </div>

                <div class="col-md-6" data-aos="fade-left">
                    <label class="form-label">NID Number *</label>
                    <input type="text" name="nid_number" class="form-control form-control-lg" required>
                </div>

                <div class="col-md-6" data-aos="fade-right">
                    <label class="form-label">Date of Birth *</label>
                    <input type="text" name="date_of_birth" class="form-control form-control-lg" value="{{ $user->date_of_birth }}" id="dob" readonly>
                </div>

                <div class="col-md-6" data-aos="fade-left">
                    <label class="form-label">Mobile *</label>
                    <input type="text" name="mobile" class="form-control form-control-lg" value="{{ $user->mobile }}" readonly>
                </div>

                <div class="col-md-6" data-aos="fade-right">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control form-control-lg" value="{{ $user->email }}" readonly>
                </div>

                <div class="col-md-6" data-aos="fade-left">
                    <label class="form-label">Gender *</label>
                    <select name="gender" class="form-select form-select-lg" required>
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>

                <div class="col-md-6" data-aos="fade-right">
                    <label class="form-label">Marital Status *</label>
                    <select name="marital_status" class="form-select form-select-lg" required>
                        <option value="">Select Status</option>
                        <option value="single">Single</option>
                        <option value="married">Married</option>
                        <option value="divorced">Divorced</option>
                        <option value="widowed">Widowed</option>
                    </select>
                </div>
            </div>

            <!-- ===================== LOAN AMOUNT ===================== -->
            <div class="section-header mt-5" data-aos="fade-left">Loan Information</div>
            <div class="row g-3">
                <div class="col-md-6" data-aos="fade-right">
                    <label class="form-label">Loan Amount (BDT) *</label>
                    <input type="number" name="loan_amount" class="form-control form-control-lg" required>
                </div>
                <div class="col-md-6" data-aos="fade-left">
                    <label class="form-label">Loan Duration (in months) *</label>
                    <input type="number" name="loan_duration" class="form-control form-control-lg" min="1" required>
                </div>
            </div>

            <!-- ===================== ADDRESS INFORMATION ===================== -->
            <div class="section-header mt-5" data-aos="fade-right">Address Details</div>
            <div class="mb-3" data-aos="fade-up">
                <label class="form-label">Present Address *</label>
                <textarea name="present_address" class="form-control form-control-lg" rows="2" required>{{ $user->address }}</textarea>
            </div>

            <div class="mb-3" data-aos="fade-up">
                <label class="form-label">Permanent Address *</label>
                <textarea name="permanent_address" class="form-control form-control-lg" rows="2" required>{{ $user->address }}</textarea>
            </div>

            <!-- ===================== DOCUMENT UPLOAD ===================== -->
            <div class="section-header mt-5" data-aos="fade-left">Upload Documents (Optional)</div>
            <div class="row g-3">
                @for($i=1; $i<=5; $i++)
                    <div class="col-md-6" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                        <label class="form-label">Document {{ $i }}</label>
                        <input type="file" name="document{{ $i }}" accept="application/pdf" class="form-control form-control-lg">
                    </div>
                @endfor
            </div>

            <div class="text-center mt-4" data-aos="zoom-in">
                <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">Submit Loan Application</button>
            </div>
        </form>
    </div>
</div>

<!-- Include AOS JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true
    });

    // Dynamic date picker
    document.addEventListener('DOMContentLoaded', function() {
        const dobInput = document.getElementById('dob');
        dobInput.type = 'date';
        dobInput.classList.add('form-control');
    });
</script>

@endsection
