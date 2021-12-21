<?php
namespace Drupal\content_payment\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\ClientInterface;

/**
 * Class ContentApi.
 *
 * @package Drupal\content_payment\Controller
 */
class ContentApi extends ControllerBase {
	/**
   * Guzzle\Client instance.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;
	/**
   * {@inheritdoc}
   */
  public function __construct(ClientInterface $http_client) {
    $this->httpClient = $http_client;
    $this->server = \Drupal::request()->server;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('http_client')
    );
  }
	public function readApi() {
		$request = $this->httpClient->request('GET', 'https://api.publicapis.org/entries');
		if ($request->getStatusCode() != 200) {
      return new JsonResponse(['wrong API']);
    }
		return new JsonResponse($request->getBody()->getContents());
	}

	public function success() {
		return new JsonResponse(['payment is success']);
	}

	public function hash() {
    if(strcasecmp($this->server->get('REQUEST_METHOD'), 'POST') == 0){
      //Request hash
      $contentType = !empty($this->server->get("CONTENT_TYPE")) ? trim($this->server->get("CONTENT_TYPE")) : '';	
      if(strcasecmp($contentType, 'application/json') == 0){
        $data = json_decode(file_get_contents('php://input'));
        $hash=hash('sha512', $data->key.'|'.$data->txnid.'|'.$data->amount.'|'.$data->pinfo.'|'.$data->fname.'|'.$data->email.'|||||'.$data->udf5.'||||||'.$data->salt);
        $json=[];
        $json['success'] = $hash;
        return new JsonResponse($json);
      }
      exit(0);
    }
	}
}