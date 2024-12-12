<?php

namespace Modules\Foobar\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;


class BazController
{
    public function get()
    {
        return Inertia::render(
            'Foobar::Baz',
            [
                'laravelVersion' => Application::VERSION,
                'phpVersion'     => PHP_VERSION,
            ]
        );
    }
}
