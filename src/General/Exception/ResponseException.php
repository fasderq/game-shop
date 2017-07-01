<?php
namespace GameShop\Site\General\Exception;


use Symfony\Component\HttpFoundation\Response;

/**
 * Class ResponseException
 * @package GameShop\Site\General\Exception
 */
class ResponseException extends \Exception
{
    protected $response;

    /**
     * ResponseException constructor.
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }
}
