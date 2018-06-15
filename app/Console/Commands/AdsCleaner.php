<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Scopes\VerifiedScope;
use App\Models\User;
use App\Models\Scopes\ActiveScope;
use App\Models\Scopes\ReviewedScope;
use App\Notifications\PostArchived;
use App\Notifications\PostDeleted;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\Country;
use App\Models\TimeZone;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class AdsCleaner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ads:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all old Posts.';

    /**
     * Default Ads Expiration Duration
     *
     * @var int
     */
    private $unactivatedPostsExpiration = 30; // Delete the unactivated Posts after this expiration
    private $activatedPostsExpiration = 150; // Archive the activated Posts after this expiration
    private $archivedPostsExpiration = 7; // Delete the archived Posts after this expiration

	/**
	 * AdsCleaner constructor.
	 */
    public function __construct()
    {
        parent::__construct();

        $this->unactivatedPostsExpiration = (int)config('settings.unactivated_posts_expiration', $this->unactivatedPostsExpiration);
        $this->activatedPostsExpiration = (int)config('settings.activated_posts_expiration', $this->activatedPostsExpiration);
        $this->archivedPostsExpiration = (int)config('settings.archived_posts_expiration', $this->archivedPostsExpiration);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get all Countries
        $countries = Country::withoutGlobalScope(ActiveScope::class)->get();
		if ($countries->count() <= 0) {
			dd('No country found.');
		}

		foreach ($countries as $country)
		{
			// Ads query
            $posts = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->countryOf($country->code);

            if ($posts->count() <= 0) {
                $this->info('No ads in "' . $country->name . '" (' . strtolower($country->code) . ') website.');
                continue;
            }

            // Get all Posts
            $posts = $posts->get();

			foreach ($posts as $post)
			{
				// Get country time zone by city
				$city = City::find($post->city_id);
				$timeZoneId = (!empty($city)) ? $city->time_zone : 'Europe/London';

				// Set today with time zone
				$today = Carbon::now($timeZoneId);

                // Debug
                // dd($today->diffInDays($post->created_at));

                /* Non-activated Posts */
                if (!isVerifiedPost($post)) {
                    // Delete non-active Ads after '$this->unactivatedPostsExpiration' days
                    if ($today->diffInDays($post->created_at) >= $this->unactivatedPostsExpiration) {
                        $post->delete();
                        continue;
                    }
                }
                /* Activated Posts */
                else
                {
                    /* Admin's Posts */
                    if (isset($post->user_id)) {
                        $possibleAdminUser = User::find($post->user_id);
                        if (!empty($possibleAdminUser)) {
                            if ($possibleAdminUser->is_admin == 1) {
                                // Delete all Admin Posts after '$this->activatedPostsExpiration' days
                                if ($today->diffInDays($post->created_at) >= $this->activatedPostsExpiration) {
                                    $post->delete();
                                    continue;
                                }
                            }
                        }
                    }

                    /* Users's Posts */

                    /* Check if Post is featured */
                    if ($post->featured == 1)
                    {
                        // Get all Packages
                        $packages = Package::transIn(config('applang.abbr', config('app.locale')))->get();

                        /* It is a website with Premium Posts */
                        if ($packages->count() > 0) {
                            // Check the Post's transaction
                            $payment = Payment::where('post_id', $post->id)->orderBy('id', 'DESC')->first();
                            if (!empty($payment)) {
                                // Get Package info
                                $package = Package::find($payment->package_id);
                                if (!empty($package)) {
                                    // Un-featured the ad after {$package->duration} days
                                    if ($today->diffInDays($post->created_at) >= $package->duration) {

                                        // Un-featured
                                        $post->featured = 0;
                                        $post->save();

                                        continue;
                                    }
                                }
                            }
                        }
                    }
                    /* It is a free website */
                    else
                    {
                        // Auto-archive
                        if ($post->archived != 1) {
                            // Archive all activated Posts after '$this->activatedPostsExpiration' days
                            if ($today->diffInDays($post->created_at) >= $this->activatedPostsExpiration) {
                                // Archive
                                $post->archived = 1;
                                $post->save();

                                if ($country->active == 1) {
									try {
										// Send an Email confirmation
										$post->notify(new PostArchived($post));
									} catch (\Exception $e) {
										$this->info($e->getMessage() . PHP_EOL);
									}
                                }

                                continue;
                            }
                        }

                        // Auto-delete
                        if ($post->archived == 1) {
                            // Send an email alert to a week of the definitive deletion (using 'updated_at')
                            if ($today->diffInWeeks($post->updated_at->subWeek()) >= 1) {
                                // @todo: Alert user 1 week later
                            }

                            // Send an email alert the day before the final deletion (using 'updated_at')
                            if ($today->diffInDays($post->updated_at->subDay()) >= 1) {
                                // @todo: Alert user 1 day later
                            }

                            // Delete all archived ads '$this->archivedPostsExpiration' days later (using 'updated_at')
                            if ($today->diffInDays($post->updated_at) >= $this->archivedPostsExpiration) {
                                if ($country->active == 1) {
									try {
										// Send an Email confirmation
										$post->notify(new PostDeleted($post));
									} catch (\Exception $e) {
										$this->info($e->getMessage() . PHP_EOL);
									}
                                }

                                // Delete
                                $post->delete();

                                continue;
                            }
                        }
                    }
                }
            }
        }
    }
}
