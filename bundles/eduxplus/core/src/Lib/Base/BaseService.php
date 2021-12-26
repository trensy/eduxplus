<?php

namespace Eduxplus\CoreBundle\Lib\Base;

use Doctrine\Persistence\ManagerRegistry;
use Eduxplus\CoreBundle\Entity\BaseUser;
use Eduxplus\CoreBundle\Lib\Base\Error;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\UsageTrackingTokenStorage;
use Psr\Container\ContainerInterface;

class BaseService
{
    use Dbtrait;
    protected $em;
    protected $serializer;
    protected $requestStack;
    protected $router;
    protected $params;
    protected $propertyAccessor;
    protected $tokenStorage;
    protected $container;

    public function inject(ManagerRegistry $em,
                         SerializerInterface $serializer,
                         RequestStack $requestStack,
                         UrlGeneratorInterface $router,
                         ContainerBagInterface $params,
                         PropertyAccessorInterface   $propertyAccessor,
                           UsageTrackingTokenStorage $tokenStorage,
                           ContainerInterface $container
    ){
        $this->em = $em;
        $this->serializer = $serializer;
        $this->requestStack = $requestStack;
        $this->router = $router;
        $this->params = $params;
        $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
        $this->tokenStorage= $tokenStorage;
        $this->container =$container;
    }

    public function get(string $id): object
    {
        return $this->container->get($id);
    }

    public function error()
    {
        return new Error();
    }

    public function getManagerRegistry(){
        return $this->em;
    }

    public function getDoctrine(){
        return $this->getManagerRegistry();
    }

    public function getSerializer(){
        return $this->serializer;
    }

    public function genUrl(string $route, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->router->generateUrl($route, $parameters, $referenceType);
    }

    public function getUser():?UserInterface
    {
        $token = $this->tokenStorage->getToken();
        return $token->getUser();
    }

    /**
     * @return Request
     */
    public function request()
    {
        return $this->requestStack->getCurrentRequest();
    }

    public function session()
    {
        return $this->request()->getSession();
    }

    public function getConfig($str)
    {
        return $this->params->get($str);
    }

    public function getBasePath()
    {
        return $this->getConfig("kernel.project_dir");
    }

    public function getPro($obj, $name)
    {
        return $this->propertyAccessor->getValue($obj, $name);
    }

    public function getEnv()
    {
        $env = $_SERVER['APP_ENV'];
        return $env;
    }


    public function baseCurlGet($url, $method, $body="")
    {
        //        debug(func_get_args());
        $method = strtoupper($method);
        $ch = curl_init();

        if ($method == 'POST' || $method == 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            if($body) curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if (substr($url, 0, 5) == 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        $rtn = curl_exec($ch);

        if ($rtn === false) {
            // 大多由设置等原因引起，一般无法保障后续逻辑正常执行，
            // 所以这里触发的是E_USER_ERROR，会终止脚本执行，无法被try...catch捕获，需要用户排查环境、网络等故障
            trigger_error("[CURL_" . curl_errno($ch) . "]: " . curl_error($ch), E_USER_ERROR);
        }
        curl_close($ch);

        return $rtn;
    }

    public function jsonGet($json, $key = 0)
    {
        if (!$json) return "";
        $arr = json_decode($json, true);
        return isset($arr[$key]) ? $arr[$key] : "";
    }

}