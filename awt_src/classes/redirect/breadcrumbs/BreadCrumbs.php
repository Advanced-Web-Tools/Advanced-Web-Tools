<?php

namespace redirect\breadcrumbs;

use session\SessionHandler;

/**
 * The BreadCrumbs class extends the SessionHandler class
 * and is responsible for managing the last visited page (breadcrumbs) in a session.
 */
class BreadCrumbs extends SessionHandler
{
    private string $last;

    public function __construct()
    {
        $this->SessionHandler();

        if (isset($_SESSION['last_page'])) {
            $this->last = $_SESSION["last_page"];
        } else {
            $this->last = $_SERVER["REQUEST_URI"];
            $_SESSION["last_page"] = $this->last;
        }

    }

    /**
     * Sets the last page URL to the current page URL and stores it in the session.
     *
     * This method updates both the `$last` property and the session's `last_page` variable
     * to the current request URI (`$_SERVER["REQUEST_URI"]`).
     */
    public function setLast(): void
    {
        $this->last = $_SERVER["REQUEST_URI"];
        $_SESSION["last_page"] = $this->last;
    }

    /**
     * Returns the last visited page URL.
     *
     * @return string The value of `$last`, which stores the last page visited.
     */
    public function getLast(): string
    {
        return $this->last;
    }
}