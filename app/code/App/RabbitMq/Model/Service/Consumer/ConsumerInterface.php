<?php

namespace App\RabbitMq\Model\Service\Consumer;

use PhpAmqpLib\Message\AMQPMessage;

/**
 * Interface ConsumerInterface
 */
interface ConsumerInterface
{
    /**
     * Callback method for message consumer
     *
     * @param AMQPMessage $message
     *
     * @return mixed
     */
    public function callback(AMQPMessage $message);
}
