<?php
namespace Eduxplus\CoreBundle\Lib\Base;

use Eduxplus\CoreBundle\Entity\BaseUser;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\SerializerInterface;

trait Dbtrait
{
    protected static $originalEventListeners=[];

    /**
     * 添加
     *
     * @param $model
     * @param null $name
     * @return mixed
     */
    public function insert($model, $name = null)
    {
        $em = $this->getManagerRegistry()->getManager($name);
        $em = $this->enableSoftDeleteable($em);
        $em->persist($model);
        $em->flush();
        $em->clear($name);
        return $model->getId();
    }


    public function save($model, $name = null)
    {
        $id = $model->getId();
        if ($id) return $this->update($model, $name);
        return $this->insert($model, $name);
    }

    /**
     * 更新或者保存
     *
     * @param $model
     * @param null $name
     * @return mixed
     */
    public function update($model, $name = null)
    {
        $em = $this->getManagerRegistry()->getManager($name);
        $em = $this->enableSoftDeleteable($em);
        $em->persist($model);
        $em->flush();
        $em->clear($name);
        return $model->getId();
    }

    /**
     * 软删除
     *
     * @param $model
     * @param null $name
     * @return bool
     */
    public function delete($models, $name = null)
    {
        $em = $this->getManagerRegistry()->getManager($name);
        $em = $this->enableSoftDeleteable($em);
        if (is_array($models)) {
            foreach ($models as $model) {
                $em->remove($model);
            }
        } else {
            $em->remove($models);
        }
        $em->flush();
        $em->clear($name);
        return true;
    }

    /**
     * dql删除,更新等
     *
     * @param $dql
     * @param null $name
     */
    public function execute($dql, $params = [], $name = null)
    {
        //execute
        $em = $this->getManagerRegistry()->getManager($name);
        $em = $this->enableSoftDeleteable($em);
        $query = $em->createQuery($dql);
        if ($params) $query = $query->setParameters($params);
        $rs = $query->execute();
        $em->clear($name);
        return $rs;
    }

    /**
     * 硬处理
     * @param $dql
     * @param array $params
     * @param null $name
     * @return mixed
     */
    public function hardExecute($dql, $params = [], $name = null)
    {
        //execute
        $em = $this->getManagerRegistry()->getManager($name);
        $em = $this->disableSoftDeleteable($em);
        $query = $em->createQuery($dql);
        if ($params) $query = $query->setParameters($params);
        $rs = $query->execute();
        $em->clear($name);
        return $rs;
    }

    /**
     * 硬删除
     *
     * @param $models
     * @param null $name
     * @return bool
     */
    public function hardDelete($models, $name = null)
    {
        $em = $this->getManagerRegistry()->getManager($name);
        $em = $this->disableSoftDeleteable($em);
        if (is_array($models)) {
            foreach ($models as $model) {
                $em->remove($model);
            }
        } else {
            $em->remove($models);
        }
        $em->flush();
        $em->clear($name);
        return true;
    }

    public function fetchField($field, $dql, $params = [])
    {
        $result = $this->fetchOne($dql, $params);
        $rs = isset($result[$field]) ? $result[$field] : "";
        return $rs;
    }

    public function fetchFields($field, $dql, $params = [])
    {
        $result = $this->fetchAll($dql, $params);
        $rs = $result ? array_column($result, $field) : [];
        return $rs;
    }

    public function fetchAll($dql, $params = [], $getObject = 0, $limit = null, $offset = null, $name = null)
    {
        $em = $this->getManagerRegistry()->getManager($name);
        $em = $this->enableSoftDeleteable($em);
        $query = $em->createQuery($dql);
        if ($params) $query = $query->setParameters($params);

        if ($limit !== null) {
            $query = $query->setMaxResults($limit);
        }

        if ($offset !== null) {
            $query = $query->setFirstResult($offset);
        }

        if (!$getObject) {
            $rs = $query->getArrayResult();
        } else {
            $rs = $query->getResult();
        }
        //        dump($query->getSql());
        return $rs ? $rs : [];
    }


    public function fetchAllHard($dql, $params = [], $getObject = 0, $limit = null, $offset = null, $name = null)
    {
        $em = $this->getManagerRegistry()->getManager($name);
        $em = $this->disableSoftDeleteable($em);
        $query = $em->createQuery($dql);
        if ($params) $query = $query->setParameters($params);

        if ($limit !== null) {
            $query = $query->setMaxResults($limit);
        }

        if ($offset !== null) {
            $query = $query->setFirstResult($offset);
        }

        if (!$getObject) {
            $rs = $query->getArrayResult();
        } else {
            $rs = $query->getResult();
        }
        //        dump($query->getSql());

        return $rs ? $rs : [];
    }

