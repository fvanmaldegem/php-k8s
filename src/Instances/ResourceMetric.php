<?php

namespace RenokiCo\PhpK8s\Instances;

use Illuminate\Contracts\Support\Arrayable;
use RenokiCo\PhpK8s\Traits\HasAttributes;

class ResourceMetric implements Arrayable
{
    use HasAttributes;

    /**
     * The resource metric type.
     *
     * @var string
     */
    protected static $type = 'Resource';

    /**
     * Initialize the class.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * Set the resource type to CPU.
     *
     * @return $this
     */
    public function cpu()
    {
        return $this->setMetric('cpu');
    }

    /**
     * Set the resource type to memory.
     *
     * @return $this
     */
    public function memory()
    {
        return $this->setMetric('memory');
    }

    /**
     * Set average utilization for the metric.
     *
     * @param  int|string  $utilization
     * @return $this
     */
    public function averageUtilization($utilization = 50)
    {
        return $this->setAttribute('resource.target.type', 'Utilization')
            ->setAttribute('resource.target.averageUtilization', $utilization);
    }

    /**
     * Set average value for the metric.
     *
     * @param  string|int|float  $value
     * @return $this
     */
    public function averageValue($value)
    {
        return $this->setAttribute('resource.target.type', 'AverageValue')
            ->setAttribute('resource.target.averageValue', $value);
    }

    /**
     * Set the specific value for the metric.
     *
     * @param  string|int|float  $value
     * @return $this
     */
    public function value($value)
    {
        return $this->setAttribute('resource.target.type', 'Value')
            ->setAttribute('resource.target.value', $value);
    }

    /**
     * Alias for ->setName().
     *
     * @param  string  $name
     * @return $this
     */
    public function setMetric(string $name)
    {
        return $this->setName($name);
    }

    /**
     * Set the resource metric name.
     *
     * @param  string  $name
     * @return $this
     */
    public function setName(string $name)
    {
        return $this->setAttribute('resource.name', $name);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_merge($this->attributes, [
            'type' => static::$type,
        ]);
    }
}
