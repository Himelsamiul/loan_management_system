@extends('frontend.master')

@section('content')

<div class="container py-4">

    <h2 class="mb-3">Review Your Loan Application</h2>
    
    <hr>

    <form action="{{ route('frontend.loan.apply.store') }}" method="POST">
        @csrf

        <!-- Hidden fields to pass all data to store -->
        <input type="hidden" name="loan_name_id" value="{{ $data['loan_name_id'] }}">
        <input type="hidden" name="loan_type_id" value="{{ $data['loan_type_id'] }}">
        <input type="hidden" name="name" value="{{ $data['name'] }}">
        <input type="hidden" name="father_name" value="{{ $data['father_name'] }}">
        <input type="hidden" name="mother_name" value="{{ $data['mother_name'] }}">
        <input type="hidden" name="nid_number" value="{{ $data['nid_number'] }}">
        <input type="hidden" name="date_of_birth" value="{{ $data['date_of_birth'] }}">
        <input type="hidden" name="gender" value="{{ $data['gender'] }}">
        <input type="hidden" name="marital_status" value="{{ $data['marital_status'] }}">
        <input type="hidden" name="loan_amount" value="{{ $data['loan_amount'] }}">
        <input type="hidden" name="loan_duration" value="{{ $data['loan_duration'] }}">
        <input type="hidden" name="present_address" value="{{ $data['present_address'] }}">
        <input type="hidden" name="permanent_address" value="{{ $data['permanent_address'] }}">

        @for($i=1; $i<=5; $i++)
            @if(isset($data["document$i"]))
                <input type="hidden" name="document{{ $i }}" value="{{ $data["document$i"] }}">
            @endif
        @endfor

        <!-- Display the data for review -->
        <div class="card p-3 mb-3">
            <h4>Personal Information</h4>
            <p><strong>Full Name:</strong> {{ $data['name'] }}</p>
            <p><strong>Father’s Name:</strong> {{ $data['father_name'] }}</p>
            <p><strong>Mother’s Name:</strong> {{ $data['mother_name'] }}</p>
            <p><strong>NID Number:</strong> {{ $data['nid_number'] }}</p>
            <p><strong>Date of Birth:</strong> {{ \Carbon\Carbon::parse($data['date_of_birth'])->format('d M Y') }}</p>
            <p><strong>Gender:</strong> {{ ucfirst($data['gender']) }}</p>
            <p><strong>Marital Status:</strong> {{ ucfirst($data['marital_status']) }}</p>
        </div>

        <div class="card p-3 mb-3">
            <h4>Loan Information</h4>
            <p><strong>Loan Type:</strong> {{ $loan->loanType->loan_name }}</p>
            <p><strong>Loan Name:</strong> {{ $loan->loan_name }}</p>
            <p><strong>Loan Amount:</strong> {{ number_format($data['loan_amount'],2) }}</p>
            <p><strong>Interest (%):</strong> {{ $loan->interest }}%</p>

            @php
                $interestAmount = $data['loan_amount'] * $loan->interest / 100;
                $totalAmount = $data['loan_amount'] + $interestAmount;
                $monthlyInstallment = $totalAmount / $data['loan_duration'];
            @endphp

            <p><strong>Total Amount (Loan + Interest):</strong> {{ number_format($totalAmount,2) }}</p>
            <p><strong>Loan Duration:</strong> {{ $data['loan_duration'] }} months</p>
            <p><strong>Monthly Installment:</strong> {{ number_format($monthlyInstallment,2) }}</p>
        </div>

        <div class="card p-3 mb-3">
            <h4>Address</h4>
            <p><strong>Present Address:</strong> {{ $data['present_address'] }}</p>
            <p><strong>Permanent Address:</strong> {{ $data['permanent_address'] }}</p>
        </div>

        <div class="card p-3 mb-3">
            <h4>Uploaded Documents</h4>
            @for($i=1; $i<=5; $i++)
                @if(isset($data["document$i"]))
                    <p>Document {{ $i }}: <a href="{{ asset('uploads/loan-documents/'.$data["document$i"]) }}" target="_blank">View</a></p>
                @else
                    <p>Document {{ $i }}: Not Uploaded</p>
                @endif
            @endfor
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Edit</a>
            <button type="submit" class="btn btn-success">Confirm & Submit</button>
        </div>

    </form>

</div>

@endsection
