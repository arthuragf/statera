<?php
namespace statera\core\environment;

class DotEnv{
    /**
     * The directory where the .env file can be located.
     *
     * @var string
     */
    protected string $sPath;


    public function __construct(string $sPath)
    {
        if(!file_exists($sPath)) {
            throw new \InvalidArgumentException(sprintf('%s does not exist', $sPath));
        }
        $this->sPath = $sPath;
    }

    public function load() :void
    {
        $sFile = $this->sPath . DIRECTORY_SEPARATOR . '.env';
        if (!is_readable($sFile)) {
            throw new \RuntimeException(sprintf('%s file is not readable', $sFile));
        }

        $aLines = file($sFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($aLines as $line) {

            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($sName, $value) = explode('=', $line, 2);
            $sName = trim($sName);
            $value = trim($value);

            if (!array_key_exists($sName, $_SERVER) && !array_key_exists($sName, $_ENV)) {
                putenv(sprintf('%s=%s', $sName, $value));
                $_ENV[$sName] = $value;
                $_SERVER[$sName] = $value;
            }
        }
    }
}