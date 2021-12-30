<?php

namespace Eduxplus\CoreBundle\DataFixtures\Fixtures;

use Eduxplus\CoreBundle\Entity\BaseMenu;
use Eduxplus\CoreBundle\Entity\BaseRoleMenu;
use Eduxplus\CoreBundle\Lib\Base\BaseService;

class MenuFixtures
{
    
    protected $baseService;

    public function __construct(BaseService $baseService)
    {
        $this->baseService = $baseService;
    }

    public function load($roleId)
    {

        //新增菜单并绑定角色
        $this->addMenu("首页", "首页", 0, "admin_index", "mdi-home", 0, $roleId, 1, 1, 1, 1);
        $this->addMenu("首页", "后台首页", 0, "admin_dashboard", "mdi-home", 0, $roleId, 1, 0, 1, 1);
        $this->addMenu("文件上传", "文件上传处理", 0, "admin_glob_upload", "mdi-upload", 1, $roleId, 1, 1, 0, 1);
        $this->addMenu("搜索用户名", "搜索用户名", 0, "admin_api_glob_searchUserDo", "", 2, $roleId, 1, 1, 0, 1);
        $this->addMenu("搜索管理员", "搜索管理员", 0, "admin_api_glob_searchAdminUserDo", "", 3, $roleId, 1, 1, 0, 1);

        $this->addMenu("获取阿里云点播视频上传地址和凭证", "获取阿里云点播视频上传地址和凭证", 0, "admin_api_glob_aliyunVodCreateUploadVideoDo", "", 6, $roleId, 1, 1, 0, 1);
        $this->addMenu("阿里云刷新视频上传凭证", "阿里云刷新视频上传凭证", 0, "admin_api_glob_aliyunVodRefreshUploadVideoDo", "", 7, $roleId, 1, 1, 0, 1);
        $this->addMenu("阿里云点播播放信息", "阿里云点播播放信息", 0, "admin_api_glob_getAliyunVodPlayInfoDo", "", 8, $roleId, 1, 1, 0, 1);

        $this->addMenu("获取腾讯云点播视频上传凭证", "获取腾讯云点播视频上传凭证", 0, "admin_api_glob_tengxunyunSignatureDo", "", 9, $roleId, 1, 1, 0, 1);
        $this->addMenu("获取腾讯云点播播放网址加密", "获取腾讯云点播播放网址加密", 0, "admin_api_glob_tengxunyunVodEncryptionPlayUrlDo", "", 10, $roleId, 1, 1, 0, 1);
        $this->addMenu("获取腾讯云点播超级播放器签名", "获取腾讯云点播超级播放器签名", 0, "admin_api_glob_tengxunyunVodAndvancePlaySignDo", "", 11, $roleId, 1, 1, 0, 1);
        //安全模块
        $accMenuId = $this->addMenu("安全", "安全方面的管理", 0, "", "mdi-key", 2, $roleId, 1, 0, 1);
        //菜单
        $menuMgId = $this->addMenu("菜单管理", "管理菜单以及对应页面的权限", $accMenuId, "admin_menu_index", "", 3, $roleId, 1, 0, 1);
        $this->addMenu("添加菜单页面", "菜单新增页面", $menuMgId, "admin_menu_add", "", 0, $roleId, 1, 1, 0);
        $this->addMenu("添加菜单", "菜单新增处理", $menuMgId, "admin_api_menu_add", "", 1, $roleId, 1, 1, 0);
        $this->addMenu("查看菜单页面", "菜单展示页面", $menuMgId, "admin_menu_view", "", 2, $roleId, 1, 1, 0);
        $this->addMenu("编辑菜单页面", "菜单编辑展示页面", $menuMgId, "admin_menu_edit", "", 3, $roleId, 1, 1, 0);
        $this->addMenu("编辑菜单", "菜单编辑处理", $menuMgId, "admin_api_menu_edit", "", 4, $roleId, 1, 1, 0);
        $this->addMenu("删除菜单", "删除菜单", $menuMgId, "admin_api_menu_delete", "", 5, $roleId, 1, 1, 0);
        $this->addMenu("更新菜单排序", "更新菜单排序", $menuMgId, "admin_api_menu_updateSort", "", 6, $roleId, 1, 1, 0);
        //角色
        $roleMgId = $this->addMenu("角色管理", "管理角色", $accMenuId, "admin_role_index", "", 1, $roleId, 1, 0, 1);
        $this->addMenu("添加角色页面", "显示添加角色页面", $roleMgId, "admin_role_add", "", 0, $roleId, 1, 1, 0);
        $this->addMenu("添加角色", "添加角色处理", $roleMgId, "admin_api_role_add", "", 1, $roleId, 1, 1, 0);
        $this->addMenu("编辑角色页面", "显示编辑角色页面", $roleMgId, "admin_role_edit", "", 2, $roleId, 1, 1, 0);
        $this->addMenu("编辑角色", "编辑角色处理", $roleMgId, "admin_api_role_edit", "", 3, $roleId, 1, 1, 0);
        $this->addMenu("删除角色", "删除角色处理", $roleMgId, "admin_api_role_delete", "", 4, $roleId, 1, 1, 0);
        $this->addMenu("批量删除角色", "批量删除角色处理", $roleMgId, "admin_api_role_batchdelete", "", 5, $roleId, 1, 1, 0);
        $this->addMenu("角色绑定菜单页面", "显示角色绑定菜单页面", $roleMgId, "admin_role_bindmenu", "", 6, $roleId, 1, 1, 0);
        $this->addMenu("角色绑定菜单", "角色绑定菜单处理", $roleMgId, "admin_api_role_bindmenu", "", 7, $roleId, 1, 1, 0);
        //用户
        $userMgId = $this->addMenu("用户管理", "管理用户", $accMenuId, "admin_user_index", "", 2, $roleId, 1, 0, 1);
        $this->addMenu("添加页面", "显示添加用户页面", $userMgId, "admin_user_add", "", 0, $roleId, 1, 1, 0);
        $this->addMenu("添加用户", "添加用户处理", $userMgId, "admin_api_user_add", "", 1, $roleId, 1, 1, 0);
        $this->addMenu("查看用户页面", "显示用户页面", $userMgId, "admin_user_view", "", 2, $roleId, 1, 1, 0);
        $this->addMenu("编辑用户页面", "显示编辑用户页面", $userMgId, "admin_user_edit", "", 3, $roleId, 1, 1, 0);
        $this->addMenu("编辑用户", "编辑用户处理", $userMgId, "admin_api_user_edit", "", 4, $roleId, 1, 1, 0);
        $this->addMenu("删除用户", "删除用户处理", $userMgId, "admin_api_user_delete", "", 5, $roleId, 1, 1, 0);
        $this->addMenu("批量删除用户", "批量删除用户处理", $userMgId, "admin_api_user_bathdelete", "", 6, $roleId, 1, 1, 0);
        $this->addMenu("锁定/解锁用户", "锁定/解锁用户", $userMgId, "admin_api_user_switchLock", "", 7, $roleId, 1, 1, 0);
        //操作日志
        $adminlogMgId = $this->addMenu("操作日志", "操作日志", $accMenuId, "admin_adminlog_index", "", 3, $roleId, 1, 0, 1);
        //系统模块
        $sysMenuId = $this->addMenu("系统", "系统方面的管理", 0, "", "mdi-cogs", 3, $roleId, 1, 0, 1);
        $optionMgId = $this->addMenu("配置", "对系统的相关配置", $sysMenuId, "admin_option_index", "", 0, $roleId, 1, 0, 1);
        $this->addMenu("添加页面", "添加配置页面展示", $optionMgId, "admin_option_add", "", 3, $roleId, 1, 1, 0);
        $this->addMenu("添加", "添加配置处理", $optionMgId, "admin_api_option_add", "", 4, $roleId, 1, 1, 0);
        $this->addMenu("编辑页面", "编辑配置页面展示", $optionMgId, "admin_option_edit", "", 3, $roleId, 1, 1, 0);
        $this->addMenu("编辑", "编辑配置处理", $optionMgId, "admin_api_option_edit", "", 4, $roleId, 1, 1, 0);
        $this->addMenu("删除", "删除配置处理", $optionMgId, "admin_api_option_delete", "", 5, $roleId, 1, 1, 0);
        $this->addMenu("批量删除", "批量删除配置处理", $optionMgId, "admin_api_option_bathdelete", "", 6, $roleId, 1, 1, 0);

    }

    protected function addMenu($name, $descr, $pid, $uri, $style, $sort, $roleId, $isLock, $isAccess, $isShow, $isGlobal = 0)
    {
        $menuModel = new BaseMenu();
        $menuModel->setName($name);
        $menuModel->setDescr($descr);
        $menuModel->setUrl($uri);
        $menuModel->setIsLock($isLock);
        $menuModel->setIsAccess($isAccess);
        $menuModel->setIsShow($isShow);
        $menuModel->setPid($pid);
        $menuModel->setSort($sort);
        $menuModel->setStyle($style);
        $menuModel->setIsGlobal($isGlobal);
        $this->baseService->getDoctrine()->getManager()->persist($menuModel);
        $this->baseService->getDoctrine()->getManager()->flush();
        $menuId = $menuModel->getId();
        $roleMenuModel = new BaseRoleMenu();
        $roleMenuModel->setRoleId($roleId);
        $roleMenuModel->setMenuId($menuId);
        $this->baseService->getDoctrine()->getManager()->persist($roleMenuModel);
        $this->baseService->getDoctrine()->getManager()->flush();
        return $menuId;
    }
}