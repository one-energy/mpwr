<?php

namespace App\Http\Controllers\Castle;

use App\Http\Controllers\Controller;
use App\Notifications\WelcomeToTheCastle;
use Illuminate\Support\Facades\Validator;

class ResponseMasterInvitationController extends Controller
{
    public function __invoke()
    {
        Validator::make(request()->all(), [
            'notification' => ['required', 'exists:notifications,id'],
            'response'     => ['required', 'boolean'],
        ])->validate();


        $notification = user()->unreadNotifications()->where('id', '=', request()->notification)->first();
        $invitation   = user()->invitations()->where('master', '=', true)->firstOrFail();

        $invitation->delete();
        $notification->markAsRead();

        if (request()->response) {
            user()->beCastleMaster();
            user()->notify(new WelcomeToTheCastle);

            return redirect()->route('castle.dashboard');
        }

        return back();
    }
}
