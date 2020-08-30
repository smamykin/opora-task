<?php

namespace app\models;

use DateTimeImmutable;
use Exception;
use yii\data\SqlDataProvider;
use yii\db\Connection;

class PostDataProviderBuilder implements DataProviderBuilderInterface
{
    /**
     * @var Connection
     */
    private $db;

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
        $createdAt =  $this->getDateTimeBySortString($sort);
        $query = $this->getQuery($createdAt);

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
     * @param string $createdAt
     * @return string
     */
    private function getQuery(string $createdAt): string
    {
        $where = $createdAt ? ' WHERE pv.created_at > :created_at ' : '';

        return <<<SQL
SELECT p.id, p.title, COUNT(pv.post_id) as countOfView
FROM post as p
INNER JOIN post_view as pv
    ON p.id = pv.post_id
{$where}
GROUP BY p.id
SQL;
    }

    /**
     * @param string $sort
     * @return string
     * @throws Exception
     */
    private function getDateTimeBySortString(string $sort): string
    {
        if ($sort) {
            $sort = '-' === $sort[0] ? mb_substr($sort, 1) : $sort;
        }

        $map = [
            self::SORT_QUERY_VAR_2H => '-2 hours',
            self::SORT_QUERY_VAR_3D => '-3 days',
            self::SORT_QUERY_VAR_5D => '-5 days',
            self::SORT_QUERY_VAR_10D => '-10 days',
        ];

        if (isset($map[$sort])) {
            return (new DateTimeImmutable($map[$sort]))->format('Y-m-d H:i:s');
        }

        return '';
    }

    /**
     * @param string $query
     * @param string $createdAt
     * @return false|string|\yii\db\DataReader|null
     * @throws \yii\db\Exception
     */
    private function getTotalCountForQuery(string $query, string $createdAt)
    {
        return $this->db
            ->createCommand("SELECT COUNT(*) FROM ({$query}) c")
            ->bindParam(':created_at', $createdAt)
            ->queryScalar();
    }

    /**
     * @return array
     */
    private function getSortAttr(): array
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
    private function getLabels(): array
    {
        return [
            self::SORT_QUERY_VAR_2H => 'Популярное за два часа',
            self::SORT_QUERY_VAR_3D => 'Популярное за три дня',
            self::SORT_QUERY_VAR_5D => 'Популярное за пять дней',
            self::SORT_QUERY_VAR_10D => 'Популярное за десять дней',
            self::SORT_QUERY_VAR_ALL => 'Популярное за все время',
        ];
    }
}
