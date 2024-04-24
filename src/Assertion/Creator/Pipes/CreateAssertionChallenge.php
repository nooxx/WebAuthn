<?php

namespace Laragear\WebAuthn\Assertion\Creator\Pipes;

use Closure;
use Illuminate\Config\Repository as ConfigContract;
use Laragear\WebAuthn\Assertion\Creator\AssertionCreation;
use Laragear\WebAuthn\Challenge\Challenge;
use Laragear\WebAuthn\Contracts\WebAuthnChallengeRepository as ChallengeRepositoryContract;
use Laragear\WebAuthn\Enums\UserVerification;

class CreateAssertionChallenge
{
    /**
     * Create a new pipe instance.
     */
    public function __construct(protected ChallengeRepositoryContract $challenge, protected ConfigContract $config)
    {
        //
    }

    /**
     * Handle the incoming Assertion.
     *
     * @throws \Random\RandomException
     */
    public function handle(AssertionCreation $assertion, Closure $next): mixed
    {
        $options = [];

        if ($assertion->acceptedCredentials?->isNotEmpty()) {
            // @phpstan-ignore-next-line
            $options['credentials'] = $assertion->acceptedCredentials->map->getKey()->toArray();
        }

        $challenge = Challenge::random(
            $this->config->get('webauthn.challenge.bytes'),
            $this->config->get('webauthn.challenge.timeout'),
            $assertion->userVerification === UserVerification::REQUIRED,
            $options
        );

        $assertion->json->set('challenge', $challenge->data);

        $this->challenge->store($assertion, $challenge);

        return $next($assertion);
    }
}
