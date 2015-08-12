Behat Drupal Extension
======================

## Overview

The commonly used functionality used in Behat testing using Drupal.

### Available Contexts

*WatchdogContext*
  Provides step definitions for interacting with Drupal watchdog.

## Installation

This project can be checked out with Composer.

```
"require": {
    "jorgegc/behat-drupal-extension": "*"
}
```

## Usage

Declare in your behat.yml file the contexts you want to use.

### Example

```yml
default:
  # ...
  suites:
    default:
      contexts:
        - JGC\Behat\DrupalExtension\Context\WatchdogContext
```
