<?php

namespace Dashboard\classes\menu;

use DOMDocument;
use DOMException;


/**
 *  Class MenuItem
 *
 * - Part of `Dashboard` package.
 *
 * Used to create navigation items.
 */
final class MenuItem
{
    public string $name;
    public string $icon;
    public string $icon_type = "image";
    public string $path;
    public array $children = [];
    public DOMDocument $dom;

    /**
     * MenuItem constructor.
     *
     * @param string $name The name of the menu item.
     * @param string $icon The icon associated with the menu item (optional).
     * @param string $path The path the menu item links to (optional).
     */
    public function __construct(string $name, string $icon = "", string $path = "")
    {
        $this->name = $name;
        $this->icon = $icon;
        $this->path = $path;
        $this->dom = new DOMDocument('1.0', 'UTF-8');
    }

    /**
     * Sets the icon type for the menu item.
     * - Can be fa (font-awesome).
     * @param string $icon_type The icon type to set.
     * @return MenuItem The current instance for method chaining.
     */
    public function setIconType(string $icon_type): self
    {
        $this->icon_type = $icon_type;
        return $this;
    }

    /**
     * Adds a child MenuItem to the current MenuItem.
     *
     * @param MenuItem $child The child menu item to add.
     * @return MenuItem The current instance for method chaining.
     */
    public function addChild(MenuItem $child): self
    {
        $this->children[] = $child;
        return $this;
    }

    /**
     * Creates the DOM representation of the menu item.
     *
     * @return MenuItem The current instance for method chaining.
     * @throws DOMException If there is an error during DOM manipulation.
     */
    public function createDom(): self
    {
        $menuItem = $this->dom->createElement('div');
        $menuItem->setAttribute('class', 'menu-item');

        $link = $this->dom->createElement('a');
        $link->setAttribute('href', $this->path);

        if (!empty($this->icon)) {
            if ($this->icon_type === 'fa') {
                $icon = $this->dom->createElement('i');
                $icon->setAttribute('class', $this->icon);
            } else {
                $icon = $this->dom->createElement('img');
                $icon->setAttribute('src', $this->icon);
            }
            $link->insertBefore($icon, $link->firstChild);
            $menuItem->appendChild($link);
        }

        $text = $this->dom->createElement("p", $this->name);

        $link->appendChild($text);

        if (!empty($this->children)) {
            $dropdown = $this->dom->createElement('div');
            $dropdown->setAttribute('class', 'dropdown-menu');

            foreach ($this->children as $child) {
                $childDom = $this->dom->importNode($child->createDom()->dom->documentElement, true);
                $dropdown->appendChild($childDom);
            }

            $menuItem->appendChild($dropdown);
            $menuItem->className = 'dropdown';
        }

        if ($this->path === $_SERVER["REQUEST_URI"])
            $link->setAttribute('class', "active");

        $this->dom->appendChild($menuItem);

        return $this;
    }

    /**
     * Adds an attribute to a specified tag in the menu item's DOM.
     *
     * @param string $tag The tag name of the element to which the attribute will be added.
     * @param string $name The name of the attribute to set.
     * @param string $value The value of the attribute to set.
     * @return MenuItem The current instance for method chaining.
     */
    public function addAttribute(string $tag, string $name, string $value): self
    {
        $elem = $this->dom->getElementsByTagName($tag)->item(0);
        $elem->setAttribute($name, $value);
        return $this;
    }

    /**
     * Gets the HTML representation of the menu item.
     *
     * @return string The generated HTML for the menu item.
     */
    public function getHTML(): string
    {
        return $this->dom->saveHTML();
    }

}
