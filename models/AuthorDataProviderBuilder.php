<?php

namespace app\models;

class AuthorDataProviderBuilder extends AbstractDataProviderBuilder
{
    /**
     * @inheritDoc
     */
    protected function getQuery(bool $isSetTime): string
    {
        $where = $isSetTime ? ' WHERE pv.created_at > :created_at ' : '';
        return <<<SQL
SELECT u.name, u.id, COUNT(pv.id) as countOfView
FROM user as u
INNER JOIN post as p
    ON u.id = p.author_id
INNER JOIN post_view as pv
    ON p.id = pv.post_id
{$where}
GROUP BY p.id
SQL;
    }
}
