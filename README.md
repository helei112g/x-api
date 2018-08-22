项目采用 Phalcon devtool 工具创建。

项目的创建使用：phalcon create-project x-api modules

> 前端访问地址：localhost/v1/xx
>
>后端访问地址：localhost/back/xx
>
>cli 模块，请进入项目跟目录后使用 php run 查看使用帮助信息

# 安装项目
1. 从 GitHub 获取项目
```php
git clone https://github.com/helei112g/x-api.git
```

2. 安装依赖包
```php
composer install
```

# 项目要求
- PHP >= 7.0
- curl
- gettext
- gd (to use the Phalcon\Image\Adapter\Gd class)
- libpcre3-dev (Debian/Ubuntu), pcre-devel (CentOS), pcre (macOS)
- json
- mbstring
- pdo_*
- fileinfo
- openssl

# 项目目录说明
所有项目编码均在 `app` 下面

3. 基本命令
```php
# 查看自定义的命令任务
php run

# 根据表结构生成model
php run model

# 生成数据库文件信息
phalocon migration
```



- `app\logs` 用来记录文件日志
- `app\migrations` 是数据库文件信息
- `app\common\contracts` 目录存放规范系统编码行为的类，以及常量定义等


