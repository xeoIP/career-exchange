@extends('layouts.master')

@section('wizard')
    @include('post.inc.wizard')
@endsection

@section('content')
    <div class="main-container">
        <div class="container">
            <div class="row">
    
                @include('post.inc.notification')

                <div class="col-md-12 page-content">
                    <div class="inner-box category-content">
                        <h2 class="title-2"><strong> <i class="icon-tag"></i> {{ t('Pricing') }}</strong></h2>
                        <div class="row">
                            <div class="col-sm-12">
                                <form class="form-horizontal" id="postForm" method="POST" action="{{ url()->current() }}">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                                    <fieldset>

                                        @if (isset($packages) and isset($paymentMethods) and $packages->count() > 0 and $paymentMethods->count() > 0)
                                            <div class="well" style="padding-bottom: 0;">
                                                <h3><i class="icon-certificate icon-color-1"></i> {{ t('Premium Ad') }} </h3>
                                                <p>
                                                    {{ t('The premium package help companies to promote their job ads by giving more visibility to their ads to attract more candidates and hire them faster.') }}
                                                </p>
                                                <div class="form-group <?php echo (isset($errors) and $errors->has('package')) ? 'has-error' : ''; ?>"
                                                     style="margin-bottom: 0;">
                                                    <table id="packagesTable" class="table table-hover checkboxtable" style="margin-bottom: 0;">
                                                        @foreach ($packages as $package)
                                                            <?php
                                                            $currentPackageId = 0;
                                                            $currentPackagePrice = 0;
                                                            $packageStatus = '';
                                                            $badge = '';
                                                            if (isset($currentPaymentPackage) and !empty($currentPaymentPackage)) {
                                                                $currentPackageId = $currentPaymentPackage->tid;
                                                                $currentPackagePrice = $currentPaymentPackage->price;
                                                            }
                                                            // Prevent Package's Downgrading
                                                            if ($currentPackagePrice > $package->price) {
                                                                $packageStatus = ' disabled';
                                                                $badge = ' <span class="label label-danger">'. t('Not available') . '</span>';
                                                            } elseif ($currentPackagePrice == $package->price) {
                                                                $badge = '';
                                                            } else {
                                                                $badge = ' <span class="label label-success">'. t('Upgrade') . '</span>';
                                                            }
                                                            if ($currentPackageId == $package->tid) {
                                                                $badge = ' <span class="label label-default">'. t('Current') . '</span>';
                                                            }
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <div class="radio">
                                                                        <label>
                                                                            <input class="package-selection" type="radio" name="package"
                                                                                   id="package-{{ $package->tid }}"
                                                                                   value="{{ $package->tid }}"
                                                                                    {{ (old('package', $currentPackageId)==$package->tid) ? ' checked' : (($package->price==0) ? ' checked' : '') }} {{ $packageStatus }}>
                                                                            <strong class="tooltipHere" title="" data-placement="right" data-toggle="tooltip" data-original-title="{!! $package->description !!}">{!! $package->name . $badge !!} </strong>
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <p id="price-{{ $package->tid }}">
                                                                        @if ($package->currency->in_left == 1)
                                                                            <span class="priceCurr">{{ $package->currency->symbol }}</span>
                                                                        @endif
                                                                        <span class="priceInt">{{ $package->price }}</span>
                                                                        @if ($package->currency->in_left == 0)
                                                                            <span class="priceCurr">{{ $package->currency->symbol }}</span>
                                                                        @endif
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                        <tr>
                                                            <td>
                                                                <div class="form-group <?php echo (isset($errors) and $errors->has('payment_method')) ? 'has-error' : ''; ?>"
                                                                     style="margin-bottom: 0;">
                                                                    <div class="col-md-8">
                                                                        <select class="form-control selecter" name="payment_method" id="payment_method">
                                                                            @foreach ($paymentMethods as $paymentMethod)
                                                                                @if (view()->exists('payment::' . $paymentMethod->name))
                                                                                    <option value="{{ $paymentMethod->id }}" data-name="{{ $paymentMethod->name }}" {{ (old('payment_method')==$paymentMethod->id) ? 'selected="selected"' : '' }}>
                                                                                        {{ $paymentMethod->display_name }}
                                                                                    </option>
                                                                                @endif
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p style="margin-top: 7px;">
                                                                    <strong>{{ t('Payable Amount') }} :
                                                                        @if ($packages->get(0)->currency->in_left == 1)
																			<span class="priceCurr">{{ $packages->get(0)->currency->symbol }}</span>
																		@endif
                                                                        <span id="payableAmount">0</span>
                                                                        @if ($packages->get(0)->currency->in_left == 0)
																			<span class="priceCurr">{{ $packages->get(0)->currency->symbol }}</span>
																		@endif
                                                                    </strong>
                                                                </p>
                                                            </td>
                                                        </tr>

                                                    </table>
                                                </div>
                                            </div>

                                            @if (isset($paymentMethods) and $paymentMethods->count() > 0)
                                                <!-- Payment Plugins -->
                                                <?php $hasCcBox = 0; ?>
                                                @foreach($paymentMethods as $paymentMethod)
                                                    @if (view()->exists('payment::' . $paymentMethod->name))
                                                        @include('payment::' . $paymentMethod->name)
                                                    @endif
                                                    <?php if ($paymentMethod->has_ccbox == 1 && $hasCcBox == 0) $hasCcBox = 1; ?>
                                                @endforeach
                                            @endif
                                        @endif

                                        <!-- Button  -->
                                        <div class="form-group">
                                            <div class="col-md-12 mt20" style="text-align: center;">
                                                @if (getSegment(2) == 'create')
                                                    <a href="{{ lurl('posts/create/' . $post->tmp_token . '/finish') }}" class="btn btn-default btn-lg">{{ t('Skip') }}</a>
                                                @else
                                                    <a href="{{ lurl(slugify($post->title) . '/' . $post->id . '.html') }}" class="btn btn-default btn-lg">{{ t('Skip') }}</a>
                                                @endif
                                                <button id="submitPostForm" class="btn btn-success btn-lg submitPostForm"> {{ t('Pay') }} </button>
                                            </div>
                                        </div>

                                        <div style="margin-bottom: 30px;"></div>

                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.page-content -->
            </div>
        </div>
    </div>
