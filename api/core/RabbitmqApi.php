<?php
/**
 * Created by PhpStorm.
 * User: olg
 * Date: 24.12.14
 * Time: 11:04
 */

require_once 'abstract/Api.php';
require_once 'config.php';

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class RabbitmqApi.
 */
class RabbitmqApi extends Api
{
    /**
     * Default Exchange name.
     */
    const EXCHANGE = 'symmetrics_test';
    /**
     * Default Queue name.
     */
    const QUEUE = 'symmetrics_test_queue';

    /**
     * @var null|AMQPConnection Rabbitmq connection.
     */
    var $connection = null;
    /**
     * @var null|\PhpAmqpLib\Channel\AMQPChannel Rabbitmq channel.
     */
    var $channel = null;

    /**
     * Init object method.
     *
     * @param  array $request Request array.
     *
     * @throws Exception
     */
    public function __construct($request)
    {
        parent::__construct($request);
        $this->connection = new AMQPConnection(HOST, PORT, USER, PASS, VHOST);
        $this->channel = $this->connection->channel();
        if ($this->channel) {
            $this->channel->queue_declare(self::QUEUE, false, true, false, false);
            $this->channel->exchange_declare(self::EXCHANGE, 'direct', false, true, false);
            $this->channel->queue_bind(self::QUEUE, self::EXCHANGE);
        } else {
            throw new Exception('Invalid Amqp server credential.');
        }
    }

    /**
     * Destruct object properties.
     */
    public function __destruct()
    {
        if ($this->channel) {
            $this->channel->close();
        }

        if ($this->connection) {
            $this->connection->close();
        }
    }

    /**
     * Publish message to rabbitmq server.
     *
     * @return bool
     *
     * @throws Exception
     */
    protected function publish()
    {
        if (array_key_exists('message', $this->request)) {
            $msg = new AMQPMessage($this->request['message'], array('content_type' => 'text/plain', 'delivery_mode' => 2));
            $this->channel->basic_publish($msg, self::EXCHANGE);
            return true;
        } else {
            throw new Exception('Request array should contain message argument.');
        }
    }

    /**
     * Retrieve message from rabbitmq server.
     *
     * @return mixed
     *
     * @throws Exception
     */
    protected function receive()
    {
        $msg = $this->channel->basic_get(self::QUEUE);
        if (is_object($msg)) {
            $this->channel->basic_ack($msg->delivery_info['delivery_tag']);
            return $msg->body;
        } else {
            throw new Exception('No messages for this customer.');
        }
    }
}