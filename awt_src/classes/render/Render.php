<?php

namespace render;

use DOMDocument;
use DOMXPath;
use Error;
use event\EventDispatcher;
use Exception;
use render\events\RenderReadyEvent;
use render\TemplateEngine\BladeOne;
use Throwable;

/**
 * The Render class provides tools to manipulate HTML documents, load templates, and manage dynamic content rendering.
 * It uses DOM manipulation and integrates an event dispatcher for handling specific events during the render process.
 */
class Render
{
    public array $routes = [];
    public string $localAssetPath;
    public array $bundle;
    protected DOMDocument $dom;
    public EventDispatcher $eventDispatcher;
    public string $packageName;

    /**
     * Creates a new DOMDocument instance and loads HTML content into it.
     *
     * @param string $html The HTML content to load into the DOMDocument. Defaults to a basic HTML structure.
     * @return DOMDocument The created and loaded DOMDocument instance.
     */
    public function createDocument(string $html = "<html lang=''></html>"): DOMDocument
    {
        try {
            $this->dom = new DOMDocument();
            $this->dom->formatOutput = true;
            $this->dom->loadHTML($html);
            return $this->dom;
        } catch (Exception $exception) {
            die("AWT Renderer: Failed to create document.");
        }
    }

    /**
     * Loads a template from an HTML document if it extends another template.
     * Uses regex to find the "@extends" directive and merges the content with the base template.
     *
     * @throws Exception If the template cannot be loaded or extended.
     */
    protected function loadTemplate(): void
    {
        $htmlContent = $this->dom->saveHTML();
        $this->createDocument($htmlContent);
    }


    public function getDom(): DOMDocument
    {
        return $this->dom;
    }

    /**
     * Replaces all elements in the DOM that match the given class name with a new DOM element.
     *
     * @param string $classname The class name to search for in the current DOM.
     * @param DOMDocument $newElement The new element to replace the matching elements.
     */
    public function replaceElementsByClassname(string $classname, DOMDocument $newElement): void
    {
        $xpath = new \DOMXPath($this->dom);
        $elements = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        foreach ($elements as $element) {
            $importedNode = $this->dom->importNode($newElement->documentElement, true);
            $element->parentNode->replaceChild($importedNode, $element);
        }
    }

    /**
     * Inserts a new element in the DOM after the elements that match the given class name.
     *
     * @param string $classname The class name to search for in the current DOM.
     * @param DOMDocument $html The new element to insert after the matching elements.
     */
    public function insertAfterByClassname(string $classname, DOMDocument $html): void
    {
        $xpath = new \DOMXPath($this->dom);
        $elements = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        foreach ($elements as $element) {
            $newNode = $this->dom->importNode($html->documentElement, true);

            if ($element->nextSibling) {
                $element->parentNode->insertBefore($newNode, $element->nextSibling);
            } else {
                $element->parentNode->appendChild($newNode);
            }
        }
    }

    /**
     * Inserts a new element in the DOM before the elements that match the given class name.
     *
     * @param string $classname The class name to search for in the current DOM.
     * @param DOMDocument $html The new element to insert before the matching elements.
     */
    public function insertBeforeByClassname(string $classname, DOMDocument $html): void
    {
        $xpath = new DOMXPath($this->dom);
        $elements = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        foreach ($elements as $element) {
            $newNode = $this->dom->importNode($html->documentElement, true);
            $element->parentNode->insertBefore($newNode, $element);
        }
    }

    /**
     * Appends a new element into elements that match the given class name.
     *
     * @param string $classname The class name to search for in the current DOM.
     * @param DOMDocument $html The new element to append to the matching elements.
     */
    public function insertIntoByClassname(string $classname, DOMDocument $html): void
    {
        if (!isset($this->dom)) {
            throw new \RuntimeException('DOMDocument instance is not initialized.');
        }

        $classname = ltrim($classname, '.'); // Remove leading `.` if present

        if (!preg_match('/^[a-zA-Z0-9-_]+$/', $classname)) {
            throw new \InvalidArgumentException("Invalid class name: '$classname'.");
        }

        if ($html->documentElement === null) {
            throw new \InvalidArgumentException('The provided DOMDocument has no valid documentElement.');
        }

        $xpath = new DOMXPath($this->dom);

        // Match the exact class name
        $elements = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' {$classname} ')]");

        foreach ($elements as $element) {
            if ($element instanceof \DOMElement) {
                $newNode = $this->dom->importNode($html->documentElement, true);
                $element->appendChild($newNode);
            }
        }
    }

    /**
     * Adds a key-value pair to the bundle. The bundle stores various data to be used in rendering.
     *
     * @param string $key The key associated with the bundle item.
     * @param string $value The value of the bundle item.
     */
    public function addToBundle(string $key, string $value): void
    {
        $this->bundle[$key] = $value;
    }

    /**
     * Converts the bundle key-value pairs into object properties for later use in rendering.
     */
    protected function createBundleObjects(): void
    {
        foreach ($this->bundle as $key => $bundle) {
            $this->{$key} = $bundle;
        }
    }
}
