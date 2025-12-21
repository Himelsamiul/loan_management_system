@extends('frontend.master')

@section('content')

<!-- AOS CSS for animations -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">

<style>
    body {
        background-color: #f0f4f8;
    }

    .card {
        background: #ffffff;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        padding: 30px;
        transition: transform 0.3s;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    h2, h4 {
        color: #0d6efd;
        font-weight: 600;
    }

    label {
        font-weight: 500;
    }

    .alert-warning {
        background-color: #fff3cd;
        color: #856404;
        border-radius: 10px;
    }

    .form-control, .form-select {
        border-radius: 10px;
        box-shadow: none;
        border: 1px solid #ced4da;
        transition: all 0.3s;
    }

    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 5px rgba(13, 110, 253, 0.3);
    }

    button.btn-primary {
        border-radius: 50px;
        padding: 10px 30px;
        font-weight: 600;
        transition: all 0.3s;
    }

    button.btn-primary:hover {
        background-color: #0b5ed7;
        transform: scale(1.05);
    }
</style>

<div class="container py-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-down">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card" data-aos="fade-up">
        <h2 class="mb-3 text-center">{{ $loan->loan_name }} Application</h2>
        <p class="text-center mb-4">
            <strong>Loan Type:</strong> {{ $loan->loanType->loan_name }} |
            <strong>Interest:</strong> {{ $loan->interest }}%
        </p>

        <!-- Loan Document Notice -->
        <div class="alert alert-warning mb-4" data-aos="fade-up" data-aos-delay="100">
            <strong>দ্রষ্টব্য:</strong> ঋণের জন্য নথি সঠিকভাবে আপলোড করা আবশ্যক। বিভিন্ন লোনের জন্য নথি নিচে দেওয়া হলো:
            <hr>
            <strong>১. Education Loan:</strong>
            <ol>
                <li>Admission Letter / University Registration</li>
                <li>Guardian’s NID Copy</li>
                <li>Income Proof of Guardian</li>
                <li>Academic Certificates</li>
                <li>Bank Account Details</li>
            </ol>
            <strong>২. Health Loan:</strong>
            <ol>
                <li>Medical Prescription / Doctor’s Letter</li>
                <li>Hospital Estimate / Invoice</li>
                <li>Patient’s NID Copy</li>
                <li>Income Proof</li>
                <li>Bank Account Details</li>
            </ol>
            <strong>৩. Personal Loan:</strong>
            <ol>
                <li>Applicant’s NID Copy</li>
                <li>Income Proof / Salary Slip</li>
                <li>Bank Statement (last 6 months)</li>
                <li>Passport Size Photo</li>
                <li>Bank Account Details</li>
            </ol>
            <strong>৪. Business Loan:</strong>
            <ol>
                <li>Business Trade License / Registration</li>
                <li>Tax Identification Number (TIN)</li>
                <li>Financial Statement / Profit-Loss Report</li>
                <li>Bank Statement (last 6 months)</li>
                <li>Bank Account Details</li>
            </ol>
        </div>

        <form action="{{ route('frontend.loan.apply.review') }}" method="POST" enctype="multipart/form-data" data-aos="fade-up" data-aos-delay="200">
            @csrf
            <input type="hidden" name="loan_name_id" value="{{ $loan->id }}">
            <input type="hidden" name="loan_type_id" value="{{ $loan->loan_type_id }}">
            @php $user = Auth::user(); @endphp

            <!-- Personal Details -->
            <h4 class="mt-4">Personal Details</h4>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Full Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }} {{ $user->sure_name }}" readonly>
                </div>
                <div class="col-md-6">
                    <label>Father’s Name *</label>
                    <input type="text" name="father_name" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Mother’s Name *</label>
                    <input type="text" name="mother_name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>NID Number *</label>
                    <input type="text" name="nid_number" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Date of Birth *</label>
                    <input type="date" name="date_of_birth" class="form-control" value="{{ $user->date_of_birth }}">
                </div>
                <div class="col-md-6">
                    <label>Mobile *</label>
                    <input type="text" name="mobile" class="form-control" value="{{ $user->mobile }}" readonly>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Email *</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" readonly>
                </div>
                <div class="col-md-3">
                    <label>Gender *</label>
                    <select name="gender" class="form-select" required>
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Marital Status *</label>
                    <select name="marital_status" class="form-select" required>
                        <option value="">Select Status</option>
                        <option value="single">Single</option>
                        <option value="married">Married</option>
                        <option value="divorced">Divorced</option>
                        <option value="widowed">Widowed</option>
                    </select>
                </div>
            </div>

            <!-- Loan Details -->
            <h4 class="mt-4">Loan Information</h4>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Loan Amount (BDT) *</label>
                    <input type="number" name="loan_amount" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Loan Duration (months) *</label>
                    <input type="number" name="loan_duration" class="form-control" min="1" required>
                </div>
            </div>

            <!-- Address Details -->
            <h4 class="mt-4">Address Details</h4>
            <div class="mb-3">
                <label>Present Address *</label>
                <textarea name="present_address" class="form-control" rows="2" required>{{ $user->address }}</textarea>
            </div>
            <div class="mb-3">
                <label>Permanent Address *</label>
                <textarea name="permanent_address" class="form-control" rows="2" required>{{ $user->address }}</textarea>
            </div>

            <!-- Documents -->
            <h4 class="mt-4">Upload Documents (Optional)</h4>
            <div class="row mb-4">
                @for($i=1; $i<=5; $i++)
                    <div class="col-md-6 mb-3">
                        <label>Document {{ $i }}</label>
                        <input type="file" name="document{{ $i }}" accept="application/pdf" class="form-control">
                    </div>
                @endfor
            </div>

            <!-- Payment Section -->
            <h4 class="mt-4">Payment Details</h4>

            <!-- Mobile Banking (Optional) -->
            <div id="mobileBankingFields" class="mb-3">
                <h5>Mobile Banking (Optional)</h5>
                <div class="row mb-2">
                    <div class="col-md-4">
                        <label>Provider</label>
                        <select name="mobile_provider" id="mobile_provider" class="form-select">
                            <option value="">Select Provider</option>
                            <option value="bKash">bKash</option>
                            <option value="Nagad">Nagad</option>
                            <option value="Rocket">Rocket</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Mobile Number</label>
                        <input type="text" name="mobile_number" id="mobile_number" class="form-control" placeholder="Enter mobile number">
                    </div>
                </div>
            </div>

            <!-- Card Payment (Mandatory) -->
            <div id="cardFields" class="mb-3">
                <h5>Card Payment (Mandatory)</h5>
                <div class="row mb-2">
                    <div class="col-md-3">
                        <label>Card Type *</label>
                        <select name="card_type" id="card_type" class="form-select" required>
                            <option value="">Select Type</option>
                            <option value="Debit">Debit</option>
                            <option value="Credit">Credit</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Card Brand *</label>
                        <select name="card_brand" id="card_brand" class="form-select" required>
                            <option value="">Select Brand</option>
                            <option value="Visa">Visa</option>
                            <option value="MasterCard">MasterCard</option>
                            <option value="American Express">American Express</option>
                            <option value="Discover">Discover</option>
                            <option value="UnionPay">UnionPay</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Card Number *</label>
                        <input type="text" name="card_number" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label>Card Holder *</label>
                        <input type="text" name="card_holder" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-3">
                        <label>Expiry *</label>
                        <input type="text" name="card_expiry" placeholder="MM/YY" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label>CVC *</label>
                        <input type="text" name="card_cvc" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg">Submit Loan Application</button>
            </div>
        </form>
    </div>
</div>

<!-- JS for Mobile Banking optional requirement -->
<script>
    const mobileProvider = document.getElementById('mobile_provider');
    const mobileNumber = document.getElementById('mobile_number');

    mobileProvider.addEventListener('change', function(){
        if(this.value){
            mobileNumber.required = true; // provider select করলে number required
        } else {
            mobileNumber.required = false; // otherwise optional
            mobileNumber.value = '';
        }
    });
</script>

<!-- AOS JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true,
    });
</script>

@endsection
