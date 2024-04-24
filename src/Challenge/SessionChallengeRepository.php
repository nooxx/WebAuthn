<?php

namespace Laragear\WebAuthn\Challenge;

use Illuminate\Contracts\Config\Repository as ConfigContract;
use Illuminate\Contracts\Session\Session as SessionContract;
use Laragear\WebAuthn\Contracts\WebAuthnChallengeRepository;

/**
 * @internal
 */
class SessionChallengeRepository implements WebAuthnChallengeRepository
{
    /**
     * Create a new challenge repository instance.
     */
    public function __construct(protected SessionContract $session, protected ConfigContract $config)
    {
        //
    }

    /**
     * Puts a ceremony challenge into the repository.
     */
    public function store(Challenge $challenge): void
    {
        $this->session->put($this->config->get('webauthn.challenge.key'), $challenge);
    }

    /**
     * Pulls out a challenge instance from the session.
     *
     * It will not return if it has expired not expired.
     */
    public function pull(): ?Challenge
    {
        /** @var \Laragear\WebAuthn\Challenge\Challenge|null $challenge */
        $challenge = $this->session->pull($this->config->get('webauthn.challenge.key'));

        // Only return the challenge if it's valid (not expired)
        if ($challenge?->isValid()) {
            return $challenge;
        }

        return null;
    }
}
