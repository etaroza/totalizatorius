<?php

namespace Toto\UserBundle\Service;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

/**
 * Service for user operations
 */
class User
{
    protected $securityContext;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext Security context
     *
     * @return \Toto\UserBundle\Service\User
     *
     */
    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * @return \Symfony\Component\Security\Core\SecurityContextInterface
     *
     * @codeCoverageIgnore
     */
    public function getSecurityContext()
    {
        return $this->securityContext;
    }

    /**
     * Get current user. 
     * 
     * @return \Toto\UserBundle\Entity\User
     * @throws AuthenticationCredentialsNotFoundException
     *
     * @codeCoverageIgnore
     */
    public function getCurrentUser()
    {
        if (null === $this->getSecurityContext()->getToken()) {
            throw new AuthenticationCredentialsNotFoundException('The security context contains no authentication token.');
        }

        return $this->getSecurityContext()->getToken()->getUser();
    }

}
