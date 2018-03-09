<?php
/**
 * Created by PhpStorm.
 * User: helei
 * Date: 2017/8/15
 * Time: 下午3:42
 */

namespace XApi\Plugin;

use XApi\Contracts\ApiCode;
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
    // 定义用户角色
    const ROLE_IS_USER = 'USER';
    const ROLE_IS_GUEST = 'GUEST';

    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        // 设置跨域的header
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Headers', 'X-Requested-With,X_Requested_With,D-Request-From,D-Device-Id,Content-Type,Authorization');
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET,POST,OPTIONS,PUT,DELETE');
        $this->response->setHeader('Access-Control-Max-Age', 1728000);

        // 从分发器获取活动的 controller/action
        $module = $dispatcher->getModuleName();

        $resourcePrefix = ucfirst($module);

        // 检查用户是否登陆
        $auth = $this->checkLogin($dispatcher);

        if (!$auth) {
            $role = self::ROLE_IS_GUEST;
        } else {
            $role = self::ROLE_IS_USER;
        }

        // Take the active controller/action from the dispatcher
        $controller = $dispatcher->getControllerName();
        $action     = $dispatcher->getActionName();

        // Obtain the ACL list
        $acl = $this->getAcl($resourcePrefix);

        $resourceName = $resourcePrefix . $controller;
        $allowed = $acl->isAllowed($role, $resourceName, $action);

        if (!$allowed) {// 没有访问权限
            // 跳到首页
            $this->response->setJsonContent([
                'code' => ApiCode::NOT_LOGIN,
                'msg' => '请登陆后访问',
                'data' => []
            ]);
            $this->response->send();

            return false;
        }

        return true;
    }

    /**
     * 获取模块的 acl 列表
     * @param string $module
     * @return AclList
     */
    protected function getAcl(string $module): AclList
    {
        $pName = strtolower($module);
        if (!isset($this->persistent->$pName) || !APP_ENV_PROD) {// 开发与测试环境，每次都要生成
            // 创建一个 ACL
            $acl = new AclList();

            // 默认行为是 DENY(拒绝) 访问
            $acl->setDefaultAction(
                Acl::DENY
            );

            // 注册角色:登陆用户 游客 超级管理员
            $roles = [
                'users' => new Role(self::ROLE_IS_USER, '登陆用户'),
                'guests' => new Role(self::ROLE_IS_GUEST, '游客用户'),
            ];

            foreach ($roles as $role) {
                $acl->addRole($role);
            }

            $method = 'get' . ucfirst($module) . 'Resources';
            $resources = $this->$method();
            // 必须登陆才可以访问
            $privateResources = $resources['private'];
            foreach ($privateResources as $controllerName => $actions) {
                $acl->addResource(new Resource($module . $controllerName), $actions);
            }

            // 不需要登陆也可以访问
            $publicResources = $resources['public'];
            foreach ($publicResources as $controllerName => $actions) {
                $acl->addResource(new Resource($module . $controllerName), $actions);
            }

            // 授权所有类型可以访问共有资源
            foreach ($roles as $role) {
                foreach ($publicResources as $controllerName => $actions) {
                    $acl->allow(
                        $role->getName(),
                        $module . $controllerName,
                        $actions
                    );
                }
            }

            // 授权登陆用户访问私有区域
            foreach ($privateResources as $controllerName => $actions) {
                if (strtolower($module) === 'v1') {// 注册前台模块
                    $acl->allow(
                        self::ROLE_IS_USER,
                        $module . $controllerName,
                        $actions
                    );
                }
            }
            $this->persistent->$pName = $acl;
        }

        return $this->persistent->$pName;
    }

    /**
     * 获取 v1 模块的数据
     * @return array
     */
    protected function getV1Resources(): array
    {
        return [
            'private' => [// 需要登陆的业务

            ],
            'public' => [// 任何情况下都可以访问的业务
                'index' => ['*'],
            ]
        ];
    }

    protected function checkLogin(Dispatcher $dispatcher): bool
    {
        $dispatcher->setParam('role', self::ROLE_IS_GUEST);
        $dispatcher->setParam('member_id', 1);// 设置登录的用户id
        $dispatcher->setParam('token', 'abcdefa');// 设置登录使用的token
        return true;
    }
}
