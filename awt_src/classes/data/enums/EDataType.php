<?php

namespace data\enums;

/**
 * Enum EDataType
 * Defines the different types of data that can be handled.
 *
 * - Image: Represents image files.
 * - Icon: Represents icon files.
 * - Audio: Represents audio files.
 * - Document: Represents document files.
 * - Video: Represents video files.
 * - Cache: Represents cached data.
 * - TempData: Represents temporary data.
 * - Other: Represents any other type of data not covered by the above types.
 */
enum EDataType
{
    case Image;
    case Icon;
    case Audio;
    case Document;
    case Video;
    case Cache;
    case TempData;
    case Other;
}
