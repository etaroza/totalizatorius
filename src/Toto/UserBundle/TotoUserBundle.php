<?php

namespace Toto\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class TotoUserBundle extends Bundle
{

    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
