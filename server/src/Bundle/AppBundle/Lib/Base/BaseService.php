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

class BaseService extends AbstractFOSRestController
{

    public function error(){
        return new Error();
    }

    /**
     * @return LoggerInterface
     */
    public function logger(){
        return $this->get("logger");
    }

    public function getParameter(string $name)
    {
        return parent::getParameter($name); // TODO: Change the autogenerated stub
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
        dump($fields);
        $sql = "";
        if($values){
            $sql .= " WHERE ";
            foreach ($values as $k=>$v){
                if($v===""){
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
                }else{
                    $sql .= $k . " = '".$v."' ";
                }

                $sql .= " AND ";
            }
        }
        $sql = rtrim($sql, " AND ");
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
        $entityManage->flush();
        return $model->getId();
    }

    /**
     * 删除
     *
     * @param $model
     * @param null $name
     * @return bool
     */
    public function delete($model, $name=null){
        $entityManage = $this->getDoctrine()->getManager($name);
        $entityManage->remove($model);
        $entityManage->flush();
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

}
