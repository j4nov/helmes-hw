<?php

namespace App\Service;

use App\Entity\Sector;
use App\Entity\UserSubmission;
use App\Entity\UserSubmissionSector;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class UserSubmissionService
{
    private EntityManagerInterface $em;
    private RequestStack $requestStack;

    public function __construct(EntityManagerInterface $em, RequestStack $requestStack)
    {
        $this->em = $em;
        $this->requestStack = $requestStack;
    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }

    public function getSectors(): array
    {
        $sectors = $this->em->getRepository(Sector::class)->findAll();

        return array_map(fn($sector) => ['id' => $sector->getId(), 'label' => $sector->getLabel()], $sectors);
    }

    public function saveSubmission(array $data): UserSubmission
    {
        $session = $this->getSession() ?? throw new \RuntimeException('No session available.');

        $sessionId = $session->getId();

        if (empty($data['name']) || empty($data['sectors']) || !$data['agreed']) {
            throw new \InvalidArgumentException('All fields are required.');
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
                throw new \InvalidArgumentException("Sector with ID $sectorId not found.");
            }

            $submissionSector = new UserSubmissionSector();
            $submissionSector->setUserSubmission($submission);
            $submissionSector->setSector($sector);
            $this->em->persist($submissionSector);
        }

        $this->em->flush();

        $session->set('user_data', [
            'name' => $submission->getName(),
            'sectors' => $data['sectors'],
            'agreed' => $submission->isAgreed()
        ]);
        $session->set('user_id', $submission->getId());

        return $submission;
    }


    public function getUserData(): ?array
    {
        $session = $this->getSession() ?? throw new \RuntimeException('No session available.');

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

}
