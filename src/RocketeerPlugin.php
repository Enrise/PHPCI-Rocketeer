<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Enrise\PHPCI\Plugins;

use PHPCI\Builder;
use PHPCI\Model\Build;
use PHPCI\Plugin;
use Psr\Log\LogLevel;

/**
 * Deploy your code with Rocketeer
 *
 * @package PHPCI\Plugin
 * @see http://rocketeer.autopergamene.eu/
 */
class RocketeerPlugin implements Plugin
{
    protected $phpci;
    protected $build;
    protected $deployments = array();

    /**
     * Standard Constructor
     *
     * $options['directory'] Output Directory. Default: %BUILDPATH%
     * $options['filename']  Phar Filename. Default: build.phar
     * $options['regexp']    Regular Expression Filename Capture. Default: /\.php$/
     * $options['stub']      Stub Content. No Default Value
     *
     * @param Builder $phpci   The thing
     * @param Build   $build   Another thing
     * @param array   $options Some options
     */
    public function __construct(
        Builder $phpci,
        Build $build,
        array $options = array()
    ) {
        $this->phpci = $phpci;
        $this->build = $build;

        if (array_key_exists('deploy', $options)) {
            $this->deployments = $options['deploy'];
        }
    }

    /**
     * Run Rocketeer to deploy / configure your project on the remote server(s)
     */
    public function execute()
    {
        if ($this->build->getExtra('build_type') == 'pull_request') {
            $this->phpci->log('Building a pull request, skipping deploy...');
            return true;
        }

        $branch = $this->build->getBranch();

        if (!array_key_exists($branch, $this->deployments)) {
            $this->phpci->log(
                'No deployment config found for branch '  . $branch . '!'
            );
            return true;
        }

        $deployCommand = array(
            $this->phpci->findBinary('rocketeer'),
            'deploy'
        );

        if (array_key_exists('connection', $this->deployments[$branch])) {
            $deployCommand[] = sprintf(
                '--on="%s"',
                $this->deployments[$branch]['connection']
            );
        }

        if (array_key_exists('stage', $this->deployments[$branch])) {
            $deployCommand[] = sprintf(
                '--stage="%s"',
                $this->deployments[$branch]['stage']
            );
        }

        $deployCommand = implode(' ', $deployCommand);

        $this->phpci->log(
            'Running deploy command: ' . $deployCommand,
            LogLevel::INFO
        );

        return $this->phpci->executeCommand($deployCommand);
    }
}
