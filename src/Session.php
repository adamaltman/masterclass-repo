<?php
/**
 * @author Adam Altman <adam@rebilly.com>
 * masterclass-repo
 */

namespace Masterclass;

/**
 * Class Session.  A login must
 */
class Session
{
    public function destroy()
    {
        if ($this->getIsOpen()) {
            session_destroy();
        }
    }

    public function open()
    {
        if (!$this->getIsOpen()) {
            session_start();
        }
    }

    public function close()
    {
        session_write_close();
    }

    public function getIsOpen()
    {
        return session_id() !== '';
    }

    public function getSessionId()
    {
        return session_id();
    }

    public function setSessionId($value)
    {
        session_id($value);
    }

    public function get($key, $defaultValue = null)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $defaultValue;
    }

    public function add($key,$value)
    {
        $_SESSION[$key]=$value;
    }

    public function regenerateId($deleteOldSession=false)
    {
        if($this->getIsOpen())
            session_regenerate_id($deleteOldSession);
    }
}
