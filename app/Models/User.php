<?php namespace App\Models;

use Namacode\ArmyKnife\Traits\R\ArrayBuildableModel;
use Namacode\ArmyKnife\Traits\R\TimeAwareModel;
use RedBeanPHP\R;

/**
 * @property mixed id
 */
class User extends \Namacode\ArmyKnife\R\Model
{
    use TimeAwareModel;

    public $first_name, $last_name, $email, $hash, $username, $account_type, $telephone;

    public static function FromArray(array $arr) : User
    {
        /** @var User $user */
        $user = new self(R::dispense(self::tableName()));

        foreach ($arr as $x => $y) {
            if (!in_array($x, ['password'])) {
                if (property_exists($user, $x))
                    $user->bean->$x = $user->$x = $y;
            }
        }

        if (array_key_exists('password', $arr))
            $user->hash($arr['password']);

        return $user;
    }

    public function hash($string)
    {
        $mode = "2y";
        $cost = 10;
        $rawSalt = substr(md5(time() . $this->email . $this->first_name . $this->last_name),  0, 22);
        $salt = "$" . implode("$",[
                $mode,
                str_pad($cost,2,"0",STR_PAD_LEFT),
                str_replace("+", ".", $rawSalt)
            ]) . "$";


        $this->setHash(crypt($string, $salt));
    }

    public static function verifyPassword (string $password, string $hash) : bool {
        return hash_equals($hash, crypt($password, $hash));
    }

    /**
     * @return String
     */
    public function getFirstName()
    {
        return $this->bean->first_name;
    }

    /**
     * @param String $first_name
     */
    public function setFirstName($first_name): void
    {
        $this->bean->first_name = $this->first_name = $first_name;
    }

    /**
     * @return String
     */
    public function getLastName()
    {
        return $this->bean->last_name;
    }

    /**
     * @param String $last_name
     */
    public function setLastName($last_name): void
    {
        $this->bean->last_name = $this->last_name = $last_name;
    }

    /**
     * @return String
     */
    public function getEmail()
    {
        return $this->bean->email;
    }

    /**
     * @param String $email
     */
    public function setEmail($email): void
    {
        $this->bean->email = $this->email = $email;
    }

    /**
     * @return String
     */
    public function getHash()
    {
        return $this->bean->hash;
    }

    /**
     * @param String $hash
     */
    private function setHash($hash)
    {
        $this->bean->hash = $this->hash = $hash;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->bean->username;
    }

    /**
     * @param String $username
     */
    public function setUsername($username): void
    {
        $this->bean->username = $this->username = $username;
    }

    /**
     * @return int
     */
    public function getAccountTypeID()
    {
        return $this->bean->account_type_id;
    }

    /**
     * @param ENUM_UserAccountType $account_type
     */
    public function setAccountType($account_type) : void
    {
        $this->bean->account_type = $this->account_type = $account_type;
    }

    public function getID() : int
    {
        return $this->bean->id;
    }

    public function getFullName() : string {
        return $this->getFirstName() . " " . $this->getLastName();
    }

    public static function isAdministrator(int $uid)
    {
        $user = User::findByID($uid);
        return ENUM_UserAccountType::is($user->box()->getAccountTypeID(), ENUM_UserAccountType::ADMIN);
    }
}
