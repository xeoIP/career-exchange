<?php
if (isset($title)) {
    $title = strip_tags($title);
}
?>
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Encrypted CSRF token for Laravel, in order for Ajax requests to work --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>
      {{ isset($title) ? $title.' :: '.config('app.name').' Admin' : config('app.name').' Admin' }}
    </title>

    @yield('before_styles')

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/dist/css/skins/_all-skins.min.css">

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/plugins/pace/pace.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/admin/pnotify/pnotify.custom.min.css') }}">

    <!-- Admin Global CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/admin/style.css') . '?v=' . vTime() }}">

    @yield('after_styles')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition {{ config('larapen.admin.skin') }} sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">

      <header class="main-header">
        <!-- Logo -->
        <a href="{{ url('') }}" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini">{!! config('larapen.admin.logo_mini') !!}</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg">
              <img src="/images/logo-2.png">
          </span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">{{ trans('admin::messages.toggle_navigation') }}</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>

          @include('admin::inc.menu')
        </nav>
      </header>

      <!-- =============================================== -->

      @include('admin::inc.sidebar')

      <!-- =============================================== -->

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
         @yield('header')

        <!-- Main content -->
        <section class="content">

          @yield('content')

        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->

      <footer class="main-footer">
        @if (config('larapen.admin.show_powered_by'))
            <div class="pull-right hidden-xs">
                {{ trans('admin::messages.powered_by') }} <a target="_blank" href="#">BPJobs</a>.
            </div>
            Version {{ config('app.version') }}
        @endif
      </footer>
    </div>
    <!-- ./wrapper -->


    @yield('before_scripts')

    <!-- jQuery 2.2.0 -->
    <script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
    <script>window.jQuery || document.write('<script src="{{ asset('vendor/adminlte') }}/plugins/jQuery/jQuery-2.2.0.min.js"><\/script>')</script>
    <!-- Bootstrap 3.3.5 -->
    <script src="{{ asset('vendor/adminlte') }}/bootstrap/js/bootstrap.min.js"></script>
    <script src="{{ asset('vendor/adminlte') }}/plugins/pace/pace.min.js"></script>
    <script src="{{ asset('vendor/adminlte') }}/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="{{ asset('vendor/adminlte') }}/plugins/fastclick/fastclick.js"></script>
    <script src="{{ asset('vendor/adminlte') }}/dist/js/app.min.js"></script>

    <!-- page script -->
    <script type="text/javascript">
        /* To make Pace works on Ajax calls */
        $(document).ajaxStart(function() { Pace.restart(); });
        /* Ajax calls should always have the CSRF token attached to them, otherwise they won't work */
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        /* Set active state on menu element */
        var current_url = "{{ url(Route::current()->uri()) }}";
        $("ul.sidebar-menu li a").each(function() {
            if ($(this).attr('href').startsWith(current_url) || current_url.startsWith($(this).attr('href')))
            {
                $(this).parents('li').addClass('active');
            }
        });
    </script>
    <script>
        var siteUrl = '<?php echo url('/'); ?>';

        $(document).ready(function()
        {
            /* Send an ajax update request */
            $(document).on('click', '.ajax-request', function(e)
            {
                e.preventDefault(); /* prevents the submit or reload */
                var confirmation = confirm("<?php echo __t('Are you sure you want to perform this action?'); ?>");

                if (confirmation) {
                    saveAjaxRequest(siteUrl, this);
                }
            });
        });

        function saveAjaxRequest(siteUrl, el)
        {
            var $self = $(this); /* magic here! */

            /* Get database info */
            var _token = $('input[name=_token]').val();
            var dataTable = $(el).data('table');
            var dataField = $(el).data('field');
            var dataId = $(el).data('id');
            var dataLineId = $(el).data('line-id');
            var dataValue = $(el).data('value');

            /* Remove dot (.) from var (referring to the PHP var) */
            dataLineId = dataLineId.split('.').join("");


            $.ajax({
                method: 'POST',
                url: siteUrl + '/<?php echo config('larapen.admin.route_prefix', 'admin'); ?>/ajax/' + dataTable + '/' + dataField + '',
                context: this,
                data: {
                    'primaryKey': dataId,
                    '_token': _token
                }
            }).done(function(data) {
                if (data.status != 1) {
                    return false;
                }

                /* Decoration */
                if (data.table == 'countries' && dataField == 'active')
                {
                    if (!data.resImport) {
                        alert("<?php echo __t('Error - You can\'t install this country.'); ?>");
                        return false;
                    }

                    if (data.isDefaultCountry == 1) {
                        alert("<?php echo __t('You can not disable the default country'); ?>");
                        return false;
                    }

                    /* Country case */
                    if (data.fieldValue == 1) {
                        $('#' + dataLineId).removeClass('fa fa-square-o').addClass('fa fa-check-square-o');
                        $('#install' + dataId).removeClass('btn-default').addClass('btn-success').empty().html('<i class="fa fa-download"></i> <?php echo __t('Installed'); ?>');
                    } else {
                        $('#' + dataLineId).removeClass('fa fa-check-square-o').addClass('fa fa-square-o');
                        $('#install' + dataId).removeClass('btn-success').addClass('btn-default').empty().html('<i class="fa fa-download"></i> <?php echo __t('Install'); ?>');
                    }
                }
                else
                {
                    /* All others cases */
                    if (data.fieldValue == 1) {
                        $('#' + dataLineId).removeClass('fa fa-square-o').addClass('fa fa-check-square-o');
                    } else {
                        $('#' + dataLineId).removeClass('fa fa-check-square-o').addClass('fa fa-square-o');
                    }
                }

                return false;
            }).fail(function(xhr, textStatus, errorThrown) {
                /*
                 console.log('FAILURE: ' + textStatus);
                 console.log(xhr);
                 */

                alert("<?php echo __t('Error!'); ?>");

                return false;
            });

            return false;
        }
    </script>

    @include('admin::inc.alerts')

    @yield('after_scripts')

</body>
</html>
