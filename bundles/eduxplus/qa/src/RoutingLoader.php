<?php

namespace Eduxplus\QaBundle;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

class RoutingLoader extends Loader
{

    public function load($resource, $type = null)
    {
        $collection = new RouteCollection();
        $resource = "./Resources/config/routes.yaml";
        $importedRoutes = $this->import($resource, 'yaml');
        $collection->addCollection($importedRoutes);
        return $collection;
    }

    public function supports($resource, $type = null)
    {
        return 'extra' === $type;
    }
}