@endsection

@section('after_styles')
@endsection

@section('after_scripts')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.payment/1.2.3/jquery.payment.min.js"></script>
    @if (file_exists(public_path() . '/assets/plugins/forms/validation/localization/messages_'.config('app.locale').'.min.js'))
        <script src="{{ url('/assets/plugins/forms/validation/localization/messages_'.config('app.locale').'.min.js') }}" type="text/javascript"></script>
    @endif

    <script>
        @if (isset($packages) and isset($paymentMethods) and $packages->count() > 0 and $paymentMethods->count() > 0)
        var currentPackagePrice = {{ $currentPackagePrice }};
        $(document).ready(function ()
        {
            /* Show price & Payment Methods */
            var selectedPackage = $('input[name=package]:checked').val();
            var packagePrice = getPackagePrice(selectedPackage);
            showAmount(packagePrice);

            /* Prevent Package's Downgrading */
            if (currentPackagePrice >= packagePrice) {
                $('#submitPostForm').hide();
            } else {
                $('#submitPostForm').show();
            }

            /* Select a Package */
            $('.package-selection').click(function () {
                selectedPackage = $(this).val();
                packagePrice = getPackagePrice(selectedPackage);
                showAmount(packagePrice);

                /* Prevent Package's Downgrading */
                if (currentPackagePrice >= packagePrice) {
                    $('#submitPostForm').hide();
                } else {
                    $('#submitPostForm').show();
                }
            });

            /* Form Default Submission */
            $('#submitPostForm').on('click', function (e) {
                e.preventDefault();

                if (packagePrice <= 0) {
                    $('#postForm').submit();
                }

                return false;
            });
        });
        @endif
    </script>
@endsection
