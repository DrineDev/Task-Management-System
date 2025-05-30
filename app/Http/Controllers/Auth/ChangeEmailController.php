<?php

namespace App\Http\Controllers\Auth;

use App\http\Controllers\Controller;

class ChangeEmailController extends Controller {

    public function showChangeEmail() {
        return view('auth.change-email');
    }
}
