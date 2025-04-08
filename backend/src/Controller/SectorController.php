<?php

namespace App\Controller;

use App\Service\UserSubmissionService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SectorController extends AbstractController
{
    private $userSubmissionService;

    public function __construct(UserSubmissionService $userSubmissionService)
    {
        $this->userSubmissionService = $userSubmissionService;
    }

    #[Route('/api/sectors', name: 'get_sectors', methods: ['GET'])]
    public function getSectors(): JsonResponse
    {
        $sectors = $this->userSubmissionService->getSectors();
        return $this->json($sectors);
    }

    #[Route('/api/save', name: 'save_submission', methods: ['POST'])]
    public function save(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $this->userSubmissionService->saveSubmission($data);

            return $this->json(['message' => 'Data saved successfully!'], Response::HTTP_OK);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/api/me', name: 'get_user_data', methods: ['GET'])]
    public function getMe(): JsonResponse
    {
        $userData = $this->userSubmissionService->getUserData();

        if ($userData) {
            return $this->json($userData);
        }

        return $this->json(['error' => 'No user data found in session.'], Response::HTTP_NOT_FOUND);
    }
}
