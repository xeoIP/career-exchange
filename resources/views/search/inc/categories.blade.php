<?php
if (!isset($cats)) {
    $cats = collect([]);
}

$cats = $cats->groupBy('parent_id');
$subCats = $cats;
$cats = $cats->get(0);
$subCats = $subCats->forget(0);
?>
<div class="container">
	<div class="category-links">
		<ul>
			@if (isset($subCats) and !empty($subCats) and isset($cat) and !empty($cat))
				@if ($subCats->has($cat->tid))
					@foreach ($subCats->get($cat->tid) as $subCat)
						<li>
							<a href="{{ lurl(trans('routes.v-search-subCat', ['countryCode' => $country->get('icode'), 'catSlug' => $cat->slug, 'subCatSlug' => $subCat->slug])) }}">
								{{ $subCat->name }}
							</a>
						</li>
					@endforeach
				@endif
			@else
				@if (isset($cats) and !empty($cats))
					@foreach ($cats as $category)
						<li>
							<a href="{{ lurl(trans('routes.v-search-cat', ['countryCode' => $country->get('icode'), 'catSlug' => $category->slug])) }}">
								{{ $category->name }}
							</a>
						</li>
					@endforeach
				@endif
			@endif
		</ul>
	</div>
</div>