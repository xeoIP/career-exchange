<?php

return [
	
	/*
	|--------------------------------------------------------------------------
	| Emails Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines are used by the Mail notifications.
	|
	*/
	
	// mail_footer
	'mail_footer_content'             => ':domain, a Jobs Portal website. Simple, fast and efficient.',
	
	
	// email_verification
	'email_verification_title'        => 'Please verify your email address.',
	'email_verification_action'       => 'Verify email address',
	'email_verification_content_1'    => 'Hi :user_name !',
	'email_verification_content_2'    => 'Click the button below to verify your email address.',
	'email_verification_content_3'    => 'Button not working? Paste the following link into your browser:<br><a href=":verificationLink">:verificationLink</a>.',
	'email_verification_content_4'    => '<br><br>You’re receiving this email because you recently created a new :app_name account or added a new email address. If this wasn’t you, please ignore this email.',
	'email_verification_content_5'    => '<br><br>Kind Regards,<br>The :domain Team',
	
	
	// post_activated (new)
	'post_activated_title'              => 'Your ad has been activated',
	'post_activated_content_1'          => 'Hello, <br><br>Your ad <a href=":postUrl">:title</a> has been activated.',
	'post_activated_content_2'          => '<br>It will soon be examined by one of our administrators for its publication on line.',
	'post_activated_content_3'          => '<br><br>You’re receiving this email because you recently created a new ad on :app_name. If this wasn’t you, please ignore this email.',
	'post_activated_content_4'          => '<br><br>Kind Regards,<br>The :domain Team',
	
	// post_reviewed (new)
	'post_reviewed_title'               => 'Your ad is now online',
	'post_reviewed_content_1'           => 'Hello, <br><br>Your ad <a href=":postUrl">:title</a> is now online.',
	'post_reviewed_content_2'           => '<br><br>You’re receiving this email because you recently created a new ad on :app_name. If this wasn’t you, please ignore this email.',
	'post_reviewed_content_3'           => '<br><br>Kind Regards,<br>The :domain Team',
	
	
	// post_deleted
	'post_deleted_title'                => 'Your ad has been deleted',
	'post_deleted_content_1'            => 'Hello,<br><br>Your ad ":title" has been deleted from <a href=":countryDomain">:domain</a> at :now.',
	'post_deleted_content_2'            => '<br><br>Thank you for your trust and see you soon,',
	'post_deleted_content_3'            => '<br><br>The <a href=":countryDomain">:domain</a> Team<br><a href=":domain">:domain</a>',
	'post_deleted_content_4'            => '<br><br><br>PS: This is an automated email, please don\'t reply.',
	
	
	// post_employer_contacted
	'post_employer_contacted_title'     => 'Your ad ":title" on :app_name',
	'post_employer_contacted_content_1' => '<strong>Contact Information :</strong><br>Name : :name<br>Email address : :email<br>Phone number : :phone<br><br>This email was sent to you about the ad ":title" you filed on <a href=":countryDomain">:domain</a> : <a href=":postUrl">:postUrl</a>',
	'post_employer_contacted_content_2' => '<br><br>PS : The person who contacted you do not know your email as you will not reply.',
	'post_employer_contacted_content_3' => '',
	'post_employer_contacted_content_4' => '<br><br>Thank you for your trust and see you soon,',
	'post_employer_contacted_content_5' => '<br><br>The <a href=":countryDomain">:domain</a> Team<br><a href=":domain">:domain</a>',
	'post_employer_contacted_content_6' => '<br><br><br>PS: This is an automated email, please don\'t reply.',
	
	
	// user_deleted
	'user_deleted_title'              => 'Your account has been deleted on :app_name',
	'user_deleted_content_1'          => 'Hello,<br><br>Your account has been deleted from <a href=":countryDomain">:domain</a> at :now.',
	'user_deleted_content_2'          => '<br><br>Thank you for your trust and see you soon,',
	'user_deleted_content_3'          => '<br><br>The <a href=":countryDomain">:domain</a> Team<br><a href=":domain">:domain</a>',
	'user_deleted_content_4'          => '<br><br><br>PS: This is an automated email, please don\'t reply.',
	
	
	// user_activated (new)
	'user_activated_title'            => 'Welcome to :app_name !',
	'user_activated_content_1'        => 'Welcome to :app_name :user_name !',
	'user_activated_content_2'        => '<br>Your account has been activated.',
	'user_activated_content_3'        => '<br><br><strong>Note : :app_name team recommends that you:</strong><br><br>1 - Never send money by Western Union or other international mandate.<br>2 - If you have any doubt about the seriousness of an advertiser, please contact us immediately. We can then neutralize as quickly as possible and prevent someone less informed do become the victim.',
	'user_activated_content_4'        => '<br><br>You’re receiving this email because you recently created a new :app_name account. If this wasn’t you, please ignore this email.',
	'user_activated_content_5'        => '<br><br>Kind Regards,<br>The :domain Team',
	
	
	// reset_password
	'reset_password_title'            => 'Reset Your Password',
	'reset_password_action'           => 'Reset Password',
	'reset_password_content_1'        => 'Forgot your password?',
	'reset_password_content_2'        => 'Let\'s get you a new one.',
	'reset_password_content_3'        => 'If you did not request a password reset, no further action is required.',
	'reset_password_content_4'        => '<br><br>Regards,<br>:app_name',
	'reset_password_content_5'        => '<br><br>---<br>If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:<br> :link',
	
	
	// contact_form
	'contact_form_title'              => 'New message from :app_name',
	'contact_form_content'            => ':app_name - New message',
	
	
	// post_report_sent
	'post_report_sent_title'            => 'New abuse report',
	'post_report_sent_content'          => 'New Report Abuse - :app_name/:country_code',
	'Post URL'                          => 'Post URL',
	
	
	// ad archived
	'post_archived_title'               => 'Your ad has been archived',
	'post_archived_content_1'           => 'Hello,<br><br>Your ad ":title" has been archived from :domain at :now.',
	'post_archived_content_2'           => '<br><br>You can repost it by clicking here : :repostLink',
	'post_archived_content_3'           => '<br><br>If you do nothing your ad will be permanently deleted on :dateDel.',
	'post_archived_content_4'           => '<br><br>Thank you for your trust and see you soon,',
	'post_archived_content_5'           => '<br><br>The :domain Team<br>:domain',
	'post_archived_content_6'           => '<br><br><br>PS: This is an automated email, please don\'t reply.',
	
	
	// post_will_be_deleted
	'post_will_be_deleted_title'        => 'Your ad will be deleted in :days days',
	'post_will_be_deleted_content_1'    => 'Hello,<br><br>Your ad ":title" will be deleted in :days days from :domain.',
	'post_will_be_deleted_content_2'    => '<br><br>You can repost it by clicking here : :repostLink',
	'post_will_be_deleted_content_3'    => '<br><br>If you do nothing your ad will be permanently deleted on :dateDel.',
	'post_will_be_deleted_content_4'    => '<br><br>Thank you for your trust and see you soon,',
	'post_will_be_deleted_content_5'    => '<br><br>The :domain Team<br>:domain',
	'post_will_be_deleted_content_6'    => '<br><br><br>PS: This is an automated email, please don\'t reply.',
	
	
	// post_sent_by_email
	'post_sent_by_email_title'          => 'New Suggestion - :app_name/:country_code',
	'post_sent_by_email_content_1'      => 'A user recommended you a job\'s link with the email address: :sender_email',
	'post_sent_by_email_content_2'      => '<br>Click below to see the details of the job offer.',
	'Job URL'                           => 'Job URL',
	
	
	// post_notification
	'post_notification_title'           => 'New job has been posted',
	'post_notification_content_1'       => 'Hello Admin,<br><br>The user :advertiserName has just posted a new job.',
	'post_notification_content_2'       => '<br>The ad title: :title<br>Posted on: :now at :time',
	'post_notification_content_3'       => '<br><br>Kind Regards,<br>The :domain Team',
	
	
	// user_notification
	'user_notification_title'         => 'New User Registration',
	'user_notification_content_1'     => 'Hello Admin,<br><br>:name has just registered.',
	'user_notification_content_2'     => '<br>Registered on: :now at :time<br>Email: <a href="mailto::email">:email</a>',
	'user_notification_content_3'     => '<br><br>Kind Regards,<br>The :domain Team',
	
	
	// payment_sent
	'payment_sent_title'              => 'Thanks for your payment !',
	'payment_sent_content_1'          => 'Hello,<br><br>We have received your payment for the job ad ":title".',
	'payment_sent_content_2'          => '<br><h1>Thank you !</h1>',
	'payment_sent_content_3'          => '<br>Kind Regards,<br>The :domain Team',
	
	
	// payment_notification
	'payment_notification_title'      => 'New payment has been sent',
	'payment_notification_content_1'  => 'Hello Admin,<br><br>The user :advertiserName has just paid a package for her job ad ":title".',
	'payment_notification_content_2'  => '<br><br><strong>The Pack details</strong><br>Name: :name<br>Price: :price',
	'payment_notification_content_3'  => '<br><br>Kind Regards,<br>The :domain Team',
	
	
	// reply_form
	'reply_form_title'                => 'RE: :postTitle',
	'reply_form_content_1'            => 'Hello,<br><br><strong>You have received a response from: :senderName. See answer below:</strong><br><br>',
	'reply_form_content_2'            => '<br><br>Kind Regards,<br>The :domain Team',


];
