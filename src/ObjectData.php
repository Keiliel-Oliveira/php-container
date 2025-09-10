<?php

declare(strict_types = 1);

namespace KeilielOliveira\Container;

class ObjectData {
    private string $objectType;

    /** @var class-string|\Closure */
    private \Closure|string $object;

    /** @var string[] */
    private array $requiredParams = [];

    /** @var string[][] */
    private array $requiredParamsTypes = [];

    /** @var string[] */
    private array $optionalParams = [];

    /** @var mixed[] */
    private array $defaultValues = [];

    /** @var mixed[] */
    private array $receivedParams = [];

    public function setObjectType( string $objectType ): void {
        $this->objectType = $objectType;
    }

    /**
     * @param class-string|\Closure $object
     */
    public function setObject( \Closure|string $object ): void {
        $this->object = $object;
    }

    /**
     * @param string[] $requiredParams
     */
    public function setRequiredParams( array $requiredParams ): void {
        $this->requiredParams = $requiredParams;
    }

    /**
     * @param string[][] $requiredParamsTypes
     */
    public function setRequiredParamsTypes( array $requiredParamsTypes ): void {
        $this->requiredParamsTypes = $requiredParamsTypes;
    }

    /**
     * @param string[] $optionalParams
     */
    public function setOptionalParams( array $optionalParams ): void {
        $this->optionalParams = $optionalParams;
    }

    /**
     * @param mixed[] $defaultValues
     */
    public function setDefaultValues( array $defaultValues ): void {
        $this->defaultValues = $defaultValues;
    }

    /**
     * @param mixed[] $receivedParams
     */
    public function setReceivedParams( array $receivedParams ): void {
        $this->receivedParams = $receivedParams;
    }

    public function getObjectType(): string {
        return $this->objectType;
    }

    /**
     * @return class-string|\Closure
     */
    public function getObject(): \Closure|string {
        return $this->object;
    }

    /**
     * @return string[]
     */
    public function getRequiredParams(): array {
        return $this->requiredParams;
    }

    /**
     * @return string[][]
     */
    public function getRequiredParamsTypes(): array {
        return $this->requiredParamsTypes;
    }

    /**
     * @return string[]
     */
    public function getOptionalParams(): array {
        return $this->optionalParams;
    }

    /**
     * @return mixed[]
     */
    public function getDefaultValues(): array {
        return $this->defaultValues;
    }

    /**
     * @return mixed[]
     */
    public function getReceivedParams(): array {
        return $this->receivedParams;
    }
}
