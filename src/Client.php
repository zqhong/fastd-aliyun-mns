<?php

namespace Zqhong\FastdAliyunMNS;

use AliyunMNS\AsyncCallback;
use AliyunMNS\Config;
use AliyunMNS\Exception\InvalidArgumentException;
use AliyunMNS\Exception\MnsException;
use AliyunMNS\Exception\QueueAlreadyExistException;
use AliyunMNS\Exception\TopicAlreadyExistException;
use AliyunMNS\Http\HttpClient;
use AliyunMNS\Model\AccountAttributes;
use AliyunMNS\Queue;
use AliyunMNS\Requests\CreateQueueRequest;
use AliyunMNS\Requests\CreateTopicRequest;
use AliyunMNS\Requests\DeleteQueueRequest;
use AliyunMNS\Requests\DeleteTopicRequest;
use AliyunMNS\Requests\GetAccountAttributesRequest;
use AliyunMNS\Requests\ListQueueRequest;
use AliyunMNS\Requests\ListTopicRequest;
use AliyunMNS\Requests\SetAccountAttributesRequest;
use AliyunMNS\Responses\CreateQueueResponse;
use AliyunMNS\Responses\CreateTopicResponse;
use AliyunMNS\Responses\DeleteQueueResponse;
use AliyunMNS\Responses\DeleteTopicResponse;
use AliyunMNS\Responses\GetAccountAttributesResponse;
use AliyunMNS\Responses\ListQueueResponse;
use AliyunMNS\Responses\ListTopicResponse;
use AliyunMNS\Responses\MnsPromise;
use AliyunMNS\Responses\SetAccountAttributesResponse;
use AliyunMNS\Topic;

/**
 * Please refer to
 * https://docs.aliyun.com/?spm=#/pub/mns/api_reference/api_spec&queue_operation
 * for more details
 */
class Client
{
    private $client;

    /**
     * Please refer to http://www.aliyun.com/product/mns for more details
     *
     * @param $endPoint
     * @param $accessId
     * @param $accessKey
     * @param string $securityToken
     * @param Config $config
     */
    public function __construct($endPoint, $accessId,
                                $accessKey, $securityToken = null, Config $config = null)
    {
        $this->client = new HttpClient($endPoint, $accessId,
            $accessKey, $securityToken, $config);
    }

    /**
     * Returns a queue reference for operating on the queue
     * this function does not create the queue automatically.
     *
     * @param string $queueName :  the queue name
     * @param bool $base64 : whether the message in queue will be base64 encoded
     *
     * @return Queue $queue: the Queue instance
     */
    public function getQueueRef($queueName, $base64 = true)
    {
        return new Queue($this->client, $queueName, $base64);
    }

    /**
     * Create Queue and Returns the Queue reference
     *
     * @param CreateQueueRequest $request :  the QueueName and QueueAttributes
     *
     * @return CreateQueueResponse $response: the CreateQueueResponse
     *
     * @throws QueueAlreadyExistException if queue already exists
     * @throws InvalidArgumentException if any argument value is invalid
     * @throws MnsException if any other exception happends
     */
    public function createQueue(CreateQueueRequest $request)
    {
        $response = new CreateQueueResponse($request->getQueueName());
        /** @var CreateQueueResponse $response */
        $response = $this->client->sendRequest($request, $response);
        return $response;
    }

    /**
     * Create Queue and Returns the Queue reference
     * The request will not be sent until calling MnsPromise->wait();
     *
     * @param CreateQueueRequest $request :  the QueueName and QueueAttributes
     * @param AsyncCallback $callback :  the Callback when the request finishes
     *
     * @return MnsPromise $promise: the MnsPromise instance
     *
     * @throws MnsException if any exception happends
     */
    public function createQueueAsync(CreateQueueRequest $request,
                                     AsyncCallback $callback = null)
    {
        $response = new CreateQueueResponse($request->getQueueName());
        return $this->client->sendRequestAsync($request, $response, $callback);
    }

    /**
     * Query the queues created by current account
     *
     * @param ListQueueRequest $request : define filters for quering queues
     *
     * @return ListQueueResponse: the response containing queueNames
     */
    public function listQueue(ListQueueRequest $request)
    {
        $response = new ListQueueResponse();
        /** @var ListQueueResponse $response */
        $response = $this->client->sendRequest($request, $response);
        return $response;
    }

    public function listQueueAsync(ListQueueRequest $request,
                                   AsyncCallback $callback = null)
    {
        $response = new ListQueueResponse();
        return $this->client->sendRequestAsync($request, $response, $callback);
    }

