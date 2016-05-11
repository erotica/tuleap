<?php
/**
 * Copyright (c) Enalean SAS, 2016. All Rights Reserved.
 * Copyright (c) Enalean, 2016. All Rights Reserved.
 *
 * This file is a part of Tuleap.
 *
 * Tuleap is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Tuleap is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Tuleap; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

namespace Tuleap\ReferenceAliasSVN;

use DataAccessObject;
use Project;

class Dao extends DataAccessObject
{
    public function insertRef($source, $repository_id, $revision_id)
    {
        $source        = $this->da->quoteSmart($source);
        $repository_id = $this->da->escapeInt($repository_id);
        $revision_id   = $this->da->escapeInt($revision_id);

        $sql = "INSERT INTO plugin_referencealias_svn(source, repository_id, revision_id)
                VALUES ($source, $repository_id, $revision_id)";

        return $this->update($sql);
    }

    public function getRef($source)
    {
        $source = $this->da->quoteSmart($source);

        $sql = "SELECT repo.project_id, compat.*
                FROM plugin_referencealias_svn AS compat
                    INNER JOIN plugin_svn_repositories AS repo ON (
                        repo.id = compat.repository_id
                    )
                WHERE source = $source";

        return $this->retrieve($sql);
    }
}
