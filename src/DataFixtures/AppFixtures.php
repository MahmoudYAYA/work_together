<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Client;
use App\Entity\Baie;
use App\Entity\Unite;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // ========================================
        // 1. CRÉATION DES USERS
        // ========================================
        echo "Création des utilisateurs...\n";

        // Admin
        $admin = new User();
        $admin->setEmail('admin@worktogether.fr');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $admin->setNom('Dupont');
        $admin->setPrenom('Marie');
        $admin->setDateCreation(new \DateTime());
        $admin->setActif(true);
        $manager->persist($admin);

        // Comptable
        $comptable = new User();
        $comptable->setEmail('comptable@worktogether.fr');
        $comptable->setRoles(['ROLE_COMPTABLE']);
        $comptable->setPassword($this->passwordHasher->hashPassword($comptable, 'compta123'));
        $comptable->setNom('Martin');
        $comptable->setPrenom('Jean');
        $comptable->setDateCreation(new \DateTime());
        $comptable->setActif(true);
        $manager->persist($comptable);

        echo "✓ 2 utilisateurs créés\n";

        // ========================================
        // 2. CRÉATION DES CLIENTS
        // ========================================
        echo "Création des clients...\n";

        $clients = [];

        $client1 = new Client();
        $client1->setEmail('contact@techcorp.fr');
        $client1->setPassword($this->passwordHasher->hashPassword($client1, 'client123'));
        $client1->setNom('Durand');
        $client1->setPrenom('Sophie');
        $client1->setSociete('TechCorp SAS');
        $client1->setTelephone('0601020304');
        $client1->setAdresse('12 rue de la Tech');
        $client1->setVille('Paris');
        $client1->setPays('France');
        $client1->setDateCreation(new \DateTime());
        $client1->setActif(true);
        $manager->persist($client1);
        $clients[] = $client1;

        $client2 = new Client();
        $client2->setEmail('admin@startupxyz.com');
        $client2->setPassword($this->passwordHasher->hashPassword($client2, 'client123'));
        $client2->setNom('Smith');
        $client2->setPrenom('John');
        $client2->setSociete('StartupXYZ');
        $client2->setTelephone('0612345678');
        $client2->setAdresse('5 avenue Innovation');
        $client2->setVille('Lyon');
        $client2->setPays('France');
        $client2->setDateCreation(new \DateTime('-2 months'));
        $client2->setActif(true);
        $manager->persist($client2);
        $clients[] = $client2;

        $client3 = new Client();
        $client3->setEmail('contact@webagency.fr');
        $client3->setPassword($this->passwordHasher->hashPassword($client3, 'client123'));
        $client3->setNom('Leroux');
        $client3->setPrenom('Emma');
        $client3->setSociete('Web Agency France');
        $client3->setTelephone('0698765432');
        $client3->setAdresse('8 boulevard Digital');
        $client3->setVille('Marseille');
        $client3->setPays('France');
        $client3->setDateCreation(new \DateTime('-1 month'));
        $client3->setActif(true);
        $manager->persist($client3);
        $clients[] = $client3;

        echo "✓ 3 clients créés\n";

        // ========================================
        // 3. CRÉATION DES BAIES + UNITÉS
        // ========================================
        echo "Création des baies et unités...\n";

        $baies = [];

        // Créer 5 baies pour tester (on fera les 30 plus tard)
        for ($b = 1; $b <= 5; $b++) {
            $baie = new Baie();
            $baie->setNumero('B' . str_pad((string)$b, 3, '0', STR_PAD_LEFT)); // B001, B002, etc.
            $baie->setCapaciteTotale(42);
            $manager->persist($baie);
            $baies[] = $baie;

            // Créer 42 unités pour chaque baie
            for ($u = 1; $u <= 42; $u++) {
                $unite = new Unite();
                $unite->setNumero('U' . str_pad((string)$u, 2, '0', STR_PAD_LEFT)); // U01, U02, etc.
                $unite->setBaie($baie);
                $unite->setStatut('libre');
                $unite->setEtat('OK');
                $unite->setNomPersonnalise(null); // Pas de nom par défaut
                $manager->persist($unite);
            }
        }

        echo "✓ 5 baies créées (B001 à B005)\n";
        echo "✓ 210 unités créées (5 × 42)\n";

        // ========================================
        // 4. OCCUPER QUELQUES UNITÉS (pour tester)
        // ========================================
        echo "Attribution de quelques unités aux clients...\n";

        // Client 1 : 10 unités dans B001
        $unitesB001 = $manager->getRepository(Unite::class)->findBy(['baie' => $baies[0]], null, 10);
        foreach ($unitesB001 as $index => $unite) {
            $unite->setStatut('occupe');
            $unite->setNomPersonnalise('Serveur-' . ($index + 1));
        }

        // Client 2 : 5 unités dans B002
        $unitesB002 = $manager->getRepository(Unite::class)->findBy(['baie' => $baies[1]], null, 5);
        foreach ($unitesB002 as $index => $unite) {
            $unite->setStatut('occupe');
            $unite->setNomPersonnalise('Web-Server-' . ($index + 1));
        }

        echo "✓ 15 unités attribuées\n";

        // ========================================
        // 5. SAUVEGARDER TOUT
        // ========================================
        $manager->flush();

        echo "\n";
        echo "========================================\n";
        echo "✅ Fixtures chargées avec succès !\n";
        echo "========================================\n";
        echo "👤 Users : 2 (admin, comptable)\n";
        echo "👥 Clients : 3\n";
        echo "📦 Baies : 5 (B001 à B005)\n";
        echo "📍 Unités : 210 (42 par baie)\n";
        echo "🔴 Unités occupées : 15\n";
        echo "🟢 Unités libres : 195\n";
        echo "========================================\n";
        echo "\n";
        echo "🔑 Identifiants de connexion :\n";
        echo "Admin      : admin@worktogether.fr / admin123\n";
        echo "Comptable  : comptable@worktogether.fr / compta123\n";
        echo "Client 1   : contact@techcorp.fr / client123\n";
        echo "Client 2   : admin@startupxyz.com / client123\n";
        echo "Client 3   : contact@webagency.fr / client123\n";
        echo "========================================\n";
    }
}