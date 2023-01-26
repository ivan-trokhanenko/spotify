<?php

namespace Drupal\spotify_artist\Plugin\Block;

use Drupal\Core\Link;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a Spotify Artist block.
 *
 * @Block(
 *   id = "Spotify Artists",
 *   admin_label = @Translation("Spotify Artists")
 * )
 */
class ArtistsBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new ArtistsBlock.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param array $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $node_storage = $this->entityTypeManager->getStorage('node');
    $ids = $node_storage->getQuery()
      ->condition('status', 1)
      ->condition('type', 'artist_spotify')
      ->sort('created', 'ASC')
      ->pager(20)
      ->execute();

    if (empty($ids)) {
      return [
        '#markup' => $this->t('No artist found.'),
      ];
    }

    $articles = $node_storage->loadMultiple($ids);
    $items = [];
    foreach ($articles as $article) {
      if (\Drupal::currentUser()->isAuthenticated()) {
        // Add link to the artist page for logged-in users.
        $items[] = Link::createFromRoute($article->get('field_name')->getString(), 'entity.node.canonical', ['node' => $article->id()]);
      }
      else {
        $items[] = $article->get('field_name')->getString();
      }
    }

    return [
      '#cache' => [
        'contexts' => ['user'],
        'tags' => ['node_list:artist_spotify']
      ],
      '#theme' => 'item_list',
      '#items' => $items,
    ];
  }

}