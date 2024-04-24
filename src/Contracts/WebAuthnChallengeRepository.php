<?php

namespace Laragear\WebAuthn\Contracts;

use Laragear\WebAuthn\Challenge\Challenge;

interface WebAuthnChallengeRepository
{
    /**
     * Puts a ceremony challenge into the repository.
     */
    public function store(Challenge $challenge): void;

    /**
     * Pulls a ceremony challenge out from the repository, if it exists.
     */
    public function pull(): ?Challenge;
}
