<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use Illuminate\Support\Str;
use TCG\Voyager\Facades\Voyager;

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
     * Return view() or other content to display.
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
}
