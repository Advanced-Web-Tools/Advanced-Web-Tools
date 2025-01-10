<?php

namespace packages\installer\interface;

interface IPackageInstall
{
    public function postInstall(int $packageID, string $packageName): bool;
}