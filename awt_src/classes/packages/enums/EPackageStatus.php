<?php
namespace packages\enums;

/**
 * Enum EPackageStatus
 *
 * Represents the various statuses that a package can have within the system.
 * This enum provides a way to categorize packages based on their current
 * state, allowing for clear and consistent handling of package management.
 * - `Active` - Package is enabled.
 *
 * - `Disabled` - Package is disabled.
 */
enum EPackageStatus {
    case Active;
    case Disabled;
}