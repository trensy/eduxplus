<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/4 20:41
 */

namespace App\Bundle\AppBundle\Lib\Base;

use Psr\Log\LoggerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class BaseService extends AbstractFOSRestController
{

    public function error(){
        return new Error();
    }


    /**
     * @return Request
     */
    public function request(){
        $requestStack = $this->get("request_stack");
        return $requestStack->getCurrentRequest();
    }

    /**
     * @return LoggerInterface
     */
    public function logger(){
        return $this->get("logger");
    }

    /**
     * dbal conn
     *
     * @return \Doctrine\DBAL\Driver\Connection
     */
    public function conn($name=null){
        $conn = $this->getDoctrine()->getManager($name)->getConnection();
        return  $conn;
    }

    /**
     * 获取项目路径
     *
     * @return void
     */
    public function getBasePath(){
        return $this->getParameter("kernel.project_dir");
    }

    public function getUid(){
        $user = $this->getUser();
        if($user) return $user->getId();
        return 0;
    }


    public function getFormatRequestSql($request){
        $fields = $request->query->all();
        if(!isset($fields['operates'])||!isset($fields['types'])||!isset($fields['values'])) return "";
        $operates = $fields['operates'];
        $types = $fields['types'];
        $values = $fields['values'];
        $sql = "";
        if($values){
            $sql .= " WHERE ";
            foreach ($values as $k=>$v){
                if( ($v==="") || (substr($k, 0, 1)=="_") || ($v == -1)){
                    continue;
                }

                if($types[$k] === "text"){
                    if($operates[$k] == "like"){
                        $sql .= $k . " like '%".$v."%'";
                    }else{
                        $sql .= $k . " = '".$v."' ";
                    }
                }elseif($types[$k] === "number"){
                    $sql .= $k . " {$operates[$k]} '".$v."' ";
                }elseif($types[$k] === "daterange" || $types[$k] === "datetimerange"){
                    list($startDate, $endDate) = explode(" - ", $v);
                    $sql .= $k . " {$operates[$k]} '{$startDate}' AND '{$endDate}' ";

                }else{
                    $sql .= $k . " = '".$v."' ";
                }

                $sql .= " AND ";
            }
        }
        if($sql == " WHERE ") return "";
        $sql = rtrim($sql, " AND ");
        dump($sql);
        return $sql;
    }

    /**
     * 添加
     *
     * @param $model
     * @param null $name
     * @return mixed
     */
    public function insert($model, $name=null){
        $entityManage = $this->getDoctrine()->getManager($name);
        $entityManage->persist($model);
        $entityManage->flush();
        // $entityManage->clear($name);
        return $model->getId();
    }


    public function save($model, $name=null){
        $id = $model->getId();
        if($id) return $this->update($model, $name);
        return $this->insert($model, $name);
    }

    /**
     * 更新或者保存
     *
     * @param $model
     * @param null $name
     * @return mixed
     */
    public function update($model, $name=null){
        $entityManage = $this->getDoctrine()->getManager($name);
        $entityManage->persist($model);
        $entityManage->flush();
        // $entityManage->clear($name);
        return $model->getId();
    }

    /**
     * 软删除
     *
     * @param $model
     * @param null $name
     * @return bool
     */
    public function delete($models, $name=null){
        $entityManage = $this->getDoctrine()->getManager($name);
        if(is_array($models)){
            foreach ($models as $model){
                $entityManage->remove($model);
            }
        }else{
            $entityManage->remove($models);
        }
        $entityManage->flush();
        // $entityManage->clear($name);
        return true;
    }

    /**
     * dql删除,更新等
     *
     * @param $dql
     * @param null $name
     */
    public function execute($dql, $params=[], $name=null){
        //execute
        $em = $this->getDoctrine()->getManager($name);
        $query = $em->createQuery($dql);
        if($params) $query= $query->setParameters($params);
        $rs = $query->execute();
        return $rs;
    }

    /**
     * 硬处理
     * @param $dql
     * @param array $params
     * @param null $name
     * @return mixed
     */
    public function hardExecute($dql, $params=[], $name=null){
        //execute
        $em = $this->getDoctrine()->getManager($name);

        foreach ($em->getEventManager()->getListeners() as $eventName => $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof \Gedmo\SoftDeleteable\SoftDeleteableListener) {
                    $em->getEventManager()->removeEventListener($eventName, $listener);
                }
            }
        }

        $query = $em->createQuery($dql);
        if($params) $query= $query->setParameters($params);
        $rs = $query->execute();
        return $rs;
    }

    /**
     * 硬删除
     *
     * @param $models
     * @param null $name
     * @return bool
     */
    public function hardDelete($models, $name=null){
        $entityManage = $this->getDoctrine()->getManager($name);
        foreach ($entityManage->getEventManager()->getListeners() as $eventName => $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof \Gedmo\SoftDeleteable\SoftDeleteableListener) {
                    $entityManage->getEventManager()->removeEventListener($eventName, $listener);
                }
            }
        }
        if(is_array($models)){
            foreach ($models as $model){
                $entityManage->remove($model);
            }
        }else{
            $entityManage->remove($models);
        }
        $entityManage->flush();
        // $entityManage->clear($name);
        return true;
    }

    public function fetchField($field,$dql, $params=[]){
        $result = $this->fetchOne($dql, $params);
        $rs = isset($result[$field])?$result[$field]:"";
        return $rs;
    }

    public function fetchFields($field,$dql, $params=[]){
        $result = $this->fetchAll($dql, $params);
        $rs = $result?array_column($result, $field):[];
        return $rs;
    }

    public function fetchAll($dql, $params=[], $getObject=0, $name=null){
        $em = $this->getDoctrine()->getManager($name);
        $query = $em->createQuery($dql);
        if($params) $query= $query->setParameters($params);
        if(!$getObject){
            $rs = $query->getArrayResult();
        }else{
            $rs = $query->getResult();
        }
//            dump(get_class_methods($query));
//        dump($query->getSql());
        return $rs;
    }


    public function fetchOne($dql, $params=[], $getObject=0, $name=null){
        $em = $this->getDoctrine()->getManager($name);
        $query = $em->createQuery($dql);
        if($params) $query= $query->setParameters($params);
        $resultType = !$getObject?2:null;
        $rs = $query->setMaxResults(1)->getOneOrNullResult($resultType);
        return $rs;
    }

    public function getOption($k, $isJson=0, $index=0, $default=null){
        $sql = "SELECT a.optionValue FROM App:BaseOption a WHERE a.optionKey =:optionKey";
        $rs = $this->fetchField("optionValue", $sql, ['optionKey'=>$k]);

        if($rs){
            if($isJson){
               $arr =  json_decode($rs, 1);
               return isset($arr[$index])?$arr[$index]:"";
            }
            return $rs;
        }else{
            return $default;
        }
    }


    public function isAuthorized($routeName){
        $uid = $this->getUid();
        if(!$uid) return false;

        $sql = "SELECT a FROM App:BaseMenu a WHERE a.url=:url";
        $menuInfo = $this->fetchOne($sql, ['url'=>$routeName]);
        if(!$menuInfo) return false;
        $menuId = $menuInfo['id'];

        $userSql = "SELECT a.roleId FROM App:BaseRoleUser a WHERE a.uid=:uid";
        $roleIds = $this->fetchFields("roleId", $userSql, ['uid'=>$uid]);
        if(!$roleIds) return false;

        $sql = "SELECT a.menuId FROM App:BaseRoleMenu a WHERE a.roleId IN(:roleId) ";
        $menuIds = $this->fetchFields("menuId", $sql, ['roleId'=>$roleIds]);
        if(!$menuIds) return false;
        return in_array($menuId, $menuIds);
    }

    public function toArray($model){
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $json = $serializer->serialize($model, 'json');
        return json_decode($json, true);
    }

    public function getPro($obj, $name){

        $method = "get".ucfirst($name);

        if(method_exists($obj, $method)) {
            $rs = call_user_func([$obj, $method]);
            return $rs;
        }else{
            if(is_array($obj)){
                $rs = isset($obj[$name])?$obj[$name]:"";
                return $rs;
            }
        }
    }

    public function getInitialPreviewConfig($path){
        if(!$path) return [];
        $initialPreviewConfig = [];
        $path = \GuzzleHttp\json_decode($path,1);
        foreach ($path as $v){
            $fileInfo = $this->getFileInfo($v);
//            dump($fileInfo);
            $type = stristr($fileInfo['mime'], "image")?"image":"other";
            $tmp=[];
            $tmp['type'] = $type;
            $tmp['caption'] = $fileInfo['filename'];
            $tmp['size'] = $fileInfo['length'];
            $initialPreviewConfig[] = $tmp;
        }
//        dump($initialPreviewConfig);
        return \GuzzleHttp\json_encode($initialPreviewConfig);
    }

    protected function getFileInfo($strUrl){
        if(!stristr($strUrl, "http")){
            $domain = $this->getOption("app.domain");
            $strUrl = trim($domain, "/").$strUrl;
        }

        $arrRet = [];
        if(($arrTmp=get_headers($strUrl,true))){
//            dump($arrTmp);
            $arrRet=array("length"=>$arrTmp['Content-Length'],"mime"=>$arrTmp['Content-Type']);
            $arrRet["filename"]= pathinfo($strUrl, PATHINFO_FILENAME).".".pathinfo($strUrl, PATHINFO_EXTENSION);
            if(preg_match('/\s(\d+)\s/',$arrTmp[0],$arr)){
                $arrRet["status"]=$arr[1];
            }
        }

        return $arrRet;
    }

}
