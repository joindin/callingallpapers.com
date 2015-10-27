<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Callingallpapers\Command;

use Callingallpapers\Parser\LanyrdCfpParser;
use Callingallpapers\Writer\DefaultCfpWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ParseLanyrdEvents extends Command
{
    protected function configure()
    {
        $this->setName("lanyrd:getCfpFromEvent")
             ->setDescription("Parse events from Lanyrd and retrieve CfPs")
             ->setDefinition(array(
                 new InputOption('start', 's', InputOption::VALUE_OPTIONAL, 'What should be the first date to be taken into account?', ''),
             ))
             ->setHelp(<<<EOT
Get details about CfPs from Lanyrds not existing API

Usage:

<info>callingallpapers lanyrd:getCfpFromEvent 2015-02-23<env></info>

If you ommit the date the current date will be used instead
<info>callingallpapers php.net:getCfpFromEvent<env></info>
EOT
             );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $header_style = new OutputFormatterStyle('white', 'green', array('bold'));
        $output->getFormatter()->setStyle('header', $header_style);

        $start = new \DateTime($input->getOption('start'));

        if (! $start instanceof \DateTime) {
            throw new \InvalidArgumentException('The given date could not be parsed');
        }

        $parser = new LanyrdCfpParser();
        $parser->parse();

        $writer = new DefaultCfpWriter();

        $output->writeln($writer->write($parser));
    }
}