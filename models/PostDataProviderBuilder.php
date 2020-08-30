<?php

namespace app\models;

class PostDataProviderBuilder extends AbstractDataProviderBuilder
{

    /**
     * @inheritDoc
     */
    protected function getQuery(bool $isSetTime): string
    {
        $where = $isSetTime ? ' WHERE pv.created_at > :created_at ' : '';

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
