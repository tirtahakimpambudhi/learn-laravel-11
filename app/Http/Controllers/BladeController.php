<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Illuminate\View\View;


class BladeController extends Controller
{
    function htmlEncoding(Request $request) :View {
        $tagHtml = $request->query->get('tag');
        return view('xss-view', ['tag' => $tagHtml]);
    }

    function disableBladeSyntax() :View {
        return view('disable-blade', [ 'testing' => 'testing the disable blade']);
    }

    function conditionStatement(Request $request) :View {
        $ifQuery =  $request->query->getBoolean('if');
        $unlessQuery = $request->query->getBoolean('unless');
        return view('conditional-statement', [
            'ifQuery' => $ifQuery,
            'unlessQuery' => $unlessQuery,
            'switchQuery' =>  $request->query->getString('case'),
        ]);
    }

    function passValueToJS () :View {
        return view('render-json', [
            'data' => [
                [
                    '_id' => 1,
                    'name' => 'John Doe',
                    'createdAt' => Date::now()->toDateTimeString(),
                    'updatedAt' => Date::now()->toDateTimeString(),
                ],
                [
                    '_id' => 2,
                    'name' => 'Foo Bar',
                    'createdAt' => Date::now()->toDateTimeString(),
                    'updatedAt' => Date::now()->toDateTimeString(),
                ]
            ]
        ]);
    }

    function loopStatement(Request $request) :View
    {
        $totalItems = $request->query->getInt('total');
        $users = [];
        $orders = [];
        for ($i = 0; $i < $totalItems; $i++) {
            $users[] = [
                '_id' => Str::ulid(),
                'name' => fake()->name,
                'email' => fake()->email,
                'createdAt' => Date::now()->toDateTimeString(),
                'updatedAt' => Date::now()->toDateTimeString(),
            ];

            $orders[] = [
                '_id' => Str::ulid(),
                'name' => fake()->city(),
                'createdAt' => Date::now()->toDateTimeString(),
                'updatedAt' => Date::now()->toDateTimeString(),
            ];
        }
        return view('loop-statement', [
            'totalItems' => $totalItems,
            'users' => $users,
            'orders' => $orders,
        ]);
    }

    function classCondition(Request $request) : View
    {
        $color = $request->query->getString('color');
        $isPrimary = $request->query->getBoolean('isPrimary');
        $isDisabled = $request->query->getBoolean('isDisabled');
        return view('class-conditional', [
            'color' => $color,
            'isPrimary' => $isPrimary,
            'isDisabled' => $isDisabled,
        ]);
    }
}
