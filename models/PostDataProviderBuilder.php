<?php

namespace app\models;

use DateTimeImmutable;
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

    public function build(string $sort): SqlDataProvider
    {
        $createdAt = null;

        if ($sort) {
            $sort = '-' === $sort[0] ? mb_substr($sort, 1) : $sort;

            $map = [
                '2hours' => '-2 hours',
                '3days' => '-3 days',
                '5days' => '-5 days',
                '10days' => '-10 days',
                'default' => '',
            ];
            if (isset($map[$sort])) {
                $sort = $map[$sort];
            }
        }

        $createdAt = $sort ? (new DateTimeImmutable($sort))->format('Y-m-d H:i:s') : '';
        $where = $createdAt ? ' WHERE pv.created_at > :created_at ' : '';

        $query = $this->getQuery($where);
        $totalCount = $this->db
            ->createCommand("SELECT COUNT(*) FROM ({$query}) c")
            ->bindParam(':created_at', $createdAt)
            ->queryScalar();
        $commonSortParam = [
            'asc' => ['countOfView' => SORT_ASC],
            'desc' => ['countOfView' => SORT_DESC],
            'default' => SORT_DESC,
        ];

        return new SqlDataProvider(
            [
                'sql' => $query,
                'totalCount' => $totalCount,
                'params' => [
                    ':created_at' => $createdAt,
                ],
                'sort' => [
                    'attributes' => [
                        '2hours' => array_merge($commonSortParam, ['label'=> 'Популярное за два часа']),
                        '3days' =>  array_merge($commonSortParam, ['label'=> 'Популярное за три дня']),
                        '5days' =>  array_merge($commonSortParam, ['label'=> 'Популярное за пять дней']),
                        '10days' =>  array_merge($commonSortParam, ['label'=> 'Популярное за десять дней']),
                        'default' =>  array_merge($commonSortParam, ['label'=> 'Популярное за все время']),
                    ],
                    'defaultOrder' => [
                        'default' => SORT_DESC,
                    ]
                ]
            ]
        );
    }

    /**
     * @param string $where
     * @return string
     */
    private function getQuery(string $where): string
    {
        return <<<SQL
SELECT p.id, p.title, COUNT(pv.post_id) as countOfView
FROM post as p
INNER JOIN post_view as pv
    ON p.id = pv.post_id
{$where}
GROUP BY p.id
SQL;
    }
}
