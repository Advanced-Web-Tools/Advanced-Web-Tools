<?php

namespace render;

use Exception;

/**
 * The TemplateParser class handles template parsing and processing,
 * including extending templates, handling sections, and replacing variables.
 */
final class TemplateParser
{
    private static array $sections = [];

    /**
     * Extends a parent template by including its content and replacing
     * - "@yield" sections with the corresponding content from the child template.
     *
     * @param string $path The file path of the parent template.
     * @param string $childHtml The HTML content of the child template.
     * @return string The processed parent template with child content.
     * @throws Exception If the parent template file is not found.
     */
    static final public function extends(string $path, string $childHtml): string
    {
        $path = trim($path);
        $path = str_replace(".", DIRECTORY_SEPARATOR, $path);
        $path = PACKAGES . DIRECTORY_SEPARATOR . $path;
        $path .= ".awt.php";

        ob_start();
        if (file_exists($path)) {
            include $path;
        } else {
            throw new Exception("Template not found: $path");
        }
        $parentContent = ob_get_clean();

        // Check if the parent template extends another template
//        if (preg_match('/@extends\s*\(\s*([\'"]?)(.+?)([\'"]?)\s*\)/', $parentContent, $matches)) {
//            // The second capture group contains the path of the extended template
//            $parentPath = trim($matches[2], '"\'');
//            // Recursively call extends for the parent template
//            $parentContent = self::extends($parentPath, $childHtml);
//            echo 1;
//        }
//
//        echo 1;

        // Replace @yield sections with content from the child template
        return TemplateParser::replaceYields($parentContent, $childHtml);
    }

    /**
     * Replaces "@yield" sections in the parent template with the corresponding
     * content from the child template's "@section" blocks.
     *
     * @param string $parentContent The HTML content of the parent template.
     * @param string $childHtml The HTML content of the child template.
     * @return string The parent content with replaced sections.
     */
    static final public function replaceYields(string $parentContent, string $childHtml): string
    {
        preg_match_all('/@section\s*\(\s*[\'"](.+?)[\'"]\s*\)(.*?)@endsection/s', $childHtml, $matches, PREG_SET_ORDER);

        return preg_replace_callback('/@yield\s*\(\s*[\'"](.+?)[\'"]\s*\)/', function ($yieldMatch) use ($matches) {
            $yieldName = $yieldMatch[1];
            foreach ($matches as $section) {
                if ($section[1] === $yieldName) {
                    return $section[2];
                }
            }

            return '';
        }, $parentContent);
    }

    /**
     * Replaces variables in the template with values from the given context.
     *
     * @param object $context The context object that contains variables.
     * @param string $line The template line with variables.
     * @return string The line with variables replaced by their values.
     */
    static final public function vars(object $context, string $line): string
    {
        $line = self::secVars($context, $line);
        return preg_replace_callback('/\{\{\s*([\w.]+)\s*\}\}/', function ($matches) use ($context) {

            $keys = explode('.', $matches[1]);
            $value = $context;

            foreach ($keys as $key) {
                if (is_object($value) && isset($value->{$key})) {
                    $value = $value->{$key};
                } elseif (is_array($value) && isset($value[$key])) {
                    $value = $value[$key];
                } else {
                    return '';
                }
            }
            return (string)$value;
        }, $line);
    }

    /**
     * Replaces variables in the template marked with percent signs (%)
     * with values from the given context.
     *
     * @param object $context The context object that contains variables.
     * @param string $line The template line with variables in % marks.
     * @return string The line with variables replaced by their values.
     */
    static final public function secVars(object $context, string $line): string
    {
        return preg_replace_callback('/%s*([\w.]+)\s*\%/', function ($matches) use ($context) {

            $keys = explode('.', $matches[1]);
            $value = $context;

            foreach ($keys as $key) {
                if (is_object($value) && isset($value->{$key})) {
                    $value = $value->{$key};
                } elseif (is_array($value) && isset($value[$key])) {
                    $value = $value[$key];
                } else {
                    return '';
                }
            }
            return (string)$value;
        }, $line);
    }

