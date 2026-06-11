<?php

namespace App\Http\Controllers;

use App\Services\OrganizerOnboardingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrganizerOnboardingController extends Controller
{
    public function __construct(private readonly OrganizerOnboardingService $service) {}

    public function show(): View
    {
        return view('organizer.onboard');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
            'bio'          => ['nullable', 'string'],
            'email'        => ['nullable', 'email', 'max:255'],
            'phone'        => ['nullable', 'string', 'max:50'],
        ]);

        $this->service->handle($request->user(), $data);

        return redirect('/organizer');
    }
}
