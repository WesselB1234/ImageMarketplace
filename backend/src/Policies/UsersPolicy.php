<?php 

namespace App\Policies;

use App\Exception\ConflictException;
use App\Models\User;
use App\Repositories\Interfaces\IUsersRepository;

class UsersPolicy
{
    private IUsersRepository $usersRepository; 

    public function __construct(IUsersRepository $usersRepository){
        $this->usersRepository = $usersRepository;
    }

    public function enforceUserIsNotDuplicate(User $user)
    {
        $duplicateUser = $this->usersRepository->getUserByUsername($user->getUsername());

        if ($duplicateUser !== null && ($user->getUserId() !== null && $duplicateUser->getUserId() === $user->getUserId()) === false) {
            throw new ConflictException("User with username ".$user->getUsername(). " already exists.");
        }
    }

    public function enforceNotDeletingSelf(int $deleteUserId, ?User $loggedInUser) 
    {
        if ($loggedInUser !== null && intval($deleteUserId) === $loggedInUser->getUserId()) {
            throw new ConflictException("You cannot delete yourself.");
        }
    }
}