    public function fetchOne($dql, $params = [], $getObject = 0, $name = null)
    {
        $em = $this->getManagerRegistry()->getManager($name);
        $em = $this->enableSoftDeleteable($em);
        $query = $em->createQuery($dql);
        if ($params) $query = $query->setParameters($params);
        $resultType = !$getObject ? 2 : null;
        $rs = $query->setMaxResults(1)->getOneOrNullResult($resultType);
        return $rs ? $rs : [];
    }

    public function fetchOneHard($dql, $params = [], $getObject = 0, $name = null)
    {
        $em = $this->getManagerRegistry()->getManager($name);
        $em = $this->disableSoftDeleteable($em);
        $query = $em->createQuery($dql);
        if ($params) $query = $query->setParameters($params);
        $resultType = !$getObject ? 2 : null;
        $rs = $query->setMaxResults(1)->getOneOrNullResult($resultType);
        return $rs ? $rs : [];
    }


    /**
     * dbal conn
     *
     * @return \Doctrine\DBAL\Driver\Connection
     */
    public function conn($name = null)
    {
        $conn = $this->getManagerRegistry()->getManager($name)->getConnection();
        return  $conn;
    }

    /**
     * 原生sql，多个值
     * @param $sql  eg "SELECT * FROM mall_msg WHERE uid =?" or "SELECT * FROM mall_msg WHERE uid =:uid"
     * @param array $params eg [1] or [":uid"=>uid]
     * @param array $types
     * @param null $name
     * @return mixed
     */
    public function fetchAllBySql($sql, array $params = [],$name = null){
        $conn = $this->conn($name);
        $result = $conn->fetchAll($sql, $params);
        return $result;
    }

    /**
     * 原生sql，第一个值
     * @param $sql
     * @param array $params
     * @param array $types
     * @param null $name
     * @return mixed
     */
    public function fetchAssocBySql($sql, array $params = [],$name = null){
        $conn = $this->conn($name);
        $result = $conn->fetchAssoc($sql, $params);
        return $result;
    }

    /**
     * 原生sql，第一行，单个字段值
     * @param $sql  eg "SELECT * FROM Core:MallMsg WHERE uid =?";
     * @param array $params eg ["Core:MallMsg"]
     * @param array $types
     * @param null $name
     * @return mixed
     */
    public function fetchColumnBySql($sql, array $params = [], $types = [],$name = null){
        $conn = $this->conn($name);
        $result = $conn->fetchcolumn($sql, $params);
        return $result;
    }


    /**
     * @param $sql
     * @param array $params
     * @param null $name
     * @return string|string[]
     */
    public function formatTableClass($sql, $params=[], $name=null){
        if(!$params) return $sql;
        $em = $this->getManagerRegistry()->getManager($name);
        foreach ($params as $v){
            $tableName = $em->getClassMetadata($v)->getTableName();
            if($tableName) $sql = str_replace($v, $tableName, $sql);
        }
        $em->clear($name);
        return $sql;
    }

    /**
     * @param $dbTableClassName eg 'Core:BaseUser'
     * @param null $name
     */
    public function getTableName($dbTableClassName, $name=null){
        $em = $this->getManagerRegistry()->getManager($name);
        $tableName = $em->getClassMetadata($dbTableClassName)->getTableName();
        $em->clear($name);
        return $tableName;
    }

    public function beginTransaction($name = null)
    {
        $em = $this->getManagerRegistry()->getManager($name);
        $em->getConnection()->beginTransaction();
        return $em;
    }

    public function commit($name = null)
    {
        $em = $this->getManagerRegistry()->getManager($name);
        $em->getConnection()->commit();
        return $em;
    }

    public function rollback($name = null)
    {
        $em = $this->getManagerRegistry()->getManager($name);
        $em->getConnection()->rollback();
        return $em;
    }


    protected function disableSoftDeleteable($em)
    {
        foreach ($em->getEventManager()->getListeners() as $eventName => $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof \Gedmo\SoftDeleteable\SoftDeleteableListener) {
                    self::$originalEventListeners[$eventName] = $listener;
                    $em->getEventManager()->removeEventListener($eventName, $listener);
                }
            }
        }
        if ($em->getFilters()->isEnabled("softdeleteable")) {
            $em->getFilters()->disable('softdeleteable');
        }
        return $em;
    }

    protected function enableSoftDeleteable($em)
    {
        foreach (self::$originalEventListeners as $eventName => $listener) {
            $em->getEventManager()->addEventListener($eventName, $listener);
        }

        if (!$em->getFilters()->isEnabled("softdeleteable")) {
            $em->getFilters()->enable('softdeleteable');
        }
        return $em;
    }
}
