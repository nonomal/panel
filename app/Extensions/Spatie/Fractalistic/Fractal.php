<?php

namespace Pterodactyl\Extensions\Spatie\Fractalistic;

use Spatie\Fractal\Fractal as SpatieFractal;
use Pterodactyl\Transformers\Api\Transformer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Pterodactyl\Extensions\League\Fractal\Serializers\PterodactylSerializer;

class Fractal extends SpatieFractal
{
    /**
     * Create fractal data.
     *
     * @return \League\Fractal\Scope
     *
     * @throws \Spatie\Fractalistic\Exceptions\InvalidTransformation
     * @throws \Spatie\Fractalistic\Exceptions\NoTransformerSpecified
     */
    public function createData()
    {
        // Set the serializer by default.
        if (is_null($this->serializer)) { // @phpstan-ignore-line
            $this->serializer = new PterodactylSerializer();
        }

        // Automatically set the paginator on the response object if the
        // data being provided implements a paginator.
        if (is_null($this->paginator) && $this->data instanceof LengthAwarePaginator) { // @phpstan-ignore-line
            $this->paginator = new IlluminatePaginatorAdapter($this->data);
        }

        // If the resource name is not set attempt to pull it off the transformer
        // itself and set it automatically.
        $class = is_string($this->transformer) ? new $this->transformer() : $this->transformer;
        if (is_null($this->resourceName) && $class instanceof Transformer) { // @phpstan-ignore-line
            $this->resourceName = $class->getResourceName();
        }

        return parent::createData();
    }
}
