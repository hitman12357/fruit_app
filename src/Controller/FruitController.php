<?php

namespace App\Controller;

use App\Repository\FruitRepository;
use App\Service\FruitGenerator;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FruitController extends AbstractController
{
    /** @var FruitGenerator */
    protected FruitGenerator $fruitGenerator;
    /** @var FruitRepository  */
    protected FruitRepository $fruitRepository;

    /**
     * @param FruitGenerator $fruitGenerator
     */
    public function __construct(
        FruitGenerator $fruitGenerator,
        FruitRepository $fruitRepository
    ) {
        $this->fruitGenerator = $fruitGenerator;
        $this->fruitRepository = $fruitRepository;
    }

    /**
     * @Route("/fruits", methods={"GET"})
     */
    public function index(): Response
    {
       try {
           $fruits = $this->fruitRepository->findAll();

           return $this->json($fruits,
               Response::HTTP_OK, [],
               ['content-type' => 'application/json']
           );
       } catch (Exception $exception) {
           return $this->json(
               ['status'=> false, 'error' => $exception->getMessage()],
               Response::HTTP_INTERNAL_SERVER_ERROR,
               ['content-type' => 'application/json']
           );
       }

    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/fruits/init", methods={"POST"})
     */
    public function init(
        Request $request
    ) : Response
    {
        try {
            $content = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
            $this->fruitGenerator->init($content);

            return $this->json(
                ['status' => true],
                Response::HTTP_OK,
                ['content-type' => 'application/json']
            );
        } catch (Exception $exception) {
            return $this->json(
                ['status'=> false, 'error' => $exception->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['content-type' => 'application/json']
            );
        }
    }
}
