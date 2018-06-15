<ul class="nav nav-pills install-steps">
	<li class="{{ $current == 1 ? "active" : "" }} {{ $step >= 0 ? "enabled" : "" }}">
		<a href="{{ $installUrl . '/system_compatibility' }}">
			<i class="icon-info-circled-alt"></i> {{ trans('messages.system_compatibility') }}
		</a>
	</li>
	<li class="{{ $current == 2 ? "active" : "" }} {{ $step >= 1 ? "enabled" : "" }}">
		<a href="{{ $installUrl . '/site_info' }}">
			<i class="icon-cog"></i> {{ trans('messages.configuration') }}
		</a>
	</li>
	<li class="{{ $current == 3 ? "active" : "" }} {{ $step >= 2 ? "enabled" : "" }}">
		<a href="{{ $installUrl . '/database' }}">
			<i class="icon-database"></i> {{ trans('messages.database') }}
		</a>
	</li>
	<li class="{{ $current == 5 ? "active" : "" }} {{ $step >= 4 ? "enabled" : "" }}">
		<a href="{{ $installUrl . '/cron_jobs' }}">
			<i class="icon-clock"></i> {{ trans('messages.cron_jobs') }}
		</a>
	</li>
	<li class="{{ $current == 6 ? "active" : "" }} {{ $step >= 5 ? "enabled" : "" }}">
		<a href="{{ $installUrl . '/finish' }}">
			<i class="icon-ok-circled2"></i> {{ trans('messages.finish') }}
		</a>
	</li>
</ul>