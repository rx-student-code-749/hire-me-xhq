<?php
/**
 * Created by PhpStorm.
 * User: raphe
 * Date: 22/9/2018
 * Time: 2:57 PM
 */

namespace Namacode\Slim\Controller\Contract;


use Namacode\Utility\Responses\JSONResponse;
use Slim\Http\Response;

abstract class AJAXController
{
    protected $JSONResponse;
    protected $response;

    public function __construct(Response $response)
    {
        $this->JSONResponse = new JSONResponse();
        $this->response = $response;
    }

    public function respond () {
        return $this->response->withJson($this->JSONResponse->getResponse());
    }
}
