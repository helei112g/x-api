<?php
/**
 * Created by PhpStorm.
 * User: helei
 * Date: 2017/8/15
 * Time: 下午3:42
 */

namespace XApi\Plugin;

use Phalcon\Acl;
use Phalcon\Acl\Resource;
use Phalcon\Acl\Role;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;

/**
 * 在进行路由前，检查用户是否具备对应的访问权限
 * Class SecurityPlugin
 * @package Isteam\Plugin
 */
class SecurityPlugin extends Plugin
{
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        // 从分发器获取活动的 controller/action
        $module = $dispatcher->getModuleName();

        $method = 'check' . ucfirst($module);

        return $this->$method($event, $dispatcher);
    }

    /**
     * 检查前台是否有权限
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return bool
     */
    protected function checkV1(Event $event, Dispatcher $dispatcher): bool
    {
        // Check whether the 'auth' variable exists in session to define the active role
        $auth = $this->session->get('auth');

        if (!$auth) {
            $role = 'Guests';
        } else {
            $role = 'Users';
        }

        // Take the active controller/action from the dispatcher
        $controller = $dispatcher->getControllerName();
        $action     = $dispatcher->getActionName();

        // Obtain the ACL list
        $acl = $this->getV1Acl();

        $resourceName = 'v1' . $controller;
        $allowed = $acl->isAllowed($role, $resourceName, $action);

        if (!$allowed) {// 没有访问权限
            // 跳到首页
            $dispatcher->forward([
                'controller' => 'index',
                'action' => 'index'
            ]);

            return false;
        }

        return true;
    }

    /**
     * 获取api的访问控制权限
     * @return AclList
     */
    protected function getV1Acl(): AclList
    {
        $resourceName = 'v1';
        // 创建一个 ACL
        $acl = new AclList();

        // 默认行为是 DENY(拒绝) 访问
        $acl->setDefaultAction(
            Acl::DENY
        );

        // 注册角色:登陆用户 游客 超级管理员
        $roles = [
            'users' => new Role('Users', '登陆用户'),
            'guests' => new Role('Guests', '游客用户'),
        ];

        foreach ($roles as $role) {
            $acl->addRole($role);
        }

        // 必须登陆才可以访问
        $privateResources = [
            'person' => ['*']
        ];
        foreach ($privateResources as $controllerName => $actions) {
            $acl->addResource(new Resource($resourceName . $controllerName), $actions);
        }

        // 不需要登陆也可以访问
        $publicResources = [
            'index'  => ['*'],
            'login' => ['*']
        ];
        foreach ($publicResources as $controllerName => $actions) {
            $acl->addResource(new Resource($resourceName . $controllerName), $actions);
        }

        // 授权所有类型可以访问共有资源
        foreach ($roles as $role) {
            foreach ($publicResources as $controllerName => $actions) {
                $acl->allow(
                    $role->getName(),
                    $resourceName . $controllerName,
                    $actions
                );
            }
        }

        // 授权登陆用户访问私有区域
        foreach ($privateResources as $controllerName => $actions) {
            $acl->allow(
                "Users",
                $resourceName . $controllerName,
                $actions
            );
        }

        return $this->persistent->acl = $acl;
    }
}