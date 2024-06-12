<?php

namespace App\Controller\Api;

use App\Entity\Album;
use App\Repository\AlbumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use OpenApi\Attributes as OA;

#[OA\Tag(name: "Album")]
class AlbumController extends AbstractController
{
    public function __construct(
        private AlbumRepository $albumRepository,
        private EntityManagerInterface $em,
        private SerializerInterface $serializer
    )
    {
        // ...
    }


    #[Route('/api/album', name: 'app_api_album', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $album = $this->albumRepository->findAll();

        return $this->json([
            'album' => $album,
        ], 200, [], [
            'groups' => ['read']
        ]);
    }

    #[Route('/api/album/{id}', name: 'app_api_album_get',  methods: ['GET'])]
    public function get(?Album $album = null): JsonResponse
    {
        if(!$album)
        {
            return $this->json([
                'error' => 'Ressource does not exist',
            ], 404);
        }

        return $this->json($album, 200, [], [
            'groups' => ['read']
        ]);
    }

    #[Route('/api/album', name: 'app_api_album_add',  methods: ['POST'])]
    public function add(
        #[MapRequestPayload('json', ['groups' => ['create']])] Album $album
    ): JsonResponse
    {
        $this->em->persist($album);
        $this->em->flush();
        
        return $this->json($album, 200, [], [
            'groups' => ['read']
        ]);
    }

    
    #[Route('/api/album/{id}', name: 'app_api_album_update',  methods: ['PUT'])]
    public function update(Album $album, Request $request): JsonResponse
    {
        
        $data = $request->getContent();
        $this->serializer->deserialize($data, album::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $album,
            'groups' => ['update']
        ]);

        $this->em->flush();

        return $this->json($album, 200, [], [
            'groups' => ['read'],
        ]);
    }

    #[Route('/api/album/{id}', name: 'app_api_album_delete',  methods: ['DELETE'])]
    public function delete(Album $album): JsonResponse
    {
        $this->em->remove($album);
        $this->em->flush();

        return $this->json([
            'message' => 'album deleted successfully'
        ], 200);
    }
}