    /**
     * Processes "@if" and "@else" blocks in the template based on conditions
     * evaluated from the context object.
     *
     * @param object $context The context object containing data for conditions.
     * @param string $html The template HTML with @if and @else blocks.
     * @return string The processed HTML with conditionally included content.
     */
    static final public function ifParser(object $context, string $html): string
    {
        $pattern = '/
        @if\s*\(([^)]+)\)   # Match @if with condition inside parentheses
        (                   # Start capturing group for the block content
            (?:             # Non-capturing group for nested blocks
                (?>         # Atomic group to prevent backtracking
                    [^@]+   # Match anything that is not @
                    |       # Or
                    @if\s*\((.*?)\)\s*.*?@endif  # Match nested @if blocks
                )*          # Repeat the above
            )
        )                   # End of capturing group for the block content
        (@else\s*(.*?)\s*)? # Match optional @else and its content
        @endif              # Match @endif
    /xs';

        return str_replace("@else", "", preg_replace_callback($pattern, function ($matches) use ($context) {
            $condition = $matches[1];
            $ifContent = $matches[2];
            $elseContent = $matches[4] ?? '';

            $evaluatedCondition = preg_replace_callback('/\b(?!["\'])(\w+(\.\w+)*)(?!["\'])\b/', function ($varMatches) use ($context) {
                $varPath = $varMatches[1];
                $varParts = explode('.', $varPath);
                $value = $context;

                foreach ($varParts as $part) {
                    $part = is_numeric($part) ? (int)$part : $part;
                    if (is_array($value) && isset($value[$part])) {
                        $value = $value[$part];
                    } elseif (is_object($value) && isset($value->{$part})) {
                        $value = $value->{$part};
                    } else {
                        return 'null';
                    }
                }
                return var_export($value, true);
            }, $condition);

            $evaluatedCondition = str_replace(['true', 'false'], ['1', '0'], $evaluatedCondition);
            $evaluatedCondition = preg_replace('/\s*&&\s*/', ' and ', $evaluatedCondition);
            $evaluatedCondition = preg_replace('/\s*\|\|\s*/', ' or ', $evaluatedCondition);

            $evaluatedCondition = preg_replace('/\s*</', ' < ', $evaluatedCondition);
            $evaluatedCondition = preg_replace('/\s*>/', ' > ', $evaluatedCondition);

            try {
                $conditionResult = eval('return ' . $evaluatedCondition . ';');
            } catch (\Throwable $e) {
                $conditionResult = false;
            }

            if ($conditionResult) {
                return self::ifParser($context, $ifContent);
            } else {
                return !empty($elseContent) ? self::ifParser($context, $elseContent) : '';
            }
        }, $html));
    }


    /**
     * Processes "@foreach" loops in the template, iterating over collections
     * in the context object and rendering the repeated block.
     *
     * @param object $context The context object with collections for looping.
     * @param string $line The template HTML containing "@foreach" blocks.
     * @return string The HTML with repeated blocks based on the collection.
     */
    static final public function foreachParser(object $context, string $line): string
    {
        $pattern = '/
        @foreach\s*\(\s*(\w+)\s+as\s+(\w+)\s*\)   # Match @foreach (collection as item)
        (.*?)                                     # Match the block content
        @endforeach                               # Match @endforeach
    /xs';

        return preg_replace_callback($pattern, function ($matches) use ($context) {
            $collectionVar = $matches[1];  // Collection name (e.g., array)
            $itemVar = $matches[2];        // Item name (e.g., item)
            $blockContent = $matches[3];   // Block content to be repeated

            if (isset($context->{$collectionVar}) && is_iterable($context->{$collectionVar})) {
                $collection = $context->{$collectionVar};
                $result = '';

                $index = 0;

                foreach ($collection as $value) {
                    $tempContext = clone $context;
                    $tempContext->index = $index;
                    $tempContext->{$itemVar} = $value;

                    $parsedBlock = TemplateParser::ifParser($tempContext, $blockContent);
                    $parsedBlock = TemplateParser::secVars($tempContext, $parsedBlock);
                    $parsedBlock = TemplateParser::vars($tempContext, $parsedBlock);
                    $parsedBlock = TemplateParser::urlVar($tempContext, $parsedBlock);
                    $parsedBlock = TemplateParser::url($parsedBlock);
                    $parsedBlock = TemplateParser::assets($tempContext->localAssetPath, $parsedBlock);
                    $parsedBlock = TemplateParser::data($tempContext->packageName, $parsedBlock);


                    if (empty($stack) || end($stack)['result']) {
                        $result .= $parsedBlock;
                    }

                    $index++;
                }

                return $result;
            } else {
                return '';
            }
        }, $line);
    }

    /**
     * Replaces "@assets" tags in the template with the actual asset paths.
     *
     * @param string $assetPath The base asset path.
     * @param string $html The template HTML with "@assets" tags.
     * @return string The HTML with asset URLs replaced.
     */
    static final public function assets(string $assetPath, string $html): string
    {
        $hostname = HOSTNAME . $assetPath;
        return preg_replace_callback('/@assets\s*\(\s*[\'"](.+?)[\'"]\s*\)/', function ($matches) use ($hostname) {
            $assetsPath = $matches[1];
            return $hostname . '/' . $assetsPath;
        }, $html);
    }

    /**
     * Replaces "@resource" tags in the template with the resource URLs.
     *
     * @param string $html The template HTML with "@resource" tags.
     * @return string The HTML with resource URLs replaced.
     */
    static final public function resource(string $html): string
    {
        $hostname = HOSTNAME . '/awt_src/vendor';
        return preg_replace_callback('/@resource\s*\(\s*[\'"](.+?)[\'"]\s*\)/', function ($matches) use ($hostname) {
            $resource = $matches[1];
            return $hostname . '/' . $resource;
        }, $html);
    }

    /**
     * Replaces "@data" tags in the template with data file URLs.
     *
     * @param string $packageName The package name for the data.
     * @param string $html The template HTML with @data tags.
     * @return string The HTML with data URLs replaced.
     */
    static final public function data(string $packageName, string $html): string
    {
        $hostname = HOSTNAME . "/awt_data/media/packages/";

        return preg_replace_callback('/@data\s*\(\s*([\'"])(.+?)\1\s*,\s*([\'"])(.+?)\3\s*\)/', function ($matches) use ($hostname, $packageName) {
            $arg1 = $matches[2]; // First argument (packages name)
            $arg2 = $matches[4]; // Second argument (packages type)

            return $hostname . urlencode($arg2) . '/' . urlencode($packageName) . '/' . urlencode($arg1);
        }, $html);
    }

    /**
     * Replaces "@urlVar" tags in the template with dynamic URLs generated
     * from variables in the context.
     *
     * @param object $context The context object containing URL variables.
     * @param string $html The template HTML with @urlVar tags.
     * @return string The HTML with URL variables replaced.
     */
    static final function urlVar(object $context, string $html): string
    {
        return preg_replace_callback('/@urlVar\s*\(\s*[\'"](.+?)[\'"]\s*\)/', function ($matches) use ($context) {
            $url = $matches[1];
            return HOSTNAME . '/' . TemplateParser::vars($context, "{{ " . $url . "  }}");
        }, $html);
    }

    /**
     * Replaces "@url" tags in the template with static URLs.
     *
     * @param string $html The template HTML with @url tags.
     * @return string The HTML with URLs replaced.
     */
    static final function url(string $html): string
    {
        return preg_replace_callback('/@url\s*\(\s*[\'"](.+?)[\'"]\s*\)/', function ($matches) {
            $url = $matches[1];
            return HOSTNAME . '/' . $url;
        }, $html);
    }

}
