#!/bin/bash

# 生成一些初始化的目录

os=$(uname -s)
echo $os
if [ $os != "Linux" ]
then
    export workspace=$(pwd)
else
    fileLocation=$(readlink -f "$0")
    export workspace=$(dirname $fileLocation)
fi

echo $workspace
cd $workspace

appDir="$workspace/app"

logDir="$appDir/logs"
cacheDir="$workspace/cache"

if [ ! -d $logDir ]
then
    mkdir -p $logDir
else
    echo "$logDir has exist"
fi

if [ ! -d $cacheDir ]
then
    mkdir -p $cacheDir
else
    echo "$cacheDir has exist"
fi

# 修改配置文件名称
cd $appDir

cp env.php.bak env.php

# 解压vendor目录
#cd $workspace
#if command -v 7za >/dev/null 2>&1; then
#    echo 'exist 7za'
#else
#    echo 'install 7za command'
#    yum -y install p7zip
#fi
#
#7za x "$workspace/vendor.7z"
