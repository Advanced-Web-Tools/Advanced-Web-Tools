<?php

namespace session;

use setting\Config;

class SessionHandler
{
    private string $time;

    /**
     * Initializes and manages session handling.
     *
     * - Starts the session if it’s not already active.
     * - Configures session cookie parameters such as SameSite, Secure, and HttpOnly based on configuration.
     * - Tracks session start time and manages session expiration and ID regeneration.
     * - Optionally binds session to a client IP address to prevent session hijacking.
     * - Clears the session when necessary (e.g., expiration, IP mismatch).
     *
     * @return bool True if the session is successfully handled, false if session clearing occurs due to IP mismatch.
     */
    public function SessionHandler(): bool
    {
        $this->time = time();

        if (session_status() !== PHP_SESSION_ACTIVE) {
            $sameSite = Config::getConfig("AWT", "Session SameSite")->getValue();
            $sameSite = ($sameSite === 'true') ? "Strict" : "None";

            session_set_cookie_params([
                "SameSite" => $sameSite,
                "Secure" => Config::getConfig("AWT", "Session HTTPS Only")->getValue(),
                "HttpOnly" => Config::getConfig("AWT", "Session HTTP Only")->getValue()
            ]);

            session_start();
        }

        $_SESSION['sessionInfo']['started'] = $_SESSION['sessionInfo']['started'] ?? $this->time;

        if (isset($_SESSION['sessionInfo']['expires'])) {
            if ($_SESSION['sessionInfo']['expires'] < $this->time) {
                $this->sessionClearing();
            } else {
                $_SESSION['sessionInfo']['expires'] += Config::getConfig("AWT", "Session ID Regeneration Time")->getValue();
            }
        }

        if (isset($_SESSION['sessionInfo']['regenerate_id'])) {
            if ($this->time - $_SESSION['sessionInfo']['regenerate_id'] > 10) {
                $_SESSION['sessionInfo']['regenerate_id'] = $this->time;
                session_regenerate_id(true);
            }
        } else {
            $_SESSION['sessionInfo']['regenerate_id'] = $this->time;
        }

        return true;
    }

    protected function sessionClearing(): void
    {
        unset($_SESSION);
        unset($_COOKIE['LAST_IP']);
        session_destroy();

    }

    public function sessionDumper(): void
    {
        var_dump($_SESSION);
    }
}
