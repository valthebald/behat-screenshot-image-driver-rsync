<?php

namespace spec\Bex\Behat\ScreenshotExtension\Driver;

use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class UploadRsyncSpec extends ObjectBehavior {
    function it_is_initializable()
    {
        $this->shouldHaveType('Bex\Behat\ScreenshotExtension\Driver\UploadRsync');
    }

    function it_should_call_the_api_with_the_correct_data(ContainerBuilder $container, UploadPieApi $api)
    {
      $dummyUrl = 'http://docs.behat.org/en/v2.5/_static/img/logo.png';
      $this->upload(Argument::any(), Argument::any())->shouldReturn($dummyUrl);
    }
}
