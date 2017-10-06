<?php
declare(strict_types = 1);
namespace Browscap\Data;

use Psr\Log\LoggerInterface;

class DataCollection
{
    /**
     * @var Platform[]
     */
    private $platforms = [];

    /**
     * @var Engine[]
     */
    private $engines = [];

    /**
     * @var Device[]
     */
    private $devices = [];

    /**
     * @var Division[]
     */
    private $divisions = [];

    /**
     * @var Division
     */
    private $defaultProperties;

    /**
     * @var Division
     */
    private $defaultBrowser;

    /**
     * @var bool
     */
    private $divisionsHaveBeenSorted = false;

    /**
     * @var \DateTimeImmutable
     */
    private $generationDate;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Create a new data collection for the specified version
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger         = $logger;
        $this->generationDate = new \DateTimeImmutable();
    }

    /**
     * @param string   $platformName Name of the platform
     * @param Platform $platform
     */
    public function addPlatform(string $platformName, Platform $platform) : void
    {
        $this->platforms[$platformName] = $platform;

        $this->divisionsHaveBeenSorted = false;
    }

    /**
     * @param string $engineName Name of the engine
     * @param Engine $engine
     */
    public function addEngine(string $engineName, Engine $engine) : void
    {
        $this->engines[$engineName] = $engine;

        $this->divisionsHaveBeenSorted = false;
    }

    /**
     * @param string $deviceName Name of the device
     * @param Device $device
     */
    public function addDevice(string $deviceName, Device $device) : void
    {
        $this->devices[$deviceName] = $device;

        $this->divisionsHaveBeenSorted = false;
    }

    /**
     * @param Division $division
     */
    public function addDivision(Division $division) : void
    {
        $this->divisions[] = $division;

        $this->divisionsHaveBeenSorted = false;
    }

    /**
     * Load the file for the default properties
     *
     * @param @param Division $division
     */
    public function addDefaultProperties(Division $division) : void
    {
        $this->defaultProperties = $division;

        $this->divisionsHaveBeenSorted = false;
    }

    /**
     * Load the file for the default browser
     *
     * @param @param Division $division
     *
     * @return void
     */
    public function addDefaultBrowser(Division $division) : void
    {
        $this->defaultBrowser = $division;

        $this->divisionsHaveBeenSorted = false;
    }

    /**
     * Get the divisions array containing UA data
     *
     * @return Division[]
     */
    public function getDivisions() : array
    {
        $this->sortDivisions();

        return $this->divisions;
    }

    /**
     * Sort the divisions (if they haven't already been sorted)
     *
     * @return void
     */
    private function sortDivisions() : void
    {
        if ($this->divisionsHaveBeenSorted) {
            return;
        }

        $sortIndex    = [];
        $sortPosition = [];

        foreach ($this->divisions as $key => $division) {
            /* @var \Browscap\Data\Division $division */
            $sortIndex[$key]    = $division->getSortIndex();
            $sortPosition[$key] = $key;
        }

        array_multisort(
            $sortIndex,
            SORT_ASC,
            SORT_NUMERIC,
            $sortPosition,
            SORT_DESC,
            SORT_NUMERIC, // if the sortIndex is identical the later added file comes first
            $this->divisions
        );

        $this->divisionsHaveBeenSorted = true;
    }

    /**
     * Get the divisions array containing UA data
     *
     * @return Division
     */
    public function getDefaultProperties() : Division
    {
        return $this->defaultProperties;
    }

    /**
     * Get the divisions array containing UA data
     *
     * @return Division
     */
    public function getDefaultBrowser() : Division
    {
        return $this->defaultBrowser;
    }

    /**
     * Get a single platform data array
     *
     * @param string $platform
     *
     * @throws \OutOfBoundsException
     * @throws \UnexpectedValueException
     *
     * @return Platform
     */
    public function getPlatform(string $platform) : Platform
    {
        if (!array_key_exists($platform, $this->platforms)) {
            throw new \OutOfBoundsException(
                'Platform "' . $platform . '" does not exist in data'
            );
        }

        return $this->platforms[$platform];
    }

    /**
     * Get a single engine data array
     *
     * @param string $engine
     *
     * @throws \OutOfBoundsException
     * @throws \UnexpectedValueException
     *
     * @return Engine
     */
    public function getEngine(string $engine) : Engine
    {
        if (!array_key_exists($engine, $this->engines)) {
            throw new \OutOfBoundsException(
                'Rendering Engine "' . $engine . '" does not exist in data'
            );
        }

        return $this->engines[$engine];
    }

    /**
     * Get a single engine data array
     *
     * @param string $device
     *
     * @throws \OutOfBoundsException
     * @throws \UnexpectedValueException
     *
     * @return Device
     */
    public function getDevice(string $device) : Device
    {
        if (!array_key_exists($device, $this->devices)) {
            throw new \OutOfBoundsException(
                'Device "' . $device . '" does not exist in data'
            );
        }

        return $this->devices[$device];
    }

    /**
     * Get the generation DateTime object
     *
     * @return \DateTimeImmutable
     */
    public function getGenerationDate() : \DateTimeImmutable
    {
        return $this->generationDate;
    }
}
