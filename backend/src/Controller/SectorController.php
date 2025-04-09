<?php

namespace App\Controller;

use App\Service\UserSubmissionService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use App\Exception\NoSessionException;

final class SectorController extends AbstractController
{
    private UserSubmissionService $userSubmissionService;
    private LoggerInterface $logger;

    public function __construct(UserSubmissionService $userSubmissionService, LoggerInterface $logger)
    {
        $this->userSubmissionService = $userSubmissionService;
        $this->logger = $logger;
    }

    #[Route('/api/sectors', name: 'get_sectors', methods: ['GET'])]
    public function getSectors(): JsonResponse
    {
        try {
            $sectors = $this->userSubmissionService->getSectors();
            return $this->json($sectors);
        } catch (\Exception $e) {
            $this->logger->error('Error fetching sectors: ' . $e->getMessage());
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/save', name: 'save_submission', methods: ['POST'])]
    public function save(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $this->userSubmissionService->saveSubmission($data);
            return $this->json(['message' => 'Data saved successfully!'], Response::HTTP_OK);
        } catch (\InvalidArgumentException $e) {
            $this->logger->warning('Invalid data during save: ' . $e->getMessage());
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (NoSessionException $e) {
            $this->logger->error('Session error: ' . $e->getMessage());
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            $this->logger->error('Unexpected error during save: ' . $e->getMessage());
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/me', name: 'get_user_data', methods: ['GET'])]
    public function getMe(): JsonResponse
    {
        try {
            $userData = $this->userSubmissionService->getUserData();

            if ($userData) {
                return $this->json($userData);
            }

            return $this->json(['error' => 'No user data found in session.'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            $this->logger->error('Error fetching user data: ' . $e->getMessage());
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
