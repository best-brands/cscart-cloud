<?php

namespace Build;

use Composer\Autoload\ClassLoader;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

/**
 * Class Preloader
 */
class Preloader
{
    /** @var string the current working directory */
    protected string $working_directory;

    /** @var ClassLoader */
    protected ClassLoader $loader;

    /** @var array */
    protected array $classMap = [];

    /** @var array|string[] namespaces we want to pre-load */
    protected array $namespaces = [
        'Tygh\\'
    ];

    /**
     * Preloader constructor.
     * @param string $working_directory
     */
    public function __construct(string $working_directory) {
        $this->working_directory = $working_directory;
    }

    /**
     * @param ClassLoader $loader
     */
    public function setComposerLoader(ClassLoader $loader): void
    {
        $this->loader = $loader;
    }

    /**
     * Autoload all auto-loaders
     */
    public function autoLoadAll(): self
    {
        foreach ($this->getAllFilesRecursively('/^(.+)?\/vendor\/autoload\.php/') as [$autoload, $directory]) {
            echo "Loading $autoload\n";
            require $autoload;

            echo "Loading class map\n";
            $this->classMap = array_merge(require $directory . '/vendor/composer/autoload_classmap.php', $this->classMap);
        }

        echo "Autoloading files\n";

        foreach ($this->namespaces as $namespace) {
            foreach ($this->classMap as $class => $path) {
                if (strpos($class, $namespace) === 0)
                    require $path;
                else if ($class === $namespace)
                    require $path;
            }
        }

        echo "Autoloaded all files\n";

        return $this;
    }

    /**
     * @param string $regex
     * @return RegexIterator
     */
    private function getAllFilesRecursively(string $regex): RegexIterator
    {
        $directory = new RecursiveDirectoryIterator($this->working_directory);
        $iterator = new RecursiveIteratorIterator($directory);
        return new RegexIterator($iterator, $regex, RecursiveRegexIterator::GET_MATCH);
    }
}
