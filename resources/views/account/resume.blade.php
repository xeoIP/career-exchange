@extends('layouts.master')

@section('content')
<!-- Titlebar
================================================== -->
<div id="titlebar" class="resume">
	<div class="container">
		<div class="ten columns">
			<div class="resume-titlebar">
					@if (!empty($gravatar))
						<img class="userImg" src="{{ $gravatar }}" alt="user">&nbsp;
					@else
						<img class="userImg" src="{{ url('images/user.jpg') }}" alt="user">
					@endif
				<div class="resumes-list-content">
					<h4>{{ $user->name }} <span>UX/UI Graphic Designer</span></h4>
					<span>Successfully started company from ground up, award-winning Tech Sales/Solutions Exec consistently exceeding quotas, wants Enterprise Software Sales.</span>
					<span class="icons"><i class="fa fa-map-marker"></i> Austin, TX</span>
					<span class="icons"><a href="#"><i class="fa fa-link"></i> Website</a></span>
					<span class="icons"><a href="mailto:john.doe@example.com"><i class="fa fa-envelope"></i> first.last@example.com</a></span>
					<div class="skills">
						<span>JavaScript</span>
						<span>PHP</span>
						<span>WordPress</span>
					</div>
					<div class="clearfix"></div>

				</div>
			</div>
		</div>

		<div class="six columns">
			<div class="two-buttons">
				Verified
				<div class="rating five-stars">
					<div class="star-rating"></div>
					<div class="star-bg"></div>
				</div>
				<a href="#small-dialog" class="popup-with-zoom-anim button"><i class="fa fa-envelope"></i> Send Message</a>

				<div id="small-dialog" class="zoom-anim-dialog mfp-hide apply-popup">
					<div class="small-dialog-headline">
						<h2>Send Message to John Doe</h2>
					</div>

					<div class="small-dialog-content">
						<form action="#" method="get" >
							<input type="text" placeholder="Full Name" value=""/>
							<input type="text" placeholder="Email Address" value=""/>
							<textarea placeholder="Message"></textarea>

							<button class="send">Send Application</button>
						</form>
					</div>

				</div>
				<a href="#" class="button dark"><i class="fa fa-star"></i> Bookmark This Resume</a>


			</div>
		</div>

	</div>
</div>
<!-- Content
================================================== -->
<div class="container">
	<!-- Recent Jobs -->
	<div class="eight columns">
	<div class="padding-right">

		<h3 class="margin-bottom-15">About Me</h3>
		<p><i class="fa fa-cog"></i> <strong>Interested in Full Stack Engineer, Backend Engineer, and UX Engineer positions</strong></p>
		<p class="margin-reset">
			Ideally looking for an enterprise opportunity to leverage my technical skills and business development experience, along with my work in assisting a successful startup gives me a great sense of what it takes to create successful results and leading teams to achieve those goals, while maintaining a strong understanding of the underlying technologies, procedures, and processes used to get there.
		</p>

		<br>

		<p>The <strong>Food Service Specialist</strong> will have responsibilities that include:</p>

		<ul class="list-1">
			<li>Successfully started company from the ground up with a concept of collaborating development efforts, data driven staff augmentation, and project based consulting</li>
			<li>Created and implemented all aspects of the business operations including the business plan, pitch deck, landing Angel Investor, Company Registration through the State, Company Insurance, Employee Insurance, CRM Online Payroll Processing, Online Timesheets and Paystubs, and Performance Analytics</li>
			<li>Named as President, Board of Directors for Texas Recruiters Association (TRA)</li>
			<li>Named as Veterans Jobs Ambassador for Our Digital Heroes (ODH)</li>
			<li>Member of the nationally recognized American Staffing Association (ASA)</li>
		</ul>

	</div>
	</div>


	<!-- Widgets -->
	<div class="eight columns">

		<h3 class="margin-bottom-20">Work Experience</h3>

		<!-- Resume Table -->
		<dl class="resume-table">
			<dt>
				<strong>TechTank</strong>
				<strong>Executive, Business Development and Operations</strong>
				<small class="date">Jul 2015 — present (2 yrs, 3 mos)</small>
			</dt>
			<dd>
				<p>Created several RFP bid responses for the Department of Information Resources (DIR) for the State of Texas leading to becoming a DIR certified vendor</p>
			</dd>


			<dt>
				<strong>MerchantAdvantage, LLC</strong>
				<strong>Senior Business Development Executive</strong>
				<small class="date">Jul 2014 — Jul 2015 (1 yr, 1 mo)</small>
			</dt>
			<dd>
				<p>Led the new sales strategy in targeting Marketing & Digital Agency clients across the U.S. to use our software for their entire portfolio of corporate clients. </p>
			</dd>


			<dt>
				<strong>Internal Data Resources</strong>
				<strong>Senior Account Manager</strong>
				<small class="date">May 2012 — May 2014 (2 yrs, 1 mo)</small>
			</dt>
			<dd>
				<p>Managed a portfolio of 10-12 enterprise level clients building strong working relationships to service their IT needs and ensure staffing deliverables are met in a timely manner</p>
			</dd>

		</dl>

	</div>

</div>
<!-- /.main-container -->
@endsection

@section('after_styles')
	<link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
	<style>
		.krajee-default.file-preview-frame:hover:not(.file-preview-error) {
			box-shadow: 0 0 5px 0 #666666;
		}
	</style>
@endsection

@section('after_scripts')
	<script src="{{ url('assets/plugins/bootstrap-fileinput/js/plugins/sortable.min.js') }}" type="text/javascript"></script>
	<script src="{{ url('assets/plugins/bootstrap-fileinput/js/fileinput.min.js') }}" type="text/javascript"></script>
	@if (file_exists(public_path() . '/assets/plugins/bootstrap-fileinput/js/locales/'.config('app.locale').'.js'))
		<script src="{{ url('assets/plugins/bootstrap-fileinput/js/locales/'.config('app.locale').'.js') }}" type="text/javascript"></script>
	@endif

	<script>
		/* initialize with defaults (resume) */
		$('#filename').fileinput(
		{
            language: '{{ config('app.locale') }}',
			showPreview: false,
			allowedFileExtensions: {!! getUploadFileTypes('file', true) !!},
			browseLabel: '{!! t("Browse") !!}',
			showUpload: false,
			showRemove: false,
			maxFileSize: {{ (int)config('settings.upload_max_file_size', 1000) }}
		});
	</script>
	<script>
		var userType = '<?php echo old('user_type', $user->user_type_id); ?>';

		$(document).ready(function ()
		{
			/* Set user type */
			setUserType(userType);
			$('#userType').change(function () {
				setUserType($(this).val());
			});
		});
	</script>
@endsection
