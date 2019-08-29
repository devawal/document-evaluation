<?php
namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use AppBundle\Component\Validator;
use AppBundle\Services\Helpers;

class DocumentCommand extends Command
{
    private $container;
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'identification-requests:process';

    public function __construct(Container $container, bool $requirePassword = false)
    {
        $this->container = $container;
        // To create root dir since $this->getContainer() can't accessable within console command
        $this->base_path = $this->container->get('kernel')->getRootDir();

        // best practices recommend to call the parent constructor first and
        // then set your own properties. That wouldn't work in this case
        // because configure() needs the properties set in this constructor
        $this->requirePassword = $requirePassword;

        parent::__construct();
    }

    /**
     *  Configuration for the command
     *
     */
    protected function configure()
    {
        // the short description shown while running "php bin/console list"
        $this->setDescription('Identification data process.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to upload identification data...')

            // configure an argument
            ->addArgument('file', InputArgument::REQUIRED, 'The document file.')
            // ...
        ;
    }

    /**
     * Execute command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Retrieve the argument value using getArgument()
        $csv_name = $input->getArgument('file');

        // Check file name
        if ($csv_name == 'input.csv') {
            // Get input file from filesystem
            $csvData = array_map('str_getcsv', file($this->base_path.'/../web/'.$csv_name));
            $formatData = Helpers::formatInputData($csvData);

            // Clear *all* cache keys
            $cache = new FilesystemCache();
            $cache->clear();

            // Check every row data
            foreach ($csvData as $key => $data) {
                if (!empty($data[0])) {
                    $validation = Validator::getInformationData($data, $formatData[$data[1]]);
                    if (!empty($validation)) {
                        $output->writeln($validation);
                    } else {
                        $output->writeln('valid');
                    }
                }
            }
        } else {
            $output->writeln('Invalid file!');
        }
    }
}