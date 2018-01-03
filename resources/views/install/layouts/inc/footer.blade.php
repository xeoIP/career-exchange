<div class="footer" id="footer">
	<div class="container">
		<ul class="pull-left navbar-link footer-nav list-inline" style="margin-left: -20px;">

		</ul>
		<ul class="pull-right navbar-link footer-nav list-inline" style="padding-right: 10px;">
			<li>
				&copy; {{ date('Y') }} <a href="{{ url('/') }}" style="padding: 0;">{{ strtolower(getDomain()) }}</a>
			</li>
			<li>
				<a href="{{ config('settings.facebook_page_url') }}" target="_blank"><i class="icon-facebook-rect"></i></a>
				<a href="{{ config('settings.twitter_url') }}" target="_blank"><i class="icon-twitter-bird"></i></a>
			</li>
		</ul>
	</div>

</div>
<!-- /.footer -->