<?php

namespace zaporylie\Tripletex\Resource\Order;

use zaporylie\Tripletex\Client\RequestMethod;
use zaporylie\Tripletex\Model\Order\Order;
use zaporylie\Tripletex\Model\Order\RequestOrderUpdate;
use zaporylie\Tripletex\Model\Order\ResponseOrderWrapper;
use zaporylie\Tripletex\Resource\ResourceBase;
use zaporylie\Tripletex\Resource\ResourceInterface;

class OrderUpdate extends ResourceBase implements ResourceInterface
{

    /**
     * @var \zaporylie\Tripletex\Client\RequestMethod
     */
    protected $method = RequestMethod::PUT;

    /**
     * @var string
     */
    protected $path = '/order/{id}';

    /**
     * @param \zaporylie\Tripletex\Model\Order\Order $requestObject
     *
     * @return \zaporylie\Tripletex\Model\Order\ResponseOrderWrapper
     */
    public function call(Order $requestObject)
    {
        $order = $this->app->getSerializer()->serialize($requestObject, 'json');
        /** @var \Psr\Http\Message\RequestInterface $request */
        $request = $this->app->getClient()->messageFactoryDiscovery()->createRequest(
            $this->getMethod(),
            $this->getPath($requestObject->getId()),
            ['Content-Type' => 'application/json; charset=utf-8'],
            $order
        );
        /** @var \Psr\Http\Message\ResponseInterface $response */
        $response = $this->doRequest($request);

        // Get response.
        $body = $response->getBody()->getContents();

        /** @var \zaporylie\Tripletex\Model\Order\ResponseOrderWrapper $responseObject */
        // Deserialize response.
        $responseObject = $this->app->getSerializer()->deserialize(
            $body,
            'zaporylie\Tripletex\Model\Order\ResponseOrderWrapper',
            'json'
        );

        return $responseObject;
    }
}
