## RebbitMQ API with php-amqplib ##

[Visit Rabbitmq](http://www.rabbitmq.com/)

### Installation: ###

* rabbitmq-server
    * wget http://www.rabbitmq.com/rabbitmq-signing-key-public.asc
    * sudo apt-key add rabbitmq-signing-key-public.asc
    * sudo apt-get update
    * sudo apt-get install rabbitmq-server
    * sudo rabbitmqctl status

* AMPQ
    * sudo apt-get install librabbitmq-dev
    * sudo pecl install amqp
    * You should add "extension=amqp.so" to php.ini
    
* GUI
    * sudo rabbitmq-plugins enable rabbitmq_management
    * http://localhost:15672/
    * Default user is: guest, pass: guest

### API: ###

#### Add message to RabbitMQ server #####

POST [http://rabbitmq.api.local/api/v1/publish](http://rabbitmq.api.local/api/v1/publish)

* data: message => ‘message text’.

#### Receive message from RabbitMQ server #####

GET [http://rabbitmq.api.local/api/v1/receive](http://rabbitmq.api.local/api/v1/receive)

### Author ###
Alex Galych <aleksandr.galych@symmetrics.de>