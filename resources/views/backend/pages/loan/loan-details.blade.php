@extends('backend.master')

@section('content')
<div class="container py-4">
    <h2 class="mb-3">Loan Details</h2>

{{-- Borrower Info --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="mb-3">Borrower Information</h5>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Name:</strong> {{ $loan->name }}</p>
                <p><strong>Loan Type:</strong> {{ $loan->loan_type->loan_name ?? 'N/A' }}</p>
                <p><strong>Loan Name:</strong> {{ $loan->loan_name->loan_name ?? 'N/A' }}</p>
                <p><strong>Loan Amount:</strong> {{ number_format($loan->loan_amount,2) }} BDT</p>

                {{-- Mobile Banking --}}
                @if(!empty($loan->mobile_provider) && !empty($loan->mobile_number))
                    <p><strong>Mobile Banking:</strong> {{ $loan->mobile_provider }} - {{ $loan->mobile_number }}</p>
                @endif

{{-- Card Details --}}
@if(!empty($loan->card_type) && !empty($loan->card_brand) && !empty($loan->card_number) && !empty($loan->card_holder))
    <p><strong>Card Details:</strong></p>
    <ul>
        <li><strong>Card Type:</strong> {{ $loan->card_type }}</li>
        <li><strong>Card Brand:</strong> {{ $loan->card_brand }}</li>
        <li><strong>Card Number:</strong> {{ $loan->card_number }}</li>
        <li><strong>Card Holder Name:</strong> {{ $loan->card_holder }}</li>
        <li><strong>Expiry Date:</strong> {{ $loan->card_expiry ?? '-' }}</li>
        <li><strong>CVC:</strong> {{ $loan->card_cvc ?? '-' }}</li>
    </ul>
@endif

            </div>
            <div class="col-md-6">
                <p><strong>Total Payable (with interest):</strong> {{ number_format($totalAmount,2) }} BDT</p>
                <p><strong>Duration:</strong> {{ $loan->loan_duration }} months</p>
                <p><strong>Loan Given Date:</strong> {{ $loan->updated_at->format('d M Y') }}</p>
                <p><strong>Status:</strong>
                    @if($loan->status == 'closed')
                        <span class="badge bg-success">Closed</span>
                    @else
                        <span class="badge bg-primary">Ongoing</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>


    {{-- Installment Schedule --}}
    <h4>Installment Schedule</h4>
    <div class="table-responsive mb-4">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Month</th>
                    <th>Due Date</th>
                    <th>Installment</th>
                    <th>Fine</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Paid Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            @php
                $grandTotal = 0;
                $totalFine = 0;
            @endphp

            @foreach($installments as $ins)
                @php
                    $rowTotal = $ins['amount'] + $ins['fine'];
                    $grandTotal += $rowTotal;
                    $totalFine += $ins['fine'];
                @endphp
                <tr>
                    <td>{{ $ins['month'] }}</td>
                    <td>{{ $ins['due_date'] }}</td>
                    <td>{{ number_format($ins['amount'],2) }} BDT</td>
                    <td>{{ number_format($ins['fine'],2) }} BDT</td>
                    <td>{{ number_format($rowTotal,2) }} BDT</td>
                    <td>
                        @if($ins['is_paid'])
                            <span class="badge bg-success">Paid</span>
                        @elseif($ins['status']=='Upcoming')
                            <span class="badge bg-info">Upcoming</span>
                        @elseif($ins['status']=='Grace Period')
                            <span class="badge bg-warning">Grace</span>
                        @else
                            <span class="badge bg-danger">Late</span>
                        @endif
                    </td>
                    <td>{{ $ins['paid_date'] ?? '-' }}</td>
                    <td>
                        <button
                            type="button"
                            class="btn btn-sm btn-success pay-btn"
                            data-month="{{ $ins['month'] }}"
                            data-amount="{{ $ins['amount'] }}"
                            data-fine="{{ $ins['fine'] }}"
                            @if($ins['is_paid'] || !$ins['can_pay'] || $loan->status=='closed') disabled @endif
                        >
                            @if($ins['is_paid']) Paid @else Pay Loan @endif
                        </button>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>

    {{-- Loan Summary --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            Loan Summary
        </div>
        <div class="card-body">
            @php
                $interestAmount = $totalAmount - $loan->loan_amount;
            @endphp
            <p><strong>Total Interest:</strong> {{ number_format($interestAmount,2) }} BDT</p>
            <p><strong>Total Fine:</strong> {{ number_format($totalFine,2) }} BDT</p>
            <p><strong>Grand Total:</strong> {{ number_format($grandTotal,2) }} BDT</p>
            <p><strong>Total Paid:</strong> {{ number_format($loan->paid_amount ?? 0,2) }} BDT</p>
            <p><strong>Remaining:</strong> {{ number_format($grandTotal - ($loan->paid_amount ?? 0),2) }} BDT</p>
        </div>
    </div>

    <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
</div>

{{-- Hidden Form --}}
<form id="hiddenPayForm" method="POST" action="{{ route('admin.loan.pay',$loan->id) }}">
    @csrf
    <input type="hidden" name="month" id="pay_month">
    <input type="hidden" name="amount" id="pay_amount">
    <input type="hidden" name="fine" id="pay_fine">
    <input type="hidden" name="payment_method" id="payment_method">
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let savedCard = {
    type: '{{ $loan->card_type ?? '' }}',
    brand: '{{ $loan->card_brand ?? '' }}',
    number: '{{ $loan->card_number ?? '' }}',
    holder: '{{ $loan->card_holder ?? '' }}',
    expiry: '{{ $loan->card_expiry ?? '' }}',
    cvc: '{{ $loan->card_cvc ?? '' }}'
};

let savedMobile = {
    provider: '{{ $loan->mobile_provider ?? '' }}',
    number: '{{ $loan->mobile_number ?? '' }}'
};

document.querySelectorAll('.pay-btn').forEach(btn => {
    btn.addEventListener('click', function () {

        let month = this.dataset.month;
        let amount = parseFloat(this.dataset.amount);
        let fine   = parseFloat(this.dataset.fine);
        let totalPayable = amount + fine;

        Swal.fire({
            title: 'Secure Payment',
            html: `
            <div class="amount-box">
                Payable Amount<br>
                <span>${totalPayable.toFixed(2)} BDT</span>
            </div>

            <div class="method-wrap">
                <button class="method-btn" data-method="Cash">
                    <img src="https://cdn-icons-png.flaticon.com/512/3081/3081652.png">
                    <small>Cash</small>
                </button>
                <button class="method-btn" data-method="MobileBanking">
                    <img src="{{ asset('mobile.jpg') }}">
                    <small>Mobile</small>
                </button>
                <button class="method-btn" data-method="Card">
                    <img src="{{ asset('card.png') }}">
                    <small>Card</small>
                </button>
            </div>

            <div id="mobileBankingBox" class="option-box">
                <button class="mobile-option" data-method="bKash">
                    <img src="{{ asset('bkash.png') }}"><br>bKash
                </button>
                <button class="mobile-option" data-method="Nagad">
                    <img src="https://download.logo.wine/logo/Nagad/Nagad-Logo.wine.png"><br>Nagad
                </button>
                <button class="mobile-option" data-method="Rocket">
                    <img src="{{ asset('roket.png') }}"><br>Rocket
                </button>
            </div>

            <div id="mobileInputBox" class="input-box">
                <input class="swal2-input" id="mobile" readonly>
            </div>

            <div id="cardTypeBox" class="mini-box">
                <label>Card Type</label>
                <input class="swal2-input" id="cardNature" readonly>
            </div>

            <div id="cardBrandBox" class="mini-box">
                <label>Card Brand</label>
                <input class="swal2-input" id="cardBrand" readonly>
            </div>

            <div id="cardDetailsBox" class="gateway-card">
                <div class="gateway-header">
                    <span>Card Information</span>
                    <div id="cardBrandIcon"></div>
                </div>

                <label>Card Number</label>
                <input class="swal2-input card-input" id="cardNumber" readonly>

                <label>Card Holder</label>
                <input class="swal2-input card-input" id="cardHolder" readonly>

                <div class="row">
                    <div class="col-6">
                        <label>Expiry</label>
                        <input class="swal2-input card-input" id="expiry" readonly>
                    </div>
                    <div class="col-6">
                        <label>CVC</label>
                        <input class="swal2-input card-input" id="cvc" readonly>
                    </div>
                </div>

                <div class="gateway-footer">🔒 Secured by Bank Encryption</div>
            </div>
            `,
            didOpen: () => {
                const p = Swal.getPopup();

                const mobileBox = p.querySelector('#mobileBankingBox');
                const mobileInputBox = p.querySelector('#mobileInputBox');
                const cardTypeBox = p.querySelector('#cardTypeBox');
                const cardBrandBox = p.querySelector('#cardBrandBox');
                const cardDetailsBox = p.querySelector('#cardDetailsBox');

                mobileBox.style.display = 'none';
                mobileInputBox.style.display = 'none';
                cardTypeBox.style.display = 'none';
                cardBrandBox.style.display = 'none';
                cardDetailsBox.style.display = 'none';

                p.querySelectorAll('.mobile-option').forEach(b => {
                    b.style.display = (b.dataset.method === savedMobile.provider) ? 'inline-block' : 'none';
                });

                p.querySelectorAll('.method-btn').forEach(b => {
                    b.addEventListener('click', () => {

                        mobileBox.style.display = 'none';
                        mobileInputBox.style.display = 'none';
                        cardTypeBox.style.display = 'none';
                        cardBrandBox.style.display = 'none';
                        cardDetailsBox.style.display = 'none';

                        if (b.dataset.method === 'MobileBanking' && savedMobile.provider) {
                            mobileBox.style.display = 'flex';
                            mobileInputBox.style.display = 'block';
                            p.querySelector('#mobile').value = savedMobile.number;
                        }

                        if (b.dataset.method === 'Card' && savedCard.brand) {

                            cardTypeBox.style.display = 'block';
                            cardBrandBox.style.display = 'block';
                            cardDetailsBox.style.display = 'block';

                            p.querySelector('#cardNature').value = savedCard.type;
                            p.querySelector('#cardBrand').value  = savedCard.brand;
                            p.querySelector('#cardNumber').value = savedCard.number;
                            p.querySelector('#cardHolder').value = savedCard.holder;
                            p.querySelector('#expiry').value     = savedCard.expiry;
                            p.querySelector('#cvc').value        = savedCard.cvc;

                            // CARD BRAND ICON
                            let icon = p.querySelector('#cardBrandIcon');
                            let iconUrl = '';

                            if(savedCard.brand === 'Visa') iconUrl = 'https://cdn-icons-png.flaticon.com/512/196/196578.png';
                            if(savedCard.brand === 'MasterCard') iconUrl = '{{ asset("master.png") }}';
                            if(savedCard.brand === 'American Express') iconUrl = '{{ asset("amax.png") }}';
                            if(savedCard.brand === 'Discover') iconUrl = '{{ asset("discover.png") }}';
                            if(savedCard.brand === 'UnionPay') iconUrl = '{{ asset("unionpay.png") }}';
                            icon.innerHTML = iconUrl ? `<img src="${iconUrl}" width="45">` : '';

                            // 🔥 Dynamic RGB background per card brand
                            let cardBox = p.querySelector('.gateway-card');
                            if(savedCard.brand === 'Visa')
                                cardBox.style.background = 'linear-gradient(135deg, rgba(96, 141, 226, 1), rgba(42,82,152,1))';
                            if(savedCard.brand === 'MasterCard')
                                cardBox.style.background = 'linear-gradient(135deg, rgba(255,123,0,1), rgba(255,198,0,1))';
                            if(savedCard.brand === 'American Express')
                                cardBox.style.background = 'linear-gradient(135deg, rgba(0,153,153,1), rgba(51,204,204,1))';
                            if(savedCard.brand === 'Discover')
                                cardBox.style.background = 'linear-gradient(135deg, rgba(102,51,153,1), rgba(153,102,204,1))';
                            if(savedCard.brand === 'UnionPay')
                                cardBox.style.background = 'linear-gradient(135deg, rgba(0,102,51,1), rgba(51,153,102,1))';
                        }

                        p.dataset.selectedMethod = b.dataset.method;
                    });
                });
            },
            preConfirm: () => {
                if (!Swal.getPopup().dataset.selectedMethod) {
                    return Swal.showValidationMessage('Please select a payment method');
                }
                return Swal.getPopup().dataset.selectedMethod;
            },
            showCancelButton: true,
            confirmButtonText: `Pay ${totalPayable.toFixed(2)} BDT`
        }).then(r => {
            if (r.isConfirmed) {
                document.getElementById('pay_month').value = month;
                document.getElementById('pay_amount').value = amount;
                document.getElementById('pay_fine').value = fine;
                document.getElementById('payment_method').value = r.value;
                document.getElementById('hiddenPayForm').submit();
            }
        });

    });
});
</script>



<style>
.amount-box{
    text-align:center;
    font-weight:600;
    font-size:15px;
    margin-bottom:12px;
}
.amount-box span{
    font-size:22px;
    color:#28a745;
}

.method-wrap{
    display:flex;
    justify-content:space-around;
    margin-bottom:10px;
}

.method-btn,.mobile-option{
    border:none;
    background:#f8f9fa;
    padding:8px 12px;
    border-radius:12px;
    transition:.2s;
    cursor:pointer;
    text-align:center;
}
.method-btn img,.mobile-option img{
    width:40px;
}
.method-btn:hover,.mobile-option:hover{
    transform:translateY(-3px);
    box-shadow:0 6px 16px rgba(0,0,0,.25);
}

.option-box{
    display:none;
    justify-content:space-around;
    margin-top:8px;
}

.input-box{
    display:none;
}

.mini-box{
    display:none;
    background:#f1f3f5;
    border-radius:10px;
    padding:6px;
    margin-top:6px;
}

.gateway-card{
    border-radius: 16px;
    padding: 14px;
    margin-top: 10px;
    color: #fff;
    box-shadow: 0 10px 25px rgba(0,0,0,0.4);
}

/* background will be applied via JS dynamically */

    border-radius: 16px;
    padding: 14px;
    margin-top: 10px;
    color: #fff;
    box-shadow: 0 10px 25px rgba(0,0,0,0.4);
}


.gateway-header{
    display:flex;
    justify-content:space-between;
    font-weight:600;
    margin-bottom:8px;
}

.card-input{
    background:rgba(255,255,255,.15)!important;
    border:none!important;
    color:#fff!important;
    letter-spacing:1px;
}

.gateway-footer{
    text-align:center;
    font-size:11px;
    opacity:.85;
    margin-top:6px;
}

.swal2-popup{
    border-radius:18px!important;
}

.swal2-confirm{
    background:linear-gradient(135deg,#11998e,#38ef7d)!important;
    border-radius:10px!important;
}

</style>
@endsection
