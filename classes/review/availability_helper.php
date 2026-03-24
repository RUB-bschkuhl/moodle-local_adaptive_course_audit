<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

declare(strict_types=1);

namespace local_adaptive_course_audit\review;

defined('MOODLE_INTERNAL') || die();

/**
 * Helper for parsing Moodle availability JSON.
 *
 * Availability rules can contain nested condition groups, so we traverse recursively.
 *
 * @package     local_adaptive_course_audit
 */
final class availability_helper {
    /**
     * Decode availability JSON safely.
     *
     * @param string $availabilityjson
     * @return object|null
     */
    public static function decode(string $availabilityjson): ?object {
        $availabilityjson = trim($availabilityjson);
        if ($availabilityjson === '') {
            return null;
        }

        try {
            $decoded = json_decode($availabilityjson);
        } catch (\Throwable $exception) {
            debugging('Error decoding availability JSON: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            return null;
        }

        return is_object($decoded) ? $decoded : null;
    }

    /**
     * Get all completion conditions contained in an availability JSON string.
     *
     * @param string $availabilityjson
     * @return int[] List of CMIDs referenced by completion conditions.
     */
    public static function get_completion_cmids(string $availabilityjson): array {
        $decoded = self::decode($availabilityjson);
        if ($decoded === null) {
            return [];
        }

        $conditions = [];
        self::collect_conditions($decoded, $conditions);

        $cmids = [];
        foreach ($conditions as $condition) {
            if (!isset($condition->type) || (string)$condition->type !== 'completion') {
                continue;
            }
            if (!isset($condition->cm)) {
                continue;
            }
            $cmid = (int)$condition->cm;
            if ($cmid > 0) {
                $cmids[] = $cmid;
            }
        }

        return array_values(array_unique($cmids));
    }

    /**
     * Get all grade conditions contained in an availability JSON string.
     *
     * @param string $availabilityjson
     * @return array[] Each entry: ['id' => int gradeitemid, 'min' => float|null, 'max' => float|null]
     */
    public static function get_grade_conditions(string $availabilityjson): array {
        $decoded = self::decode($availabilityjson);
        if ($decoded === null) {
            return [];
        }

        $conditions = [];
        self::collect_conditions($decoded, $conditions);

        $grades = [];
        foreach ($conditions as $condition) {
            if (!isset($condition->type) || (string)$condition->type !== 'grade') {
                continue;
            }
            if (!isset($condition->id)) {
                continue;
            }

            $min = null;
            $max = null;
            if (isset($condition->min) && $condition->min !== '') {
                $min = (float)$condition->min;
            }
            if (isset($condition->max) && $condition->max !== '') {
                $max = (float)$condition->max;
            }

            $grades[] = [
                'id' => (int)$condition->id,
                'min' => $min,
                'max' => $max,
            ];
        }

        return $grades;
    }

    /**
     * Recursively collect condition objects within availability JSON.
     *
     * @param mixed $node
     * @param array $out
     * @return void
     */
    private static function collect_conditions($node, array &$out): void {
        if (is_array($node)) {
            foreach ($node as $item) {
                self::collect_conditions($item, $out);
            }
            return;
        }

        if (!is_object($node)) {
            return;
        }

        // Condition object.
        if (isset($node->type)) {
            $out[] = $node;
        }

        // Condition group contains nested conditions in ->c.
        if (isset($node->c) && is_array($node->c)) {
            foreach ($node->c as $child) {
                self::collect_conditions($child, $out);
            }
        }
    }
}

