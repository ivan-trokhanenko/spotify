<?php

namespace Drupal\spotify;

/**
 * Interface SpotifyManagerInterface.
 *
 * @package Drupal\spotify
 */
interface SpotifyManagerInterface {

  /**
   * The Spotify API link.
   */
  const ENDPOINT = 'https://api.spotify.com/v1/';

  /**
   * Get artist by ID.
   *
   * @param string $artist_id
   *   The artist ID on Spotify.
   *
   * @return mixed|void
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getArtist($artist_id);

}
