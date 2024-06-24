<div class="p-5 checkout-payment-success my-3">
    <div class="mb-4">
        <h5 class="text-success fw-semibold">{{ $title }}</h5>
    </div>
    <div class="mb-4">
        <p class="mb-1 fs-14">
            {{ $content }}
        </p>
    </div>
    <button type="button" class="btn btn-success" id="continue">
        <div class="spinner-border spinner-border-sm align-middle" style="display: none" aria-hidden="true">
            <span class="visually-hidden">Loading...</span>
        </div>
        <span id="text">{{ __('Continue') }}</span>
    </button>
</div>
