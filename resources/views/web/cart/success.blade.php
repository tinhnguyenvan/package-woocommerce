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
        <article id="content" class="">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="alert alert-success">{{ trans('common.cart.checkout.success_description') }}</div>
                </div>
            </div>
        </article>
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

