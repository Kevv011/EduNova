<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Support\Header;

class CustomExceptionInertia extends Exception
{
    public function render(Request $request)
    {
        if ($request->header(Header::INERTIA)) {
            return Redirect::back()->with('flash', [
                'banner' => $this->message,
                'bannerStyle' => 'error'
            ]);
        }

        return response()->json([
            'message' => $this->message,
        ], $this->code);
    }
}
