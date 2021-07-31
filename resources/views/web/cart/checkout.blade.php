<!doctype html>
<html lang="en">
<head>
    @include('site.element.head')

    <link href="{{ asset("site/css/bootstrap.5.0.min.css") }}" rel="stylesheet">
    <link href="{{ asset("site/css/dashboard.css") }}" rel="stylesheet">
    <link href="{{ asset("common/plugin/select2/css/select2.min.css") }}" rel="stylesheet"/>
    <link href="{{ asset("common/plugin/select2/css/select2-bootstrap4.min.css") }}" rel="stylesheet"/>
    <link href="{{ asset("site/css/member_v2.css") }}" rel="stylesheet"/>
    <script type="text/javascript">
        let configs = {
            'base_url': '<?php echo e(base_url()); ?>',
            'admin_url': '<?php echo e(base_url('admin')); ?>',
            'MAX_FILE_UPLOAD': '<?php echo e(@config('constant.MAX_FILE_UPLOAD')); ?>',
        };
    </script>

    <script src="{{ asset("site/js/jquery-3.2.1.min.js") }}"></script>
</head>
<body class="bg-light">

<div class="container">
    <main>
        <div class="py-5 text-center">
            <a href="{{ base_url() }}" class="header-logo hidden-sm hidden-xs">
                @if(!empty($config['logo']))
                    <img class="d-block mx-auto mb-4" height="57" src="{{ $config['logo'] }}"
                         alt="{{ $config['company_name'] ?? '' }}">
                @else
                    <span class="text">{{ $config['company_name'] ?? '' }}</span>
                @endif
            </a>
        </div>

        <div class="row g-5">
            <div class="col-md-5 col-lg-4 order-md-last">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-primary">Your cart</span>
                    <span class="badge bg-primary rounded-pill">{{ \Cart::getTotalQuantity()  }}</span>
                </h4>
                <ul class="list-group mb-3">
                    @if($items->count() > 0)
                        @foreach($items as $item)
                            <li class="list-group-item d-flex justify-content-between lh-sm">
                                <div>
                                    <h6 class="my-0">{{ $item->name }} </h6>
                                    <small class="text-muted"> x {{ $item->quantity }}</small>
                                </div>
                                <span class="text-muted">{{ number_format($item->price) }}</span>
                            </li>
                        @endforeach
                    @endif

                    <li class="list-group-item text-info d-flex justify-content-between">
                        <span class="">{{ trans('common.cart.sub_total') }} ({{ config('app.currency') }})</span>
                        <strong>{{ number_format(\Cart::getSubTotal()) }}</strong>
                    </li>
                    <li class="list-group-item text-primary d-flex justify-content-between">
                        <span class="">{{ trans('common.cart.total') }} ({{ config('app.currency') }})</span>
                        <strong>{{ number_format(\Cart::getTotal()) }}</strong>
                    </li>
                </ul>
            </div>
            <div class="col-md-7 col-lg-8">
                <h4 class="mb-3">Billing address</h4>
                <form method="post" action="{{ base_url('cart/checkout/'.$token_checkout) }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="billing_fullname" class="form-label">{{ trans('common.fullname') }}</label>
                            <input class="form-control @error('billing_fullname') is-invalid @enderror"
                                   name="billing_fullname"
                                   required
                                   value="{{ old('billing_fullname', (auth()->user()->fullname ?? '')) }}"
                                   placeholder="{{ trans('common.fullname') }}"
                                   autocomplete="off">

                            @error('billing_fullname')
                            <div class="text text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="billing_email" class="form-label">Email <span
                                        class="text-muted">(Optional)</span></label>
                            <input class="form-control @error('billing_email') is-invalid @enderror"
                                   value="{{ old('billing_email', (auth()->user()->email ?? '')) }}"
                                   required
                                   name="billing_email"
                                   type="email" autocomplete="off"
                                   placeholder="you@example.com">

                            @error('billing_email')
                            <div class="text text-danger">{{ $message }}</div>
                            @enderror

                        </div>

                        <div class="col-12">
                            <label for="billing_phone" class="form-label">Phone</label>
                            <input class="form-control @error('billing_phone') is-invalid @enderror"
                                   name="billing_phone"
                                   required
                                   value="{{ old('billing_phone', (auth()->user()->phone ?? '')) }}"
                                   placeholder="{{ trans('common.phone') }}"
                                   autocomplete="off">
                            @error('billing_phone')
                            <div class="text text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="billing_address" class="form-label">Address</label>
                            <input class="form-control"
                                   name="billing_address"
                                   value="{{ old('billing_address') }}"
                                   placeholder="{{ trans('common.address') }}"
                                   autocomplete="off">
                        </div>

                        <!--
                        <div class="col-12">
                            <label for="address2" class="form-label">Address 2 <span
                                        class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control" id="address2" placeholder="Apartment or suite">
                        </div>

                        <div class="col-md-5">
                            <label for="country" class="form-label">Country</label>
                            <select class="form-select" id="country" required>
                                <option value="">Choose...</option>
                                <option>United States</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select a valid country.
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="state" class="form-label">State</label>
                            <select class="form-select" id="state" required>
                                <option value="">Choose...</option>
                                <option>California</option>
                            </select>
                            <div class="invalid-feedback">
                                Please provide a valid state.
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="zip" class="form-label">Zip</label>
                            <input type="text" class="form-control" id="zip" placeholder="" required>
                            <div class="invalid-feedback">
                                Zip code required.
                            </div>
                        </div>
                        -->
                    </div>

                    <!--
                    <hr class="my-4">

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="same-address">
                        <label class="form-check-label" for="same-address">Shipping address is the same as my billing
                            address</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="save-info">
                        <label class="form-check-label" for="save-info">Save this information for next time</label>
                    </div>

                    <hr class="my-4">

                    <h4 class="mb-3">Payment</h4>

                    <div class="my-3">
                        <div class="form-check">
                            <input id="credit" name="paymentMethod" type="radio" class="form-check-input" checked
                                   required>
                            <label class="form-check-label" for="credit">Credit card</label>
                        </div>
                        <div class="form-check">
                            <input id="debit" name="paymentMethod" type="radio" class="form-check-input" required>
                            <label class="form-check-label" for="debit">Debit card</label>
                        </div>
                        <div class="form-check">
                            <input id="paypal" name="paymentMethod" type="radio" class="form-check-input" required>
                            <label class="form-check-label" for="paypal">PayPal</label>
                        </div>
                    </div>

                    <div class="row gy-3">
                        <div class="col-md-6">
                            <label for="cc-name" class="form-label">Name on card</label>
                            <input type="text" class="form-control" id="cc-name" placeholder="" required>
                            <small class="text-muted">Full name as displayed on card</small>
                            <div class="invalid-feedback">
                                Name on card is required
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="cc-number" class="form-label">Credit card number</label>
                            <input type="text" class="form-control" id="cc-number" placeholder="" required>
                            <div class="invalid-feedback">
                                Credit card number is required
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="cc-expiration" class="form-label">Expiration</label>
                            <input type="text" class="form-control" id="cc-expiration" placeholder="" required>
                            <div class="invalid-feedback">
                                Expiration date required
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="cc-cvv" class="form-label">CVV</label>
                            <input type="text" class="form-control" id="cc-cvv" placeholder="" required>
                            <div class="invalid-feedback">
                                Security code required
                            </div>
                        </div>
                    </div>
                    -->
                    <hr class="my-4">

                    <button class="w-100 btn btn-primary btn-lg" type="submit">Continue to checkout</button>
                </form>
            </div>
        </div>
    </main>

    <footer class="my-5 pt-5 text-muted text-center text-small">
        <p class="mb-1">&copy; {{ $config['company_name'] }}</p>
    </footer>
</div>

<script src="{{ asset("site/js/bootstrap.bundle.5.0.min.js") }}"></script>
<script src="{{ asset("site/js/feather.min.js")}}"></script>
<script src="{{ asset("common/plugin/select2/js/select2.full.min.js") }}"></script>
<script src="{{ asset("site/js/script.js") }}"></script>


<div class="show-message-footer">
    @isset($error)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {!! is_array($error) ? '- '.implode("<br>- ",$error) : $error !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endisset

    @isset($success)
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! is_array($success) ? '- '.implode("<br>- ",$success) : $success !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endisset
</div>
</body>
</html>
