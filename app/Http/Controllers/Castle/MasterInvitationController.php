<?php

namespace App\Http\Controllers\Castle;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\User;
use App\Notifications\MasterExistingUserInvitation;
use App\Notifications\MasterInvitation;
use App\Rules\Castle\MasterEmailUnique;
use App\Rules\Castle\MasterEmailYourSelf;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class MasterInvitationController extends Controller
{
    public function form()
    {
        return view('castle.masters.invite');
    }

    public function invite()
    {
        $data = Validator::make(request()->all(), [
            'email' => ['required', 'email', 'unique:invitations', new MasterEmailUnique, new MasterEmailYourSelf],
        ], [
            'email.unique' => __('There is a pending invitation for this email.'),
        ])->validate();

        $user = $this->findUser($data['email']);

        $invitation = new Invitation();
        $invitation->forceFill($data);
        $invitation->token   = Uuid::uuid4();
        $invitation->master  = true;
        $invitation->user_id = optional($user)->id;
        $invitation->save();

        $user ? $user->notify(new MasterExistingUserInvitation) : $invitation->notify(new MasterInvitation);

        return back()->with('message', __('The invitation was sent to ') . "<span class='font-bold'>{$data['email']}</span>");
    }

    /**
     * @param $email
     * @return User|null
     */
    private function findUser($email)
    {
        /** @var User $user */
        if ($user = User::query()->where('email', '=', $email)->first()) {
            return $user;
        }

        return null;
    }
}
