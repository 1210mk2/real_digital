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

create tables from sql dump voucher_approve.sql
```
docker exec -i rd-mysql mysql -uroot -proot rd < voucher_approve.sql
```

##Solution description
The best way to make a voucher module separate of an order is to divide it by message broker, eg. RabbitMQ.
Imagine, we have the order created and we send a message to a Rabbit with order parameters.
This message should be consumed and the voucher module script will be executed.

There's no problem to give a voucher to a customer. The problem is to NOT give it twice or more.

Imagine, we have the structure:
1) select from db and figure out did we give the voucher already (SSS below). if a record exists - yes.
2) а) if yes, we return "already given"
   
   b) if no, we create the record - let's call it "the fact" - (IIIII below) and return back the voucher (EOK)

```
--SSS-IIIII---EOK
```

But it is absolutely possible if we have many consumers working in parallel mode. There could be a case when by any (accidental) reason we have 2 consumers processing the same order at the same (almost) time.

```
1--SSS-IIIII---EOK
2----SSS-IIIII---EOK
```
So, as you see, we can get a positive voucher give decision by 2nd consumer, because it was made before 1st consumer finished recording "the fact" to db.
There's a way when we can wrap every `SSS-IIIII` to a transaction and block database, but multiple consumers' mode becomes useless.


My proposal is to record "the fact" to db first, then select and count "facts".
```
1--IIIII-SSS--EOK
2-----IIIII-SSS--ENOK
```
As we can see the 1st consumer will have count of 1, and the 2nd will have 2, what means the coupon given before.

My implementation of the script return is:
- echo 0 - no applicable voucher is found
- echo {id} - the voucher campaign id to be applied to the order and provided to the exact customer.  
- Exception - something wrong with database

Due to thoughts of simplicity I've limited my solution:
1) I don't process Exceptions. Many ways how to do it with error collection, log, sending NOK message by Rabbit, etc.
2) I put json of test order to the script. For sure this json should be accepted from Rabbit.
3) I don't create voucher campaigns table in Db, don't create their DTO or model instances, just provide getters. 
   And I expect that such table exist in the main module to provide campaigns' control, settings, etc. And it is in sync with voucher module. Many ways of implementing of it.  
4) I don't provide Tests.
5) I don't provide a solution with other recording instance, eg. Redis, but give a way of substitute.
6) I assume that Db table is indexed the way I proposed or better.
7) I don't provide any ORM implementation or so.

###Pros
1) If the same order comes in parallel instances, we have a protection.
2) If the same order come again later (eg. by MQ fault and resend then), we have a protection.
3) Multiple consumer processing instances.
4) No Db lock or transaction lock.
5) Same Db load (select + insert vs insert + select)

###Cons
1) The worst case if Db becomes off after "the fact" but before the EOK. Should be solved manually by check the time of loss, selecting "facts" in around that time, and check if customers are happy.
2) "The fact" record redundancy. We can achieve duplicates, but it should be much less than single records. It is entrusted to reliability of MQ.    

## Run task
by running index.php in fpm container.
```
docker exec -i rd-fpm php index.php
```

