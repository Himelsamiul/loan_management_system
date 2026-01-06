@extends('backend.master')

@section('content')
<div class="container-fluid py-4">

    <h2 class="mb-4 text-center text-primary fw-bold">Loan Application Full Details</h2>

    <div class="row">

        {{-- Left Column --}}
        <div class="col-md-6">

            {{-- Applicant Information --}}
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <strong>Applicant Information</strong>
                </div>
                <div class="card-body">
                    <p><strong>Full Name:</strong> {{ $application->name }}</p>
                    <p><strong>Mobile Number:</strong> {{ $application->user->mobile ?? 'N/A' }}</p>
                    <p><strong>Loan Type:</strong> {{ $application->loan_type->loan_name ?? 'N/A' }}</p>
                    <p><strong>Loan Name:</strong> {{ $application->loan_name->loan_name ?? 'N/A' }}</p>
                    <p><strong>Status:</strong>
                        @if($application->status=='pending') Pending
                        @elseif($application->status=='approved') Approved
                        @elseif($application->status=='loan_given') Ongoing
                        @elseif($application->status=='closed') Closed
                        @elseif($application->status=='rejected') Rejected
                        @endif
                    </p>
                    <p><strong>Applied At:</strong> {{ $application->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>

            {{-- Personal Information --}}
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <strong>Personal Information</strong>
                </div>
                <div class="card-body">
                    <p><strong>Father’s Name:</strong> {{ $application->father_name }}</p>
                    <p><strong>Mother’s Name:</strong> {{ $application->mother_name }}</p>
                    <p><strong>NID Number:</strong> {{ $application->nid_number }}</p>
                    <p><strong>Date of Birth:</strong> {{ \Carbon\Carbon::parse($application->date_of_birth)->format('d M Y') }}</p>
                    <p><strong>Gender:</strong> {{ ucfirst($application->gender) }}</p>
                    <p><strong>Marital Status:</strong> {{ ucfirst($application->marital_status) }}</p>
                </div>
            </div>

            {{-- Address --}}
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <strong>Address</strong>
                </div>
                <div class="card-body">
                    <p><strong>Present Address:</strong> {{ $application->present_address }}</p>
                    <p><strong>Permanent Address:</strong> {{ $application->permanent_address }}</p>
                </div>
            </div>

        </div>

        {{-- Right Column --}}
        <div class="col-md-6">

            {{-- Loan Information --}}
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <strong>Loan Information</strong>
                </div>
                <div class="card-body">
                    <p><strong>Loan Amount:</strong> {{ number_format($application->loan_amount,2) }}</p>
                    <p><strong>Loan Duration:</strong> {{ $application->loan_duration }} months</p>
                    @php
                        $interestRate = $application->loan_name->interest ?? 0;
                        $totalAmount = $application->loan_amount + ($application->loan_amount * $interestRate / 100);
                        $monthlyInstallment = $application->loan_duration ? $totalAmount / $application->loan_duration : 0;
                    @endphp
                    <p><strong>Total Amount (Loan + Profit):</strong> {{ number_format($totalAmount,2) }}</p>
                    <p><strong>Monthly Installment:</strong> {{ number_format($monthlyInstallment,2) }}</p>
                </div>
            </div>

            {{-- Payment / Mobile Banking Information --}}
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <strong>Payment / Mobile Banking Information</strong>
                </div>
                <div class="card-body">
                    <p><strong>Card Type:</strong> {{ $application->card_type }}</p>
                    <p><strong>Card Brand:</strong> {{ $application->card_brand }}</p>
                    <p><strong>Card Number:</strong> {{ $application->card_number }}</p>
                    <p><strong>Card Holder:</strong> {{ $application->card_holder }}</p>
                    <p><strong>Card Expiry:</strong> {{ $application->card_expiry }}</p>
                    <p><strong>Card CVC:</strong> {{ $application->card_cvc }}</p>
                    <p><strong>Mobile Banking Provider:</strong> {{ $application->mobile_provider ?? 'N/A' }}</p>
                    <p><strong>Mobile Number:</strong> {{ $application->mobile_number ?? 'N/A' }}</p>
                </div>
            </div>

            {{-- Nominee Information --}}
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <strong>Nominee Information</strong>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $application->nominee_name ?? 'N/A' }}</p>
                    <p><strong>Relationship:</strong> {{ $application->nominee_relation ?? 'N/A' }}</p>
                    <p><strong>NID Number:</strong> {{ $application->nominee_nid ?? 'N/A' }}</p>
                    <p><strong>Date of Birth:</strong> 
                        @if($application->nominee_dob) 
                            {{ \Carbon\Carbon::parse($application->nominee_dob)->format('d M Y') }}
                        @else 
                            N/A 
                        @endif
                    </p>
                    <p><strong>Address:</strong> {{ $application->nominee_address ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="card mb-3 shadow-sm">
    <div class="card-header bg-secondary text-white">
        <strong>Uploaded Documents</strong>
    </div>
    <div class="card-body">
        @for($i=1; $i<=5; $i++)
            @php $doc = 'document'.$i; @endphp
            @if(!empty($application->$doc))
                <p>
                    <strong>Document {{ $i }}:</strong>
                    <a href="{{ asset('uploads/loan-documents/' . $application->$doc) }}" target="_blank">
                        View Document
                    </a>
                </p>
            @else
                <p><strong>Document {{ $i }}:</strong> Not uploaded</p>
            @endif
        @endfor
    </div>
</div>


        </div>

    </div>

    {{-- Back Button --}}
    <div class="d-flex justify-content-center mt-4">
        <a href="{{ route('admin.loan.applications') }}" class="btn btn-secondary">Back to Applications</a>
    </div>

</div>
@endsection
