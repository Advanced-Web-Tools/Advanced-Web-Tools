<?php

namespace AWTRespond\src;

use AWTRespond\src\enums\EAWTRespondType;
use redirect\Redirect;

/**
 * # AWTRespond
 *
 * Library that overrides `Redirect::getRedirectTo()` method, to handle custom API responses.
 *
 * ### Usage
 * - Wait for `AWTRespond` package in your `RuntimeLinkerAPI` file in `setupEnvironment()` method, to make sure this package exists.
 * - Create a controller with method that returns `Redirect` or `AWTRespond`.
 * - Add it to your router.
 * - In your controller instead of `Redirect` object return `AWTRespond` object.
 * - Enjoy your simple but effective API.
 *
 * ### Note:
 * *If invalid type is provided it will redirect to last visited page.*
 */
class AWTRespond extends Redirect
{
    private EAWTRespondType $type;
    private string|array $content = [];
    private int $code;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Response type can be `EAWTRespondType::JSON` or `EAWTRespondType::TEXT_HTML`.
     * If response type is JSON your content will be converted to it.
     * @param EAWTRespondType $type
     * @return $this
     */
    public function setType(EAWTRespondType $type): self
    {
        $this->type = $type;
        return $this;
    }


    /**
     * Sets response content
     * @param string|array $content
     * @return $this
     */
    public function setContent(string|array $content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Sets response code (400, 200, 404, etc..).
     *
     * @param int $code
     * @return $this
     */
    public function setCode(int $code): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Overriden method to provide API interface.
     * @return string
     */
    public function getRedirectTo(): string
    {

        $ret = match ($this->type) {
            EAWTRespondType::JSON => function () {
                $string = json_encode($this->content, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                http_response_code($this->code);
                print($string);
                exit();
            },
            EAWTRespondType::TEXT_HTML => function () {
                http_response_code($this->code);
                print($this->content);
                exit();
            },

            default => function () {}
        };

        $ret();

        $this->back();
        return parent::getRedirectTo();
    }

}