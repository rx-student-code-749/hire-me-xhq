<?php namespace Namacode\ArmyKnife;
class Configuration
{
    const CONFIG_KEY = "cfg_";

    /**
     * @param null $path
     * @return bool|mixed
     */
    public static function Get($path = null)
    {
        if ($path !== null) {
            $tree = $GLOBALS[self::CONFIG_KEY];
            $branches = explode('/', $path);

            foreach ($branches as $limb)
                if (isset($tree[$limb]))
                    $tree = $tree[$limb];
            if ( $tree == $GLOBALS[self::CONFIG_KEY])
                return false;
            return $tree;
        }
        return $GLOBALS[self::CONFIG_KEY];
    }
}