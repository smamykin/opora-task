<?php

namespace app\models;

use DateTimeImmutable;
use Exception;
use yii\data\SqlDataProvider;
use yii\db\Connection;

/**
 * Позволяет получить настроенный DataProvider для вывода списка с пагинацией и сортировкой по числу просмотров за период.
 *
 * @see AuthorDataProviderBuilder
 * @see PostDataProviderBuilder
 */
abstract class AbstractDataProviderBuilder implements DataProviderBuilderInterface
{
    private const SORT_QUERY_VAR_10D = '10days';
    private const SORT_QUERY_VAR_3D = '3days';
    private const SORT_QUERY_VAR_ALL = 'default';
    private const SORT_QUERY_VAR_5D = '5days';
    private const SORT_QUERY_VAR_2H = '2hours';

    /**
     * @var Connection
     */
    protected $db;

    /**
     * @param bool $isSetTime
     * @return string
     */
    abstract protected function getQuery(bool $isSetTime): string;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @param string $sort
     * @return SqlDataProvider
     * @throws \yii\db\Exception
     * @throws Exception
     */
    public function build(string $sort): SqlDataProvider
    {
        $createdAt = $this->getDateTimeBySortString($sort);
        $query = $this->getQuery((bool)$createdAt);

        return new SqlDataProvider(
            [
                'sql' => $query,
                'totalCount' => $this->getTotalCountForQuery($query, $createdAt),
                'params' => [
                    ':created_at' => $createdAt,
                ],
                'sort' => [
                    'attributes' => $this->getSortAttr(),
                    'defaultOrder' => [
                        'default' => SORT_DESC,
                    ]
                ]
            ]
        );
    }

    /**
     * @param string $sort
     * @return string
     * @throws Exception
     */
    protected function getDateTimeBySortString(string $sort): string
    {
        if ($sort) {
            $sort = '-' === $sort[0] ? mb_substr($sort, 1) : $sort;
        }

        $map = [
            DataProviderBuilderInterface::SORT_QUERY_VAR_2H => '-2 hours',
            DataProviderBuilderInterface::SORT_QUERY_VAR_3D => '-3 days',
            DataProviderBuilderInterface::SORT_QUERY_VAR_5D => '-5 days',
            DataProviderBuilderInterface::SORT_QUERY_VAR_10D => '-10 days',
        ];

        if (isset($map[$sort])) {
            return (new DateTimeImmutable($map[$sort]))->format('Y-m-d H:i:s');
        }

        return '';
    }

    /**
     * @return array
     */
    protected function getSortAttr(): array
    {
        $commonSortParam = [
            'asc' => ['countOfView' => SORT_ASC],
            'desc' => ['countOfView' => SORT_DESC],
            'default' => SORT_DESC,
        ];

        $attrs = [];
        foreach ($this->getLabels() as $attrName => $label) {
            $attrs[$attrName] = array_merge($commonSortParam, ['label' => $label]);
        }

        return $attrs;
    }

    /**
     * @return string[]
     */
    protected function getLabels(): array
    {
        return [
            self::SORT_QUERY_VAR_2H => 'Популярное за два часа',
            self::SORT_QUERY_VAR_3D => 'Популярное за три дня',
            self::SORT_QUERY_VAR_5D => 'Популярное за пять дней',
            self::SORT_QUERY_VAR_10D => 'Популярное за десять дней',
            self::SORT_QUERY_VAR_ALL => 'Популярное за все время',
        ];
    }

    /**
     * @param string $query
     * @param string $createdAt
     * @return false|string|\yii\db\DataReader|null
     * @throws \yii\db\Exception
     */
    protected function getTotalCountForQuery(string $query, string $createdAt)
    {
        return $this->db
            ->createCommand("SELECT COUNT(*) FROM ({$query}) c")
            ->bindParam(':created_at', $createdAt)
            ->queryScalar();
    }
}
