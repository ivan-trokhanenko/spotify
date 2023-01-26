<?php

namespace Drupal\spotify;

use Drupal\Core\State\StateInterface;
use Drupal\Core\Config\ConfigFactory;
use GuzzleHttp\ClientInterface;

/**
 * Helper Spotify API service.
 *
 * @package Drupal\spotify
 */
class SpotifyManager implements SpotifyManagerInterface {

  /**
   * The HTTP client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * The state service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  private $config;

  /**
   * PostManager constructor.
   *
   * @param \GuzzleHttp\ClientInterface $httpClient
   *   The HTTP client.
   * @param \Drupal\Core\State\StateInterface $state
   *   The state service.
   * @param \Drupal\Core\Config\ConfigFactory $configFactory
   *   The config factory service.
   */
  public function __construct(ClientInterface $httpClient, StateInterface $state, ConfigFactory $configFactory) {
    $this->httpClient = $httpClient;
    $this->state = $state;
    $this->config = $configFactory->getEditable('spotify.settings');
  }

  /**
   * {@inheritdoc}
   */
  public function getArtist($artist_id) {
    // Check authentication.
    if (!$this->state->get('spotify.access_token.expire')
      || ($this->state->get('spotify.access_token.expire') && time() >= $this->state->get('spotify.access_token.expire'))) {
      $this->authenticate();
    }
    // Make API call.
    try {
      $request = $this->httpClient->request('GET', self::ENDPOINT . 'artists/' . trim($artist_id), [
        'headers' => [
          'Content-Type' => 'application/json',
          'Authorization' => 'Bearer ' . $this->state->get('spotify.access_token'),
        ],
      ]);
      return json_decode($request->getBody());
    }
    catch (\Exception $e) {
      // TODO: replace with logger.factory service.
      watchdog_exception('spotify', $e);
      return FALSE;
    }
  }

  /**
   * Provides user authentication.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  private function authenticate() {
    try {
      $request = $this->httpClient->request('POST', 'https://accounts.spotify.com/api/token', [
        'form_params' => [
          'grant_type' => 'client_credentials',
          'client_id' => $this->config->get('client_id'),
          'client_secret' => $this->config->get('client_secret')
        ]
      ]);
      if ($request->getReasonPhrase() == 'OK') {
        $response = json_decode($request->getBody());
        $this->state->set('spotify.access_token.expire', time() + $response->expires_in);
        $this->state->set('spotify.access_token', $response->access_token);
      }
    }
    catch (\Exception $e) {
      // TODO: replace with logger.factory service.
      watchdog_exception('spotify', $e);
    }
  }

}
