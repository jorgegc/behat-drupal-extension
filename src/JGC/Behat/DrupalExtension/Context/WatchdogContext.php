<?php

namespace JGC\Behat\DrupalExtension\Context;

use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Provides step definitions for interacting with Drupal watchdog.
 */
class WatchdogContext extends RawDrupalContext {

  /**
   * Ensures watchdog is empty.
   *
   * @Given the system logs are empty
   */
  public function assertWatchdogIsEmpty() {
    $this->clearWatchdog();
  }

  /**
   * Fails if there are messages of a given type in watchdog.
   *
   * @Then the system logs should not contain any :type messages
   */
  public function assertWatchdogContainMessage($type) {
    $messages = $this->fetchWatchdog($type);
    if ($messages) {
      throw new \Exception(implode(PHP_EOL, $messages));
    }
  }

  /**
   * For watchdog scenarios, always clear watchdog.
   *
   * @BeforeScenario @watchdog
   */
  public function beforeWatchdogScenario() {
    $this->assertWatchdogIsEmpty();
  }

  /**
   * For watchdog scenarios, always fail if there are PHP messages in watchdog.
   *
   * @AfterScenario @watchdog
   */
  public function afterWatchdogScenario() {
    $this->assertWatchdogContainMessage('php');
  }

  /**
   * Clears watchdog messages.
   */
  public function clearWatchdog() {
    db_truncate('watchdog')->execute();
  }

  /**
   * Fetches watchdog messages.
   *
   * @param string $type
   *   The type.
   *
   * @return array
   *   An array of messages.
   */
  public function fetchWatchdog($type) {
    $messages = array();

    $result = db_select('watchdog', 'w')
      ->fields('w', array('message', 'variables'))
      ->condition('type', $type)
      ->execute();

    while ($row = $result->fetchAssoc()) {
      $row['variables'] = unserialize($row['variables']);
      $message = format_string($row['message'], $row['variables']);
      $messages[] = strip_tags($message);
    }

    return $messages;
  }

}
