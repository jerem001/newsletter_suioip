<?php

declare(strict_types=1);

use Modules\Auth\Domain\Exceptions\InvalidEmailException;
use Modules\Auth\Domain\ValueObjects\Email;

it('accepte une adresse email valide et la normalise en minuscules', function (): void {
    $email = new Email('  User@Example.ORG ');

    expect($email->value())->toBe('user@example.org');
});

it('rejette une adresse email invalide', function (): void {
    new Email('pas-un-email');
})->throws(InvalidEmailException::class);

it('compare deux emails par valeur', function (): void {
    $a = new Email('a@example.org');
    $b = new Email('A@Example.org');

    expect($a->equals($b))->toBeTrue();
});
