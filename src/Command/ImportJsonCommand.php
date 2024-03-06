<?php
// src/Command/ImportJsonCommand.php
namespace App\Command;
use Symfony\Component\Console\Attribute\AsCommand;
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
            ->setDescription('Importa un JSON con todas las provincias y localidades de España a la base de datos.');
    }

    // #[AsCommand(
    //     name: 'app:import-json',
    //     description: 'Importa un JSON a la base de datos.',
    //     hidden: false,
    //     aliases: ['app:json-to-db'],
    // )]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $json = file_get_contents('public/pruebas/arbol.json');
        $data = json_decode($json, true);

        foreach ($data as $region) {
            foreach ($region['provinces'] as $prov) {
                $provincia = new Provincia();
                $provincia->setId(intval($prov['code']));
                $provincia->setNombre($prov['label']);

                $this->entityManager->persist($provincia);
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