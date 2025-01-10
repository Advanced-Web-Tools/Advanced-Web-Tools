<?php

namespace packages;

use packages\enums\EPackageType;

/**
 * Class ManifestReader
 *
 * The ManifestReader class is responsible for reading and processing the
 * manifest file of a package in JSON format. It extends
 * the Package class to inherit common package attributes and methods.
 */
class ManifestReader extends Package
{
    public array $manifest;
    private string $path;

    public function __construct(string $path)
    {
        parent::__construct();

        $this->path = $path;
    }

    public function readManifest(): self
    {
        $content = file_get_contents($this->path . '/manifest.json');

        $this->manifest = json_decode($content, true);

        return $this;
    }

    public function getManifest(): array
    {
        return $this->manifest;
    }

    public function createPackage(): ?Package
    {
        $package = new Package();

        $type = array_key_first($this->manifest);
        $values = $this->manifest[$type];
        $package->name = $values['name'];
        $package->description = $values['description'] ?? null;
        $package->setIcon($values['icon'] ?? null);
        $package->setMinimumAwtVersion($values['minimumAwtVersion'] ?? null);
        $package->setMaximumAwtVersion($values['maximumAwtVersion'] ?? null);
        $package->setVersion($values['version'] ?? null);
        $package->systemPackage = $values['system'];
        $package->author = $values['author'] ?? null;

        $type = match ($type) {
            "theme" => EPackageType::Theme,
            "plugin" => EPackageType::Plugin,
            "system" => EPackageType::System,
        };

        $package->setPackageType($type);
        $package->setPreviewImage($values['preview_image'] ?? null);

        return $package;
    }


}