<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\User;
use App\Notifications\UserInvitation;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    use VerifiesEmails;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function resendInvitationEmail(User $user)
    {
        
        $invitation = Invitation::whereEmail($user->email)->first();
        
        $invitation->notify(new UserInvitation);
        alert()
            ->withTitle(__("Success"))
            ->withDescription(__("The invitation was sent to {$user->email}"))
            ->send();  
            
        return back();
    }
}
