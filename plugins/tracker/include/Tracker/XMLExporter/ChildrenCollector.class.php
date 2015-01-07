<?php
/**
 * Copyright (c) Enalean, 2015. All Rights Reserved.
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
 * along with Tuleap. If not, see <http://www.gnu.org/licenses/>.
 */

class Tracker_XMLExporter_ChildrenCollector {

    const MAX = 50;

    /** @var int[] */
    private $children_stack = array();

    /** @var array */
    private $parents = array();

    /** @var int */
    private $index = 0;

    /**
     * @return int[]
     */
    public function getAllChildrenIds() {
        return $this->children_stack;
    }

    /**
     * @return int || null
     */
    public function pop() {
        if ($this->index >= count($this->children_stack)) {
            return;
        }

        $child = $this->children_stack[$this->index];
        $this->index++;

        return $child;
    }

    public function addChild($artifact_id, $parent_id) {
        if (count($this->children_stack) >= self::MAX) {
            throw new Tracker_XMLExporter_TooManyChildrenException();
        }

        if (in_array($artifact_id, $this->children_stack)) {
            return;
        }

        $this->children_stack[] = $artifact_id;
        if (! isset($this->parents[$parent_id])) {
            $this->parents[$parent_id] = array();
        }
        $this->parents[$parent_id][] = $artifact_id;
    }

    public function getAllParents() {
        return array_keys($this->parents);
    }

    public function getChildrenForParent($parent_id) {
        if (! isset($this->parents[$parent_id])) {
            return array();
        }

        return $this->parents[$parent_id];
    }
}