<?php

namespace App\Http\Controllers\Account;

use App\Helpers\Arr;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Notifications\ReplySent;
use Illuminate\Support\Facades\Input;
use Torann\LaravelMetaTags\Facades\MetaTag;

class MessagesController extends AccountBaseController
{
    private $perPage = 10;

    public function __construct()
    {
        parent::__construct();

        $this->perPage = (is_numeric(config('settings.posts_per_page'))) ? config('settings.posts_per_page') : $this->perPage;
    }

    /**
     * List Messages
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data = [];
        $data['messages'] = $this->messages->paginate($this->perPage);

        view()->share('pagePath', 'messages');

        // Meta Tags
        MetaTag::set('title', t('Messages Received'));
        MetaTag::set('description', t('Messages Received on :app_name', ['app_name' => config('settings.app_name')]));

        return view('account.messages', $data);
    }

    public function reply($messageId, Request $request)
    {
        // Form validation
        $rules = ['message' => 'required|mb_between:20,500'];
        $this->validate($request, $rules);

        // Get Message
        $message = Message::find($messageId);
        if (empty($message)) {
            abort(404);
        }

        // Store Reply Info
        $replyForm = [
            'country_code' => $this->country->get('code'),
            'country'      => $this->country->get('name'),
            'post_title'   => $request->input('post_title'),
            'sender_name'  => $request->input('sender_name'),
            'sender_email' => $request->input('sender_email'),
            'sender_phone' => $request->input('sender_phone'),
            'message'      => $request->input('message'),
        ];
        $replyForm = Arr::toObject($replyForm);

        // Send Reply Email
        try {
            $message->notify(new ReplySent($message, $replyForm));
            flash(t("Your reply has been sent. Thank you!"))->success();

            // Mark as replied
            $message->reply_sent = 1;
            $message->save();

        } catch (\Exception $e) {
            flash($e->getMessage())->error();
        }

        return back();
    }

    /**
     * Delete Messages
     *
     * @param null $messageId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($messageId = null)
    {
        // Get Entries ID
        $ids = [];
        if (Input::filled('message')) {
            $ids = Input::get('message');
        } else {
            $id = $messageId;
            if (!is_numeric($id) && $id <= 0) {
                $ids = [];
            } else {
                $ids[] = $id;
            }
        }

        // Delete
        $nb = 0;
        foreach ($ids as $id) {
            $message = Message::find($id);
            if (!empty($message)) {
                // Delete Message
                $nb = $message->delete();
            }
        }

        // Confirmation
        if ($nb == 0) {
            flash(t("No deletion is done. Please try again."))->error();
        } else {
            $count = count($ids);
            if ($count > 1) {
                flash(t("x :entities has been deleted successfully.", ['entities' => t('messages'), 'count' => $count]))->success();
            } else {
                flash(t("1 :entity has been deleted successfully.", ['entity' => t('message')]))->success();
            }
        }

        return redirect(config('app.locale') . '/account/messages');
    }
}
