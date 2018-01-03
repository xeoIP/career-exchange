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
	'mail_footer_content'             => ':domain, un site internet pour l\'emploi. Simple, rapide et efficace.',
	
	
	// email_verification
	'email_verification_title'        => 'Vérifier votre adresse email.',
	'email_verification_action'       => 'Verify email address',
	'email_verification_content_1'    => 'Bonjour :user_name !',
	'email_verification_content_2'    => 'Cliquez sur le bouton ci-dessous pour vérifier votre adresse email.',
	'email_verification_content_3'    => 'Le bouton ne fonctionne pas? Copiez/Collez le lien ci-après dans votre navigateur:<br><a href=":verificationLink">:verificationLink</a>.',
	'email_verification_content_4'    => '<br><br>Vous recevez ce message parce que vous avez récemment créé un nouveau compte :app_name ou ajouté un nouvelle adresse email. Nous vous prions d\'ignorer ce message si nous nous sommes trompés.',
	'email_verification_content_5'    => '<br><br>Cordialement,<br>L\'équipe :domain',
	
	
	// post_activated
	'post_activated_title'              => 'Votre annonce a bien été activée',
	'post_activated_content_1'          => 'Bonjour, <br><br>Votre annonce <a href=":postUrl">:title</a> a bien été activée.',
	'post_activated_content_2'          => '<br>Elle sera prochainement examinée par un de nos administrateurs pour sa mise en ligne.',
	'post_activated_content_3'          => '<br><br>Vous recevez ce message parce que vous avez récemment publié une nouvelle annonce sur :app_name. Nous vous prions d\'ignorer ce message si nous nous sommes trompés.',
	'post_activated_content_4'          => '<br><br>Cordialement,<br>L\'équipe :domain',
	
	// post_reviewed
	'post_reviewed_title'               => 'Votre annonce est maintenant en ligne',
	'post_reviewed_content_1'           => 'Bonjour, <br><br>Votre annonce <a href=":postUrl">:title</a> is now online.',
	'post_reviewed_content_2'           => '<br><br>Vous recevez ce message parce que vous avez récemment publié une nouvelle annonce sur :app_name. Nous vous prions d\'ignorer ce message si nous nous sommes trompés.',
	'post_reviewed_content_3'           => '<br><br>Cordialement,<br>L\'équipe :domain',
	
	
	// post_deleted
	'post_deleted_title'                => 'Votre annonce a bien été supprimée',
	'post_deleted_content_1'            => 'Bonjour,<br><br>Votre annonce ":title" a bien été supprimée de <a href=":countryDomain">:domain</a> le :now.',
	'post_deleted_content_2'            => '<br><br>Merci de votre confiance et à très bientôt sur notre site,',
	'post_deleted_content_3'            => '<br><br>L\'équipe <a href=":countryDomain">:domain</a><br><a href=":domain">:domain</a>',
	'post_deleted_content_4'            => '<br><br><br>PS: Ceci est un email automatique, merci de ne pas y répondre.',
	
	
	// post_employer_contacted
	'post_employer_contacted_title'     => 'Votre annonce ":title" sur :app_name',
	'post_employer_contacted_content_1' => '<strong>Coordonnées du contact :</strong><br>Nom : :name<br>Email : :email<br>Tel : :phone<br><br>Cet email vous a été envoyé au sujet de l\'annonce ":title" que vous avez déposée sur <a href=":countryDomain">:domain</a> : <a href=":postUrl">:postUrl</a>',
	'post_employer_contacted_content_2' => '<br><br>PS : la personne qui vous a contacté ne connaîtra pas votre email tant que vous ne lui aurez pas répondu.',
	'post_employer_contacted_content_3' => '',
	'post_employer_contacted_content_4' => '<br><br>Merci de votre confiance et à très bientôt sur notre site,',
	'post_employer_contacted_content_5' => '<br><br>L\'équipe <a href=":countryDomain">:domain</a><br><a href=":domain">:domain</a>',
	'post_employer_contacted_content_6' => '<br><br><br>PS: Ceci est un email automatique, merci de ne pas y répondre.',
	
	
	// user_deleted
	'user_deleted_title'              => 'Votre compte a bien été supprimé',
	'user_deleted_content_1'          => 'Bonjour,<br><br>Votre compte a bien été supprimée de <a href=":countryDomain">:domain</a> le :now.',
	'user_deleted_content_2'          => '<br><br>Merci de votre confiance et à très bientôt sur notre site,',
	'user_deleted_content_3'          => '<br><br>L\'équipe <a href=":countryDomain">:domain</a><br><a href=":domain">:domain</a>',
	'user_deleted_content_4'          => '<br><br><br>PS: Ceci est un email automatique, merci de ne pas y répondre.',
	
	
	// user_activated
	'user_activated_title'            => 'Bienvenu(e) sur :app_name !',
	'user_activated_content_1'        => 'Bienvenu(e) sur :app_name :user_name !',
	'user_activated_content_2'        => '<br>Votre compte a bien été activé.',
	'user_activated_content_3'        => '<br><br><strong>Attention, l\'équipe de :app_name vous recommande de :</strong><br><br>1 - Ne jamais envoyer d\'argent par Western Union ou autre mandat international.<br>2 - Si vous avez un doute concernant le sérieux d\'un annonceur, contactez-nous immédiatement. Nous pourrons ainsi le neutraliser au plus vite et éviter qu\'une personne moins avisée n\'en devienne la victime.',
	'user_activated_content_4'        => '<br><br>Vous recevez ce message parce que vous avez récemment créé un nouveau compte :app_name. Nous vous prions d\'ignorer ce message si nous nous sommes trompés.',
	'user_activated_content_5'        => '<br><br>Cordialement,<br>L\'équipe :domain',
	
	
	// reset_password
	'reset_password_title'            => 'Réinitialisez votre mot de passe',
	'reset_password_action'           => 'Reset Password',
	'reset_password_content_1'        => 'Mot de passe oublié?',
	'reset_password_content_2'        => 'Vous pouvez en obtenir un nouveau.',
	'reset_password_content_3'        => 'Si vous n\'avez pas demandé de réinitialisation d\'un mot de passe, aucune autre action n\'est requise.',
	'reset_password_content_4'        => '<br><br>Cordialement,<br>:app_name',
	'reset_password_content_5'        => '<br><br>---<br>Si vous rencontrez des difficultés pour cliquer sur le bouton "Réinitialiser le mot de passe" copiez et collez l\'adresse URL ci-dessous dans votre navigateur web:<br> :link',
	
	
	// contact_form
	'contact_form_title'              => 'Nouveau message de :app_name',
	'contact_form_content'            => ':app_name - Nouveau message',
	
	
	// post_report_sent
	'post_report_sent_title'            => 'Nouveau report d\'abus - :app_name/:country_code',
	'post_report_sent_content'          => 'Nouveau report d\'abus - :app_name/:country_code',
	'Post URL'                          => 'URL de l\'annonce',
	
	
	// ad archived
	'post_archived_title'               => 'Votre annonce a été archivée',
	'post_archived_content_1'           => 'Bonjour,<br><br>Votre annonce ":title" a été archivé sur :domain le :now.',
	'post_archived_content_2'           => '<br><br>Vous pouvez la re-publier en cliquant sur ce lien: :repostLink',
	'post_archived_content_3'           => '<br><br>Si vous ne faites rien votre annonce sera définitivement supprimée le :dateDel.',
	'post_archived_content_4'           => '<br><br>Merci de votre confiance et à très bientôt sur notre site,',
	'post_archived_content_5'           => '<br><br>Meilleures salutations,<br>L\'équipe :domain',
	'post_archived_content_6'           => '<br><br><br>PS: Ceci est un email automatique, merci de ne pas y répondre.',
	
	
	// post_will_be_deleted
	'post_will_be_deleted_title'        => 'Votre annonce sera supprimée dans :days jours',
	'post_will_be_deleted_content_1'    => 'Bonjour,<br><br>Votre annonce ":title" sera supprimée dans :days jours de :domain.',
	'post_will_be_deleted_content_2'    => '<br><br>Vous pouvez la re-publier en cliquant sur ce lien: :repostLink',
	'post_will_be_deleted_content_3'    => '<br><br>Si vous ne faites rien votre annonce sera définitivement supprimée le :dateDel.',
	'post_will_be_deleted_content_4'    => '<br><br>Merci de votre confiance et à très bientôt sur notre site,',
	'post_will_be_deleted_content_5'    => '<br><br>Meilleures salutations,<br>L\'équipe :domain',
	'post_will_be_deleted_content_6'    => '<br><br><br>PS: Ceci est un email automatique, merci de ne pas y répondre.',
	
	
	// post_sent_by_email
	'post_sent_by_email_title'          => 'Nouvelle Suggestion - :app_name/:country_code',
	'post_sent_by_email_content_1'      => 'Un utilisateur vous a recommandé le lien d\'une offre d\'emploi avec l\'adresse email: :sender_email',
	'post_sent_by_email_content_2'      => '<br>Cliquez ci-dessous pour voir les détails de l\'offre d\'emploi.',
	'Job URL'                           => 'URL de l\'annonce',
	
	
	// post_notification
	'post_notification_title'           => 'Une offre vient d\'être posté,',
	'post_notification_content_1'       => 'Bonjour Admin,<br><br>L\'utilisateur :advertiser_name vient de poster une nouvelle offre d\'emploi.',
	'post_notification_content_2'       => '<br>Titre de l\'annonce: :title<br>Publiée le: :now à :time',
	'post_notification_content_3'       => '<br><br>Meilleures salutations,<br>L\'équipe :domain',
	
	
	// user_notification
	'user_notification_title'         => 'Un nouvel utilisateur',
	'user_notification_content_1'     => 'Bonjour Admin,<br><br>:name vient de s\'inscrire.',
	'user_notification_content_2'     => '<br>Inscrit le: :now à :time<br>Email: <a href="mailto::email">:email</a>',
	'user_notification_content_3'     => '<br><br>Meilleures salutations,<br>L\'équipe :domain',
	
	
	// payment_sent
	'payment_sent_title'              => 'Merci pour votre paiement !',
	'payment_sent_content_1'          => 'Bonjour,<br><br>Nous avons bien reçu votre paiement pour l\'annonce ":title".',
	'payment_sent_content_2'          => '<br><h1>Merci !</h1>',
	'payment_sent_content_3'          => '<br>Meilleures salutations,<br>L\'équipe :domain',
	
	
	// payment_notification
	'payment_notification_title'      => 'Un paiement vient d\'être effectué',
	'payment_notification_content_1'  => 'Bonjour Admin,<br><br>L\'utilisateur :advertiser_name vient de payer un package pour son annonce ":title".',
	'payment_notification_content_2'  => '<br><br><strong>Détails du Pack</strong><br>Nom: :name<br>Tarif: :price',
	'payment_notification_content_3'  => '<br><br>Meilleures salutations,<br>L\'équipe :domain',
	
	
	// reply_form
	'reply_form_title'                => 'RE: :postTitle',
	'reply_form_content_1'            => 'Bonjour,<br><br><strong>Vous avez reçu une réponse de: :senderName. Voir le message ci-dessous:</strong><br><br>',
	'reply_form_content_2'            => '<br><br>Meilleures salutations,<br>L\'équipe :domain',


];
