<?php

namespace Pagon\Cli;

use Pagon\App;
use Pagon\EventEmitter;
use Pagon\Exception\Stop;

/**
 * Cli Output
 *
 * @package Pagon\Cli
 * @property int    status
 * @property string body
 */
class Output extends EventEmitter
{
    /**
     * @var array Local variables
     */
    public $locals = array();

    /**
     * @var App App
     */
    public $app;

    /**
     * @param App $app
     * @return Output
     */
    public function __construct(App $app)
    {
        $this->app = $app;

        parent::__construct(array(
            'status' => 0,
            'body'   => '',
        ));

        $this->locals = & $this->app->locals;
    }

    /**
     * Set or get status
     *
     * @param null $status
     * @return int|Output
     */
    public function status($status = null)
    {
        if (is_numeric($status)) {
            $this->injectors['status'] = $status;
            return $this;
        }
        return $this->injectors['status'];
    }

    /**
     * Set body
     *
     * @static
     * @param string $content
     * @return string|Output
     */
    public function body($content = null)
    {
        if ($content !== null) {
            $this->injectors['body'] = $content;
            return $this;
        }

        return $this->injectors['body'];
    }

    /**
     * Write body
     *
     * @param string $data
     * @return string|Output
     */
    public function write($data)
    {
        if (!$data) return $this->injectors['body'];

        $this->injectors['body'] .= $data;

        return $this;
    }

    /**
     * End the response
     *
     * @param string $data
     * @throws Stop
     * @return void
     */
    public function end($data = '')
    {
        $this->write($data);
        throw new Stop();
    }

    /**
     * Is ok?
     *
     * @return bool
     */
    public function isOk()
    {
        return $this->injectors['status'] === 0;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->injectors['body'];
    }
}