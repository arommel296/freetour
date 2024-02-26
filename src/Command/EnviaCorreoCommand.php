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
use App\Service\CorreoManager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;

class EnviaCorreoCommand extends Command
{
    // private $entityManager;
    private $correo;

    public function __construct(CorreoManager $correoManager)
    {
        $this->correo = $correoManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:envia-correo')
            ->setDescription('Envía un correo con el contenido que se desee y al destinatario que le indiques.');
            // ->addArgument('correo', InputArgument::REQUIRED, 'Introduce el correo del destinatario')
            // ->addArgument('subject', InputArgument::REQUIRED, 'Introducir el Título del correo')
            // ->addArgument('text', InputArgument::REQUIRED, 'Introduce el texto del cuerpo del correo');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $correo = $io->ask('Introduce el correo del destinatario');
        while ($correo=="") {
            $correo = $io->ask('Introduce el correo del destinatario');
        }

        $subject = $io->ask('Introduce el titulo del correo');
        while ($subject=="") {
            $subject = $io->ask('Introduce el titulo del correo');
        }

        $text = $io->ask('Introduce el texto del cuerpo del correo');
        while ($text=="") {
            $text = $io->ask('Introduce el texto del cuerpo del correo');
        }

        $this->correo->sendEmail($correo, $subject, $text);
        
        return Command::SUCCESS;
    }
}