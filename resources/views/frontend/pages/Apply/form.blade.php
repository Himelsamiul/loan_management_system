@extends('frontend.master')

@section('content')

<div class="container py-4">
   @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <h2 class="mb-3">Apply for: {{ $loan->loan_name }}</h2>
    <p><strong>Loan Type:</strong> {{ $loan->loanType->loan_name }}</p>
    <p><strong>Interest:</strong> {{ $loan->interest }}%</p>

    <hr>

    <form action="{{ route('frontend.loan.apply.review') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="loan_name_id" value="{{ $loan->id }}">
       <input type="hidden" name="loan_type_id" value="{{ $loan->loan_type_id }}">


        <!-- ===================== PERSONAL INFORMATION ===================== -->
        <h4 class="mt-4">Personal Details</h4>
        <div class="row">

            <div class="col-md-6 mb-3">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Father’s Name *</label>
                <input type="text" name="father_name" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Mother’s Name *</label>
                <input type="text" name="mother_name" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">NID Number *</label>
                <input type="text" name="nid_number" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Date of Birth *</label>
                <input type="date" name="date_of_birth" class="form-control" required>
            </div>

            <!-- GENDER FIELD -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Gender *</label>
                <select name="gender" class="form-control" required>
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Marital Status *</label>
                <select name="marital_status" class="form-control" required>
                    <option value="">Select Status</option>
                    <option value="single">Single</option>
                    <option value="married">Married</option>
                    <option value="divorced">Divorced</option>
                    <option value="widowed">Widowed</option>
                </select>
            </div>

        </div>


        <!-- ===================== LOAN AMOUNT ===================== -->
        <h4 class="mt-4">Loan Information</h4>

        <div class="col-md-6 mb-3">
            <label class="form-label">Loan Amount (How much you want?) *</label>
            <input type="number" name="loan_amount" class="form-control" required>
        </div>
<div class="col-md-6 mb-3">
    <label class="form-label">Loan Duration (in months) *</label>
    <input type="number" name="loan_duration" class="form-control" min="1" required>
</div>


        <!-- ===================== ADDRESS INFORMATION ===================== -->
        <h4 class="mt-4">Address Details</h4>

        <div class="mb-3">
            <label class="form-label">Present Address *</label>
            <textarea name="present_address" class="form-control" rows="2" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Permanent Address *</label>
            <textarea name="permanent_address" class="form-control" rows="2" required></textarea>
        </div>


        <!-- ===================== DOCUMENT UPLOAD (NOT REQUIRED) ===================== -->
        <h4 class="mt-4">Upload Documents (Optional - PDF Only)</h4>

        <div class="row">

            <div class="col-md-6 mb-3">
                <label class="form-label">Document 1 (optional)</label>
                <input type="file" name="document1" accept="application/pdf" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Document 2 (optional)</label>
                <input type="file" name="document2" accept="application/pdf" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Document 3 (optional)</label>
                <input type="file" name="document3" accept="application/pdf" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Document 4 (optional)</label>
                <input type="file" name="document4" accept="application/pdf" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Document 5 (optional)</label>
                <input type="file" name="document5" accept="application/pdf" class="form-control">
            </div>

        </div>


        <button type="submit" class="btn btn-success mt-4 px-4">
            Submit Loan Application
        </button>

    </form>

</div>

@endsection
