<?php

namespace App\Controller;

use App\DataTransferObject\Salad as SaladDto;
use App\Entity\Salad;
use App\Entity\SaladFruit;
use App\Repository\FruitRepository;
use App\Repository\SaladFruitRepository;
use App\Repository\SaladRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use DateInterval;

class SaladController extends AbstractController
{
    /** @var SaladRepository  */
    private SaladRepository $saladRepository;
    /** @var FruitRepository */
    private FruitRepository $fruitRepository;
    /** @var Serializer  */
    private Serializer $serializer;
    /** @var ObjectManager  */
    protected ObjectManager $entityManager;
    /** @var AdapterInterface  */
    private AdapterInterface $adapter;

    /**
     * SaladController constructor
     */
    public function __construct(
        AdapterInterface $adapter,
        SaladRepository $saladRepository,
        FruitRepository $fruitRepository,
        ManagerRegistry $doctrine
    ) {
        $this->adapter = $adapter;
        $this->saladRepository = $saladRepository;
        $this->fruitRepository = $fruitRepository;
        $encoders = [new JsonEncoder()];
        $normalizers =[
            new ArrayDenormalizer(),
            new DateTimeNormalizer(),
            new ObjectNormalizer(
                null,
                null,
                null,
                new ReflectionExtractor()
            ),
        ];
        $this->serializer = new Serializer($normalizers, $encoders);
        $this->entityManager = $doctrine->getManager();
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     *
     * @Route("/salad", methods={"PUT"})
     */
    public function create(
        Request $request,
        ValidatorInterface $validator
    ): Response {
        try {
            /** @var Salad $salad */
            $salad = $this->serializer->deserialize($request->getContent(), Salad::class, 'json');
            /** @var SaladFruit $saladFruit */
            foreach ($salad->getFruits() as $saladFruit) {
                $saladFruit->setSalad($salad);
                $fruit = $this->fruitRepository->findOneBy(['id'=>$saladFruit->getFruit()->getId()]);
                if(!is_null($fruit)) {
                    $saladFruit->setFruit($fruit);
                }
            }

            $errors = $validator->validate($salad);

            if (count($errors) > 0) {
                /*
                 * Uses a __toString method on the $errors variable which is a
                 * ConstraintViolationList object. This gives us a nice string
                 * for debugging.
                 */
                $errorsString = (string) $errors;

                return $this->json(
                    ['status'=> false, 'error' => $errorsString],
                    Response::HTTP_BAD_REQUEST,
                    ['content-type' => 'application/json']
                );
            }



            $this->entityManager->persist($salad);
            $this->entityManager->flush();

            return $this->json([], Response::HTTP_CREATED);
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
     * @param ValidatorInterface $validator
     * @return Response
     *
     * @Route("/salad", methods={"PATCH"})
     */
    public function update(
        Request $request,
        ValidatorInterface $validator
    ): Response {
        try {
            $id = $request->query->get('id');
            /** @var Salad $salad */
            $salad = $this->saladRepository->findOneBy(['id' => $id]);
            if(is_null($salad)) {
                return $this->json(
                    ['status'=> false, 'error' => 'Not found'],
                    Response::HTTP_NOT_FOUND,
                    ['content-type' => 'application/json']
                );
            }

            /** @var Salad $obSalad */
            $obSalad = $this->serializer->deserialize($request->getContent(), Salad::class, 'json');
            if($obSalad->getName()) {
                $salad->setName($obSalad->getName());
            }
            if($obSalad->getDescription()) {
                $salad->setDescription($obSalad->getDescription());
            }

            if($obSalad->getFruits()) {
                foreach ($salad->getFruits() as $fruit) {
                    $this->entityManager->remove($fruit);
                }
                $salad->setFruits(new ArrayCollection([]));
                /** @var SaladFruit $saladFruit */
                foreach ($obSalad->getFruits() as $saladFruit) {
                    $saladFruit->setSalad($salad);
                    $fruit = $this->fruitRepository->findOneBy(['id'=>$saladFruit->getFruit()->getId()]);
                    if(!is_null($fruit)) {
                        $saladFruit->setFruit($fruit);
                    }
                    $this->entityManager->persist($saladFruit);
                    $salad->addFruit($saladFruit);
                }
            }

            $errors = $validator->validate($salad);
            if (count($errors) > 0) {
                $errorsString = (string) $errors;

                return $this->json(
                    ['status'=> false, 'error' => $errorsString],
                    Response::HTTP_BAD_REQUEST,
                    ['content-type' => 'application/json']
                );
            }

            $this->entityManager->persist($salad);
            $this->entityManager->flush();

            return $this->json([], Response::HTTP_OK);
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
     * @Route("/salad", methods={"DELETE"})
     */
    public function delete(
        Request $request
    ): Response {
        $id = $request->query->get('id');
        $salad = $this->saladRepository->findOneBy(['id' => $id]);
        if(is_null($salad)) {
            return $this->json(
                ['status'=> false, 'error' => 'Not found'],
                Response::HTTP_NOT_FOUND,
                ['content-type' => 'application/json']
            );
        }
        try {
            $this->saladRepository->remove($salad);
            return $this->json([], Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->json(
                ['status'=> false, 'error' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['content-type' => 'application/json']
            );
        }
    }

    /**
     * @Route("/salads", methods={"GET"})
     */
    public function list(): Response
    {
        try {
            $items = $this->adapter->getItem("salad_list");

            if (!$items->isHit()) {
                $salads = $this->saladRepository->findAll();
                $result = $this->json($salads, Response::HTTP_OK, [],
                    ['groups' => ['list'],'content-type' => 'application/json']);
                $items->set($result);
                $items->expiresAfter(new DateInterval('PT1H'));
                $this->adapter->save($items);
            }

           return $items->get();
        } catch (Exception $e) {
            return $this->json(
                ['status'=> false, 'error' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['content-type' => 'application/json']
            );
        }
    }

    /**
     * @Route("/salad", methods={"GET"})
     */
    public function detail(
        Request $request
    ): Response
    {
        try {
            $itemId = $request->query->get('id');
            $item = $this->adapter->getItem("salad_detail_$itemId");

            if (!$item->isHit()) {
                $salad = $this->saladRepository->findOneBy(['id' => $itemId]);
                $result = $this->json(
                    $salad, Response::HTTP_OK, [],
                    ['groups' => ['detail'], 'content-type' => 'application/json']);
                $item->set($result);
                $item->expiresAfter(new DateInterval('PT1H'));
                $this->adapter->save($item);
            }

            return $item->get();
        } catch (Exception $e) {
            return $this->json(
                ['status'=> false, 'error' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['content-type' => 'application/json']
            );
        }
    }
}
