<div id="stepWizard" class="container">
    <div class="row">
        <div class="col-lg-12">
            <section>
                <div class="wizard">
                    
                    <ul class="nav nav-wizard">
                        @if (getSegment(1) == 'create')
                            <?php $uriPath = getSegment(4); ?>
                            <li class="{{ ($uriPath == '') ? 'active' : (in_array($uriPath, ['photos', 'packages', 'finish']) or (isset($post) and !empty($post)) ? '' : 'disabled') }}">
                                @if (isset($post) and !empty($post))
                                    <a href="{{ lurl('posts/create/' . $post->tmp_token) }}">{{ t('Job Details') }}</a>
                                @else
                                    <a href="{{ lurl('posts/create') }}">{{ t('Job Details') }}</a>
                                @endif
                            </li>
        
                            @if (isset($countPackages) and isset($countPaymentMethods) and $countPackages > 0 and $countPaymentMethods > 0)
                            <li class="{{ ($uriPath == 'packages') ? 'active' : ((in_array($uriPath, ['finish']) or (isset($post) and !empty($post))) ? '' : 'disabled') }}">
                                @if (isset($post) and !empty($post))
                                    <a href="{{ lurl('posts/create/' . $post->tmp_token . '/packages') }}">{{ t('Packages') }}</a>
                                @else
                                    <a>{{ t('Packages') }}</a>
                                @endif
                            </li>
                            @endif
                            
                            @if ($uriPath == 'activation')
                            <li class="{{ ($uriPath == 'activation') ? 'active' : 'disabled' }}">
                                <a>{{ t('Activation') }}</a>
                            </li>
                            @else
                            <li class="{{ ($uriPath == 'finish') ? 'active' : 'disabled' }}">
                                <a>{{ t('Finish') }}</a>
                            </li>
                            @endif
                        @else
                            <?php $uriPath = getSegment(3); ?>
                            <li class="{{ (in_array($uriPath, [null, 'edit'])) ? 'active' : '' }}">
                                @if (isset($post) and !empty($post))
                                    <a href="{{ lurl('posts/' . $post->id . '/edit') }}">{{ t('Job Details') }}</a>
                                @else
                                    <a href="{{ lurl('posts/create') }}">{{ t('Job Details') }}</a>
                                @endif
                            </li>
    
                            @if (isset($countPackages) and isset($countPaymentMethods) and $countPackages > 0 and $countPaymentMethods > 0)
                            <li class="{{ ($uriPath == 'packages') ? 'active' : '' }}">
                                @if (isset($post) and !empty($post))
                                    <a href="{{ lurl('posts/' . $post->id . '/packages') }}">{{ t('Packages') }}</a>
                                @else
                                    <a>{{ t('Packages') }}</a>
                                @endif
                            </li>
                            @endif
        
                            <li class="{{ ($uriPath == 'finish') ? 'active' : 'disabled' }}">
                                <a>{{ t('Finish') }}</a>
                            </li>
                        @endif
                    </ul>
                    
                </div>
            </section>
        </div>
    </div>
</div>

@section('after_styles')
    @parent
    <link href="{{ url('/assets/css/wizard.css') }}" rel="stylesheet">
@endsection
@section('after_scripts')
    @parent
@endsection