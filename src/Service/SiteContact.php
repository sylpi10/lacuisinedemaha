<?php

namespace App\Service;

use App\Repository\ContactPageRepository;
use Symfony\Contracts\Service\ResetInterface;
use Twig\Attribute\AsTwigFunction;

/**
 * Adresse de contact du site, modifiable dans l'admin (page Contact).
 * Une seule requête par page, même si l'adresse est affichée à plusieurs endroits.
 */
final class SiteContact implements ResetInterface
{
    // si le champ est vidé dans l'admin, le site garde une adresse valide
    public const DEFAULT_EMAIL = 'contact@lacuisinedemaha31.fr';

    private ?string $email = null;

    public function __construct(private readonly ContactPageRepository $contactPageRepository)
    {
    }

    #[AsTwigFunction('contact_email')]
    public function getEmail(): string
    {
        return $this->email ??= trim($this->contactPageRepository->getContent()?->getEmail() ?? '') ?: self::DEFAULT_EMAIL;
    }

    // vide le cache entre deux requêtes si le kernel est réutilisé (worker FrankenPHP)
    public function reset(): void
    {
        $this->email = null;
    }
}
