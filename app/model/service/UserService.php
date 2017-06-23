<?php
namespace App\Model\Service;

use App\Model\Repository\UserRepository;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;

class UserService extends BaseService implements IAuthenticator{

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository) {
        parent::__construct($userRepository);
    }

    /**
     * Performs an authentication against e.g. database.
     * and returns IIdentity on success or throws AuthenticationException
     * @return IIdentity
     * @throws AuthenticationException
     */
    public function authenticate(array $credentials) {
        list($email, $password) = $credentials;
        $user = $this->getById($email);
        if(!$user)
            throw new AuthenticationException("Uživatel s emailem ". $email ." nebyl nalezen.");
        if(!Passwords::verify($password, $user->password))
            throw new AuthenticationException("Zadal jsi špatné heslo.");
        $user = $user->toArray();
        unset($user["password"]);
        return new Identity($user["email"], null, $user);
    }

    /**
     * @param array $data
     */
    public function add(array $data) {
        $data["password"] = Passwords::hash($data["password"]);
        parent::add($data);
    }

    public function exists($email){
        return $this->countBy(["email" => $email]) > 0;
    }
}