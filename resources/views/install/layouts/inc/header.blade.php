<div class="container">
	<div class="header">
		<nav class="navbar navbar-site navbar-default" role="navigation">
			<div class="container" style="padding-left: 0; padding-right: 0;">
				<div class="navbar-header">
					<button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a href="{{ url('/') }}" class="navbar-brand logo logo-title">
						<img src="/uploads/app/default/logo.png" style="width:auto; height:40px; float:left; margin:0 5px 0 0;"/>
						@if (isset($country) and !$country->isEmpty())
							@if (file_exists(public_path() . '/images/flags/24/'.strtolower($country->get('code')).'.png'))
								<span>
									<img src="{{ url('images/flags/24/'.strtolower($country->get('code')).'.png') }}" style="float: left; margin: 8px 0 0 0;">
								</span>
							@endif
						@endif
					</a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right"></ul>
				</div>
			</div>
		</nav>
	</div>
</div>