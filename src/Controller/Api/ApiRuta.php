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
use App\Service\FileUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

#[Route('/api/ruta')]
class ApiRuta extends AbstractController
{
    private $entityManager;
    private $fileUploaderService;
    public function __construct(EntityManagerInterface $entityManager, FileUploaderService $fileUploaderService) {
        $this->entityManager = $entityManager;
        $this->fileUploaderService = $fileUploaderService;
    }

    #[Route('/all', name: 'getRutas', methods: ['GET'])]
    public function getRutas(): Response
    {
        $rutas = $this->entityManager->getRepository(Ruta::class)->findAll();
        $rutasJson = json_encode($rutas);
        if ($rutasJson == null) {
            return new Response(null, 404, $headers = ["no se han encontrado Rutas"]);
        }
        return new Response($rutasJson, 200, $headers = ["Content-Type" => "application/json"]);
    }

    #[Route('/unica/{id}', name: 'getRuta', methods: ['GET'])]
    public function getRuta($id): Response
    {
        $ruta = $this->entityManager->getRepository(Ruta::class)->find($id);
        if ($ruta == null) {
            return new Response(null, 404, $headers = ["No se ha encontrado la Ruta"]);
        }
        $rutaJson = json_encode($ruta);
        return new Response($rutaJson, 200, $headers = ["Content-Type" => "application/json"]);
    }

    // #[Route('/mejores/{pagina}', name: 'getMejoresRutas', methods: ['GET'])]
    // public function getMejoresRutas(): Response
    // {
    //     $rutas = $this->entityManager->getRepository(Ruta::class)->findMejoresRutas();
    //     $rutasJson = json_encode($rutas);
    //     if ($rutasJson == null) {
    //         return new Response(null, 404, $headers = ["no se han encontrado Rutas"]);
    //     }
    //     return new Response($rutasJson, 200, $headers = ["Content-Type" => "application/json"]);
    // }

    // #[Route('/mejores/{pagina}', name: 'getPaginaRutas', methods: ['GET'])]
    // public function getPaginaRutas(): Response
    // {
    //     $rutas = $this->entityManager->getRepository(Ruta::class)->findMejoresRutas();
    //     $rutasJson = json_encode($rutas);
    //     if ($rutasJson == null) {
    //         return new Response(null, 404, $headers = ["no se han encontrado Rutas"]);
    //     }
    //     return new Response($rutasJson, 200, $headers = ["Content-Type" => "application/json"]);
    // }


    //No hace falta con el método general que comprueba los query params de la ruta
    #[Route('/localidad/{nombre}', name: 'getRutasByLocalidad', methods: ['GET'])]
    public function getRutasByLocalidad($nombre): JsonResponse
    {
        $localidad = $this->entityManager->getRepository(Localidad::class)->findOneBy(['nombre' => $nombre]);

        if ($localidad === null) {
            // Handle the case where no Localidad with the given name was found
            return new JsonResponse(null, 404, $headers = ["No se ha encontrado la localidad"]);
        }
        $items = $this->entityManager->getRepository(Item::class)->findBy(['localidad' => $localidad]);
        // var_dump($items);

        $rutas = [];
        foreach ($items as $item) {
            // obtiene las Rutas de cada item
            // var_dump($item->getLocalidad()->getNombre());

            // if ($item->getLocalidad()->getNombre()==$nombre) {
            //     rutas[]=
            // }

            // if ($item->get) {
            //     rutas[]=
            // }
            $rutasItem=[];
            $rutasItem[] = $this->entityManager->getRepository(Ruta::class)->findBy(['items' => $item]);
            //Añade las Rutas al array de rutas en bruto
            $rutas = array_merge($rutas, $rutasItem);
        }
        // Elimina las Rutas duplicadas
        $rutas = array_unique($rutas);
        $rutaJson=[];
        foreach ($rutas as $ruta) {
            $rutaJson[] = $ruta->jsonSerialize();
        }

        if ($rutaJson == []) {
            return new JsonResponse(null, 404, $headers = ["no se han encontrado rutas"]);
        }
        return new JsonResponse($rutaJson, 200, $headers = ["Content-Type" => "application/json"]);
    }

    #[Route('/pagina/{page}', name: 'getRutasPaginadas', methods: ['GET'])]
    public function getRutasPaginadas($page, Request $request): Response
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $resultado = $queryBuilder->select('ruta')
                    ->from('App:Ruta', 'ruta')
                    ->join('ruta.itemRuta', 'itemRuta')
                    ->join('itemRuta.item', 'item')
                    ->setFirstResult(($page-1)*9)
                    ->setMaxResults(18);
        
        // $parametros = $request->query->get('localidad');
        // var_dump($parametros);

        if ($request->query->get('localidad')) {
            $queryBuilder
                        ->andWhere('item.localidad = :localidad')
                        ->setParameter('localidad', $request->query->get('localidad'));
        }
        
        if ($request->query->get('valoracion')) {
            $queryBuilder->andWhere('t.valoracion >= :valoracion')
               ->setParameter('valoracion', $request->query->get('valoracion'));
        }
    
        if ($request->query->get('fecha')) {
            $fecha = new \DateTime($request->query->get('fecha'));
            $queryBuilder->andWhere('t.fecha >= :fecha')
               ->setParameter('fecha', $fecha);
        }

        // $rutas = $this->entityManager->getRepository(Ruta::class)->findAll();
        // $rutasJson = json_encode($rutas);
        $rutasJson = json_encode($resultado);
        if ($rutasJson == null) {
            return new Response(null, 404, $headers = ["no se han encontrado Rutas"]);
        }
        return new Response($rutasJson, 200, $headers = ["Content-Type" => "application/json"]);
    }



    #[Route('/guardaRuta', name: 'guardaRuta', methods: ['POST'])]
    public function creaRuta(HttpFoundationRequest $request): Response
    {
        try {
            $data = $request->getContent();
            // var_dump($request->request->all());

            // if ($data) {
                $foto = $request->files->get('foto');
                $nombre = $request->request->get('nombre');
                $coordInicio = $request->request->get('coordInicio');
                $descripcion = $request->request->get('descripcion');
                $inicio = $request->request->get('inicio');
                $fin = $request->request->get('fin');
                $aforo = $request->request->get('aforo');
                $programacion = json_decode($request->request->get('programacion'), true);
                $items = json_decode($request->request->get('items'), true);

                //Se sube el fichero al servidor
                $fileName = $this->fileUploaderService->upload($foto);

                //Creación de ruta nueva
                $ruta = new Ruta();
                $ruta->setNombre($nombre);
                $ruta->setCoordInicio($coordInicio);
                $ruta->setDescripcion($descripcion);
                $ruta->setFoto($fileName);
                $ruta->setInicio(new \DateTime($inicio));
                $ruta->setFin(new \DateTime($fin));
                $ruta->setAforo($aforo);
                $ruta->setProgramacion($programacion);
                // Añade cada item a la ruta
                foreach ($items as $itemId) {
                    $item = $this->entityManager->getRepository(Item::class)->find($itemId);
                    $ruta->addItem($item);
                }
                $this->entityManager->persist($ruta);
                $this->entityManager->flush();
                return new JsonResponse($ruta->jsonSerialize(), 201, $headers = ["Content-Type" => "application/json"]);
            // } else{
            //     throw new \Exception('No se han recibido datos');
            // }

        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }

    }
}