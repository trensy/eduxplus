<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/8 13:43
 */

namespace App\Bundle\AdminBundle\Service;


use App\Bundle\AppBundle\Lib\Base\BaseService;
use App\Bundle\AppBundle\Lib\Service\HelperService;
use App\Entity\BaseRoleUser;
use App\Entity\BaseUser;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService extends BaseService
{

    protected $paginator;
    protected $userPasswordEncoder;

    public function __construct(PaginatorInterface $paginator, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->paginator = $paginator;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function userList($request, $page, $pageSize){
        $sql = $this->getFormatRequestSql($request);
        $values = $request->get("values");
        dump($values);
        $isAdmin = (int) (isset($values['_isAdmin'])?$values['_isAdmin']:-1);
        if($isAdmin === 1){
            if($sql){
                $sql .= " AND a.roles LIKE '%ROLE_ADMIN%'";
            }else{
                $sql = " WHERE a.roles LIKE '%ROLE_ADMIN%'";
            }
        }elseif($isAdmin === 0){
            if($sql) {
                $sql .= " AND a.roles NOT LIKE '%ROLE_ADMIN%'";
            }else{
                $sql .= " WHERE a.roles NOT LIKE '%ROLE_ADMIN%'";
            }
        }

        $dql = "SELECT a FROM App:BaseUser a " . $sql;
        dump($dql);
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
        $pagination = $this->paginator->paginate(
            $query,
            $page,
            $pageSize
        );

        $items = $pagination->getItems();
        $itemsArr = [];
        if($items){
            foreach ($items as $v){
                $vArr =  $this->toArray($v);
                $roles = $vArr['roles'];
                $isAdmin  =  in_array("ROLE_ADMIN", $roles);
                $vArr['isAdmin'] = $isAdmin;
                $itemsArr[] = $vArr;
            }
        }
        return [$pagination, $itemsArr];
    }

    public function checkDisplayName($name, $id=0){
        $sql = "SELECT a FROM App:BaseUser a where a.displayName =:displayName ";
        $params = [];
        $params['displayName'] = $name;
        if($id){
            $sql = $sql." AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->fetchOne($sql, $params);
    }

    public function checkFullName($name, $id=0){
        $sql = "SELECT a FROM App:BaseUser a where a.fullName =:fullName ";
        $params = [];
        $params['fullName'] = $name;
        if($id){
            $sql = $sql." AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->fetchOne($sql, $params);
    }

    public function checkMobile($name, $id=0){
        $sql = "SELECT a FROM App:BaseUser a where a.mobile =:mobile ";
        $params = [];
        $params['mobile'] = $name;
        if($id){
            $sql = $sql." AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->fetchOne($sql, $params);
    }

    public function add($mobile, $displayName, $pwd1, $fullName, $sex, $roles){
        $helperService = new HelperService();
        $uuid = $helperService->getUuid();
        $model = new BaseUser();
        $model->setMobile($mobile);
        $model->setUuid($uuid);
        $model->setGravatar("/assets/images/defaultFace.jpeg");
        $pwd = $this->userPasswordEncoder->encodePassword($model, $pwd1);
        $model->setPassword($pwd);
        $model->setFullName($fullName);
        $model->setDisplayName($displayName);
        $model->setSex($sex);
        $model->setRegSource("admin");
        if($roles){
            $model->setRoles(['ROLE_ADMIN']);
        }
        $uid = $this->save($model);

        if($roles){
            foreach ($roles as $roleId){
                $modelRole = new BaseRoleUser();
                $modelRole->setRoleId($roleId);
                $modelRole->setUid($uid);
                $this->save($modelRole);
            }
        }
        return $uid;
    }

    public function getById($id){
        $sql = "SELECT a FROM App:BaseUser a WHERE a.id=:id";
        return $this->fetchOne($sql, ['id'=>$id]);
    }

    public function getMyRoleIds($uid){
        $sql = "SELECT a FROM App:BaseRoleUser a WHERE a.uid=:uid";
        return $this->fetchFields('roleId', $sql, ['uid'=>$uid]);
    }

    public function edit($id, $mobile, $displayName, $fullName, $sex, $roles){
        $sql = "SELECT a FROM App:BaseUser a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id'=>$id], 1);
        $model->setMobile($mobile);
        $model->setFullName($fullName);
        $model->setDisplayName($displayName);
        $model->setSex($sex);
        if($roles){
            $model->setRoles(['ROLE_ADMIN']);
        }else{
            $model->setRoles(['ROLE_USER']);
        }
        $uid = $this->save($model);

        if($roles){
            //先删除
            $sql = "SELECT a FROM App:BaseRoleUser a WHERE a.uid=:uid";
            $models = $this->fetchAll($sql, ['uid'=>$id], 1);
            $this->hardDelete($models);
            //后添加
            foreach ($roles as $roleId){
                $modelRole = new BaseRoleUser();
                $modelRole->setRoleId($roleId);
                $modelRole->setUid($uid);
                $this->save($modelRole);
            }
        }
        return $uid;
    }

    public function delUser($id){
        $sql = "SELECT a FROM App:BaseUser a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ["id"=>$id], 1);
        $this->delete($model);
    }

    public function switchLock($id, $state){
        $sql = "SELECT a FROM App:BaseUser a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id'=>$id], 1);
        $model->setIsLock($state);
        return $this->save($model);
    }

}