    /**
     * Delete the specified queue
     * the request will succeed even when the queue does not exist
     *
     * @param $queueName : the queueName
     *
     * @return DeleteQueueResponse
     */
    public function deleteQueue($queueName)
    {
        $request = new DeleteQueueRequest($queueName);
        $response = new DeleteQueueResponse();
        /** @var DeleteQueueResponse $deleteResponse */
        $deleteResponse = $this->client->sendRequest($request, $response);
        return $deleteResponse;
    }

    public function deleteQueueAsync($queueName,
                                     AsyncCallback $callback = null)
    {
        $request = new DeleteQueueRequest($queueName);
        $response = new DeleteQueueResponse();
        return $this->client->sendRequestAsync($request, $response, $callback);
    }

    // API for Topic

    /**
     * Returns a topic reference for operating on the topic
     * this function does not create the topic automatically.
     *
     * @param string $topicName :  the topic name
     *
     * @return Topic $topic: the Topic instance
     */
    public function getTopicRef($topicName)
    {
        return new Topic($this->client, $topicName);
    }

    /**
     * Create Topic and Returns the Topic reference
     *
     * @param CreateTopicRequest $request :  the TopicName and TopicAttributes
     *
     * @return CreateTopicResponse $response: the CreateTopicResponse
     *
     * @throws TopicAlreadyExistException if topic already exists
     * @throws InvalidArgumentException if any argument value is invalid
     * @throws MnsException if any other exception happends
     */
    public function createTopic(CreateTopicRequest $request)
    {
        $response = new CreateTopicResponse($request->getTopicName());
        /** @var CreateTopicResponse $createTopicResponse */
        $createTopicResponse = $this->client->sendRequest($request, $response);
        return $createTopicResponse;
    }

    /**
     * Delete the specified topic
     * the request will succeed even when the topic does not exist
     *
     * @param $topicName : the topicName
     *
     * @return DeleteTopicResponse
     */
    public function deleteTopic($topicName)
    {
        $request = new DeleteTopicRequest($topicName);
        $response = new DeleteTopicResponse();
        /** @var DeleteTopicResponse $deleteTopicRespose */
        $deleteTopicRespose = $this->client->sendRequest($request, $response);
        return $deleteTopicRespose;
    }

    /**
     * Query the topics created by current account
     *
     * @param ListTopicRequest $request : define filters for quering topics
     *
     * @return ListTopicResponse: the response containing topicNames
     */
    public function listTopic(ListTopicRequest $request)
    {
        $response = new ListTopicResponse();
        /** @var ListTopicResponse $listTopicResponse */
        $listTopicResponse = $this->client->sendRequest($request, $response);
        return $listTopicResponse;
    }

    /**
     * Query the AccountAttributes
     *
     * @return GetAccountAttributesResponse: the response containing topicNames
     * @throws MnsException if any exception happends
     */
    public function getAccountAttributes()
    {
        $request = new GetAccountAttributesRequest();
        $response = new GetAccountAttributesResponse();
        /** @var GetAccountAttributesResponse $getAccountAttrsResponse */
        $getAccountAttrsResponse = $this->client->sendRequest($request, $response);
        return $getAccountAttrsResponse;
    }

    /**
     * @param AsyncCallback|null $callback
     * @return MnsPromise
     */
    public function getAccountAttributesAsync(AsyncCallback $callback = null)
    {
        $request = new GetAccountAttributesRequest();
        $response = new GetAccountAttributesResponse();
        return $this->client->sendRequestAsync($request, $response, $callback);
    }

    /**
     * Set the AccountAttributes
     *
     * @param AccountAttributes $attributes : the AccountAttributes to set
     *
     * @return SetAccountAttributesResponse: the response
     *
     * @throws MnsException if any exception happends
     */
    public function setAccountAttributes(AccountAttributes $attributes)
    {
        $request = new SetAccountAttributesRequest($attributes);
        $response = new SetAccountAttributesResponse();
        /** @var SetAccountAttributesResponse $setAccountAttrsResponse */
        $setAccountAttrsResponse = $this->client->sendRequest($request, $response);
        return $setAccountAttrsResponse;
    }

    /**
     * @param AccountAttributes $attributes
     * @param AsyncCallback|null $callback
     * @return MnsPromise
     */
    public function setAccountAttributesAsync(AccountAttributes $attributes,
                                              AsyncCallback $callback = null)
    {
        $request = new SetAccountAttributesRequest($attributes);
        $response = new SetAccountAttributesResponse();
        return $this->client->sendRequestAsync($request, $response, $callback);
    }

    /**
     * @return HttpClient
     */
    public function getClient()
    {
        return $this->client;
    }
}