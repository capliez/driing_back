<?php

namespace App\Doctrine;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Building;
use App\Entity\Resident;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;

class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private $security;
    private $auth;

    public function __construct(Security $security, AuthorizationCheckerInterface $checker)
    {
        $this->security = $security;
        $this->auth = $checker;
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass)
    {
        $user = $this->security->getUser();

        if (($resourceClass === User::class || $resourceClass === Building::class || $resourceClass === Resident::class) && $user instanceof User && !$this->auth->isGranted('ROLE_ADMIN')) {
            $rootAlias = $queryBuilder->getRootAliases()[0];

            if ($resourceClass === User::class) {
                $queryBuilder->andWhere("$rootAlias.id = :user");
            } elseif ($resourceClass === Building::class) {
                $queryBuilder->andWhere("$rootAlias.user = :user");
            } elseif ($resourceClass === Resident::class) {
                $queryBuilder->andWhere("$rootAlias.user = :user");
            }
            $queryBuilder->setParameter("user", $user);
        }
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = [])
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }
}
