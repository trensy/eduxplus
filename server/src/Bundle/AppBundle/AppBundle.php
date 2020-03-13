<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/3 20:20
 */

namespace App\Bundle\AppBundle;

use App\Bundle\AppBundle\DependencyInjection\AppExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{

    public function getContainerExtension()
    {
        return new AppExtension();
    }

}
