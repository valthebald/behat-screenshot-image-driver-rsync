<?php

namespace Bex\Behat\ScreenshotExtension\Driver;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Filesystem\Filesystem;

class Rsync implements ImageDriverInterface {

  /**
   * Filesystem service.
   *
   * @var \Symfony\Component\Filesystem\Filesystem
   */
  private $filesystem;

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
   * Optional URL to view uploaded screenshots.
   *
   * @var string
   */
  private $preview_url;

  /**
   * Optional options to pass to SSH.
   *
   * @var string
   */
  private $ssh_options;

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
          ->scalarNode('preview_url')
          ->defaultValue('')
          ->end()
          ->scalarNode('ssh_options')
          ->defaultValue('')
          ->end()
          ->end();
    }

    public function __construct(Filesystem $filesystem = NULL) {
      $this->filesystem = $filesystem ? $filesystem : new Filesystem();
    }

  /**
     * @param  ContainerBuilder $container
     * @param  array            $config
     */
    public function load(ContainerBuilder $container, array $config) {
      $this->server = $config['server'];
      $this->username = $config['username'];
      $this->path = $config['path'];
      $this->preview_url = $config['preview_url'];
      $this->ssh_options = $config['ssh_options'];
    }

    /**
     * @param string $binaryImage
     * @param string $filename
     *
     * @return string URL to the image
     */
    public function upload($binaryImage, $filename) {
      $tmp_filename = "/tmp/$filename-" . time();
      $this->filesystem->dumpFile($tmp_filename, $binaryImage);
      $ssh_options = $this->ssh_options ? '-e "' . $this->ssh_options . '"' : '';
      exec("rsync $ssh_options $tmp_filename {$this->username}@{$this->server}:{$this->path}/$filename");
      if ($this->preview_url) {
        return "{$this->preview_url}/$filename";
      }
      else {
        return "{$this->path}/$filename";
      }
    }

}
