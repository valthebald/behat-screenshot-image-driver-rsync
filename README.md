ImageDriver-Rsync for Behat-ScreenshotExtension
=========================

This package is an image driver for the [bex/behat-screenshot](https://github.com/elvetemedve/behat-screenshot) behat extension 
which uploads screenshots to remote server using rsync protocol.

Installation
------------

Install by adding to your `composer.json`:

```bash
composer require --dev valthebald/behat-screenshot-image-driver-rsync
```

Configuration
-------------

Enable the image driver in the Behat-ScreenshotExtension's config in `behat.yml` like this:

```yml
default:
  extensions:
    Bex\Behat\ScreenshotExtension:
      active_image_drivers: rsync
```

Required parameters:

```yaml
default:
  extensions:
    Bex\Behat\ScreenshotExtension:
      active_image_drivers: rsync
      image_drivers:
        rsync:
          server: remote.example.com
          username: username
          path: /full/path/to/screenshots/folder # No trailing slash here!
```

Optional parameters:
```yaml
      image_drivers:
        rsync:
          ssh_options: "ssh -i private_file.pem -p 2222" # This parameter will be passed as -e to rsync command
          preview_url: "https://remote.example.com/scheenshots/folder" # If screenshots folder is accessible via HTTP/S
```


Usage
-----

When you run behat and a step fails then the Behat-ScreenshotExtension will automatically take the screenshot and will pass it to the image driver, which will upload it and returns the URL of the uploaded image. So you will see something like this:

```bash
  Scenario:                           # features/feature.feature:2
    Given I have a step               # FeatureContext::passingStep()
    When I have a failing step        # FeatureContext::failingStep()
      Error (Exception)
Screenshot has been taken. Open image at http://uploadpie.com/idoftheimage
    Then I should have a skipped step # FeatureContext::skippedStep()
```
