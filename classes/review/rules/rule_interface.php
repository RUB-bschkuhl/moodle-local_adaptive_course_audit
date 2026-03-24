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

namespace local_adaptive_course_audit\review\rules;

defined('MOODLE_INTERNAL') || die();

/**
 * Interface for adaptive course audit rules.
 *
 * @package     local_adaptive_course_audit
 * @copyright   2025
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
interface rule_interface {
    /**
     * Check a target against this rule.
     *
     * @param object $target Target object to evaluate.
     * @param object|null $course Course record when available.
     * @return object|null Result object or null when rule is not applicable.
     */
    public function check_target($target, $course = null);

    /**
     * Get the rule name (for display).
     *
     * @return string
     */
    public function get_name();

    /**
     * Get the rule description.
     *
     * @return string
     */
    public function get_description();

    /**
     * Get the rule category.
     *
     * @return string One of 'hint', 'action'.
     */
    public function get_category();
}

