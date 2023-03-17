<?PHP

namespace Core\Interfaces;

interface IBuilder {
    public function select($select);

    public function orderBy(string $column, string $arrangement);

    public function orWhere($column, string $operator, string $value);

    public function limit(int $limit);

    public function orWhereIn(string $column, array $data);

    public function where($column, ?string $operator, ?string $value);

    public function whereIn(string $column, array $data);

    public function join();
}