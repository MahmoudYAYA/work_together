<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $numeroCommande = null;

    #[ORM\Column]
    private ?\DateTime $dateCommande = null;

    // ===== AJOUT 1 : Correction du montantTotal avec 2 décimales =====
    // AVANT : scale: 0 (pas de centimes) ❌
    // APRÈS : scale: 2 (avec centimes) ✅
    // Exemple : 1000.50 € s'enregistre correctement maintenant
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $montantTotal = null;

    // ===== AJOUT 2 : Correction du montantTVA avec 2 décimales =====
    // Même raison que montantTotal : besoin des centimes pour les taxes
    // Exemple : 200.00 € (TVA à 20% sur 1000€)
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $montantTVA = null;

    #[ORM\Column(length: 255)]
    private ?string $typeFacture = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateDebutService = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateFinService = null;

    // ===== AJOUT 3 : Relation ManyToOne vers Client =====
    // Une Commande appartient à UN Client
    // Un Client peut avoir PLUSIEURS Commandes
    // inversedBy='commandes' = le Client a une collection 'commandes'
    // nullable: false = une commande DOIT avoir un client (obligatoire)
    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    // ===== AJOUT 4 : Relation ManyToOne vers Offre =====
    // Une Commande est associée à UNE Offre (Base, Start-up, PME, Entreprise)
    // Plusieurs Commandes peuvent utiliser la même Offre
    // nullable: false = une commande DOIT avoir une offre (obligatoire)
    #[ORM\ManyToOne(targetEntity: Offre::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Offre $offre = null;

    // ===== AJOUT 5 : Statut avec valeur par défaut =====
    // Représente l'état de la commande dans son cycle de vie
    // options: ['default' => '...'] = valeur par défaut en BD
    // Valeurs possibles : 'En attente paiement', 'Payée', 'Livrée', 'Annulée'
    // Exemple de flux : En attente paiement → Payée → Livrée
    #[ORM\Column(length: 50, options: ['default' => 'En attente paiement'])]
    private string $statut = 'En attente paiement';

    // ===== AJOUT 6 : Type de paiement (nullable) =====
    // Enregistre COMMENT le client a payé
    // Valeurs : 'Carte', 'Virement', 'Chèque', etc.
    // nullable: true = optionnel (on le remplit quand il paie)
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $typePaiement = null;

    // ===== AJOUT 7 : Numéro de facture (nullable) =====
    // Format unique : FAC-2024-12345
    // nullable: true = généré seulement quand la commande est payée
    // Exemple : "FAC-2024-00001"
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $numeroFacture = null;

    // ===== AJOUT 8 : Date de paiement (nullable) =====
    // Enregistre QUAND le client a payé
    // nullable: true = null tant que le paiement n'est pas reçu
    // Exemple : 2024-12-14 14:30:00
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $datePaiement = null;

    // ===== AJOUT 9 : Date de création (immutable) =====
    // Enregistre QUAND la commande a été créée
    // DateTimeImmutable = impossible à changer après création (sécurité)
    // Utilisé pour l'audit et la traçabilité
    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $dateCreation;

    // ===== AJOUT 10 : Constructeur pour initialiser dateCreation =====
    // S'exécute AUTOMATIQUEMENT à la création d'une nouvelle Commande
    // Fixe la date/heure actuelle
    // Exemple : new Commande() → dateCreation = "2024-12-14 14:30:00"
    public function __construct()
    {
        $this->dateCreation = new \DateTimeImmutable();
    }

    // ============ GETTERS ORIGINAUX (inchangés) ============

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getnumeroCommande(): ?string
    {
        return $this->numeroCommande;
    }

    public function setnumeroCommande(string $numeroCommande): static
    {
        $this->numeroCommande = $numeroCommande;

        return $this;
    }

    public function getDateCommande(): ?\DateTime
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTime $dateCommande): static
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getMontantTotal(): ?string
    {
        return $this->montantTotal;
    }

    public function setMontantTotal(string $montantTotal): static
    {
        $this->montantTotal = $montantTotal;

        return $this;
    }

    public function getMontantTVA(): ?string
    {
        return $this->montantTVA;
    }

    public function setMontantTVA(string $montantTVA): static
    {
        $this->montantTVA = $montantTVA;

        return $this;
    }

    public function getTypeFacture(): ?string
    {
        return $this->typeFacture;
    }

    public function setTypeFacture(string $typeFacture): static
    {
        $this->typeFacture = $typeFacture;

        return $this;
    }

    public function getDateDebutService(): ?\DateTime
    {
        return $this->dateDebutService;
    }

    public function setDateDebutService(\DateTime $dateDebutService): static
    {
        $this->dateDebutService = $dateDebutService;

        return $this;
    }

    public function getDateFinService(): ?\DateTime
    {
        return $this->dateFinService;
    }

    public function setDateFinService(\DateTime $dateFinService): static
    {
        $this->dateFinService = $dateFinService;

        return $this;
    }

    // ============ AJOUT 11 : GETTERS POUR LES NOUVELLES RELATIONS ============

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;
        return $this;
    }

    public function getOffre(): ?Offre
    {
        return $this->offre;
    }

    public function setOffre(?Offre $offre): self
    {
        $this->offre = $offre;
        return $this;
    }

    // ============ AJOUT 12 : GETTERS/SETTERS POUR STATUT ET PAIEMENT ============

    public function getStatut(): string
    {
        return $this->statut;
    }

    // ===== AJOUT 12a : setStatut avec VALIDATION =====
    // Vérifie que le statut est VALIDE avant de l'assigner
    // Lance une exception si valeur invalide
    // Évite les erreurs : ex "En attente paiement2" (typo)
    public function setStatut(string $statut): self
    {
        $valid_statuts = ['En attente paiement', 'Payée', 'Livrée', 'Annulée'];
        if (!in_array($statut, $valid_statuts)) {
            throw new \InvalidArgumentException('Statut invalide : ' . $statut);
        }
        $this->statut = $statut;
        return $this;
    }

    public function getTypePaiement(): ?string
    {
        return $this->typePaiement;
    }

    public function setTypePaiement(?string $typePaiement): self
    {
        $this->typePaiement = $typePaiement;
        return $this;
    }

    public function getNumeroFacture(): ?string
    {
        return $this->numeroFacture;
    }

    public function setNumeroFacture(?string $numeroFacture): self
    {
        $this->numeroFacture = $numeroFacture;
        return $this;
    }

    public function getDatePaiement(): ?\DateTimeInterface
    {
        return $this->datePaiement;
    }

    public function setDatePaiement(?\DateTimeInterface $datePaiement): self
    {
        $this->datePaiement = $datePaiement;
        return $this;
    }

    public function getDateCreation(): \DateTimeImmutable
    {
        return $this->dateCreation;
    }

    // ============ AJOUT 13 : MÉTHODES MÉTIER (logique applicative) ============

    // ===== AJOUT 13a : calculerMontantTTC =====
    // Calcule le montant TOTAL (HT + TVA)
    // Exemple : HT=1000, TVA=200 → TTC=1200
    // Utilisé pour afficher le prix final au client
    public function calculerMontantTTC(): float
    {
        $ht = (float)$this->montantTotal;
        $tva = (float)$this->montantTVA;
        return $ht + $tva;
    }

    // ===== AJOUT 13b : calculerTauxTVA =====
    // Calcule le pourcentage de TVA appliqué
    // Exemple : HT=1000, TVA=200 → Taux=20%
    // Utilisé pour les rapports comptables
    public function calculerTauxTVA(): float
    {
        $ht = (float)$this->montantTotal;
        if ($ht === 0) {
            return 0;
        }
        $tva = (float)$this->montantTVA;
        return ($tva / $ht) * 100;
    }

    // ===== AJOUT 13c : calculerDureeService =====
    // Calcule le nombre de jours entre dateDebutService et dateFinService
    // Exemple : du 01/01/2024 au 31/01/2024 = 31 jours
    // Utilisé pour facturation à la durée
    public function calculerDureeService(): int
    {
        if ($this->dateDebutService === null || $this->dateFinService === null) {
            return 0;
        }
        $debut = $this->dateDebutService instanceof \DateTime ?
            $this->dateDebutService : new \DateTime($this->dateDebutService);
        $fin = $this->dateFinService instanceof \DateTime ?
            $this->dateFinService : new \DateTime($this->dateFinService);
        $interval = $debut->diff($fin);
        return $interval->days + 1;
    }

    // ===== AJOUT 13d : marquerPayee =====
    // Enregistre le paiement et change le statut
    // Paramètre : typePaiement ('Carte', 'Virement', etc.)
    // Exemple : $commande->marquerPayee('Carte'); → statut='Payée', datePaiement=now
    // Utilisé quand le paiement est reçu
    public function marquerPayee(string $typePaiement): void
    {
        if ($this->statut === 'En attente paiement') {
            $this->statut = 'Payée';
            $this->typePaiement = $typePaiement;
            $this->datePaiement = new \DateTime();
        }
    }

    // ===== AJOUT 13e : marquerLivree =====
    // Change le statut de 'Payée' à 'Livrée'
    // Exemple : $commande->marquerLivree(); → statut='Livrée'
    // Utilisé quand les unités sont affectées au client
    public function marquerLivree(): void
    {
        if ($this->statut === 'Payée') {
            $this->statut = 'Livrée';
        }
    }

    // ===== AJOUT 13f : annuler =====
    // Change le statut à 'Annulée' (si possible)
    // Ne peut PAS annuler si déjà 'Livrée'
    // Exemple : $commande->annuler(); → statut='Annulée'
    // Utilisé pour les remboursements
    public function annuler(): void
    {
        if ($this->statut !== 'Livrée') {
            $this->statut = 'Annulée';
        }
    }

    // ===== AJOUT 13g : genererNumeroFacture =====
    // Génère automatiquement un numéro de facture UNIQUE
    // Format : FAC-YYYY-XXXXX (ex: FAC-2024-00001)
    // Exemple : $commande->genererNumeroFacture(); → "FAC-2024-45678"
    // Utilisé pour la facturation
    public function genererNumeroFacture(): string
    {
        $annee = date('Y');
        $random = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $this->numeroFacture = "FAC-{$annee}-{$random}";
        return $this->numeroFacture;
    }

    // ===== AJOUT 13h : peutEtreAnnulee =====
    // Vérifie si la commande peut être annulée
    // Retourne false si déjà 'Livrée' ou 'Annulée'
    // Exemple : if ($commande->peutEtreAnnulee()) { ... }
    // Utilisé pour les contrôles avant annulation
    public function peutEtreAnnulee(): bool
    {
        return $this->statut !== 'Livrée' && $this->statut !== 'Annulée';
    }

    // ===== AJOUT 13i : estPayee =====
    // Vérifie si le paiement a été reçu
    // Retourne true si statut 'Payée' ou 'Livrée'
    // Exemple : if ($commande->estPayee()) { ... }
    // Utilisé pour vérifier l'état du paiement
    public function estPayee(): bool
    {
        return $this->statut === 'Payée' || $this->statut === 'Livrée';
    }

    // ===== AJOUT 13j : getMontantTTCFormate =====
    // Retourne le montant TTC formaté pour affichage
    // Format : "1 200,00 €" (français avec espace et virgule)
    // Exemple : echo $commande->getMontantTTCFormate(); → "1 200,00 €"
    // Utilisé pour l'affichage aux clients
    public function getMontantTTCFormate(): string
    {
        return number_format($this->calculerMontantTTC(), 2, ',', ' ') . ' €';
    }

    // ===== AJOUT 13k : getMontantHTFormate =====
    // Retourne le montant HT formaté
    // Format : "1 000,00 €"
    // Utilisé pour afficher les détails HT/TTC
    public function getMontantHTFormate(): string
    {
        return number_format((float)$this->montantTotal, 2, ',', ' ') . ' €';
    }

    // ===== AJOUT 13l : getMontantTVAFormate =====
    // Retourne la TVA formatée
    // Format : "200,00 €"
    // Utilisé pour afficher la décomposition du prix
    public function getMontantTVAFormate(): string
    {
        return number_format((float)$this->montantTVA, 2, ',', ' ') . ' €';
    }
}
