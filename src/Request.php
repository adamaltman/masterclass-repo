<?php

namespace Masterclass;


class Request
{
    protected $post;
    protected $get;
    protected $server;
    protected $session;

    public function __construct($post, $get, $server, Session $session)
    {
        $this->post = $post;
        $this->get = $get;
        $this->server = $server;
        $this->session = $session;
        $this->session->open();
    }

    public function getQueryParams()
    {
        return $this->get;
    }

    public function getPostParams()
    {
        return $this->post;
    }

    public function getServerParams()
    {
        return $this->server;
    }

    public function getQueryParam($paramName)
    {
        return is_string($paramName) ? $this->get[$paramName] ?? null : null;
    }

    public function getPostParam($paramName)
    {
        return is_string($paramName) ? $this->post[$paramName] ?? null : null;
    }

    public function getServerParam($paramName)
    {
        return is_string($paramName) ? $this->server[$paramName] ?? null : null;
    }

    /**
     * @TODO: maybe session is strange here... I don't use them much because we're API-focused.
     *
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @deprecated
     *
     * @TODO This may violate SRP.  Here for convenience temporarily.
     *
     * @param string $value
     *
     * @return mixed
     */
    public function getSanitizedValue(string $value)
    {
        // original:
        // filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS)
        return filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    /**
     * @deprecated
     *
     * @TODO This may violate SRP.  Here for convenience temporarily.
     *
     * @param string $value
     *
     * @return bool
     */
    public function validateUrl(string $value)
    {
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * @deprecated
     *
     * @TODO This may violate SRP.  Here for convenience temporarily.
     *
     * @param string $value
     *
     * @return bool
     */
    public function validateEmail(string $value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
}
