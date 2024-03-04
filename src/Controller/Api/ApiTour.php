<?php

namespace App\Controller\Api;

use App\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Usuario;
use App\Entity\Localidad;
use App\Entity\Ruta;
use App\Entity\Tour;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api/tour')]
class ApiTour extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    #[Route('/unico/{id}', name: 'getTour', methods: ['GET'])]
    public function getTour(Tour $tour): Response
    {
        $tr = $tour->getId();
        if($tr == null){
            return new Response(null, 404, $headers = ["no se ha encontrado el tour"]);
        }else{
            // $url = $this->generateUrl('localidad_json', ['id' => $localidad->getId()]);
            return new Response($tr, 200, $headers = ["Content-Type" => "application/json"]);
        }
    }

    #[Route('/all', name: 'getTours', methods: ['GET'])]
    public function getTours(): Response
    {
        $tours = $this->entityManager->getRepository(Tour::class)->findAll();

        $toursJson = [];
        foreach ($tours as $tour) {
            $toursJson[] = $tour->jsonSerialize();
        }

        // $toursJson = json_encode($tours);
        if ($toursJson == null) {
            return new Response(null, 404, $headers = ["no se han encontrado tours"]);
        }
        return new JsonResponse($toursJson, 200, $headers = ["Content-Type" => "application/json"]);
    }

    #[Route('/all/fechas/ruta/{id}', name: 'getFechasTours', methods: ['GET'])]
    public function getFechasTours($id): Response
    {
        $hoy = new \DateTime;
        $tours = $this->entityManager->getRepository(Tour::class)->findToursBiggerDate($hoy, $id);
        // $ruta = $this->entityManager->getRepository(Ruta::class)->find($id);
        // $tours = $ruta->getTours();
        // $tours = $this->entityManager->getRepository(Tour::class)->findAll();

        $toursJson = [];
        foreach ($tours as $tour) {
            $toursJson[] = json_encode($tour->getFechaHora());
        }

        // $toursJson = json_encode($tours);
        if ($toursJson == null) {
            return new Response(null, 404, $headers = ["no se han encontrado tours"]);
        }
        return new JsonResponse($toursJson, 200, $headers = ["Content-Type" => "application/json"]);
    }

    #[Route('/all/day/ruta/{id}/{fecha}', name: 'getToursOfDay', methods: ['GET'])]
    public function getToursOfDay($id, $fecha): Response
    {
        $fecha = \DateTime::createFromFormat('d-m-Y', $fecha);
        $tours = $this->entityManager->getRepository(Tour::class)->findToursDate($fecha, $id);

        $toursJson = [];
        foreach ($tours as $tour) {
            $tourJson['tour'] = $tour->jsonSerialize();
            $tourJson['aforo'] = $this->entityManager->getRepository(Tour::class)->findAvailableSeats($tour->getId());
            $toursJson[] = $tourJson;
        }

        if ($toursJson == null) {
            return new Response(null, 404, $headers = ["no se han encontrado tours"]);
        }
        return new JsonResponse($toursJson, 200, $headers = ["Content-Type" => "application/json"]);
    }


    #[Route('/guia/{id}', name: 'getToursByGuia', methods: ['GET'])]
    public function getItemsByGuia(Usuario $guia): Response
    {
        $tours = $this->entityManager->getRepository(Tour::class)->findBy(['usuario' => $guia]);
        $toursJson=[];
        foreach ($tours as $tour) {
            $toursJson[] = $tour->jsonSerialize();
        }

        if ($toursJson == []) {
            return new JsonResponse(null, 404, $headers = ["no se han encontrado tours"]);
        }
        return new JsonResponse($toursJson, 200, $headers = ["Content-Type" => "application/json"]);
    }

    #[Route('/all/today', name: 'getToursToday', methods: ['GET'])]
    public function getToursToday(): Response
    {
        $tours = $this->entityManager->getRepository(Localidad::class)->findAll();
        $toursJson = json_encode($tours);
        if ($toursJson == null) {
            return new Response(null, 404, $headers = ["no se han encontrado tours"]);
        }
        return new Response($toursJson, 200, $headers = ["Content-Type" => "application/json"]);
    }

    #[Route('/all/guia', name: 'getAllToursGuia', methods: ['GET'])]
    public function getAllToursGuia(): Response
    {
        $guia = $this->getUser();
        $fecha = new \DateTime;
        $fecha->setTime(0, 0);

        $fechaFin = clone $fecha;
        $fechaFin->setTime(23, 59, 59);

        $tours = $this->entityManager->getRepository(Tour::class)->createQueryBuilder('tour')
            ->where('tour.usuario = :guia')
            ->setParameters([
                'guia' => $guia,
            ])
            ->getQuery()
            ->getResult();

        $toursJson=[];
        foreach ($tours as $tour) {
            $toursJson[]=$tour->jsonSerialize();
        }

        if ($toursJson == null) {
            return new Response(null, 404, $headers = ["no se han encontrado tours"]);
        }
        return new JsonResponse($toursJson, 200, $headers = ["Content-Type" => "application/json"]);
    }

    #[Route('/today/guia', name: 'getToursGuia', methods: ['GET'])]
    public function getToursGuia(): Response
    {
        $guia = $this->getUser();
        $fecha = new \DateTime;
        $fecha->setTime(0, 0);

        $fechaFin = clone $fecha;
        $fechaFin->setTime(23, 59, 59);

        $tours = $this->entityManager->getRepository(Tour::class)->createQueryBuilder('tour')
            ->where('tour.usuario = :guia')
            ->andWhere('tour.fechaHora BETWEEN :fechaInicio AND :fechaFin')
            ->setParameters([
                'guia' => $guia,
                'fechaInicio' => $fecha,
                'fechaFin' => $fechaFin,
            ])
            ->getQuery()
            ->getResult();

        $toursJson=[];
        foreach ($tours as $tour) {
            $toursJson[]=$tour->jsonSerialize();
        }

        if ($toursJson == null) {
            return new Response(null, 404, $headers = ["no se han encontrado tours"]);
        }
        return new JsonResponse($toursJson, 200, $headers = ["Content-Type" => "application/json"]);
    }


    #[Route('/crear/masivo', name: 'creaToursAuto', methods: 'POST')]
    // Crea los tours de la ruta asociados en la programación.
    public function creaToursAuto(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true);

            $id = $data['id'];
            $ruta = $this->entityManager->find(Ruta::class, $id);
            $tours = $ruta->getProgramacion();

            // Cambia los días de la semana en español a inglés.
            $dias = [
                'lunes' => 'Mon',
                'martes' => 'Tue',
                'miercoles' => 'Wed',
                'jueves' => 'Thu',
                'viernes' => 'Fri',
                'sabado' => 'Sat',
                'domingo' => 'Sun',
            ];

            // Procesa cada tour de la programación
            foreach ($tours as $tour) {
                $this->procesaTours($tour, $dias, $id);
            }

            $this->entityManager->flush();
        } catch (\Throwable $th) {
            // Si ocurre un error, devuelve una respuesta JSON con un mensaje de error
            return new JsonResponse(["error" => "error al crear los tours: ".$th], 500, $headers = ["Content-Type" => "application/json"]);
        }
        // Si todo sale bien, devuelve una respuesta JSON con un 200
        return $this->json(['message' => 'Tour creado con éxito'], 201);
    }

    // Esta función cambia días de la semana en español a inglés.
    // private function cambiaDias(): array
    // {
    //     return [
    //         'lunes' => 'Mon',
    //         'martes' => 'Tue',
    //         'miercoles' => 'Wed',
    //         'jueves' => 'Thu',
    //         'viernes' => 'Fri',
    //         'sabado' => 'Sat',
    //         'domingo' => 'Sun',
    //     ];
    // }

    // Esta función procesa los datos de un tour y crea los tours correspondientes en la base de datos.
    // private function procesaTours(array $tourData, array $dayMap, int $id): void
    // {
    //     // Crea objetos DateTime para las fechas de inicio y fin del tour.
    //     $fecha_inicio = \DateTime::createFromFormat('d-m-Y', $tourData['fecha_inicio']);
    //     $fecha_fin = \DateTime::createFromFormat('d-m-Y', $tourData['fecha_fin']);

    //     // Cambia los días del tour a su equivalente en inglés.
    //     $dias = array_map(function ($dia) use ($dayMap) {
    //         return $dayMap[$dia];
    //     }, $tourData['dias']);

    //     // Crea un objeto DateTime para el horario del tour y obtiene el ID del guía.
    //     $horario = \DateTime::createFromFormat('H:i', $tourData['turno'][0][0]);
    //     $guia = $tourData['turno'][0][1];

    //     // Crea un periodo de tiempo que abarca desde la fecha de inicio hasta la fecha de fin del tour.
    //     $interval = new \DateInterval('P1D');
    //     $period = new \DatePeriod($fecha_inicio, $interval, $fecha_fin->modify('+1 day'));

    //     // Para cada día en el periodo de tiempo, si el día coincide con uno de los días del tour, crea un tour para ese día.
    //     foreach ($period as $date) {
    //         $dayOfWeek = $date->format('D');

    //         if (in_array($dayOfWeek, $dias)) {
    //             $this->creaTours($date, $tourData, $id, $guia);
    //         }
    //     }
    // }

    private function procesaTours(array $tourData, array $dias, int $id): void
    {
        $fecha_inicio = \DateTime::createFromFormat('d-m-Y', $tourData['fecha_inicio']);
        $fecha_fin = \DateTime::createFromFormat('d-m-Y', $tourData['fecha_fin']);

        $dias_tour = [];
        foreach ($tourData['dias'] as $dia) {
            $dias_tour[] = $dias[$dia];
        }

        // $horario = \DateTime::createFromFormat('H:i', $tourData['turno'][0][0]);
        $idGuia = $tourData['turno'][0][1];

        // $fecha_actual = clone $fecha_inicio;
        $fecha_actual = $fecha_inicio; // inicio de fecha actual al comienzo del periodo de los tours
        while ($fecha_actual <= $fecha_fin) {
            $diaSemana = $fecha_actual->format('D'); //Día d ela semana a string
            if (in_array($diaSemana, $dias_tour)) {
                $this->creaTours($fecha_actual, $tourData, $id, $idGuia);
            }
            $fecha_actual->modify('+1 day'); //Suma 1 día a la fecha actual
        }
    }


    // Esta función crea un tour en la base de datos.
    private function creaTours(\DateTime $date, array $tourData, int $id, int $idGuia): void
    {
        // Crea un nuevo objeto Tour.
        $tour = new Tour();

        // Crea un objeto DateTime para la fecha y hora del tour.
        $tourDateTime = \DateTime::createFromFormat('H:i', $tourData['turno'][0][0]);
        $tourDateTime->setDate($date->format('Y'), $date->format('m'), $date->format('d'));
        $tour->setFechaHora($tourDateTime);

        // Busca al usuario (guía) y la ruta en la base de datos y los asigna al tour.
        $user = $this->entityManager->find(Usuario::class, $idGuia);
        $tour->setUsuario($user);

        $ruta = $this->entityManager->find(Ruta::class, $id);
        $tour->setRuta($ruta);

        // Persiste el tour en la base de datos.
        $this->entityManager->persist($tour);
    }

    // private function procesaTours(array $tourData, array $dayMap, int $id): void
    // {
    //     // Crea objetos DateTime para las fechas de inicio y fin del tour.
    //     $fecha_inicio = \DateTime::createFromFormat('d-m-Y', $tourData['fecha_inicio']);
    //     $fecha_fin = \DateTime::createFromFormat('d-m-Y', $tourData['fecha_fin']);

    //     // Cambia los días de la semana en español a inglés usando un bucle foreach en lugar de array_map.
    //     $dias = [];
    //     foreach ($tourData['dias'] as $dia) {
    //         $dias[] = $dayMap[$dia];
    //     }

    //     // Crea un objeto DateTime para el horario del tour y obtiene el ID del guía.
    //     $horario = \DateTime::createFromFormat('H:i', $tourData['turno'][0][0]);
    //     $guia = $tourData['turno'][0][1];

    //     // Crea un periodo de tiempo que abarca desde la fecha de inicio hasta la fecha de fin del tour.
    //     $interval = new \DateInterval('P1D');
    //     $period = new \DatePeriod($fecha_inicio, $interval, $fecha_fin->modify('+1 day'));

    //     // Para cada día en el periodo de tiempo, si el día coincide con uno de los días del tour, crea un tour para ese día.
    //     foreach ($period as $date) {
    //         $dayOfWeek = $date->format('D');
    //         if (in_array($dayOfWeek, $dias)) {
    //             $this->creaTours($date, $tourData, $id, $guia);
    //         }
    //     }
    // }

    
}