<aside>
	<div class="inner-box">
		<div class="user-panel-sidebar">

            @if (isset($user))
                <div class="collapse-box">
                    <h4 class="collapse-title no-border">
                        {{ t('My Account') }}&nbsp;
                        <a href="#MyClassified" data-toggle="collapse" class="pull-right"><i class="fa fa-angle-down"></i></a>
                    </h4>
                    <div class="panel-collapse collapse in" id="MyClassified">
                        <ul class="acc-list">
                            <li>
                                <a {!! ($pagePath=='') ? 'class="active"' : '' !!} href="{{ lurl('account') }}">
                                    <i class="fa fa-home"></i> {{ t('Personal Home') }}
                                </a>
																</li>
																<li>
																		<a {!! ($pagePath=='') ? 'class="active"' : '' !!} href="{{ lurl('account/resume') }}">
																				<i class="fa fa-file-text-o"></i> {{ t('Resume') }}
																		</a>
																		</li>
                        </ul>
                    </div>
                </div>
                <!-- /.collapse-box  -->

                @if (!empty($user->user_type_id) and $user->user_type_id != 0)
                    <div class="collapse-box">
                        <h4 class="collapse-title">
                            {{ t('My Ads') }}&nbsp;
                            <a href="#MyAds" data-toggle="collapse" class="pull-right"><i class="fa fa-angle-down"></i></a>
                        </h4>
                        <div class="panel-collapse collapse in" id="MyAds">
                            <ul class="acc-list">
                                @if (in_array($user->user_type_id, [1, 2]))
                                    <li>
                                        <a{!! ($pagePath=='my-posts') ? ' class="active"' : '' !!} href="{{ lurl('account/my-posts') }}">
                                        <i class="icon-docs"></i> {{ t('My ads') }}&nbsp;
                                        <span class="badge">{{ isset($countMyPosts) ? $countMyPosts : 0 }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a{!! ($pagePath=='pending-approval') ? ' class="active"' : '' !!} href="{{ lurl('account/pending-approval') }}">
                                        <i class="icon-hourglass"></i> {{ t('Pending approval') }}&nbsp;
                                        <span class="badge">{{ isset($countPendingPosts) ? $countPendingPosts : 0 }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a{!! ($pagePath=='archived') ? ' class="active"' : '' !!} href="{{ lurl('account/archived') }}">
                                        <i class="icon-folder-close"></i> {{ t('Archived ads') }}&nbsp;
                                        <span class="badge">{{ isset($countArchivedPosts) ? $countArchivedPosts : 0 }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a{!! ($pagePath=='messages') ? ' class="active"' : '' !!} href="{{ lurl('account/messages') }}">
                                        <i class="icon-mail-1"></i> {{ t('Messages') }}&nbsp;
                                        <span class="badge">{{ isset($countMessages) ? $countMessages : 0 }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a{!! ($pagePath=='transactions') ? ' class="active"' : '' !!} href="{{ lurl('account/transactions') }}">
                                        <i class="icon-money"></i> {{ t('Transactions') }}&nbsp;
                                        <span class="badge">{{ isset($countTransactions) ? $countTransactions : 0 }}</span>
                                        </a>
                                    </li>
                                @endif
                                @if (in_array($user->user_type_id, [1, 3]))
                                    <li>
                                        <a{!! ($pagePath=='favorite') ? ' class="active"' : '' !!} href="{{ lurl('account/favorite') }}">
                                        <i class="icon-heart"></i> {{ t('Favorite jobs') }}&nbsp;
                                        <span class="badge">{{ isset($countFavoritePosts) ? $countFavoritePosts : 0 }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a{!! ($pagePath=='saved-search') ? ' class="active"' : '' !!} href="{{ lurl('account/saved-search') }}">
                                        <i class="icon-star-circled"></i> {{ t('Saved search') }}&nbsp;
                                        <span class="badge">{{ isset($countSavedSearch) ? $countSavedSearch : 0 }}</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <!-- /.collapse-box  -->
                @endif
            @endif

		</div>
	</div>
	<!-- /.inner-box  -->
</aside>
