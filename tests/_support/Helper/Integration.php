<?php

namespace Helper;

use Baka\TestCase\Phinx;
use Codeception\Module;
use Codeception\TestInterface;
use Phalcon\Di\FactoryDefault;

// here you can define custom actions
// all public methods declared in helper class will be available in $I
class Integration extends Module
{
    /**
     * @var null|PhDI
     */
    protected ?FactoryDefault $diContainer = null;
    protected $savedModels = [];
    protected $savedRecords = [];
    protected $config = ['rollback' => false];

    /**
     * Test initializer.
     */
    public function _before(TestInterface $test)
    {
        FactoryDefault::reset();
        $this->setDi();
    }

    public function _after(TestInterface $test)
    {
    }

    /**
     * Run migration.
     *
     * @param array $settings
     *
     * @return void
     */
    public function _beforeSuite($settings = [])
    {
    }

    /**
     * After all is done.
     *
     * @return void
     */
    public function _afterSuite()
    {
        //Phinx::dropTables();
    }

    /**
     * Set DI.
     *
     * @return void
     */
    public function setDi()
    {
        $this->diContainer = new FactoryDefault();
    }

    /**
     * @return mixed
     */
    public function grabDi()
    {
        return $this->diContainer;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function grabFromDi(string $name)
    {
        return $this->diContainer->get($name);
    }

    /**
     * @param string $name
     * @param mixed  $service
     */
    public function haveService(string $name, $service)
    {
        $this->diContainer->set($name, $service);
    }

    /**
     * @param string $name
     */
    public function removeService(string $name)
    {
        if ($this->diContainer->has($name)) {
            $this->diContainer->remove($name);
        }
    }
}
