<?php

namespace PharmIT\Push;

use Illuminate\Support\Facades\Facade;

class PushServiceFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'Push';
    }
}
