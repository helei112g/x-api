项目采用 Phalcon devtool 工具创建。

项目的创建使用：phalcon create-project x-api modules

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

- `logs` 用来记录文件日志
- `migrations` 是数据库文件信息


**注意: 由于我们是接口项目，所有整个项目没有 view 相关的东西**