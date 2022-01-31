<?php

namespace Drupal\event_countdown\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\event_countdown\CountdownService;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an Event Countdown Block.
 *
 * @Block(
 *   id = "event_countdown",
 *   admin_label = @Translation("Event Countdown"),
 *   category = @Translation("Events"),
 * )
 */
class CountdownBlock extends BlockBase implements ContainerFactoryPluginInterface
{
  protected CountdownService $countdownService;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, CountdownService $countdownService)
  {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->countdownService = $countdownService;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('event_countdown.countdown')
    );
  }

  public function build() {
    $node = \Drupal::routeMatch()->getParameter('node');
    $eventDate = $node->field_date->value;

    $this->validate($node, $eventDate);

    $data = $this->countdownService->countDays($eventDate);

    return [
      '#markup' => $this->t($data),
    ];
  }

  /**
   * @throws Exception
   */
  private function validate($node, $eventDate) {
    if ($node->getType() !== 'event') {
      throw new Exception('This module only works with nodes of the event type.');
    }

    if (!strtotime($eventDate)) {
      throw new Exception('Invalid date format.');
    }
  }

  public function getCacheMaxAge(): int {
    return 0;
  }
}
