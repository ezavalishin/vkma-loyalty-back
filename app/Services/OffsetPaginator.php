<?php

namespace App\Services;

use ArrayAccess;
use Countable;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\Request;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;
use IteratorAggregate;
use JsonException;
use JsonSerializable;

class OffsetPaginator extends AbstractPaginator implements Arrayable, ArrayAccess, Countable, IteratorAggregate, JsonSerializable, Jsonable, Paginator
{
    protected Request $request;
    protected int $offset;
    protected int $total;
    protected bool $hasMore;

    public function __construct($items, $perPage, $total = null, array $options = [])
    {
        foreach ($options as $key => $value) {
            $this->{$key} = $value;
        }

        $this->perPage = $perPage;
        $this->request = request();

        $this->offset = $this->request->offset ?? 0;

        $this->query = $this->getRawQuery();
        $this->path = $this->path !== '/' ? rtrim($this->path, '/') : rtrim($this->request->path(), '/');

        $this->setItems($items);

        $this->total = $total ?? $this->items->count();
    }

    public function render($view = null, $data = []): ?string
    {
        return null;
    }

    protected function setItems($items): void
    {
        $this->items = $items instanceof Collection ? $items : Collection::make($items);

        $this->hasMore = $this->items->count() > $this->perPage;

        $this->items = $this->items->slice(0, $this->perPage);
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function nextOffset()
    {
        return $this->total - $this->perPage > $this->offset ? $this->offset + $this->perPage : null;
    }

    /**
     * The URL for the next page, or null.
     *
     * @return string|null
     */
    public function nextPageUrl()
    {
        if ($this->nextOffset()) {
            $query = [
                'offset' => $this->nextOffset(),
            ];

            return $this->url($query);
        }
    }

    /**
     * @return string|null
     */
    public function prevOffset()
    {
        if ($this->offset >= $this->perPage) {
            return $this->offset - $this->perPage;
        }
    }

    /**
     * @return null|string
     */
    public function previousPageUrl()
    {
        if (!is_null($this->prevOffset())) {
            $query = [
                'offset' => $this->prevOffset(),
            ];

            return $this->url($query);
        }
    }

    /**
     * Returns the request query without the offset parameters.
     *
     * @return array
     */
    protected function getRawQuery()
    {
        return collect($this->request->query())
            ->diffKeys([
                'offset' => true,
            ])->all();
    }

    /**
     * @param array $offset
     *
     * @return string
     */
    public function url($offset = [])
    {
        $query = array_merge($this->query, $offset);

        return $this->request->root() . '/' . $this->path
            . (str_contains($this->path, '?') ? '&' : '?')
            . http_build_query($query, '', '&')
            . $this->buildFragment();
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function perPage()
    {
        return $this->perPage;
    }

    /**
     * Determine if there is more items in the data store.
     *
     * @return bool
     */
    public function hasMorePages()
    {
        return $this->hasMore;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'data' => $this->items->toArray(),
            'offset' => $this->getOffset(),
            'prev' => $this->prevOffset(),
            'next' => $this->nextOffset(),
            'limit' => $this->perPage(),
            'total' => $this->getTotal(),
            'next_page_url' => $this->nextPageUrl(),
            'prev_page_url' => $this->previousPageUrl(),
        ];
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     *
     * @return string
     * @throws JsonException
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), JSON_THROW_ON_ERROR | $options);
    }
}
