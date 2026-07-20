<?php

namespace App\View\Composers;

use App\Services\NotifikasiService;
use Illuminate\View\View;

class NotifikasiComposer
{
    protected NotifikasiService $notifikasiService;

    public function __construct(NotifikasiService $notifikasiService)
    {
        $this->notifikasiService = $notifikasiService;
    }

    public function compose(View $view): void
    {
        $user = auth()->user();

        if (!$user) {
            $view->with([
                'notifications'   => collect(),
                'unreadCount'     => 0,
            ]);
            return;
        }

        $data = $this->notifikasiService->getNotificationsForUser($user);

        $view->with([
            'notifications' => collect($data['notifications']),
            'unreadCount'   => $data['unreadCount'],
        ]);
    }
}