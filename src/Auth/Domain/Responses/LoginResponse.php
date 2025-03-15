<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\Auth\Domain\Responses;

use GridCP\Proxmox_Client\Commons\Domain\Models\CoockiesPVE;
use GuzzleHttp\Cookie\CookieJar;


final class LoginResponse implements CoockiesPVE
{

   private string $CSRFPreventionToken;
   private CookieJar $cookies;

   private string $ticket;

    /**
     * @param string $CSRFPreventionToken
     * @param CookieJar $cookies
     * @param string $ticket
     */
    public function __construct(string $CSRFPreventionToken, CookieJar $cookies, string $ticket)
    {
        $this->CSRFPreventionToken = $CSRFPreventionToken;
        $this->cookies = $cookies;
        $this->ticket = $ticket;
    }


    public function getCSRFPreventionToken(): string
    {
        return $this->CSRFPreventionToken;
    }

    public function getCookies(): CookieJar
    {
        return $this->cookies;
    }

    public function getTicket(): string
    {
        return $this->ticket;
    }

}