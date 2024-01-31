<?php
// src/Command/ImportJsonCommand.php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Provincia;
use App\Entity\Localidad;

class ImportJsonCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:import-json')
            ->setDescription('Importa un JSON a la base de datos.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $json = file_get_contents('/path/to/your/file.json');
        $data = json_decode($json, true);

        foreach ($data as $region) {
            $provincia = new Provincia();
            $provincia->setId(intval($region['code']));
            $provincia->setNombre($region['label']);

            $this->entityManager->persist($provincia);

            foreach ($region['provinces'] as $prov) {
                foreach ($prov['towns'] as $town) {
                    $localidad = new Localidad();
                    $localidad->setId(intval($town['code']));
                    $localidad->setNombre($town['label']);
                    $localidad->setProvincia($provincia);

                    $this->entityManager->persist($localidad);
                }
            }
        }

        $this->entityManager->flush();

        $output->writeln('Importación completada.');

        return Command::SUCCESS;
    }
}