<?php

namespace App\Controller;

use stdClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Pimcore\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    /**
     * @return Response
     */
    public function returnErrorResponse(string $msg = '', int $statusCode = 500) {
    $errorObj = new stdClass();
    $errorObj->success = false;
    $errorObj->message = $msg;

    $response = new Response(
        json_encode($errorObj),
        $statusCode,
        ['content-type' => 'application/json']
    );

    return $response;
  }

  /**
   * @return Response
   */
  public function returnContent($content) {
    $response = new Response(
        json_encode($content),
        Response::HTTP_OK,
        ['content-type' => 'application/json']
    );

    return $response;
  }
}
