<?php

namespace App\Security\Voter;

use App\Entity\Competence;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CompetenceVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['POST_COMP','EDIT_COMP', 'VIEW_COMP','DELETE_COMP'])
            && $subject instanceof \App\Entity\Competence;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'EDIT_COMP':
                // logic to determine if the user can EDIT
                // return true or false
                return $user->getRoles()[0] === "ROLE_ADMIN" ;
                break;
            case 'VIEW_COMP':
                // logic to determine if the user can VIEW
                // return true or false
                return $user->getRoles()[0] === "ROLE_ADMIN" || $user->getRoles()[0] === "ROLE_FORMATEUR" || $user->getRoles()[0] === "ROLE_CM";
                break;
                case 'DELETE_COMP':
                    // logic to determine if the user can VIEW
                    // return true or false
                    return $user->getRoles()[0] === "ROLE_ADMIN" ;
                    break;
                case 'POST_COMP':
                        // logic to determine if the user can POST
                        // return true or false
                    return $user->getRoles()[0] === "ROLE_ADMIN" ;
                    break;

        return false;
    }
}
}
