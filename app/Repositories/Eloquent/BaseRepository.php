<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\Eloquent\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Builder|Model $query;

    abstract protected function modelName(): string;

    public function __construct()
    {
        $this->query = resolve($this->modelName());
    }

    public function paginate($perPage = 15, $filter = null)
    {
        return $this->query
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findOneById(int $id)
    {
        return $this->query->findOrFail($id);
    }

    public function destroy(int $id): void
    {
        $model = $this->findOneById($id);
        $model->delete();
    }

    public function update(int $id, array $data)
    {
        $model = $this->findOneById($id);
        $model->update($data);

        return $model->refresh();
    }

    public function insert($data)
    {
        return $this->query->create(
            $data
        );
    }
}
