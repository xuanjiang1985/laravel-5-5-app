 
 
 ```
 // dockerfile 安装镜像
 docker build -t mysql/myapp .
 // 查看镜像
 docker images
 // 构建容器
 docker run -d -p 8000:80  nginx/myapp
 // 运行容器
 docker start **
 // 查看容器
 docker ps | grep nginx
 // 进入容器
 docker exec -it ccc37 /bin/bash
 ```
 
 ```
 // mysql 容器
 docker pull mysql/mysql-server:5.7
 
 docker run --name=mysql1 --restart=always -v `pwd`/mysql:/var/lib/mysql -d -p 3306:3306 mysql/mysql-server:5.7
 // 查看密码
 docker logs mysql1
 // GENERATED ROOT PASSWORD: Axegh3kAJyDLaRuBemecis&EShOs
 docker exec -it mysql1 mysql -uroot -p
 mysql> ALTER USER 'root'@'localhost' IDENTIFIED BY 'newpassword';
 ```
 
  
 ```
 // redis 容器
 docker pull redis:3.2.12
 docker run --restart=always -v `pwd`/conf/redis.conf:/usr/local/etc/redis/redis.conf -v `pwd`/data:/data -d --name redis3.2 -p 6379:6379 redis:3.2.12
 ```

 
 
 ```
 //  Nginx + PHP-FPM 容器
 docker pull richarvey/nginx-php-fpm:1.3.10
 docker run --name=nginx-php7.1 --restart=always -v `pwd`/log/nginx:/var/log/nginx -v `pwd`/nginx/sites-enabled:/etc/nginx/sites-enabled -v `pwd`/www:/var/www --link=mysql1 --link=redis3.2 -d -p 80:80 -p 443:443 richarvey/nginx-php-fpm:1.3.10
 ```