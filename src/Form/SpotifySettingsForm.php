<?php

namespace Drupal\spotify\Form;

use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Client credentials settings form.
 *
 * @package Drupal\spotify\Form
 */
class SpotifySettingsForm extends ConfigFormBase {

  /**
   * The config name.
   */
  const CONFIG = 'spotify.settings';

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      self::CONFIG,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'spotify_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(self::CONFIG);
    $form['client_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Client ID'),
      '#description' => $this->t('Enter your client ID.'),
      '#required' => TRUE,
      '#default_value' => $config->get('client_id'),
    ];
    $form['client_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Client Secret'),
      '#description' => $this->t('Enter your client secret.'),
      '#required' => TRUE,
      '#default_value' => $config->get('client_secret'),
    ];

    $dashboard_link = Link::fromTextAndUrl($this->t('Spotify Dashboard'),
      Url::fromUri('https://developer.spotify.com/dashboard/applications',
        ['attributes' => ['target' => '_blank']]
      )
    );
    $form['text']['#markup'] = $this->t("Note. If you don't have a client ID and secret key, go to your @spotify_dashboard and create an app.",
      ['@spotify_dashboard' => $dashboard_link->toString()]
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $config = $this->config(self::CONFIG);
    $form_state->cleanValues();
    foreach ($form_state->getValues() as $key => $value) {
      $config->set($key, $value);
    }
    $config->save();
  }

}
