<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261003210503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Pages légales (mentions légales, CGV) éditables en BO';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE legal_page (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(50) NOT NULL, subtitle VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, meta_description VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_39715897989D9B62 (slug), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');

        // contenu de départ, à compléter ensuite en BO
        $insert = 'INSERT INTO legal_page (slug, subtitle, title, meta_description, content) VALUES (?, ?, ?, ?, ?)';
        $this->addSql($insert, [
            'mentions-legales',
            'Informations légales',
            'Mentions légales',
            'Mentions légales et politique de confidentialité du site La cuisine de Maha, cheffe à domicile à Pins-Justaret (31).',
            self::MENTIONS_LEGALES,
        ]);
        $this->addSql($insert, [
            'cgv',
            'Informations légales',
            'Conditions générales de vente',
            'Conditions générales de vente des prestations de La cuisine de Maha : devis, acompte, annulation, allergies et paiement.',
            self::CGV,
        ]);
    }

    private const MENTIONS_LEGALES = <<<'HTML'
<h2>Éditeur du site</h2>
<div>Le site est édité par [À COMPLÉTER : prénom et nom], entrepreneur individuel (micro-entreprise), sous le nom commercial « La cuisine de Maha ».<br>Adresse : [À COMPLÉTER : adresse postale], 31860 Pins-Justaret<br>SIRET : [À COMPLÉTER]<br>E-mail : <a href="mailto:contact@lacuisinedemaha31.fr">contact@lacuisinedemaha31.fr</a><br>Téléphone : [À COMPLÉTER]</div>
<div>Directeur de la publication : [À COMPLÉTER : prénom et nom].</div>
<div>TVA non applicable, article 293 B du Code général des impôts.</div>
<h2>Conception et développement</h2>
<div>Site conçu et développé par <a href="https://sylvainpillet.com">S Pillet</a>.</div>
<h2>Hébergement</h2>
<div>[À COMPLÉTER : nom de l'hébergeur]<br>[À COMPLÉTER : adresse de l'hébergeur]<br>[À COMPLÉTER : téléphone ou site de l'hébergeur]</div>
<h2>Propriété intellectuelle</h2>
<div>Les textes, photographies, logo et marque « La cuisine de Maha » sont la propriété de l'éditeur. Toute reproduction, totale ou partielle, sans autorisation écrite préalable est interdite.</div>
<h2>Données personnelles</h2>
<div>Le formulaire de contact recueille votre nom, votre adresse e-mail et, si vous les renseignez, votre téléphone, la formule souhaitée, la date envisagée, le nombre de convives et votre message. Ces données servent uniquement à répondre à votre demande et, le cas échéant, à établir un devis. La base légale est votre demande (mesures précontractuelles).</div>
<div>Elles sont destinées à l'éditeur seul, ne sont ni vendues ni transmises à des tiers, et sont conservées au maximum 3 ans après le dernier contact.</div>
<div>Conformément au RGPD et à la loi Informatique et Libertés, vous disposez d'un droit d'accès, de rectification, d'effacement, d'opposition et de limitation du traitement de vos données. Pour l'exercer, écrivez à <a href="mailto:contact@lacuisinedemaha31.fr">contact@lacuisinedemaha31.fr</a>. Vous pouvez également adresser une réclamation à la CNIL (<a href="https://www.cnil.fr">www.cnil.fr</a>).</div>
<h2>Cookies</h2>
<div>Le site n'utilise ni cookie publicitaire ni outil de mesure d'audience. Seul un cookie technique, nécessaire au fonctionnement du formulaire de contact, peut être déposé ; il ne requiert pas votre consentement.</div>
HTML;

    private const CGV = <<<'HTML'
<div>En vigueur au [À COMPLÉTER : date].</div>
<h2>1. Objet</h2>
<div>Les présentes conditions s'appliquent aux prestations de cuisine proposées par La cuisine de Maha ([À COMPLÉTER : prénom et nom], entrepreneur individuel, SIRET [À COMPLÉTER]) : brunchs, repas et petits événements, préparés au domicile du client ou en amont. Toute commande vaut acceptation de ces conditions.</div>
<h2>2. Devis et réservation</h2>
<div>Chaque prestation fait l'objet d'un devis gratuit, valable [À COMPLÉTER : 30] jours. Les tarifs affichés sur le site sont indicatifs ; seul le devis fait foi. La réservation est ferme à réception du devis signé et de l'acompte.</div>
<h2>3. Prix et acompte</h2>
<div>Les prix sont indiqués en euros, TVA non applicable (article 293 B du CGI). Ils comprennent [À COMPLÉTER : courses, préparation, service, rangement…]. Un acompte de [À COMPLÉTER : 30] % du montant total est demandé à la réservation. Les frais de déplacement au-delà de [À COMPLÉTER : X] km de Pins-Justaret sont précisés sur le devis.</div>
<h2>4. Nombre de convives et modifications</h2>
<div>Le nombre définitif de convives est confirmé au plus tard [À COMPLÉTER : 7] jours avant la prestation ; il sert de base à la facturation. Toute modification du menu après cette date reste possible selon la disponibilité des produits.</div>
<h2>5. Annulation</h2>
<div>En cas d'annulation par le client :</div>
<ul><li>plus de [À COMPLÉTER : 15] jours avant : l'acompte est remboursé ;</li><li>entre [À COMPLÉTER : 15] et [À COMPLÉTER : 7] jours avant : l'acompte est conservé ;</li><li>moins de [À COMPLÉTER : 7] jours avant : [À COMPLÉTER : 50] % du montant total est dû.</li></ul>
<div>En cas d'annulation par La cuisine de Maha, hors force majeure, les sommes versées sont intégralement remboursées.</div>
<h2>6. Allergies et régimes alimentaires</h2>
<div>Le client signale par écrit, au moment de la réservation, toute allergie, intolérance ou régime particulier des convives. La cuisine de Maha ne peut être tenue responsable d'une réaction liée à une information non communiquée. Les préparations peuvent contenir des traces d'allergènes.</div>
<h2>7. Prestation à domicile</h2>
<div>Le client met à disposition une cuisine en état de fonctionnement (plaques, four, réfrigérateur, eau) et en assure l'accès à l'heure convenue. Les plats non consommés relèvent de la responsabilité du client une fois la prestation terminée.</div>
<h2>8. Paiement</h2>
<div>Le solde est réglé [À COMPLÉTER : le jour de la prestation / sous X jours] par [À COMPLÉTER : virement, chèque, espèces]. Tout retard de paiement entraîne des pénalités au taux légal et, pour les clients professionnels, une indemnité forfaitaire de 40 € pour frais de recouvrement.</div>
<h2>9. Droit de rétractation</h2>
<div>Conformément à l'article L221-28 du Code de la consommation, le droit de rétractation ne s'applique pas aux prestations de restauration fournies à une date déterminée.</div>
<h2>10. Réclamations et médiation</h2>
<div>Toute réclamation est à adresser à <a href="mailto:contact@lacuisinedemaha31.fr">contact@lacuisinedemaha31.fr</a>. À défaut d'accord amiable, le client consommateur peut recourir gratuitement au médiateur de la consommation : [À COMPLÉTER : nom, adresse et site du médiateur].</div>
<h2>11. Données personnelles</h2>
<div>Le traitement des données personnelles est décrit dans les <a href="/mentions-legales">mentions légales</a>.</div>
<h2>12. Droit applicable</h2>
<div>Les présentes conditions sont soumises au droit français.</div>
HTML;

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE legal_page');
    }
}
