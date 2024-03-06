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
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/ruta')]
class ApiRuta extends AbstractController
{
    private $entityManager;
    private $fileUploaderService;

    private $validator;
    public function __construct(EntityManagerInterface $entityManager, FileUploaderService $fileUploaderService, ValidatorInterface $validator) {
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

    #[Route('/fecha', name: 'getRutasByFecha', methods: ['GET'])]
    public function getRutasByFecha(Request $request): Response
    {
        $fechaBusqueda = $request->query->get('fecha');

        if (!$fechaBusqueda) {
            return $this->json([
                'error' => 'La fecha es requerida',
            ], 400);
        }

        $fechaBusqueda = new \DateTime($fechaBusqueda);

        $rutas = $this->entityManager->getRepository(Ruta::class)->buscarfechaenrango($fechaBusqueda);

        return $this->json([
            'rutas' => $rutas,
        ]);
    }


    
    #[Route('/guardaRuta', name: 'guardaRuta', methods: ['POST'])]
    public function creaRuta(HttpFoundationRequest $request): Response
    {
        try {
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
                return new JsonResponse(["idRuta" => $ruta->getId()], 201, $headers = ["Content-Type" => "application/json"]);
            // } else{
            //     throw new \Exception('No se han recibido datos');
            // }

        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }

    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/modificarRuta/{id}', name: 'modificarRuta', methods: ['POST'])]
    public function modificaRuta($id, HttpFoundationRequest $request): Response
    {
        try {
            $ruta = $this->entityManager->getRepository(Ruta::class)->find($id);

            if (!$ruta) {
                throw new \Exception('No se encontró la ruta con id ' . $id);
            }

            // $foto = $request->files->get('foto');
            // $nombre = $request->request->get('nombre');
            // $coordInicio = $request->request->get('coordInicio');
            // $descripcion = $request->request->get('descripcion');
            // $inicio = $request->request->get('inicio');
            // $fin = $request->request->get('fin');
            // $aforo = intval($request->request->get('aforo'));
            // $programacion = json_decode($request->request->get('programacion'), true);
            // $items = json_decode($request->request->get('items'), true);

            // Validaciones
            if ($request->files->has('foto')) {
                $foto = $request->files->get('foto');
                if ($foto){
                    $fileName = $this->fileUploaderService->upload($foto);
                    $ruta->setFoto($fileName);
                }

            }

            if ($request->request->has('nombre')) {
                $ruta->setNombre($request->request->get('nombre'));
            }

            if ($request->request->has('descripcion')) {
                $ruta->setDescripcion($request->request->get('descripcion'));
            }

            if ($request->request->has('coordInicio')) {
                $ruta->setCoordInicio($request->request->get('coordInicio'));
            }

            if ($request->request->has('aforo')) {
                $ruta->setAforo($request->request->get('aforo'));
            }

            if ($request->request->has('inicio')) {
                $ruta->setInicio(new \DateTime($request->request->get('inicio')));
            }

            if ($request->request->has('fin')) {
                $ruta->setFin(new \DateTime($request->request->get('fin')));
            }

            if ($request->request->has('items')) {
                $items = json_decode($request->request->get('items'), true);
                foreach ($ruta->getItems() as $item) {
                    $ruta->removeItem($item);
                }
                foreach ($items as $itemId) {
                    $item = $this->entityManager->getRepository(Item::class)->find($itemId);
                    $ruta->addItem($item);
                }
            }

            if ($request->request->has('programacion')) {
                $programacion = json_decode($request->request->get('programacion'), true);
                $ruta->setProgramacion($programacion);
            }

            $this->entityManager->flush();

            return new JsonResponse(["idRuta" => $ruta->getId()], 200, $headers = ["Content-Type" => "application/json"]);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

}