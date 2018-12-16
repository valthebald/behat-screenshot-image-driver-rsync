<?php

namespace Bex\Behat\ScreenshotExtension\Driver;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class UploadRsync implements ImageDriverInterface {

  /**
   * @var string
   */
  private $server;

  /**
   * @var string
   */
  private $username;

  /**
   * @var string
   */
  private $path;

    /**
     * @param  ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder->children()
          ->scalarNode('server')
          ->isRequired()
          ->cannotBeEmpty()
          ->end()
          ->scalarNode('username')
          ->isRequired()
          ->cannotBeEmpty()
          ->end()
          ->scalarNode('path')
          ->isRequired()
          ->cannotBeEmpty()
          ->end()
          ->end();
    }

    /**
     * @param  ContainerBuilder $container
     * @param  array            $config
     */
    public function load(ContainerBuilder $container, array $config) {
      $this->server = $config['server'];
      $this->username = $config['username'];
      $this->path = $config['path'];
    }

    /**
     * @param string $binaryImage
     * @param string $filename
     *
     * @return string URL to the image
     */
    public function upload($binaryImage, $filename) {
      $tmp_filename = "/tmp/$filename-" . time();
      file_put_contents($tmp_filename, $binaryImage);
      exec("rsync $tmp_filename {$this->username}@{$this->server}:{$this->path}/$filename");
    }

}
