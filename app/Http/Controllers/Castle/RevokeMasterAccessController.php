<?php

namespace App\Http\Controllers\Castle;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\YourAccessToTheCastleWasRevoked;

class RevokeMasterAccessController extends Controller
{
    public function __invoke(User $master)
    {
        if (user()->is($master)) {
            return back()->with('error', __('You can\'t revoke your own access.'));
        }

        $master->revokeMastersAccess();

        $master->notify(new YourAccessToTheCastleWasRevoked);

        alert()->withTitle(__('Access revoked!'))->send();

        return back();
    }
}
