<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use AppBundle\Entity\ParserLog;
use ZipArchive;

class ParserCommand extends ContainerAwareCommand
{
    const PARSE_DIR = __DIR__ . '/../../../web/files/upload';
    const ARCHIVE_FILENAME = 'archive.zip';
    const CSV_FILENAME = 'upload1.csv';
    const TEMP_FOLDER_NAME = 'TMP';
    const CSV_SEPARATOR = ';';
    const CSV_IGNORE_FIRST_LINE = true;

    /**
     * @var null|Filesystem
     */
    private $fs = null;

    /**
     * @var null
     */
    private $errors = null;

    /**
     * @var null|string
     */
    private $tmpFolder = null;

    /**
     * ParserCommand constructor.
     * @param null|string $name
     */
    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->fs = new Filesystem();
        $this->tmpFolder = sys_get_temp_dir() . '/' . self::TEMP_FOLDER_NAME;
    }

    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('app:parser')

            ->setDescription('Parse pictures from CSV file.')

            ->setHelp('Parse pictures from CSV file.')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $this->validate();

        if($this->errors) {
            foreach($this->errors as $v) {
                $output->writeln($v);
            }
            return;
        }

        $rows = array();
        if (($handle = fopen(self::PARSE_DIR . '/' . self::CSV_FILENAME, "r")) !== FALSE) {
            $i = 0;
            while (($data = fgetcsv($handle, null, self::CSV_SEPARATOR)) !== FALSE) {
                $i++;
                if (self::CSV_IGNORE_FIRST_LINE && $i == 1) {
                    continue;
                }
                $rows[] = $data;
            }
            fclose($handle);
        }

        var_dump($rows);

        $this->updateLog('sfdfsfs');

        $output->writeln('Success!');
    }

    private function validate()
    {
        if(!$this->fs->exists(self::PARSE_DIR)) {
            $this->errors[] = 'Upload folder doesn\'t exists!';
        }

        if(!$this->fs->exists(self::PARSE_DIR . '/' . self::ARCHIVE_FILENAME)) {
            $this->errors[] = 'Upload zip file doesn\'t exists!';
        } else {
            $this->prepareFiles();
        }

        if(!$this->fs->exists(self::PARSE_DIR . '/' . self::CSV_FILENAME)) {
            $this->errors[] = 'Upload CSV file doesn\'t exists!';
        }

        $lastDate = $this->em->getRepository('AppBundle:ParserLog')->findOneBy([], ['fileDate' => 'DESC']);

        if($lastDate && $lastDate->getFileDate()->format("F d Y H:i:s.") == date("F d Y H:i:s.", filectime(self::PARSE_DIR . '/' . self::CSV_FILENAME))) {
            $this->errors[] = 'Upload CSV file doesn\'t changed!';
        }
    }

    private function prepareFiles() {
        $zip = new ZipArchive;
        if ($zip->open(self::PARSE_DIR . '/' . self::ARCHIVE_FILENAME) === TRUE) {
            $zip->extractTo($this->tmpFolder);
            $zip->close();
        } else {
            $this->errors[] = 'Can\'t extract to temp folder';
        }
    }

    private function updateLog($message) {
        $entity = new ParserLog();
        $entity->setMessage($message);
        $entity->setFileDate(new \DateTime(date("F d Y H:i:s.", filectime(self::PARSE_DIR . '/' . self::CSV_FILENAME))));
        $entity->setUpdatedAt(new \DateTime());
        $this->em->persist($entity);
        $this->em->flush();
    }
}