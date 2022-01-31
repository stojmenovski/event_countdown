<?php

namespace Drupal\event_countdown;

use DateTime;

class CountdownService
{
  public function countDays($date): string {
    $now = new DateTime();
    $eventDateTime = new DateTime($date);

    // used for date comparison
    $dateNow = $now->format('Y-m-d');
    $eventDate = $eventDateTime->format('Y-m-d');

    if ($dateNow > $eventDate) {
      $data = Strings::EVENT_PASSED;
    } elseif ($dateNow === $eventDate) {
      $data = Strings::EVENT_TODAY;
    } else {
      $dateDiff = $eventDateTime->diff($now)->days;

      if ($dateDiff === 1) {
        $data = $dateDiff . Strings::EVENT_DAY_LEFT;
      } else {
        $data = $dateDiff . Strings::EVENT_DAYS_LEFT;
      }
    }

    return $data;
  }
}
