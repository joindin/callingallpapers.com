<?php
/**
 * Copyright (c)2015-2015 heiglandreas
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIBILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @category
 * @author    Andreas Heigl<andreas@heigl.org>
 * @copyright ©2015-2015 Andreas Heigl
 * @license   http://www.opesource.org/licenses/mit-license.php MIT-License
 * @version   0.0
 * @since     12.06.15
 * @link      https://github.com/heiglandreas/
 */

namespace Callingallpapers\Console\Command;

use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class CreateAtomCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('create:atom')
            ->setDescription('Create an ATOM-feed containing the CfPs known to joind.in')
            ->addArgument(
                'path',
                InputArgument::REQUIRED,
                'Where do you want the RSS-File stored?'
            )

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/view');
        $twig = new \Twig_Environment($loader, array(
    //        'cache' => sys_get_temp_dir(),
        ));
        $output->writeln('fetching data from the joind.in-API');

        $client = new Client();
        $res = $client->get('http://api.joind.in/v2.1/events?filter=cfp&verbose=yes');
        if (200 !== $res->getStatusCode()) {
            throw new \UnexpectedValueException(sprintf(
                'Expected return code of "200" but got "%s"',
                $res->getStatusCode()
            ));
        };

        $content = json_decode($res->getBody(), true);

        $content = $twig->render('createAtomCommand.tpl', array('events'=> $content['events']));

        $fh = fopen($input->getArgument('path'), 'w+');
        fwrite($fh, $content);
        fclose($fh);

        return ;
    }
}