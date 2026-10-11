<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\DemandesCouverture;
use App\Models\Livraison;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $reservationsByStatut = Reservation::query()
            ->select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->pluck('total', 'statut');

        $livraisonsByStatut = Livraison::query()
            ->select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->pluck('total', 'statut');

        $demandesByStatut = DemandesCouverture::query()
            ->select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->pluck('total', 'statut');

        $messagesByStatut = ContactMessage::query()
            ->select('est_lu', DB::raw('count(*) as total'))
            ->groupBy('est_lu')
            ->pluck('total', 'est_lu');

        $stats = [
            'reservations' => [
                'total' => Reservation::count(),
                'en_attente' => $reservationsByStatut->get(Reservation::EN_ATTENTE, 0),
                'confirmees' => $reservationsByStatut->get(Reservation::CONFIRMEE, 0),
                'terminees' => $reservationsByStatut->get(Reservation::TERMINEE, 0),
            ],
            'livraisons' => [
                'total' => Livraison::count(),
                'en_attente' => $livraisonsByStatut->get(Livraison::EN_ATTENTE, 0),
                'confirmees' => $livraisonsByStatut->get(Livraison::CONFIRMEE, 0),
                'en_cours' => $livraisonsByStatut->get(Livraison::EN_COURS, 0),
                'livrees' => $livraisonsByStatut->get(Livraison::LIVREE, 0),
            ],
            'demandes' => [
                'total' => DemandesCouverture::count(),
                'en_attente' => $demandesByStatut->get(DemandesCouverture::EN_ATTENTE, 0),
                'confirmees' => $demandesByStatut->get(DemandesCouverture::CONFIRMEE, 0),
                'terminees' => $demandesByStatut->get(DemandesCouverture::TERMINEE, 0),
            ],
            'messages' => [
                'total' => ContactMessage::count(),
                'non_lus' => $messagesByStatut->get(false, 0),
                'lus' => $messagesByStatut->get(true, 0),
            ],
        ];

        $recentActivity = collect()
            ->merge(
                Reservation::query()
                    ->latest()
                    ->take(4)
                    ->get()
                    ->map(fn ($reservation) => [
                        'type' => 'Réservation',
                        'label' => $reservation->reference ?? 'Réservation',
                        'title' => $reservation->nom,
                        'date' => $reservation->created_at,
                        'status' => $reservation->statut,
                        'url' => route('admin.reservations.index'),
                    ])
            )
            ->merge(
                Livraison::query()
                    ->latest()
                    ->take(4)
                    ->get()
                    ->map(fn ($livraison) => [
                        'type' => 'Livraison',
                        'label' => $livraison->reference ?? 'Livraison',
                        'title' => $livraison->nom,
                        'date' => $livraison->created_at,
                        'status' => $livraison->statut,
                        'url' => route('admin.livraisons.index'),
                    ])
            )
            ->merge(
                DemandesCouverture::query()
                    ->latest()
                    ->take(4)
                    ->get()
                    ->map(fn ($demande) => [
                        'type' => 'Média',
                        'label' => $demande->reference ?? 'Demande média',
                        'title' => $demande->nom,
                        'date' => $demande->created_at,
                        'status' => $demande->statut,
                        'url' => route('admin.demandes-couverture.index'),
                    ])
            )
            ->merge(
                ContactMessage::query()
                    ->latest()
                    ->take(4)
                    ->get()
                    ->map(fn ($message) => [
                        'type' => 'Message',
                        'label' => 'Contact',
                        'title' => $message->nom,
                        'date' => $message->created_at,
                        'status' => $message->est_lu ? 'lu' : 'non_lu',
                        'url' => route('admin.contacts.index'),
                    ])
            )
            ->sortByDesc('date')
            ->take(8)
            ->values();

        return view('admin.dashboard', compact('stats', 'recentActivity'));
    }
}
