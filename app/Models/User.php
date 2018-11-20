<?php namespace App\Models;

use RedBeanPHP\OODBBean;
use RedBeanPHP\R;

/**
 * @property mixed id
 */
class User extends \Namacode\ArmyKnife\R\Model
{
    public $first_name, $last_name, $email, $hash, $username, $account_type;

    public function __construct(OODBBean $bean = null)
    {
//        if (is_null($bean))
//            $this->bean = R::dispense(self::tableName());
//        else if (is_int($bean))
//            $this->bean = self::findByID($bean);
//        else
        if (!is_null($bean))
            $this->bean = $bean;
    }
    public static function newFromArray(array $arr) : User
    {
        /** @var User $user */
        $user = new self(R::dispense(self::tableName()));

        foreach ($arr as $x => $y) {
            if (!in_array($x, ['password'])) {
                if (property_exists($user, $x))
                    $user->bean->$x = $user->$x = $y;
            }
        }

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


    public function __toString()
    {
        $o = [];
        $tmp = get_object_vars($this);
        foreach ($tmp as $k)
            $o[$k] = $this->{$k};
        return json_encode($o);
    }

    /**
     * @return String
     */
    public function getFirstName()
    {
        return $this->first_name;
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
        return $this->last_name;
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
        return $this->email;
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
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param String $salt
     */
    private function setSalt($salt): void
    {
        $this->bean->salt = $this->salt = $salt;
    }

    /**
     * @return String
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param String $hash
     */
    private function setHash($hash): void
    {
        $this->bean->hash = $this->hash = $hash;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param String $username
     */
    public function setUsername($username): void
    {
        $this->bean->username = $this->username = $username;
    }

    /**
     * @return ENUM_UserAccountType
     */
    public function getAccountType()
    {
        return $this->account_type;
    }

    /**
     * @param ENUM_UserAccountType $account_type
     */
    public function setAccountType($account_type) : void
    {
        $this->account_type = $account_type;
        $this->bean->account_type = $account_type->unbox();
    }

    private function encrypt($string)
    {
        return md5(str_rot13($string));
    }

    public function getID() : int
    {
        return $this->id;
    }

    public function getFullName() : string {
        return $this->getFirstName() . " " . $this->getLastName();
    }
}
