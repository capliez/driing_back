<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class SecurizerRole {

    private $accessDecisionManager;

    public function __construct(AccessDecisionManagerInterface $accessDecisionManager) {
        $this->accessDecisionManager = $accessDecisionManager;
    }

    public function isGranted(User $user, $attribute, $object = null) {
        $token = new UsernamePasswordToken($user, 'none', 'none', $user->getRoles());
        return ($this->accessDecisionManager->decide($token, [$attribute], $object));
    }

}