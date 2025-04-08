<?php

namespace App\Service;

use App\Entity\Sector;
use App\Entity\UserSubmission;
use App\Entity\UserSubmissionSector;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Psr\Log\LoggerInterface;
use App\Exception\NoSessionException;

final class UserSubmissionService
{
    private EntityManagerInterface $em;
    private LoggerInterface $logger;
    private RequestStack $requestStack;

    /**
     * UserSubmissionService constructor.
     *
     * @param EntityManagerInterface $em The EntityManagerInterface for handling database operations.
     * @param RequestStack $requestStack The RequestStack for handling session-related operations.
     * @param LoggerInterface $logger The logger to log messages for actions performed.
     */
    public function __construct(EntityManagerInterface $em, RequestStack $requestStack, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->requestStack = $requestStack;
        $this->logger = $logger;
    }

    /**
     * Retrieves a list of all sectors from the database.
     *
     * @return array A list of sectors with each sector's ID and label.
     */
    public function getSectors(): array
    {
        $sectors = $this->em->getRepository(Sector::class)->findAll();

        return array_map(fn($sector) => ['id' => $sector->getId(), 'label' => $sector->getLabel()], $sectors);
    }

    /**
     * Saves the user submission data to the database.
     *
     * @param array $data The submission data, including the name, agreed status, and selected sectors.
     *
     * @return UserSubmission The saved UserSubmission object.
     *
     * @throws NoSessionException If no session is available for the current request.
     * @throws InvalidArgumentException If any required field is missing or invalid.
     */
    public function saveSubmission(array $data): UserSubmission
    {
        $session = $this->getSession();

        $sessionId = $session->getId();

        if (empty($data['name']) || empty($data['sectors']) || !$data['agreed']) {
            throw new InvalidArgumentException('All fields are required.');
        }

        $submission = $this->em->getRepository(UserSubmission::class)
            ->findOneBy(['sessionId' => $sessionId]) ?? new UserSubmission();

        $submission->setSessionId($sessionId);
        $submission->setName($data['name']);
        $submission->setAgreed($data['agreed']);
        $this->em->persist($submission);
        $this->em->flush();

        $this->em->getRepository(UserSubmissionSector::class)->deleteBySubmission($submission);

        foreach ($data['sectors'] as $sectorId) {
            $sector = $this->em->getRepository(Sector::class)->find($sectorId);
            if (!$sector) {
                throw new InvalidArgumentException("Sector with ID $sectorId not found.");
            }

            $submissionSector = new UserSubmissionSector();
            $submissionSector->setUserSubmission($submission);
            $submissionSector->setSector($sector);
            $this->em->persist($submissionSector);
        }

        $this->em->flush();

        $session->set('user_data', [
            'name' => $submission->getName(),
            'agreed' => $submission->isAgreed(),
            'sessionId' => $sessionId,
        ]);

        $this->logger->info('Submission saved successfully.');

        return $submission;
    }


    /**
     * Retrieves the user data associated with the current session.
     *
     * @return array|null The user data including the name, agreed status, and selected sectors, or null if no submission exists.
     *
     * @throws NoSessionException If no session is available for the current request.
     */
    public function getUserData(): ?array
    {
        $session = $this->getSession();

        $sessionId = $session->getId();
        $submission = $this->em->getRepository(UserSubmission::class)->findOneBy(['sessionId' => $sessionId]);

        if (!$submission) {
            return null;
        }

        $sectors = $this->em->getRepository(UserSubmissionSector::class)->findBy(['userSubmission' => $submission]);

        return [
            'name' => $submission->getName(),
            'sectors' => array_map(fn($rel) => ['id' => $rel->getSector()->getId()], $sectors),
            'agreed' => $submission->isAgreed()
        ];
    }

    /**
     * Retrieves the current session for the request.
     *
     * @return SessionInterface The current session.
     *
     * @throws NoSessionException If no session is available for the current request.
     */
    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession() ?? throw new NoSessionException('No session available.');
    }


}
