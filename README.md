# Real.digital challenge
Solution is using composer.

There will be 4 docker containers arranged: nginx, fpm, mysql and pma.

##Prerequisites
1) you should have docker with docker-compose installed
2) 1.5 Gb of free space

download, build and run containers by
```
docker-compose up -d --build
```


## Run task
by running index.php in fpm container.
```
docker exec -i rd-fpm php index.php
```