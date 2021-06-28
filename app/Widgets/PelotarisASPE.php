<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use Illuminate\Support\Str;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\Auth;

class PelotarisASPE extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or otheuse Illuminate\Support\Facades\Auth;r content to display.
     */
    public function run()
    {
        $count = \App\PelotarisAspe::count();
        $string = 'Pelotaris de ASPE';

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-group',
            'title'  => "{$count} {$string}",
            'text'   => "Tiene {$count} {$string} en su base de datos. Haga click en el botón de abajo para ver todos los {$string}.",
            'button' => [
                'text' => "Ver todos los {$string}",
                'link' => '/admin/pelotaris-aspe',
            ],
            'image' => 'images/pelotaris_aspe.jpg',
        ]));
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
        return Auth::user()->can('browse', Voyager::model('User'));
    }
}
