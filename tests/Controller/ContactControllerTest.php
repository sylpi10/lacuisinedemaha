<?php

namespace App\Tests\Controller;

use App\Entity\Contact;
use App\Entity\ContactPage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Rejoue le formulaire de contact comme un navigateur : affichage, envoi, réponse.
 * Les contacts créés vont dans la base de test et sont supprimés à la fin.
 */
final class ContactControllerTest extends WebTestCase
{
    // domaine réservé aux tests : sert aussi à retrouver (et supprimer) les contacts créés ici
    private const EMAIL_DOMAIN = 'example.test';

    // page Contact créée par le test si la base de test n'en a pas, supprimée à la fin
    private ?int $createdContactPageId = null;

    /**
     * après un envoi refusé, le délai anti-spam repartait de zéro.
     * Un visiteur qui corrigeait vite son e-mail était pris pour un robot et son
     * message perdu, sans aucun message d'erreur.
     */
    public function testVisitorWhoQuicklyFixesAnErrorIsNotTakenForARobot(): void
    {
        $client = $this->createClientWithContactPage();
        $crawler = $client->request('GET', '/contact');

        // un humain met plus de 4s à remplir le formulaire
        sleep(5);

        // 1er envoi : e-mail sans point dans le domaine, accepté par le navigateur
        // mais refusé par Symfony → le formulaire est réaffiché avec l'erreur (422)
        $crawler = $client->submit($this->fillForm($crawler, 'jeanne@example'));
        self::assertResponseStatusCodeSame(422);
        self::assertSelectorTextContains('#contact_senderEmail_error1', 'Cet e-mail ne semble pas valide.');

        // 2e envoi, aussitôt, avec l'e-mail corrigé : il doit passer, car le délai
        // compte depuis le premier affichage et pas depuis le formulaire réaffiché
        $client->submit($this->fillForm($crawler, 'jeanne@'.self::EMAIL_DOMAIN));
        self::assertResponseRedirects('/contact');
        self::assertEmailCount(1);

        self::assertSame(1, $this->countContacts('jeanne@'.self::EMAIL_DOMAIN));

        $client->followRedirect();
        self::assertSelectorTextContains('#contact-form', 'Votre demande a bien été envoyée');
    }

    /**
     * La correction ne doit pas avoir désactivé l'anti-spam, un envoi valide
     * mais immédiat est toujours jeté, sans rien enregistrer ni envoyer.
     */
    public function testInstantSubmissionIsStillRejectedAsSpam(): void
    {
        $client = $this->createClientWithContactPage();
        $crawler = $client->request('GET', '/contact');

        $client->submit($this->fillForm($crawler, 'robot@'.self::EMAIL_DOMAIN));

        // le robot est redirigé comme un visiteur normal (aucun indice sur le rejet)...
        self::assertResponseRedirects('/contact');
        // ... mais rien n'est enregistré ni envoyé
        self::assertEmailCount(0);
        self::assertSame(0, $this->countContacts('robot@'.self::EMAIL_DOMAIN));
    }

    protected function tearDown(): void
    {
        // supprime les contacts créés par ces tests, pour laisser la base de test propre
        $this->entityManager()
            ->createQuery('DELETE FROM '.Contact::class.' c WHERE c.senderEmail LIKE :domain')
            ->setParameter('domain', '%@'.self::EMAIL_DOMAIN)
            ->execute();

        if (null !== $this->createdContactPageId) {
            $this->entityManager()
                ->createQuery('DELETE FROM '.ContactPage::class.' p WHERE p.id = :id')
                ->setParameter('id', $this->createdContactPageId)
                ->execute();
        }

        parent::tearDown();
    }

    /**
     * La page /contact affiche les textes de la page Contact éditée dans l'admin :
     * la base de test, vide au départ (en CI notamment), doit en contenir une.
     */
    private function createClientWithContactPage(): KernelBrowser
    {
        $client = static::createClient();

        $entityManager = $this->entityManager();
        if (null === $entityManager->getRepository(ContactPage::class)->findOneBy([])) {
            $page = new ContactPage()->setTitle('Contact');
            $entityManager->persist($page);
            $entityManager->flush();
            $this->createdContactPageId = $page->getId();
        }

        return $client;
    }

    /**
     * Remplit le formulaire affiché dans la page. Le jeton anti-spam, le champ
     * piège et le jeton CSRF sont des champs cachés de la page : ils sont repris
     * tels quels, comme le ferait un navigateur.
     */
    private function fillForm(Crawler $crawler, string $email): \Symfony\Component\DomCrawler\Form
    {
        $form = $crawler->selectButton('Envoyer la demande')->form([
            'contact[senderName]' => 'Jeanne Dupont',
            'contact[senderEmail]' => $email,
            'contact[senderMessage]' => 'Un dîner pour six le mois prochain.',
        ]);
        $form['contact[agreeTerms]']->tick();

        return $form;
    }

    private function countContacts(string $email): int
    {
        return $this->entityManager()->getRepository(Contact::class)->count(['senderEmail' => $email]);
    }

    private function entityManager(): EntityManagerInterface
    {
        return static::getContainer()->get(EntityManagerInterface::class);
    }
}
