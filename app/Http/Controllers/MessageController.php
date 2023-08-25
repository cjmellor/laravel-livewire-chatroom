<?php

namespace App\Http\Controllers;

use App\Concerns\CanTransform;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    use CanTransform;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'message' => 'required',
        ]);

        $request
            ->user()
            ->messages()
            ->create([
                'message' => $this->transform($request->message),
            ]);
    }
}
