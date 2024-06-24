<h5>{{ __('Request Withdrawal') }}</h5>
<form method="POST" action="{{ route('withdrawal.store') }}">
    @csrf
    <div class="mb-3">
        <label for="" class="d-flex justify-content-between"><span>{{ __('Amount') }}</span> <span>Balance:
                ${{ number_format(Auth::user()->bonuWallet ? Auth::user()->bonuWallet->amount * systemSettings()->bv_equivalent : 0.0) }}</span></label>
        <input type="number" min="1" step="any" name="amount" class="form-control" id="">
    </div>
    <h6>{{ __('Recepient Details') }}</h6>
    <div class="mb-3">
        <label for="">{{ __('Account Number') }}</label>
        <input type="number" name="account_number" class="form-control" id="">
    </div>
    <div class="mb-3">
        <label for="">{{ __('Account Name') }}</label>
        <input type="text" name="account_name" class="form-control" id="">
    </div>
    <button class="btn btn-primary" type="submit">
        <div class="spinner-border" style="display: none" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <span id="text">Submit</span>
    </button>

</form>
